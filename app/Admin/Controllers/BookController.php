<?php

namespace App\Http\Controllers;

use App\Constants\CodeConstant;
use App\Http\Services\BookService;
use App\Http\Services\BookContentService;
use App\Http\Services\CommonService;
use App\Http\Services\JWTService;
use App\Http\Services\ParentService;
use App\Http\Services\UserService;

use \Illuminate\Http\Request;

class BookController extends Controller
{
    public function __construct(private BookService $bookService, private BookContentService $bookContentService){
    }

    public function booklist(Request $request) {
        $token = $request->header('Authorization');
        $userId = JWTService::parseToken($token)->user_id;

        $content = $request->all();
        $rules = [
            'page' => 'nullable|string',
            'per_page' => 'nullable|string'
        ];

        $validator = $this->getValidationFactory()->make($content, $rules);
        if($validator->fails()) {
            return $this->json_fail($validator->errors()->first(), CodeConstant::PARAM_FAIL);
        }

        $page = empty($content['page']) ? 1 : CommonService::page_format($content['page']) ;
        $perPage = empty($content['per_page']) ? CodeConstant::PER_PAGE : CommonService::page_format($content['per_page']) ;

        $result = $this->bookService->booklist($page, $perPage, $userId);
        if(!$result) {
            return $this->json_fail('網路錯誤，請稍後再試！');
        }
        return $this->json_success('取得成功！ ', $result);
    }

    public function editInfo(Request $request) {
        $token = $request->header('Authorization');
        $userId = JWTService::parseToken($token)->user_id;

        $content = $request->all();
        $rules = [
            'book_id' => 'nullable|string',
            'book_name_ch' => 'nullable|string',
            'book_name_en' => 'nullable|string',
            'style' => 'nullable|string',
            'book_frontcover' => 'nullable|string',
            'book_backcover' => 'nullable|string',
            'preface' => 'nullable|string'
        ];

        $validator = $this->getValidationFactory()->make($content, $rules);
        if($validator->fails()) {
            return $this->json_fail($validator->errors()->first(), CodeConstant::PARAM_FAIL);
        }

        $inputData = [];
        $inputData['book_id'] = $content['book_id'] ?? '';
        $inputData['book_name_ch'] = $content['book_name_ch'] ?? '';
        $inputData['book_name_en'] = $content['book_name_en'] ?? '';
        $inputData['style'] = $content['style'] ?? '';
        $inputData['book_frontcover'] = $content['book_frontcover'] ?? '';
        $inputData['book_backcover'] = $content['book_backcover'] ?? '';
        $inputData['preface'] = $content['preface'] ?? '';

        $result = $this->bookService->editInfo($inputData, $userId);
        if(!$result['status']){
            return $this->json_fail($result['msg']);
        }
        return $this->json_success($result['msg'], $result['data']);

    }

    public function getInfo(Request $request) {
        $token = $request->header('Authorization');
        $userId = JWTService::parseToken($token)->user_id;

        $content = $request->all();
        $rules = [
            'book_id' => 'required|string'
        ];

        $validator = $this->getValidationFactory()->make($content, $rules);
        if($validator->fails()) {
            return $this->json_fail($validator->errors()->first(), CodeConstant::PARAM_FAIL);
        }

        $bookId = $content['book_id'];

        $result = $this->bookService->getInfo($bookId, $userId);
        if(!$result['status']){
            return $this->json_fail($result['msg']);
        }
        return $this->json_success($result['msg'], $result['data']);
    }

