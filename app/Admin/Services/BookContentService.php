<?php
/**
 * 繪本(內頁)服務
 *
 */
declare(strict_types=1);

namespace App\Http\Services;
use App\Models\Book;
use App\Models\BookCollect;
use App\Models\BookContent;
use App\Models\BookStar;
use App\Models\User;

use Illuminate\Support\Facades\Log;
class BookContentService
{
    // 從使用者的書本內頁中隨機取出四張圖片
    public function getRandomPics($bookId, $num = 4){
        $fields = ['story_pic_ai_1', 'story_pic_ai_2', 'story_pic_ai_3', 'story_pic_ai_4'];
        $pics = BookContent::query()->where('book_id', $bookId)->select($fields)->get()->toArray();

        $tmp = [];

        foreach($pics as $pic){
            // 整理一下
            $tmp['story_pic_ai_1'][] = $pic['story_pic_ai_1'];
            $tmp['story_pic_ai_2'][] = $pic['story_pic_ai_2'];
            $tmp['story_pic_ai_3'][] = $pic['story_pic_ai_3'];
            $tmp['story_pic_ai_4'][] = $pic['story_pic_ai_4'];
        }

        $result = [];

        foreach($tmp as $item){
            $randKey =  array_rand($item, 1); //隨機取一個給他
            $result[] = $item[$randKey];
        }

        return $result;
    }

    public function saveData($inputData, $userId) {
        $res = [
            'status' => false,
            'msg' => ''
        ];

        $inputData = array_filter($inputData);

        if(!empty($inputData['user_pic_input'])){
            // 解出array
            foreach($inputData['user_pic_input'] as $item){
                $itemKeys = array_keys($item);

                $itemKey = $itemKeys[0];
                switch($itemKey){
                    case 'who':
                        $inputData['user_pic_input_who'] = $item['who'];
                        break;
                    case 'where':
                        $inputData['user_pic_input_where'] = $item['where'];
                        break;
                    case 'what':
                        $inputData['user_pic_input_what'] = $item['what'];
                        break;
                }
            }
            unset($inputData['user_pic_input']);
        }

        if(!empty($inputData['story_pic_ai'])){
            foreach($inputData['story_pic_ai'] as $key => $item){
                $inputData['story_pic_ai_' . ($key+1)] = $item;
            }
            unset($inputData['story_pic_ai']);
        }

        $book = Book::query()
        ->where('book_id', $inputData['book_id'])
        ->where('book_author_id', $userId)
        ->first();

        if(empty($book)){
            $res['msg'] = '繪本id錯誤';
            return $res;
        }

        if(empty($inputData['page'])){ // 沒 page
            $currentPage = BookContent::query()
            ->where('book_id', $inputData['book_id'])
            ->where('step', $inputData['step'])
            ->max('page');

            if(empty($currentPage)){
                $currentPage = 0;
            }

            $inputData['page'] = $currentPage +1;
        }

        try{
            $bookContent = BookContent::query()
            ->where('book_id', $inputData['book_id'])
            ->where('page', $inputData['page'])
            ->where('step', $inputData['step'])
            ->first();

            if(empty($bookContent)) { // 沒有就新增
                BookContent::create($inputData);
            } else {
                BookContent::where('id', $bookContent->id)->update($inputData);
            }

        } catch(\Exception $e){
            $res['msg'] = '存檔失敗，請稍後再試！';
            return $res;
        }

        $res['status'] = true;
        $res['data'] = ['page' => $inputData['step'] . "_" . $inputData['page']];

        return $res;
    }

