<?php
/**
 * 寄信服務
 *
 */
declare(strict_types=1);

namespace App\Http\Services;
use Illuminate\Support\Facades\Mail;



class MailService
{

    public static function sendForgotPassword($receiver, $username, $resetCode) {

        $subject = '【Hello GPT 小學生AI繪本創作系統】密碼修改申請';
        $url = env('APP_URL', 'http://localhost') . '/api/user/forgot?code=' . $resetCode ;
        // todo 內容可調整
        $text = "哈囉 $username  ！<br/>
        我們收到您更改密碼的申請<br/>
        <br/>
        您可以直接點選 <a href='$url' target='_blank'> 這裡 </a> 進行重設密碼，或是將以下連結複製貼到您的瀏覽器內<br/>
        $url";

        self::send($subject, $text, $receiver);
    }

    public static function send($subject, $text, $receiver) {

        Mail::html($text, function($message) use($receiver, $subject)
        {
            $message->to($receiver)->subject($subject);
        });
    }
}