    public function saveData(Request $request) {
        $token = $request->header('Authorization');
        $userId = JWTService::parseToken($token)->user_id;

        $content = $request->all();
        $rules = [
            'book_id' => 'required|string',
            'user_input' => 'nullable|string',
            'en_user_input' => 'nullable|string',
            'step' => 'required|string',
            'ch_story_ai' => 'nullable|string',
            'en_story_ai' => 'nullable|string',
            'page' => 'nullable|string',
            'user_pic_input' => 'nullable|array',
            'story_pic_ai' => 'nullable|array',
            'user_pic_select' => 'nullable|string',
            'position_input' => 'nullable|string',
        ];

        $validator = $this->getValidationFactory()->make($content, $rules);
        if($validator->fails()) {
            return $this->json_fail($validator->errors()->first(), CodeConstant::PARAM_FAIL);
        }

        $inputData = [];
        $inputData['book_id'] = $content['book_id'];
        $inputData['user_input'] = $content['user_input'] ?? '';
        $inputData['step'] = $content['step'] ;
        $inputData['ch_story_ai'] = $content['ch_story_ai'] ?? '';
        $inputData['en_story_ai'] = $content['en_story_ai'] ?? '';
        $inputData['en_user_input'] = $content['en_user_input'] ?? '';
        $inputData['page'] = $content['page'] ?? '';
        $inputData['user_pic_input'] = $content['user_pic_input'] ?? '';
        $inputData['story_pic_ai'] = $content['story_pic_ai'] ?? '';
        $inputData['user_pic_select'] = $content['user_pic_select'] ?? '';
        $inputData['position_input'] = $content['position_input'] ?? '';

        $result = $this->bookContentService->saveData($inputData, $userId);

        if(!$result['status']){
            return $this->json_fail($result['msg']);
        }
        return $this->json_success($result['msg'], $result['data']);
    }

    public function read(Request $request){
        $token = $request->header('Authorization');

        $content = $request->all();
        $rules = [
            'book_id' => 'required|string',
        ];

        $validator = $this->getValidationFactory()->make($content, $rules);
        if($validator->fails()) {
            return $this->json_fail($validator->errors()->first(), CodeConstant::PARAM_FAIL);
        }

        $bookId = $content['book_id'];

        $result = $this->bookService->read($bookId);

        if(!$result['status']){
            return $this->json_fail($result['msg']);
        }
        return $this->json_success($result['msg'], $result['data']);
    }

    public function getData(Request $request){
        $token = $request->header('Authorization');
        $userId = JWTService::parseToken($token)->user_id;

        $content = $request->all();
        $rules = [
            'book_id' => 'required|string',
            'step' => 'required|string',
            'page' => 'nullable|string'
        ];

        $validator = $this->getValidationFactory()->make($content, $rules);
        if($validator->fails()) {
            return $this->json_fail($validator->errors()->first(), CodeConstant::PARAM_FAIL);
        }

        $bookId = $content['book_id'];
        $step = $content['step'];
        $page = $content['page'] ?? '';

        $result = $this->bookContentService->getData($bookId, $step, $page, $userId);

        if(!$result['status']){
            return $this->json_fail($result['msg']);
        }
        return $this->json_success($result['msg'], $result['data']);
    }