    public function getData($bookId, $step, $page, $userId) {
        $res = [
            'status' => false,
            'msg' => ''
        ];

        $book = Book::query()
        ->where('book_id', $bookId)
        ->where('book_author_id', $userId)
        ->first();

        if(empty($book)){
            $res['msg'] = '繪本id錯誤';
            return $res;
        }

        $resData= [];
        // 沒page 一次抓回此步驟所有頁面的資料，陣列資料排序順序請依據page順序
        if(empty($page)){
            $bookContents = $this->getDataWithoutPage($bookId, $step);

            if(empty($bookContents)){
                $res['msg'] = '繪本id/ step/ page 錯誤';
                return $res;
            }

            $resData = $this->arrangeData($bookContents);
        } else {
            $bookContent = $this->getDataWithPage($page, $bookId, $step);

            if(empty($bookContent)){
                $res['msg'] = '繪本id/ step/ page 錯誤';
                return $res;
            }

            $storyPicAi = [
                $bookContent->story_pic_ai_1,
                $bookContent->story_pic_ai_2,
                $bookContent->story_pic_ai_3,
                $bookContent->story_pic_ai_4
            ];

            unset($bookContent->story_pic_ai_1);
            unset($bookContent->story_pic_ai_2);
            unset($bookContent->story_pic_ai_3);
            unset($bookContent->story_pic_ai_4);

            $userPicInput = [
                ["who" => $bookContent->user_pic_input_who],
                ["where" => $bookContent->user_pic_input_where],
                ["what" => $bookContent->user_pic_input_what]
            ];

            unset($bookContent->user_pic_input_who);
            unset($bookContent->user_pic_input_where);
            unset($bookContent->user_pic_input_what);


            $resData = $bookContent;
            $resData['story_pic_ai'] = $storyPicAi;
            $resData['user_pic_input'] = $userPicInput;
        }

        $res['status'] = true;
        $res['data'] = $resData;
        return $res;
    }

    private function arrangeData($bookContents){
        $resData = [];

        // 整理回傳數據
        foreach($bookContents as $bookContent){
            $resDatum = [];
            $resDatum['page'] = $bookContent->step . "_"  .$bookContent->page;
            $resDatum['user_input'] = $bookContent->user_input;
            $resDatum['en_user_input'] = $bookContent->en_user_input;
            $resDatum['ch_story_ai'] = $bookContent->ch_story_ai;
            $resDatum['en_story_ai'] = $bookContent->en_story_ai;

            $userPicInput = [
                ['who' => $bookContent->user_pic_input_who],
                ['where' => $bookContent->user_pic_input_where],
                ['what' => $bookContent->user_pic_input_what]
            ];

            $resDatum['user_pic_input'] = $userPicInput;

            $storyPicAi = [
                $bookContent->story_pic_ai_1,
                $bookContent->story_pic_ai_2,
                $bookContent->story_pic_ai_3,
                $bookContent->story_pic_ai_4
            ];

            $resDatum['story_pic_ai'] = $storyPicAi;
            $resDatum['user_pic_select'] = $bookContent->user_pic_select;
            $resDatum['position_input'] = $bookContent->position_input;

            $resData[] = $resDatum;
        }

        return $resData;
    }

    public function getDataWithoutPage($bookId, $step) {
        return BookContent::query()
                ->select('user_input', 'ch_story_ai', 'en_story_ai',
                'user_pic_input_who','user_pic_input_where', 'user_pic_input_what',
                'story_pic_ai_1', 'story_pic_ai_2', 'story_pic_ai_3', 'story_pic_ai_4',
                'user_pic_select', 'position_input', 'page', 'step', 'en_user_input')
                ->where('book_id', $bookId)
                ->where('step', $step)
                ->orderBy('page')
                ->get();
    }

