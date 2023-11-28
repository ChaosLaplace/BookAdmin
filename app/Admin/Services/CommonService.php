<?php
/**
 * 通用服務
 *
 */
declare(strict_types=1);

namespace App\Http\Services;

use App\Constants\CodeConstant;

use Carbon\Carbon;

class CommonService
{

    public static function generateRandomString($length = 10) {
        $characters       = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString     = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[ random_int(0, $charactersLength - 1) ];
        }
        return $randomString;
    }

    public static function downloadPicToLocal($remoteImageURL, $downloadDirectory){
        $res = array();
        $res['state'] = false;
        $res['msg'] ='';
        $res['filePath'] = '';

        // 創建以當前日期為名的文件夾
        $todayDirectory = date('Y-m-d');
        $downloadDirectory .= $todayDirectory . '/';

        if (!is_dir($downloadDirectory)) {
            mkdir($downloadDirectory, 0755, true); // 創建目錄
        }

        // 生成隨機文件名
        $randomFilename = uniqid() . '-' . rand(1000, 9999) . '.jpg';

        // 下載遠程圖片
        $imageData = file_get_contents($remoteImageURL);

        if ($imageData !== false) {
            $localImagePath = $downloadDirectory . $randomFilename;
            // 保存到本地文件
            $result = file_put_contents($localImagePath, $imageData);

            if ($result !== false) {
                $res['state'] = true;
                $res['msg'] = "遠程圖片已成功下載到本地：$localImagePath";
                $res['filePath'] = $localImagePath;
            } else {
                $res['msg'] =  "保存到本地時出現問題，請檢查文件路徑和文件權限。";
            }
        } else {
            $res['msg'] =  "無法獲取遠程圖片，請檢查URL和網絡連接。";
        }
        return $res;
    }

    public static function generateRandomNum($length = 5) {
        $characters       = '0123456789';
        $charactersLength = strlen($characters);
        $randomString     = '';
        for ( $i = 0; $i < $length; ++$i ) {
            $randomString .= $characters[ random_int(0, $charactersLength - 1) ];
        }
        return $randomString;
    }

    public static function page_format($page) {
        $page = intval($page);
        if ($page < 1) {
            return 1;
        }
        return $page;
    }
    // 轉換特殊字元
    public static function specialTrans($data) {
        return htmlspecialchars($data);
    }
    // 轉換時間成 timestamp
    public static function transferTimestamp($time) {
        $carbonTime = Carbon::parse($time);
        return $carbonTime->timestamp;
    }

    public static function json_fail($msg, $code = CodeConstant::FAIL, $data = []) {
        $res = [
            'state' => false,
            'code'  => $code,
            'msg'   => $msg
        ];

        if ($data) {
            $res['data'] = $data;
        }
        return response()->json($res);
    }


    public static function json_success($msg, $data = [], $token = "", $accType="") {
        $res = [
            'state' => true,
            'code'  => CodeConstant::SUCCESS,
            'msg'   => $msg
        ];

        if ($data) {
            $res['data'] = $data;
        }
        if ($token) {
            $res['token'] = $token;
        }
        if($accType) {
            $res['accType'] = $accType;
        }
        return response()->json($res);
    }
}
