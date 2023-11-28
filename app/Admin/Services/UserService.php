<?php
/**
 * 用戶服務
 *
 */
declare(strict_types=1);

namespace App\Http\Services;

use App\Models\Book;
use App\Models\BankInfo;
use App\Models\BookOrder;
use App\Models\User;
use App\Models\UserPayRecord;
use App\Models\PasswordReset;
use App\Models\WithdrawOrder;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

use Tymon\JWTAuth\Facades\JWTAuth;
class UserService
{

    public function getUserByEmail($email) {
        $result = User::query()->where(function ($query) use ($email){
            $query->orwhere('email', $email);
        })->first();
        if($result) {
            return $result;
        }
        return false;
    }

    public function checkUser($username, $email) {
        $result = User::query()->where(function ($query) use ($username, $email){
            $query->where('username', $username);
            $query->orwhere('email', $email);
        })->first();
        if($result) {
            return true;
        }
        return false;
    }

    public static function checkUserById($id) {
        $result = User::query()->where(function ($query) use ($id){
            $query->where('id', $id);
        })->first();
        if ( $result ) {
            return true;
        }
        return false;
    }

    /**
     * 註冊
     * @param string $name
     * @param string $email
     * @param string $username
     * @param string $password
     * @param string $accType school (學校版) / personal (個人版)
     * @param integer $point
     */
    public function register($name, $email, $username, $password, $accType = 'personal', $point = 0) {
        $exist = self::checkUser($username, $email);
        if($exist){
            return ['status' => false, 'msg' => '此信箱/帳號已註冊！', 'res'=>$exist];
        }

        $inputData = [
            'name' =>$name,
            'email' => $email,
            'username' => $username,
            'password' => $password,
            'acc_type' => $accType,
            'point' => $point
        ];

        $user = User::create($inputData);
        auth()->login($user);

        $res = ['status' => true, 'msg' => ''];

        return $res;
    }


    /**
     * 登入
     * @param string $username
     * @param string $password
     * @param string $accType school (學校版) / personal (個人版)
     */
    public function login($username, $password, $accType = 'personal'){

        // 使用這三個來比對是否登入成功
        $valid = JWTAuth::attempt(['username'=>$username, 'password' =>$password]);
        $res = [
            'status' => false,
            'msg' => '',
            'token' => '',
        ];

        if( !$valid){
            $res['msg'] = '帳號或密碼錯誤！';
        } else {
            $user = Auth::user();
            if($user->acc_type != $accType) {
                if($accType == 'school') {
                    $res['msg'] = '您的帳號是『個人帳號』請回首頁點選『個人版登入』';
                } else if($accType == 'personal') {
                    $res['msg'] = '您的帳號是『學校帳號』請回首頁點選『學校版登入』';
                }
            } else {
                $res['status'] = true;
                $res['token'] = JWTService::getToken($user->username, $user->name, $user->id);
            }
        }
        
        return $res;
    }

    public function getProfile($userId)
    {
        return User::query()->where('id', $userId)
            ->select(['username', 'email', 'name', 'birthday', 'gender', 'avatar', 'point', 'acc_type'])
            ->first();
    }

    public function editProfile($userId, $data)
    {
        $res = [
            'status' => false,
            'msg' => '',
            'data' =>[]
        ];
        $updateData = array_filter($data);

        // 檢查是否改到別人的 email, username
        if(isset($updateData['email'])){
            $isExist = User::query()
            ->where('email', $updateData['email'])
            ->where('id', '!=',  $userId)
            ->first();

            if(!empty($isExist)){
                $res['msg'] = '修改失敗, 用戶帳號/ 信箱已存在';
                return $res;
            }
        }

        if(isset($updateData['username'])){
            $isExist = User::query()
            ->where('username', $updateData['username'])
            ->where('id', '!=',  $userId)
            ->first();

            if(!empty($isExist)){
                $res['msg'] = '修改失敗, 用戶帳號 /信箱已存在';
                return $res;
            }
        }

        $updateResult =  User::query()->where('id', $userId)->update($updateData);

        if(empty($updateResult)){
            $res['msg'] = '網路錯誤，請稍後再試！';
            return $res;
        }

        $res['status'] = true;
        $res['msg'] = '修改成功！';

        return $res;
    }