    public function getDataWithPage($page, $bookId, $step) {
        return BookContent::query()
                ->select('ch_story_ai', 'en_story_ai', 'user_pic_select', 'position_input',
                'story_pic_ai_1', 'story_pic_ai_2', 'story_pic_ai_3', 'story_pic_ai_4',
                'user_pic_select',
                'user_pic_input_who','user_pic_input_where', 'user_pic_input_what', 'user_input', 'en_user_input')
                ->where('page', $page)
                ->where('book_id', $bookId)
                ->where('step', $step)
                ->first();
    }
    // 繪本簡介
    public static function getIntroductionById($book_id, $userId) {
        $res = [
            'status' => false,
            'msg'    => '繪本不存在',
            'data'   => []
        ];

        try {
            $book = Book::query()
            ->select('book_id', 'book_frontcover', 'book_name_ch', 'book_name_en', 'book_author', 'preface', 'book_backcover',
            'story_main', 'book_author_id', 'book_point', 'preview_page')
            ->where('book_id', $book_id)
            ->first();
            if ( !empty($book) ) {
                // 作者頭像
                $user = User::select('name', 'avatar')->where('id', $book['book_author_id'])->first();
                $book['book_author']        = $user['name'];
                $book['book_author_avatar'] = $user['avatar'];
                unset($book['book_author_id']);
                // 檢查是否收藏
                // $bookCollect = BookCollect::query()
                // ->where('book_id', $id)
                // ->where('user_id', $userId)
                // ->first();

                // $book['collect'] = 0;
                // if ( !empty($bookCollect) ) {
                //     $book['collect'] = 1;
                // }
                // 繪本內容 book_content 打 api/book/read API
                // $book['book_content'] = BookContent::query()
                // ->select('ch_story_ai', 'en_story_ai', 'user_pic_select', 'position_input',
                // 'story_pic_ai_1', 'story_pic_ai_2', 'story_pic_ai_3', 'story_pic_ai_4','user_pic_select',
                // 'user_pic_input_who','user_pic_input_where', 'user_pic_input_what', 'user_input', 'en_user_input')
                // ->where('book_id', $book_id)
                // ->first();

                $res['status'] = true;
                $res['msg']    = '成功';
                $res['data']   = $book;
            }
        } catch(\Exception $e) {
            Log::error('[繪本簡介 Error] ' . $e->getMessage());
        }
        return $res;
    }