    //下載-> 強制下載
    public function download(Request $request){

        $content = $request->all();
        $rules   = [
            'book_id' => 'required|string',
        ];

        $validator = $this->getValidationFactory()->make($content, $rules);
        if($validator->fails()) {
            return $this->json_fail($validator->errors()->first(), CodeConstant::PARAM_FAIL);
        }

        $bookId = $content['book_id'];

        $result = $this->bookService->createPdf($bookId);

        if ( !$result['status'] ) {
            return $this->json_fail($result['msg']);
        }
        $filePath = $result['data'];
        $headers = [
            'Content-Type: application/pdf',
        ];

        $bookNameEn = $result['data']['bookNameEn'];
        $filePath = $result['data']['filePath'];

        return response()->download($filePath, $bookNameEn .'.pdf', $headers); //強制下載
    }
    // 繪本簡介
    public function introduction(Request $request) {
        $token  = $request->header('Authorization');
        $userId = JWTService::parseToken($token)->user_id;

        $content = $request->all();
        $rules   = [
            'book_id' => 'bail|required|string'
        ];

        $validator = $this->getValidationFactory()->make($content, $rules);
        if ( $validator->fails() ) {
            return $this->json_fail($validator->errors()->first(), CodeConstant::PARAM_FAIL);
        }

        $result = $this->bookContentService::getIntroductionById($content['book_id'], $userId);

        if ( !$result['status'] ) {
            return $this->json_fail($result['msg']);
        }
        return $this->json_success($result['msg'], $result['data']);
    }
    // 設定繪本 分享 收費 查看審核狀態
    public function config(Request $request) {
        $content = $request->all();
        $rules   = [
            'book_id' => 'bail|required|string'
        ];

        $validator = $this->getValidationFactory()->make($content, $rules);
        if ( $validator->fails() ) {
            return $this->json_fail($validator->errors()->first(), CodeConstant::PARAM_FAIL);
        }

        $result = $this->bookContentService::getConfigById($content['book_id']);

        if ( !$result['status'] ) {
            return $this->json_fail($result['msg']);
        }
        return $this->json_success($result['msg'], $result['data']);
    }
    // 我的收藏
    public function getCollect(Request $request) {
        $token  = $request->header('Authorization');
        $userId = JWTService::parseToken($token)->user_id;

        $content = $request->all();
        $rules   = [
            'page' => 'bail|required|max:99|int',
        ];

        $validator = $this->getValidationFactory()->make($content, $rules);
        if ( $validator->fails() ) {
            return $this->json_fail($validator->errors()->first(), CodeConstant::PARAM_FAIL);
        }

        $content['page']    = empty($content['page']) ? CodeConstant::PAGE : CommonService::page_format($content['page']);
        $content['perPage'] = CodeConstant::PER_PAGE;
        $result = ParentService::getCollect($content, $userId);

        if ( !$result['status'] ) {
            return $this->json_fail($result['msg']);
        }
        return $this->json_success($result['msg'], $result['data']);
    }
    // 收藏繪本
    public function setCollect(Request $request) {
        $token  = $request->header('Authorization');
        $userId = JWTService::parseToken($token)->user_id;

        $content = $request->all();
        $rules   = [
            'book_id' => 'bail|required|string'
        ];

        $validator = $this->getValidationFactory()->make($content, $rules);
        if ( $validator->fails() ) {
            return $this->json_fail($validator->errors()->first(), CodeConstant::PARAM_FAIL);
        }

        $result = $this->bookContentService::updateCollectById($content['book_id'], $userId);

        if ( !$result['status'] ) {
            return $this->json_fail($result['msg']);
        }
        return $this->json_success($result['msg'], $result['data']);
    }
    // 繪本搜尋
    public function search(Request $request) {
        $token   = $request->header('Authorization');
        $userId  = JWTService::parseToken($token)->user_id;

        $content = $request->all();
        $rules   = [
            'search_tag'     => 'bail|required|max:15|string',
            'search_content' => 'bail|max:20|string',
            'free'           => 'bail|max:1|string',
            'page'           => 'bail|required|max:99|int',
            'action'         => 'bail|max:10|string',
        ];

        $validator = $this->getValidationFactory()->make($content, $rules);
        if ( $validator->fails() ) {
            return $this->json_fail($validator->errors()->first(), CodeConstant::PARAM_FAIL);
        }

        $content['page']    = empty($content['page']) ? CodeConstant::PAGE : CommonService::page_format($content['page']);
        $content['perPage'] = CodeConstant::PER_PAGE;
        $result = $this->bookContentService::getInfoBySearch($content, $userId);

        if ( !$result['status'] ) {
            return $this->json_fail($result['msg']);
        }
        return $this->json_success($result['msg'], $result['data']);
    }
    // 繪本分類（最新上架、推薦專區、火熱專區）
    public function category(Request $request) {
        $content = $request->all();
        $rules   = [
            'search_tag' => 'bail|required|max:10|string',
            'free'       => 'bail|required|max:1|string',
            'page'       => 'bail|required|max:99|int'
        ];

        $validator = $this->getValidationFactory()->make($content, $rules);
        if ( $validator->fails() ) {
            return $this->json_fail($validator->errors()->first(), CodeConstant::PARAM_FAIL);
        }

        $content['page']    = empty($content['page']) ? CodeConstant::PAGE : CommonService::page_format($content['page']);
        $content['perPage'] = CodeConstant::PER_PAGE;
        $result = $this->bookContentService::getCategoryByTag($content);

        if ( !$result['status'] ) {
            return $this->json_fail($result['msg']);
        }
        return $this->json_success($result['msg'], $result['data']);
    }
    // 繪本推薦（根據創建的繪本風格）
    public function recommend(Request $request) {
        $token  = $request->header('Authorization');
        $userId = JWTService::parseToken($token)->user_id;

        $content = $request->all();
        $rules   = [
            'page' => 'bail|required|max:99|int'
        ];

        $validator = $this->getValidationFactory()->make($content, $rules);
        if ( $validator->fails() ) {
            return $this->json_fail($validator->errors()->first(), CodeConstant::PARAM_FAIL);
        }

        $content['page']    = empty($content['page']) ? CodeConstant::PAGE : CommonService::page_format($content['page']);
        $content['perPage'] = CodeConstant::PER_PAGE;
        $result = $this->bookContentService::getRecommendById($content, $userId);

        if ( !$result['status'] ) {
            return $this->json_fail($result['msg']);
        }
        return $this->json_success($result['msg'], $result['data']);
    }
    // // 繪本清單(家長設置) 取得
    // public function getParentBookList(Request $request) {
    //     $token  = $request->header('Authorization');
    //     $userId = JWTService::parseToken($token)->user_id;

