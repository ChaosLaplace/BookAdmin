<?php
/**
 * 家長
 *
 */
declare(strict_types=1);

namespace App\Http\Services;

use App\Models\Book;
use App\Models\BookCollect;
use App\Models\BookComment;
use App\Models\BookIncome;
use App\Models\BookOrder;
use App\Models\BookStar;
// use App\Models\ParentBookList;
use App\Models\User;

use App\Http\Services\CommonService;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

use Tymon\JWTAuth\Facades\JWTAuth;
class ParentService
{
    // // 取得家長繪本列表
    // public static function getParentBookListByParentId($userId) {
    //     $res = [
    //         'status' => false,
    //         'msg'    => '',
    //         'data'   => []
    //     ];

    //     try {
    //         $ParentBookList = ParentBookList::query()->where('parent_id', $userId)->value('book_list');
    //         if ( !empty($ParentBookList) ) {
    //             $res['status'] = true;
    //             $res['data']   = $ParentBookList;
    //         }
    //     } catch (\Exception $e) {
    //         Log::error('[取得家長繪本列表 Error] ' . $e->getMessage());
    //     }
    //     return $res;
    // }
    // // 更新家長繪本列表
    // public static function setParentBookList($book_list, $userId) {
    //     $res = [
    //         'status' => false,
    //         'msg'    => '',
    //         'data'   => []
    //     ];

    //     try {
    //         $data['book_list'] = CommonService::specialTrans($book_list);
    //         // 沒有就新增
    //         if ( !ParentBookList::where('parent_id', $userId)->exists() ) {
    //             $data['parent_id'] = $userId;
    //             ParentBookList::create($data);
    //         }
    //         else {
    //             ParentBookList::where('parent_id', $userId)->update($data);
    //         }