    public static function getConfigById($id) {
        $res = [
            'status' => false,
            'msg'    => '',
            'data'   => []
        ];

        try {
            $book = Book::query()
            ->select('book_verify', 'book_share', 'book_free')
            ->where('book_id', $id)
            ->first();

            if ( !empty($book) ) {
                $res['status'] = true;
                $res['data']   = $book;
            } else {
                $res['msg']    = '繪本不存在';
            }
        } catch(\Exception $e) {
            $res['msg'] = '繪本不存在';
        }
        return $res;
    }
    // 收藏繪本
    public static function updateCollectById($id, $userId) {
        $res = [
            'status' => false,
            'msg'    => '收藏失敗！',
            'data'   => []
        ];

        $data = [
            'book_id' => $id,
            'user_id' => $userId
        ];

        try {
            $book = Book::query()
            ->where('book_id', $id)
            ->first();

            if ( !empty($book) ) {
                $where = [
                    'user_id' => $userId,
                    'book_id' => $id,
                ];
                $bookCollect = BookCollect::query()->where($where)->first();
                
                $res['msg'] = '收藏成功!';
                if ( empty($bookCollect) ) {
                    BookCollect::create($data);
                }
                else {
                    BookCollect::where($where)->update( ['status' => !$bookCollect['status']] );
                    if ( $bookCollect['status'] === 1 ) {
                        $res['msg'] = '取消收藏！';
                    }
                }

                $res['status'] = true;
            }
        } catch(\Exception $e) {
            Log::error('[收藏繪本 Error] ' . $e->getMessage());
        }
        return $res;
    }
    // 繪本搜尋
    public static function getInfoBySearch($data, $userId) {
        $res = [
            'status' => false,
            'msg'    => '',
            'data'   => []
        ];

        try {
            // 繪本審核: 0 是未審核、1是已審核 | 繪本狀態: 0 是未完成、1是已完成
            $where = [
                // ['book_verify', 1],
                ['book_state', 1],
            ];
            if ( !empty($data['free']) ) {
                $where[] = ['book_free', (int)$data['free']];
            }
            // tittle=書名(ch=中文 | en=英文) | auth=作者
            switch ($data['search_tag']) {
                case 'tittle_ch':
                    $switch = 'book_name_ch';
                break;
                case 'tittle_en':
                    $switch = 'book_name_en';
                break;
                default:
                    $switch = 'book_author';
                break;
            }
            $where[] = [$switch, 'like', $data['search_content'] . '%'];

            if ( empty($data['action']) ) {
                $data['action'] = '';
            }
            // new=每日最新 | like=猜你喜歡
            switch ($data['action']) {
                case 'new':
                    $orderBy = 'updated_at';
                break;
                case 'like':
                    $orderBy = 'book_like';
                break;
                default:
                    $orderBy = 'updated_at';
                break;
            }

            $book = Book::query()
            ->where($where)
            ->orderByDesc($orderBy)
            ->paginate($data['perPage'], ['book_id', 'book_frontcover', 'book_name_ch', 'book_name_en', 'book_author', 'book_author_id']
            , '', $data['page'])->items();
            if ( !empty($book) ) {
                // Log::info('[取得繪本內容] ' . json_encode($book));
                // pluck 當 key 的放後面
                // 取得作者資訊
                $book_author_id_list = array_column($book, 'book_author_id');
                $user                = User::select('id', 'name', 'avatar')->whereIn('id', $book_author_id_list)->get()->toArray();
                $user_list = [];
                foreach ($user as $v) {
                    $user_list[ $v['id'] ] = [
                        'name'   => $v['name'],
                        'avatar' => $v['avatar']
                    ];
                }
                // Log::info('[取得作者資訊] ' . json_encode($user_list));
                // 判斷是否收藏
                $where = [
                    'user_id' => $userId
                ];
                $book_collect = BookCollect::query()->where($where)->pluck('status', 'book_id')->all();
                // Log::info('[判斷是否收藏] ' . json_encode($book_collect));
                foreach ($book as &$v) {
                    $v['book_author']        = isset($user_list[ $v['book_author_id'] ]['name']) ? $user_list[ $v['book_author_id'] ]['name'] : NULL;
                    $v['book_author_avatar'] = isset($user_list[ $v['book_author_id'] ]) ? $user_list[ $v['book_author_id'] ] : NULL;
                    $v['collect']            = isset($book_collect[ $v['book_id'] ]) ? $book_collect[ $v['book_id'] ] : 0;
                    unset($v['book_author_id']);
                }
                
                $res['status'] = true;
                $res['data']   = $book;
            }
            else {
                $res['msg']    = '繪本不存在';
            }
        } catch(\Exception $e) {
            Log::error('[繪本搜尋 Error] ' . $e->getMessage());
        }
        return $res;
    }

    public static function getCategoryByTag($data) {
        $res = [
            'status' => false,
            'msg'    => '',
            'data'   => []
        ];

        try {
            $where = [
                ['book_verify', 1],
                ['book_free', (int)$data['free']]
            ];
            switch ($data['search_tag']) {
                case 'last':
                    $orderByDesc = 'updated_at';
                break;
                case 'recommend':
                    $orderByDesc = 'book_recommend';
                break;
                case 'hot':
                    $orderByDesc = 'book_like';
                break;
                default:
                    $res['msg'] = '繪本不存在';
                    return $res;
                break;
            }

            $book = Book::query()
            ->where($where)
            ->orderByDesc($orderByDesc)
            ->paginate($data['perPage'],
            ['book_author_id', 'book_id', 'book_frontcover', 'book_name_ch', 'book_name_en',
            'book_author', 'updated_at', 'book_like', 'book_recommend']
            , '', $data['page'])->items();

            if ( !empty($book) ) {
                $res['status'] = true;
                $res['data']   = $book;
            }
            else {
                $res['msg']    = '繪本不存在';
            }
        } catch(\Exception $e) {
            $res['msg'] = '繪本不存在';
        }
        return $res;
    }

