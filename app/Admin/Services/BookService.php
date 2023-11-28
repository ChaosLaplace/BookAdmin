<?php
/**
 * 繪本主要資訊服務
 *
 */
declare(strict_types=1);

namespace App\Http\Services;

use App\Constants\CodeConstant;
use App\Models\Book;
use App\Models\BookContent;
use Illuminate\Support\Facades\Log;
use Dompdf\Dompdf;

class BookService
{
    public function bookList($page = 1, $perPage = 15, $userId) {

        $result = [];
        $result['my_book'] = [];

        try{
            $myBooks = Book::query()
            ->with('user')
            ->where('book_author_id' , $userId)
            ->orderByDesc('updated_at')
            ->paginate($perPage, ['book_author_id', 'book_id', 'book_frontcover', 'book_name_ch', 'book_name_en', 'book_author', 'book_state', 'book_verify']
            , '', $page)->items();

            $arrangedBook = [];

            foreach($myBooks as $key => $myBook){
                $myBook['book_author'] = $myBook['user']['name'];
                unset($myBooks[$key]['user']);
                $arrangedBook[] = $myBook;
            }

            $result['my_book'] = $arrangedBook;

        } catch(\Exception $e){
            return false;
        }

        return $result;
    }

    public function editInfo($inputData, $userId) {

        $res = [
            'status' => false,
            'msg' => ''
        ];

        $inputData = array_filter($inputData);
        if(empty($inputData)){
            $res['msg'] = '繪本建立失敗!';
            return $res;
        }

        /*
        如果改成使用者有存到preface
        就將此繪本book_state設為1已完成好否！
        */
        if(isset($inputData['preface'])){
            $inputData['book_state'] = 1;
        }

        if(isset($inputData['book_id'])){ // update

            $book = Book::query()->where('book_id', $inputData['book_id'])
                ->where('book_author_id', $userId) // 不要改到別人的
                ->first();

            if(empty($book)){
                $res['msg'] = '繪本id錯誤';
                return $res;
            }
            $bookId = $inputData['book_id'];
            unset($inputData['book_id']);
            try{
                Book::query()->where('book_id', $bookId)->update($inputData);

                if ( isset($inputData['preface']) ) {
                    $bookContent = BookContent::query()
                    ->where('book_id', $bookId)
                    ->first();
                    if ( empty($bookContent) ) {
                        $res['msg'] = '繪本id錯誤';
                        return $res;
                    }
                    // 繪本網改成 API
                    $data_post = [
                        'frontcover' => $book['book_frontcover'],
                        'title_ch'   => $book['book_name_ch'],
                        'title_en'   => $book['book_name_en'],
                        'auther'     => $book['book_author'],
                        'desc'       => $book['preface'],

                        'story_ch'   => $bookContent['ch_story_ai'],
                        'story_en'   => $bookContent['en_story_ai'],
                    ];
                    $bookContent = BookContent::where('book_id', $bookId)->pluck('user_pic_select', 'id');
                    $pic_list = '';
                    foreach ($bookContent as $v) {
                        $pic_list .= $v . ',';
                    }
                    $pic_list = (isset($book['book_backcover']) ? $pic_list . $book['book_backcover'] : rtrim($pic_list, ','));
                    $data_post['pic_select'] = $pic_list;
                    Log::info('[BookWebStore PostBefore]' . json_encode($data_post, JSON_UNESCAPED_UNICODE));

                    $result = self::send_post( env('BOOK_WEB_STORE') , $data_post);
                    Log::info('[BookWebStore PostAfter]' . json_encode($result, JSON_UNESCAPED_UNICODE));
                }

            } catch(\Exception $e){
                $res['msg'] = '網路錯誤，請稍後再試！';
                return $res;
            }
            $res['status'] = true;
            $res['msg'] = '繪本更新成功！';
            $res['data'] = [];
        } else { // insert
            $inputData['book_author_id'] = $userId;
            try{
                $book = Book::create($inputData);
            } catch(\Exception $e){
                $res['msg'] = '網路錯誤，請稍後再試！';
                return $res;
            }
            $res['status'] = true;
            $res['msg'] = '繪本建立成功！';
            $res['data'] = ['book_id' => $book->id];
        }

        return $res;
    }

    public function getInfo($bookId, $userId) {

        $res = [
            'status' => false,
            'msg' => ''
        ];
        try{
            $book = Book::query()
                ->where('book_id', $bookId)
                ->select([
                    'book_name_ch',
                    'book_name_en',
                    'style',
                    'cover_image_1',
                    'cover_image_2',
                    'cover_image_3',
                    'cover_image_4',
                    'book_frontcover',
                    'book_backcover',
                    'preface',
                    'book_state',
                    'book_verify',
                    'book_author_id',
                ])->with('user')->first();

                $book['book_author']  = $book['user']['name'];
                unset($book['user']);
        } catch(\Exception $e){
            $res['msg'] = '網路錯誤，請稍後再試！';
            return $res;
        }

        if (empty($book)) {
            $res['msg'] = '繪本id錯誤';
            return $res;
        }

        // 如果識別人的繪本, 檢查驗證/完成狀態
        if($book->book_author_id != $userId){
            // todo 有後臺審核功能時打開 book->book_verify 檢查
            if($book->book_state != 0 ){ //|| $book->book_status != 0){
                $res['msg'] = '繪本id錯誤!';
                return $res;
            }
        }

        $resData = [];
        $resData['book_name_ch'] = $book->book_name_ch;
        $resData['book_name_en'] = $book->book_name_en;
        $resData['style'] = $book->style;

        // 從 book_contents中 隨機取出四張圖
        $bookContentService = new BookContentService();
        $cover_img = $bookContentService->getRandomPics($bookId);

        $resData['cover_img'] = $cover_img;
        $resData['book_frontcover'] = $book->book_frontcover;
        $resData['book_backcover'] = $book->book_backcover;
        $resData['preface'] = $book->preface;
        $resData['book_state'] = $book->book_state;
        $resData['book_verify'] = $book->book_verify;
        $resData['book_author'] = $book->book_author;

        $res['status'] = true;
        $res['msg'] = '繪本查詢成功！';
        $res['data'] = $resData;

        return $res;
    }