    //     $result = ParentService::getParentBookListByParentId($userId);

    //     if ( !$result['status'] ) {
    //         return $this->json_fail($result['msg']);
    //     }
    //     return $this->json_success($result['msg'], $result['data']);
    // }
    // // 繪本清單(家長設置) 設置
    // public function setParentBookList(Request $request) {
    //     $token  = $request->header('Authorization');
    //     $userId = JWTService::parseToken($token)->user_id;

    //     $content = $request->all();
    //     $rules   = [
    //         'book_list' => 'required|string'
    //     ];

    //     $validator = $this->getValidationFactory()->make($content, $rules);
    //     if ( $validator->fails() ) {
    //         return $this->json_fail($validator->errors()->first(), CodeConstant::PARAM_FAIL);
    //     }

    //     $result = ParentService::setParentBookList($content['book_list'], $userId);

    //     if ( !$result['status'] ) {
    //         return $this->json_fail($result['msg']);
    //     }
    //     return $this->json_success($result['msg'], $result['data']);
    // }
    // 繪本密碼鎖取得
    public function pwd(Request $request) {
        $token  = $request->header('Authorization');
        $userId = JWTService::parseToken($token)->user_id;

        $content = $request->all();
        $rules   = [
            'pwd' => 'bail|required|max:50|string'
        ];

        $validator = $this->getValidationFactory()->make($content, $rules);
        if ( $validator->fails() ) {
            return $this->json_fail($validator->errors()->first(), CodeConstant::PARAM_FAIL);
        }

        $result = ParentService::getPwdByParentId($content['pwd'], $userId);

        if ( !$result['status'] ) {
            return $this->json_fail($result['msg']);
        }
        return $this->json_success($result['msg'], $result['data']);
    }
    // 繪本留言
    public function comments(Request $request) {
        $token  = $request->header('Authorization');
        $userId = JWTService::parseToken($token)->user_id;

        $content = $request->all();
        $rules   = [
            'bookId'  => 'bail|required|int',
            'content' => 'bail|required|max:100|string'
        ];

        $validator = $this->getValidationFactory()->make($content, $rules);
        if ( $validator->fails() ) {
            return $this->json_fail($validator->errors()->first(), CodeConstant::PARAM_FAIL);
        }

        $result = ParentService::comments($content, $userId);
        
        if ( !$result['status'] ) {
            return $this->json_fail($result['msg']);
        }
        return $this->json_success($result['msg'], $result['data']);
    }
    // 繪本按讚
    public function like(Request $request) {
        $token  = $request->header('Authorization');
        $userId = JWTService::parseToken($token)->user_id;

        $content = $request->all();
        $rules   = [
            'book_id' => 'bail|required|int'
        ];

        $validator = $this->getValidationFactory()->make($content, $rules);
        if ( $validator->fails() ) {
            return $this->json_fail($validator->errors()->first(), CodeConstant::PARAM_FAIL);
        }

        $result = ParentService::like($content['book_id']);
        
        if ( !$result['status'] ) {
            return $this->json_fail($result['msg']);
        }
        return $this->json_success($result['msg'], $result['data']);
    }
    // 獲取繪本星星評價
    public function getStars(Request $request) {
        $token  = $request->header('Authorization');
        $userId = JWTService::parseToken($token)->user_id;

        $content = $request->all();
        $rules   = [
            'book_id'  => 'bail|required|int',
        ];

        $validator = $this->getValidationFactory()->make($content, $rules);
        if ( $validator->fails() ) {
            return $this->json_fail($validator->errors()->first(), CodeConstant::PARAM_FAIL);
        }

        $result = ParentService::getStars($content['book_id'], $userId);
        
        if ( !$result['status'] ) {
            return $this->json_fail($result['msg']);
        }
        return $this->json_success($result['msg'], $result['data']);
    }
    // 繪本星星評價 (不能改 只能一次)
    public function setStars(Request $request) {
        $token  = $request->header('Authorization');
        $userId = JWTService::parseToken($token)->user_id;

        $content = $request->all();
        $rules   = [
            'book_id'  => 'bail|required|int',
            'my_stars' => 'bail|required|int'
        ];

        $validator = $this->getValidationFactory()->make($content, $rules);
        if ( $validator->fails() ) {
            return $this->json_fail($validator->errors()->first(), CodeConstant::PARAM_FAIL);
        }

        $result = ParentService::setStars($content, $userId);
        
        if ( !$result['status'] ) {
            return $this->json_fail($result['msg']);
        }
        return $this->json_success($result['msg'], $result['data']);
    }
    // 繪本購買紀錄
    public function bookOrder(Request $request) {
        $token  = $request->header('Authorization');
        $userId = JWTService::parseToken($token)->user_id;

        $content = $request->all();
        $rules   = [
            'page' => 'bail|required|max:99|int',
        ];

        $validator = $this->getValidationFactory()->make($content, $rules);
        if ( $validator->fails() ) {
            return $this->json_fail($validator->errors()->first(), CodeConstant::PARAM_FAIL);
        }

        $content['page']    = empty($content['page']) ? CodeConstant::PAGE : CommonService::page_format($content['page']);
        $content['perPage'] = CodeConstant::PER_PAGE;
        $result = ParentService::bookOrder($content, $userId);
        
        if ( !$result['status'] ) {
            return $this->json_fail($result['msg']);
        }
        return $this->json_success($result['msg'], $result['data']);
    }
    // 購買繪本清單取得
    public function cartBookInfo(Request $request) {
        $token  = $request->header('Authorization');
        $userId = JWTService::parseToken($token)->user_id;

        $content = $request->all();
        $rules   = [
            'shopping_list' => 'bail|required|string',
        ];

        $validator = $this->getValidationFactory()->make($content, $rules);
        if ( $validator->fails() ) {
            return $this->json_fail($validator->errors()->first(), CodeConstant::PARAM_FAIL);
        }

        $result = ParentService::cartByBookList($content['shopping_list'], $userId);
        
        if ( !$result['status'] ) {
            return $this->json_fail($result['msg']);
        }
        return $this->json_success($result['msg'], $result['data']);
    }
    // 購買繪本結帳
    public function checkout(Request $request) {
        $token  = $request->header('Authorization');
        $userId = JWTService::parseToken($token)->user_id;

        $content = $request->all();
        $rules   = [
            'shopping_list' => 'bail|required|string',
        ];

        $validator = $this->getValidationFactory()->make($content, $rules);
        if ( $validator->fails() ) {
            return $this->json_fail($validator->errors()->first(), CodeConstant::PARAM_FAIL);
        }

        $result = ParentService::checkoutByBookList($content['shopping_list'], $userId);
        
        if ( !$result['status'] ) {
            return $this->json_fail($result['msg']);
        }
        return $this->json_success($result['msg'], $result['data']);
    }
    // 獲取繪本審核清單
    public function getShelf(Request $request) {
        $token  = $request->header('Authorization');
        $userId = JWTService::parseToken($token)->user_id;

        $result = ParentService::getShelf($userId);
        
        if ( !$result['status'] ) {
            return $this->json_fail($result['msg']);
        }
        return $this->json_success($result['msg'], $result['data']);
    }
    // 上架繪本（繪本文字設定、金額設定、設定預覽頁面）
    public function setShelf(Request $request) {
        $token  = $request->header('Authorization');
        $userId = JWTService::parseToken($token)->user_id;

        $content = $request->all();
        $rules   = [
            'book_id'      => 'bail|required|string',
            'story_main'   => 'bail|required|string',
            'book_point'   => 'bail|required|int',
            'preview_page' => 'bail|required|int'
        ];

        $validator = $this->getValidationFactory()->make($content, $rules);
        if ( $validator->fails() ) {
            return $this->json_fail($validator->errors()->first(), CodeConstant::PARAM_FAIL);
        }

        $result = ParentService::setShelf($content, $userId);
        
        if ( !$result['status'] ) {
            return $this->json_fail($result['msg']);
        }
        return $this->json_success($result['msg'], $result['data']);
    }
    // 獲取未上架繪本清單
    public function shelfList(Request $request) {
        $token  = $request->header('Authorization');
        $userId = JWTService::parseToken($token)->user_id;

        $result = ParentService::shelfList($userId);
        
        if ( !$result['status'] ) {
            return $this->json_fail($result['msg']);
        }
        return $this->json_success($result['msg'], $result['data']);
    }
    // 提現申請
    public function withdraw(Request $request) {
        $token  = $request->header('Authorization');
        $userId = JWTService::parseToken($token)->user_id;

        $content = $request->all();
        $rules   = [
            'book_list' => 'bail|required|string',
        ];

        $validator = $this->getValidationFactory()->make($content, $rules);
        if ( $validator->fails() ) {
            return $this->json_fail($validator->errors()->first(), CodeConstant::PARAM_FAIL);
        }

        $result = ParentService::checkoutByBookList($content['book_list'], $userId);
        
        if ( !$result['status'] ) {
            return $this->json_fail($result['msg']);
        }
        return $this->json_success($result['msg'], $result['data']);
    }
    // 提現（設置銀行帳戶訊息）
    public function bankInfo(Request $request) {
        $token  = $request->header('Authorization');
        $userId = JWTService::parseToken($token)->user_id;

        $content = $request->all();
        $rules   = [
            'book_list' => 'bail|required|string',
        ];

        $validator = $this->getValidationFactory()->make($content, $rules);
        if ( $validator->fails() ) {
            return $this->json_fail($validator->errors()->first(), CodeConstant::PARAM_FAIL);
        }

        $result = ParentService::checkoutByBookList($content['book_list'], $userId);
        
        if ( !$result['status'] ) {
            return $this->json_fail($result['msg']);
        }
        return $this->json_success($result['msg'], $result['data']);
    }
    // 提現申請紀錄
    public function withdrawRecord(Request $request) {
        $token  = $request->header('Authorization');
        $userId = JWTService::parseToken($token)->user_id;

        $content = $request->all();
        $rules   = [
            'book_list' => 'bail|required|string',
        ];

        $validator = $this->getValidationFactory()->make($content, $rules);
        if ( $validator->fails() ) {
            return $this->json_fail($validator->errors()->first(), CodeConstant::PARAM_FAIL);
        }

        $result = ParentService::checkoutByBookList($content['book_list'], $userId);
        
        if ( !$result['status'] ) {
            return $this->json_fail($result['msg']);
        }
        return $this->json_success($result['msg'], $result['data']);
    }
}