    public function changepwd($userId, $passwordOld, $passwordNew)
    {
        $res = [
            'status' => false,
            'msg' => '',
            'token' => '',
        ];
        $user = User::query()->where('id', $userId)->first();
        if(empty($user)){
            $res['msg'] = '帳號或密碼錯誤！';
            return $res;
        }

        $valid = JWTAuth::attempt(['username'=>$user->username, 'password' =>$passwordOld]);

        if(!$valid) {
            $res['msg'] = '舊密碼錯誤！';
            return $res;
        }

        $updateData = ['password' => Hash::make($passwordNew)];
        $updateResult =  User::query()->where('id', $userId)->update($updateData);

        if(!$updateResult) {
            $res['msg'] = '修改失敗！';
            return $res;
        }

        $res['status'] = true;
        return $res;
    }


    public function resetPasswordByCodePassword($code, $password) {
        $res = [
            'status' => false,
            'msg'    => '',
            'data'   => []
        ];
        // 查看code 是否過期
        $passwordResetService = new PasswordResetService();
        $res = $passwordResetService->checkResetCode($code);

        if(!$res['status'] || empty($res['user'])) {
            $res['msg'] = '頁面已過期, 請重新申請驗證信';
            return $res;
        }

        $updateData = ['password' => Hash::make($password)];
        $updateResult =  User::query()->where('id', $res['user']->id)->update($updateData);

        if(!$updateResult) {
            $res['msg'] = '網路錯誤，請稍後再試！';
            return $res;
        }

        $res['status'] = true;
        $res['msg'] = '修改成功, 下次請使用新密碼登入';

        // 把狀態改為已使用
        $passwordResetService->setCodeStatus($code);

        return $res;
    }
    // 獲取儲值紀錄 使用紀錄 兌現紀錄 (point_deposit、point_usage、point_redemption)
    public static function getRecommendById($data, $userId) {
        $res = [
            'status' => false,
            'msg'    => '',
            'data'   => []
        ];

        try {
            $where = [
                ['user_id', $userId]
            ];
            $orderByDesc = 'id';
            // 儲值紀錄
            $user_pay_record = UserPayRecord::query()
            ->where($where)
            ->orderByDesc($orderByDesc)
            ->paginate($data['perPage'],
            [
                'user_order_no as orderId', 'user_point as point', 'user_payment as payment', 'created_at as timestamp'
            ]
            , '', $data['page'])->items();
            $where = [
                ['parent_id', $userId]
            ];
            foreach ($user_pay_record as &$v) {
                $v['timestamp'] = CommonService::transferTimestamp($v['timestamp']);
            }
            // 使用紀錄
            $book_order = BookOrder::query()
            ->where($where)
            ->orderByDesc($orderByDesc)
            ->paginate($data['perPage'],
            [
                'book_id', 'book_point as point', 'created_at as timestamp'
            ]
            , '', $data['page'])->items();
            // 查詢書本名稱
            $bookCHNameList = array_column($book_order, 'book_id');
            $bookCHNameList = Book::whereIn('book_id', $bookCHNameList)->pluck('book_name_ch', 'book_id')->all();
            foreach ($book_order as &$v) {
                if ( isset($bookCHNameList[  $v['book_id'] ]) ) {
                    $v['book_name_ch'] = $bookCHNameList[  $v['book_id'] ];
                }
                $v['timestamp'] = CommonService::transferTimestamp($v['timestamp']);
            }
            // 兌現紀錄
            $withdraw_order = WithdrawOrder::query()
            ->where($where)
            ->orderByDesc($orderByDesc)
            ->paginate($data['perPage'],
            [
                'book_point as point', 'payment', 'status', 'created_at as timestamp'
            ]
            , '', $data['page'])->items();
            foreach ($withdraw_order as &$v) {
                $v['timestamp'] = CommonService::transferTimestamp($v['timestamp']);
            }

            if ( !empty($user_pay_record) || !empty($book_order) || !empty($withdraw_order) ) {
                $res['status'] = true;
                $res['data']['point_deposit']    = $user_pay_record;
                $res['data']['point_usage']      = $book_order;
                $res['data']['point_redemption'] = $withdraw_order;
            }
            else {
                $res['msg']    = '沒有購買紀錄';
            }
        } catch(\Exception $e) {
            Log::error('[儲值紀錄 使用紀錄 兌現紀錄 Error] ' . $e->getMessage());
        }
        return $res;
    }

