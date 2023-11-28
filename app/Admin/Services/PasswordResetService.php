<?php
/**
 * 忘記密碼服務
 *
 */
declare(strict_types=1);

namespace App\Http\Services;
use App\Models\PasswordReset;
use App\Constants\CodeConstant;


class PasswordResetService
{
    // 建立重製密碼的驗證碼
    public function setCode($email) {

        self::setCodeToUsed($email);
        $resetCode = CommonService::generateRandomString(20);

        $insertData = [
            'email' => $email,
            'reset_code' => $resetCode,
        ];

        PasswordReset::create($insertData);
        return $resetCode;
    }

    // 將驗證碼狀態改為已使用(使用email)
    public function setCodeToUsed($email) {
        PasswordReset::query()
        ->where('email', $email)
        ->update(['status' => 1]); // 其他先讓其他失效
    }

    // 更改驗證碼的狀態
    public function setCodeStatus($code, $status= CodeConstant::STATUS_COMPLETE){
        PasswordReset::query()
            ->where('reset_code', $code)
            ->update(['status' => $status]);
    }

    // 查看驗證碼有效性
    public function checkResetCode($code){

        $res =[
            'status' => false,
            'user' => ''
        ];

        $expire = time() - env('RESET_CODE_VALID_TIME', 1800);
        $expireDate = date('Y-m-d H:i:s', $expire);

        $passwordReset = PasswordReset::query()
        ->where('reset_code', $code)
        ->where('status', 0)
        ->where('created_at', '>=', $expireDate)
        ->first();

        if(empty($passwordReset)) { // 代表已過期, 或沒存在, 先把它改為失效
            // 隨著用戶變多 這邊建議使用 queue 排程更新 reset_code 狀態
            self::setCodeStatus($code);

            return $res;
        }
        $email = $passwordReset->email;
        $userService = new UserService();
        $user = $userService->getUserByEmail($email);

        $res['status'] = true;
        $res['user'] = $user;
        return $res;
    }
}