    public function getBook($bookId) {
        return Book::with('user')
            ->with(['bookContents'=> function($query) {
                $query->orderBy('step', 'asc');
                $query->orderBy('page', 'asc');
            }])
            ->where('book_id', $bookId)
            ->first();
    }

    public function read($bookId){
        $res = [
            'status' => false,
            'bookId' => $bookId,
            'msg' => "bookId:$bookId",
        ];
        $book = $this->getBook($bookId);

        if(empty($book)){
            return $res;
        }


        $resData = [];
        $resData["book_id"] = $bookId;
        $resData["book_name_ch"] = $book->book_name_ch;
        $resData["book_name_en"] = $book->book_name_en;
        $resData["book_author"] = $book->user->name;
        $resData["book_frontcover"] = $book->book_frontcover;
        $resData["book_backcover"] = $book->book_backcover;

        $bookContentTemp = []; // 整理結構用

        foreach($book->bookContents as $bookContent){
            $pages =
            [
                'page' => $bookContent->step . '_' . $bookContent->page,
                'en_story_ai' => $bookContent->en_story_ai,
                'en_user_input' => $bookContent->en_user_input,
                'user_input' => $bookContent->user_input,
                'user_pic_select' => $bookContent->user_pic_select,
                'position_input' => $bookContent->position_input
            ];
            $bookContentTemp[$bookContent->step][] = $pages;
        }

        $bookContentArranged = []; // 整理完的結果

        foreach($bookContentTemp as $step => $pages){
            $bookContentArrangedItem = [];
            $bookContentArrangedItem['step'] = $step;
            $bookContentArrangedItem['pages'] = $pages;
            $bookContentArranged[] = $bookContentArrangedItem;
        }

        $resData["book_content"] = $bookContentArranged;


        $res['status'] = true;
        $res['data'] = $resData;


        return $res;
    }

    public function createPdf($bookId) {
        $res = [
            'status' => false,
            'msg' => "網路錯誤，請稍後再試！",
        ];
        $bookData = $this->read($bookId); // 取得與前端一樣的資料結構

        if(!$bookData['status']){ // 取得失敗
            return $res;
        }

        // 創建dompdf  物件
        $domPdf = new Dompdf();

        // 大小A4, 縱向
        $domPdf->setPaper('A4', 'portrait');

        // html頭
        $html = '<html><body>';
        //取得書本相關資訊->比照前端辦理

        // 封面
        $html .= view('pdf-cover',['bookData'=>$bookData['data']])->render(); // 取得封面模板

        // 換頁
        $html .= '<div style="page-break-before: always;"></div>'; // 換頁
        // 循環取出內頁
        foreach($bookData['data']['book_content'] as $stepPages){
            // 取出每步驟的每頁
            foreach($stepPages['pages'] as $page){
                $html .= view('pdf-content',['pageData'=>$page])->render(); // 取得內容模板
                // 換頁
                $html .= '<div style="page-break-before: always;"></div>'; // 換頁
            }
        }

        // 封底
        $html .= view('pdf-backcover',['bookData'=>$bookData['data']])->render();
        $html .= "</body></html>";

        // 將 HTML 加載到 Dompdf
        $domPdf->loadHtml($html);

        // 渲染 PDF
        $domPdf->render();

        $fileName = $bookData['data']['book_name_en'] . '.pdf'; // PDF 文件名, 使用他的英文書名避免莫名其妙的亂碼
        $folderPath = app()->basePath('public') . "/download/pdf/" . date('Y-m-d', time()) . '/';  // 存儲路徑

        if (!file_exists($folderPath)) { // 不在就創一個
            mkdir($folderPath, 0777, true);
        }

        $filePath = $folderPath  . $fileName; // 存儲路徑

        file_put_contents($filePath, $domPdf->output());

        $data = [];
        $data['bookNameEn'] = $bookData['data']['book_name_en'];
        $data['filePath'] = $filePath;

        // 不要在這時候刪檔案 用戶下載行為還沒完成 要刪的話 定時排程刪(文件按照日期分別放在不同資料夾
        // 可先刪舊的日期)
        $res['status'] = true;
        $res['data'] = $data;

        return $res;

    }

    public static function send_post($url, $post_data) {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_POSTFIELDS => http_build_query($post_data),
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/x-www-form-urlencoded',
            ),
        ));
        $response = curl_exec($curl);
        if ( curl_errno($curl) ) {
            $response = 'Curl error: ' . curl_error($curl);
        }
        curl_close($curl);
        return $response;
    }

    // 查詢作者所有繪本id
    public function getBookId($userId): array
    {
        $result = Book::where('book_author_id', $userId)->pluck('book_id')->toArray();

        return $result;
    }

    // 查詢繪本簡易資料 中文繪本名、英文繪本名、封面位址
    public function getBookSimpleInfo($bookId) {
        return Book::select('book_name_ch', 'book_name_en', 'book_frontcover')->where('book_id', $bookId)->first();
    }
}