    //         $res['status'] = true;
    //     } catch (\Exception $e) {
    //         Log::error('[更新家長繪本列表 Error] ' . $e->getMessage());
    //     }
    //     return $res;
    // }
    // 繪本密碼鎖取得
    public static function getPwdByParentId($pwd, $user_id) {
        $res = [
            'status' => false,
            'msg'    => '密碼錯誤！',
            'data'   => []
        ];

        try {
            $username = User::query()->where('id', $user_id)->value('username');

            $valid = JWTAuth::attempt(['username' => $username, 'password' => $pwd]);
            if( $valid ) {
                $res['status'] = true;
                $res['msg']    = '密碼正確！';
            }
        } catch (\Exception $e) {
            Log::error('[繪本密碼鎖取得 Error] ' . $e->getMessage());
        }
        return $res;
    }
    // 家長繪本留言
    public static function comments($content, $id) {
        $res = [
            'status' => false,
            'msg'    => '',
            'data'   => []
        ];

        try {
            $data = [
                'book_id'   => $content['bookId'],
                'parent_id' => $id,
                'content'   => CommonService::specialTrans($content['content']),
            ];
            BookComment::create($data);

            $res['status'] = true;
        } catch (\Exception $e) {
            Log::error('[家長繪本留言 Error] ' . $e->getMessage());
        }
        return $res;
    }
    // 家長繪本按讚
    public static function like($book_id) {
        $res = [
            'status' => false,
            'msg'    => '',
            'data'   => []
        ];

        try {
            Book::query()->where('book_id', $book_id)->increment('book_like', 1);

            $res['status'] = true;
        } catch (\Exception $e) {
            Log::error('[家長繪本按讚 Error] ' . $e->getMessage());
        }
        return $res;
    }
    // 獲取繪本星星評價
    public static function getStars($book_id, $userId, $page = 1, $perPage = 20) {
        $res = [
            'status' => false,
            'msg'    => '',
            'data'   => []
        ];

        try {
            $where = [
                'book_id'   => $book_id,
                'parent_id' => $userId,
            ];
            // 計算星星評價
            $where = ['book_id' => $book_id];
            $stars = BookStar::query()->where($where)->sum('stars');
            $count = BookStar::query()->where($where)->count();
            $avg_stars = 0;
            if ( $count > 0 ) {
                $avg_stars = $stars / $count;
            }
            // 取得自己的星星評價
            $my_stars = BookStar::query()->where($where)->value('stars');
            // 取得評價用戶
            $user_list       = BookStar::query()->where($where)->pluck('stars', 'parent_id')->all();
            $parent_id_llist = array_keys($user_list);

            $filed        = ['id as user_id', 'avatar as user_avatar'];
            $user_ratings = User::query()->whereIn('id', $parent_id_llist)->paginate($perPage, $filed, '', $page)->items();
            foreach ($user_ratings as &$v) {
                $v['rating'] = isset($user_list[ $v['user_id'] ]) ? floatval($user_list[ $v['user_id'] ]) : NULL;
            }

            $res['status'] = true;
            $res['data'] = [
                'stars'        => floatval($avg_stars),
                'my_stars'     => intval($my_stars) ?? 0,
                'user_ratings' => $user_ratings
            ];
        } catch (\Exception $e) {
            Log::error('[獲取繪本星星評價 Error] ' . $e->getMessage());
        }
        return $res;
    }
    // 繪本星星評價 (不能改 只能一次)
    public static function setStars($content, $userId) {
        $res = [
            'status' => false,
            'msg'    => '',
            'data'   => []
        ];

        try {
            $where = [
                'book_id'   => $content['book_id'],
                'parent_id' => $userId,
            ];
            // 沒有就新增
            if ( !BookStar::where($where)->exists() ) {
                $data = [
                    'book_id'   => $content['book_id'],
                    'parent_id' => $userId,
                    'stars'     => $content['my_stars'],
                ];
                BookStar::create($data);

                $res['status'] = true;
                $res['msg']    = '評價成功!';
            }
            else {
                $res['msg']    = '已評價!';
            }
        } catch (\Exception $e) {
            Log::error('[繪本星星評價 Error] ' . $e->getMessage());
        }
        return $res;
    }
    // 我的收藏
    public static function getCollect($content, $userId) {
        $res = [
            'status' => false,
            'msg'    => '',
            'data'   => []
        ];

        try {
            $where = [
                'user_id' => $userId,
                'status'  => 1,
            ];
            $book_collect = BookCollect::query()->where($where)->get('book_id')->toArray();
            if ( !empty($book_collect) ) {
                $res = self::bookContent($book_collect, $res, $userId, $content['page'], $content['perPage']);
            }
        } catch (\Exception $e) {
            Log::error('[我的收藏 Error] ' . $e->getMessage());
        }
        return $res;
    }
    // 繪本購買紀錄
    public static function bookOrder($content, $userId) {
        $res = [
            'status' => false,
            'msg'    => '',
            'data'   => []
        ];

        try {
            $where = [
                'parent_id' => $userId
            ];
            $book_collect = BookOrder::query()->select('book_id')->where($where)->get()->toArray();
            if ( !empty($book_collect) ) {
                $res = self::bookContent($book_collect, $res, $userId, $content['page'], $content['perPage']);
            }
        } catch (\Exception $e) {
            Log::error('[繪本購買紀錄 Error] ' . $e->getMessage());
        }
        return $res;
    }
    // 購買繪本清單取得
    public static function cartByBookList($book_list, $userId) {
        $res = [
            'status' => false,
            'msg'    => '',
            'data'   => []
        ];

        try {
            $res['action'] = 'info';
            $res = self::bookContent( explode(',', $book_list), $res, $userId);
        } catch (\Exception $e) {
            Log::error('[購買繪本清單取得 Error] ' . $e->getMessage());
        }
        return $res;
    }
    // 購買繪本結帳
    public static function checkoutByBookList($book_list, $userId) {
        $res = [
            'status' => false,
            'msg'    => '購買繪本失敗',
            'data'   => []
        ];

        try {
            // 取得繪本購買價格
            $book = self::bookContent( explode(',', $book_list), $res, $userId);
            $book = isset($book['data']) ? $book['data'] : [];
            if ( !empty($book) ) {
                Log::info('[取得繪本] ' . json_encode($book));

                $book_author_id_list = array_column($book, 'book_author_id');
                $book_id_list        = array_column($book, 'book_id');
                $total_point         = array_column($book, 'book_point');
                $total_point         = array_sum($total_point);
                // 更新點數前
                $point = User::query()->where('id', $userId)->value('point');
                Log::info('[購買繪本結帳 Before] ' . $point);
                // 判斷金額
                if ( $total_point > 0 && $total_point <= $point ) {
                    // 驗證繪本是否已買過
                    $book_collect = BookOrder::query()->select('book_id')
                    ->where('parent_id', $userId)
                    ->whereIn('book_id', $book_id_list)->get()->toArray();
                    if ( !empty($book_collect) ) {
                        $book_collect = array_column($book_collect, 'book_id');
                    }
                    Log::info('[購買繪本結帳 驗證繪本是否已買過] ' . json_encode($book_collect));
                    // 取得所有作者當前點數
                    $author_point_list = User::query()->whereIn('id', $book_author_id_list)->pluck('point', 'id')->all();
                    // 計算訂單價格 && 繪本收益分潤 預設 10%
                    $data         = [];
                    $auth_data    = [];
                    $point_before = 0;
                    $temp_point   = $point;
                    $fee_rate     = 10;
                    $fee          = 0;
                    $income       = 0;
                    foreach ($book as $v) {
                        // 過濾買過的繪本
                        if ( !in_array($v['book_id'], $book_collect) ) {
                            $point_before = $temp_point;
                            $temp_point  -= $v['book_point'];
                            // 所有訂單
                            $data[] = [
                                'parent_id'         => $userId,
                                'book_id'           => $v['book_id'],
                                'book_point'        => $v['book_point'],
                                'user_point_before' => $point_before,
                                'user_point_after'  => $temp_point
                            ];

                            $fee          = $v['book_point'] * $fee_rate / 100;
                            $income       = $v['book_point'] - $fee;
                            $point_before = $author_point_list[ $v['book_author_id'] ];
                            $temp_point  += $income;
                            // 所有作者分潤
                            $auth_data[] = [
                                'auth_id'           => $v['book_author_id'],
                                'book_id'           => $v['book_id'],
                                'parent_id'         => $userId,
                                'book_point'        => $v['book_point'],
                                'book_income'       => $income,
                                'book_fee'          => $fee,
                                'auth_point_before' => $point_before,
                                'auth_point_after'  => $temp_point
                            ];
                        }
                    }
                    Log::info('[購買繪本結帳 插入訂單] ' . json_encode($data));
                    Log::info('[計算所有作者分潤 插入訂單] ' . json_encode($auth_data));

                    if ( !empty($data) ) {
                        DB::beginTransaction();
                        // 插入訂單
                        BookOrder::insert($data);
                        // 扣除點數
                        Log::info('[購買繪本結帳 扣除點數] ' . $total_point);
                        User::query()->where('id', $userId)->decrement('point', $total_point);
                        // 更新點數後
                        $point = User::query()->where('id', $userId)->value('point');
                        Log::info('[購買繪本結帳 After] ' . $point);
                        // 插入作者分潤訂單
                        BookIncome::insert($auth_data);
                        // 更新所有作者點數
                        foreach ($auth_data as $v) {
                            // 更新收益前
                            Log::info('[作者收益 Before] ' . $v['auth_point_before']);
                            User::query()->where('id', $v['auth_id'])->increment('point', $v['book_income']);
                            // 更新收益後
                            $point = User::query()->where('id', $v['auth_id'])->value('point');
                            Log::info('[作者收益 After] ' . $point);
                        }
                        DB::commit();

                        $res['status'] = true;
                        $res['msg']    = '購買繪本成功';
                    }
                    else {
                        $res['msg']    = '不存在未購買的繪本';
                    }
                }
                else {
                    Log::info('[判斷金額] total_point-> ' . $total_point . ' | point-> ' . $point);
                }
            }
        } catch (\Exception $e) {
            Log::error('[購買車結帳 Error] ' . $e->getMessage());
            DB::rollback();
        }
        return $res;
    }
    // 獲取繪本審核清單
    public static function getShelf($userId) {
        $res = [
            'status' => false,
            'msg'    => '',
            'data'   => []
        ];

        try {
            $res['action'] = 'info';
            // 未上架
            $where = [
                'book_author_id' => $userId,
                'book_verify'    => 0,
            ];
            $book = Book::query()->where($where)->get('book_id')->toArray();
            if ( !empty($book) ) {
                $res = self::bookContent($book, $res, $userId);
            }
        } catch (\Exception $e) {
            Log::error('[我的收藏 Error] ' . $e->getMessage());
        }
        return $res;
    }
    // 上架繪本（繪本文字設定、金額設定、設定預覽頁面）
    public static function setShelf($content, $userId) {
        $res = [
            'status' => false,
            'msg'    => '存檔失敗',
            'data'   => []
        ];

        try {
            $where = [
                'book_id'        => $content['book_id'],
                'book_author_id' => $userId
            ];
            // 更改繪本狀態為待審核
            if ( !Book::where($where)->exists() ) {
                $data = [
                    'book_state'   => 2,
                    'story_main'   => CommonService::specialTrans($content['story_main']),
                    'book_point'   => intval($content['book_point']),
                    'preview_page' => CommonService::specialTrans($content['preview_page'])
                ];
                Book::where($where)->update($data);

                $res['status'] = true;
                $res['msg']    = '存檔成功';
            }
            else {
                $res['msg']    = '繪本不存在';
            }
        } catch (\Exception $e) {
            Log::error('[我的收藏 Error] ' . $e->getMessage());
        }
        return $res;
    }
    // 獲取未上架繪本清單
    public static function shelfList($userId) {
        $res = [
            'status' => false,
            'msg'    => '',
            'data'   => []
        ];

        try {
            $res['action'] = 'info';
            $where = [
                'book_author_id' => $userId,
                'book_verify'    => 0,
                'book_state'     => 1
            ];
            $book = Book::query()->where($where)->get('book_id')->toArray();
            if ( !empty($book) ) {
                $res = self::bookContent($book, $res, $userId);
            }
        } catch (\Exception $e) {
            Log::error('[我的收藏 Error] ' . $e->getMessage());
        }
        return $res;
    }
    // 取得繪本內容
    public static function bookContent($book_collect, $res, $userId, $page = 1, $perPage = 4) {
        $bookList = array_column($book_collect, 'book_id');
        if ( empty($bookList) ) {
            $bookList = $book_collect;
        }

        if ( isset($res['action']) && $res['action'] === 'info' ) {
            $perPage = count($bookList);
        }
        $filed = ['book_id', 'book_frontcover', 'book_name_ch', 'book_name_en', 'book_author', 'book_author_id', 'book_point', 'preview_page'];
        $book = Book::query()->whereIn('book_id', $bookList)->paginate($perPage, $filed, '', $page)->items();
        // Log::info('[取得繪本內容] ' . json_encode($book));
        // pluck 當 key 的放後面
        // 取得作者資訊
        $book_author_id_list = array_column($book, 'book_author_id');
        // $book_author_id_list = User::query()->whereIn('id', $book_author_id_list)->pluck('name', 'avatar', 'id')->all();
        $user      = User::select('id', 'name', 'avatar')->whereIn('id', $book_author_id_list)->get()->toArray();
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
        $switch = empty($res['action']);
        foreach ($book as &$v) {
            $v['book_author'] = isset($user_list[ $v['book_author_id'] ]['name']) ? $user_list[ $v['book_author_id'] ]['name'] : NULL;
            
            if ( $switch ) {
                $v['book_author_avatar'] = isset($user_list[ $v['book_author_id'] ]['avatar']) ? $user_list[ $v['book_author_id'] ]['avatar'] : NULL;
                $v['collect']            = isset($book_collect[ $v['book_id'] ]) ? $book_collect[ $v['book_id'] ] : 0;
            }
        }

        $res['status'] = true;
        if ( !empty($book) ) {
            $res['data'] = $book;
        }
        return $res;
    }


}