    public static function getRecommendById($data, $id) {
        $res = [
            'status' => false,
            'msg'    => '',
            'data'   => []
        ];

        try {
            $where = [
                ['book_verify', 1],
                ['book_author_id', '=' , $id]
            ];
            // 判斷風格
            $book_style = Book::query()
            ->where($where)
            ->pluck('style')
            ->toArray();

            $counts = array_count_values($book_style);
            arsort($counts);
            $book_style = array_keys($counts);

            $where = [
                ['book_verify', 1]
            ];
            // 推薦的繪本
            $book = Book::query()
            ->where($where)
            ->whereIn('style', $book_style)
            ->paginate($data['perPage'],
            ['book_author_id', 'book_id', 'book_frontcover', 'book_name_ch', 'book_name_en',
            'book_author', 'updated_at', 'book_like', 'book_recommend']
            , '', $data['page'])->items();

            if ( !empty($book) ) {
                $res['status'] = true;
                $res['data']   = $book;
            }
            else {
                $res['msg']    = '請先創建一本繪本建立風格';
            }
        } catch(\Exception $e) {
            $res['msg'] = '繪本不存在';
        }
        return $res;
    }

    // 計算作者所有繪本總頁數
    public function getAllPagesCount($userId, $bookId = null) {
        $query = BookContent::query();

        if ($bookId !== null) {
            $query->where('book_id', $bookId);
        } else {
            $query->whereIn('book_id', function ($subQuery) use ($userId) {
                $subQuery->select('book_id')
                        ->from('books')
                        ->where('book_author_id', $userId);
            });
        }

        $count = $query->count();

        return $count;
    }


    // 計算 step count
    public function getStepCount($userId) {
        $result = BookContent::select('book_id', 'step')
            ->distinct()
            ->whereIn('book_id', function ($query) use ($userId) {
                $query->select('book_id')
                    ->from('books')
                    ->where('book_author_id', $userId);
            })
            ->get()
            ->toArray();

        return $result;
    }


    /**
     * 根據用戶 ID 和可選的繪本 ID 選取用戶輸入的繪本內容。
     * 如果提供了繪本 ID，則只選取該繪本的用戶輸入內容；
     * 如果沒有提供繪本 ID，則選取該用戶創建的所有繪本的用戶輸入內容。
     * 返回的是一個包含所有用戶輸入的串聯字串，各個輸入間用逗號分隔。
     *
     * @param  int      $userId 用戶的唯一識別 ID。
     * @param  int|null $bookId 可選的繪本唯一識別 ID。
     * @return string   串聯後的用戶輸入內容字串。
     */
    public function selectUserInput($userId, $bookId = null)
    {
        try {
            // 使用 Eloquent 的 selectRaw 方法來構建自定義的 SQL 查詢串聯字串。
            $query = BookContent::selectRaw("CONCAT_WS(',',
            IF(user_input IS NOT NULL AND user_input <> '', user_input, NULL),
            IF(user_pic_input_who IS NOT NULL AND user_pic_input_who <> '', user_pic_input_who, NULL),
            IF(user_pic_input_where IS NOT NULL AND user_pic_input_where <> '', user_pic_input_where, NULL),
            IF(user_pic_input_what IS NOT NULL AND user_pic_input_what <> '', user_pic_input_what, NULL),
            IF(en_user_input IS NOT NULL AND en_user_input <> '', en_user_input, NULL)
            ) AS concatenated_values");

            // 根據是否提供了繪本 ID，應用適當的查詢條件。
            if($bookId) {
                // 如果提供了繪本 ID，則添加條件以僅選取該繪本的內容。
                $query->where('book_id', $bookId);
            } else {
                // 如果沒有提供繪本 ID，則添加條件以選取該用戶創建的所有繪本的內容。
                $query->whereIn('book_id', Book::where('book_author_id', $userId)->pluck('book_id'));
            }
        } catch (\Exception $e) {
            // Handle exception
            Log::error('[selectUserInput Error] ' . $e->getMessage());
        }
        
        // 執行查詢，獲取結果並將非空的用戶輸入內容串聯成一個字串返回。
        return $query->get()
            ->pluck('concatenated_values')
            ->filter()
            ->implode(', ');
    }

}