    public static function updateRecommendById($data) {
        $res = [
            'status' => false,
            'msg'    => '',
            'data'   => []
        ];

        try {
            $where = [
                ['user_order_sno', $data['id']]
            ];
            // 更新購買紀錄
            if ( $data['status'] === 'test' ) {
                $order_status = 1;
            }
            else {
                $order_status = 2;
            }
            $update_data   = ['user_payment_status' => $order_status];
            $update_result =  UserPayRecord::query()->where($where)->update($update_data);

            if ( $update_result ) {
                $res['status'] = true;
            }
            else {
                $res['msg']    = '沒有購買紀錄';
            }
        } catch(\Exception $e) {
            $res['msg'] = '沒有購買紀錄' . $e->getMessage();
        }
        return $res;
    }
    // 提現（設置銀行帳戶訊息）
    public static function bankInfo($content, $userId) {
        $res = [
            'status' => false,
            'msg'    => '設置失敗',
            'data'   => []
        ];

        try {
            // 沒有就新增
            if ( !BankInfo::where('parent_id', $userId)->exists() ) {
                $data = [
                    'parent_id'        => $userId,
                    'bank_country'     => CommonService::specialTrans($content['bankCountry']),
                    'bank_swift'       => CommonService::specialTrans($content['bankSwift']),
                    'bank_name'        => CommonService::specialTrans($content['bankName']),
                    'bank_code'        => CommonService::specialTrans($content['bankCode']),
                    'bank_branch_code' => CommonService::specialTrans($content['bankBranchCode']),
                    'bank_branch_addr' => CommonService::specialTrans($content['bankBranchAddr']),
                    'bank_account'     => CommonService::specialTrans($content['bankAccount']),
                    'bank_number'      => CommonService::specialTrans($content['bankNumber'])
                ];
                BankInfo::create($data);

                $res['status'] = true;
                $res['msg']    = '設置成功';
            }
            else {
                $res['msg']    = '修改需聯絡客服驗證本人';
            }
        } catch (\Exception $e) {
            Log::error('[提現（設置銀行帳戶訊息） Error] ' . $e->getMessage());
        }
        return $res;
    }
    // 提現申請
    public static function withdraw($content, $userId) {
        $res = [
            'status' => false,
            'msg'    => '設置失敗',
            'data'   => []
        ];

        try {
            // 有設置銀行帳戶訊息 才能申請
            if ( BankInfo::where('parent_id', $userId)->exists() ) {
                // 已有審核中提現紀錄
                if ( WithdrawOrder::where(['parent_id' => $userId, 'status' => 0])->exists() ) {
                    $res['msg'] = '已有審核中提現，請稍待 3 ~ 7 個工作日';
                    return $res;
                }

                $point = User::where('id', $userId)->value('point');
                // 判斷提現點數
                if ( $content['point'] > $point ) {
                    $res['msg'] = '提現點數異常';
                    return $res;
                }

                $bank_id = BankInfo::where('parent_id', $userId)->value('id');
                $data = [
                    'parent_id'  => $userId,
                    'bank_id'    => $bank_id,
                    'phone'      => CommonService::specialTrans($content['phone']),
                    'book_point' => $content['point'],
                    'payment'    => $content['payment']
                ];
                WithdrawOrder::create($data);

                $res['status'] = true;
                $res['msg']    = '提現提交成功，請稍待 3 ~ 7 個工作日';
            }
            else {
                $res['msg']    = '未設置銀行帳戶訊息';
            }
        } catch (\Exception $e) {
            Log::error('[提現（設置銀行帳戶訊息） Error] ' . $e->getMessage());
        }
        return $res;
    }
    // 提現申請紀錄
    public static function withdrawRecord($userId) {
        $res = [
            'status' => false,
            'msg'    => '查詢失敗',
            'data'   => []
        ];

        try {
            // 有設置銀行帳戶訊息 才能申請
            if ( $WithdrawOrder = WithdrawOrder::where('parent_id', $userId)->first() ) {
                $res['status'] = true;
                $res['msg']    = '查詢成功';
                $res['data']   = [
                    'id'         => $WithdrawOrder['id'],
                    'phone'      => $WithdrawOrder['phone'],
                    'book_point' => $WithdrawOrder['book_point'],
                    'payment'    => $WithdrawOrder['payment'],
                    'status'     => $WithdrawOrder['status']
                ];
            }
            else {
                $res['msg'] = '沒有提現申請紀錄';
            }
        } catch (\Exception $e) {
            Log::error('[提現申請紀錄 Error] ' . $e->getMessage());
        }
        return $res;
    }
}
