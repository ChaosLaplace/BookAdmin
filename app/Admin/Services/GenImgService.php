<?php
/**
 * 自家產圖服務
 *
 */
declare(strict_types=1);

namespace App\Http\Services;


use App\Constants\StyleConstant;
use Illuminate\Support\Facades\Log;

class GenImgService
{

    public function promptToImg($storyPicPrompt, $style) {
        $res = [
            'status' => false,
            'msg' => '網路錯誤，麻煩稍後再試！',
            'data' => '',
        ];

        $style = StyleConstant::$styles[$style];

        $content = [];
        $prompt = $storyPicPrompt . $style . ' without any text'; // 不要有對話
        $res['data'] = $content;
        $res['status'] = true;
        $width = 250;
        $height = 250;
        $expectedCount = 4;
        $content = self::buildContent($width,$height,$prompt, $expectedCount);
        $res['data']['content'] = $content;

        $responseArray = [];

        // 發送產圖請求
        $responseGenImg = self::genImgRequest($content);
        if(!empty($responseGenImg)){ // 非空
            $responseContents = $responseGenImg->getBody()->getContents(); // 取出psr7 content
            $responseContentJson = json_decode($responseContents, true); // 解析json
            if(!empty($responseContentJson['data'])){ // data不為空
                if(!empty($responseContentJson['data']['id'])){ // id有設定
                    $id = $responseContentJson['data']['id'];
                    Log::info("[生圖id: " . $id ." ] 開始循環call結果");
                    // 取得圖片id 開始請求圖片
                    // 循環呼叫取圖api 但是循環到120秒(2分鐘時) 自己結束 避免server當機
                    $counter = 0;
                    while(true){ // 有時間再改進吧...
                        $responseGetImgRes = self::getImgRequest($id); // 打 取圖api

                        Log::info("[開始取圖:  " . $responseGetImgRes['url'] ." ]");
                        $responseGetImg = $responseGetImgRes['res']; //取圖的結果
                        // 檢查圖片產出進度
                        if(!empty($responseGetImg)){
                            $responseContents = $responseGetImg->getBody()->getContents();// 取出psr7 content
                            $responseContentJson = json_decode($responseContents, true);// 解析json
                            if(!empty($responseContentJson['data'])) {
                                if(!empty($responseContentJson['data']['currentCount'])){
                                    if($expectedCount > $responseContentJson['data']['currentCount']){ // 需求數量
                                        Log::info("[生圖id: " . $id ." ], 需求數量: $expectedCount, 還沒好! -> :" . $responseContentJson['data']['currentCount']);
                                    } else {
                                        Log::info("[生圖id: " . $id ." ] 數量比對OK, 需求數量: $expectedCount, 回傳數量: " . $responseContentJson['data']['currentCount']);
                                        // 再檢查一下imgs數量
                                        if(!empty($responseContentJson["data"]["imgs"])
                                            && count($responseContentJson["data"]["imgs"]) == $expectedCount) {
                                                Log::info("[生圖id: " . $id ." ] 數量比對OK, 需求數量: $expectedCount , 陣列長度: " . count($responseContentJson["data"]["imgs"]));

                                                foreach($responseContentJson["data"]["imgs"] as $value){

                                                    // 存圖
                                                    $res = CommonService::downloadPicToLocal(env('GEN_API_URL') . $value, 'images/');
                                                    if($res['state']){
                                                        $responseArray[] = env('APP_URL') .'/'. $res['filePath'];
                                                    }
                                                }
                                                Log::info("[生圖id: " . $id ." ] 陣列內容: " . json_encode($responseArray));
                                            break;
                                        }

                                    }
                                } else {
                                    Log::info("[生圖id: " . $id ." ] 沒currentCount!!: " . json_encode($responseContentJson['data']));
                                }
                            } else {
                                Log::info("[生圖id: " . $id ." ] 沒data!!: " . json_encode($responseContentJson));
                            }
                        } else {
                            Log::info("[生圖id: " . $id ." ] 整個請求都失敗, 沒回傳!!!: " . json_encode($responseGetImg));
                        }

                        Log::info("[生圖id: " . $id ." ] 計數器(已經過秒數): " . $counter);
                        sleep(2);// 兩秒一次
                        $counter = $counter +2; // 計數器
                        $timeLimit = 120; // 時間改這
                        if($counter>=$timeLimit){
                            Log::info("[生圖id: " . $id ." ] 沒data!!: " . json_encode($responseContentJson));
                            break;
                        }
                    }
                } else {
                    Log::info("[生圖id空的!!], request content:" . json_encode($content) );
                    $res['msg'] = "網路錯誤，麻煩稍後再試!！!";
                    return $res;
                }
            } else {
                Log::info("[data空的!!], request content:".  json_encode($content) );
                $res['msg'] = "網路錯誤，麻煩稍後再試!！";
                return $res;
            }
            Log::info("[responseContentJson!!], request content:".  json_encode($content) . " , response: " . json_encode($responseContentJson));
        }

        $res['status'] = true;
        $res['msg'] = '操作成功！';
        $res['data'] = ['story_pic_ai' => $responseArray];

        return $res;
    }

    /**
     * 產生圖片
     */
    public function genImgRequest($content){
        $uri = '/text_to_img';
        return self::requestJson($content, $uri);
    }

    /**
     * 取得圖片
     */
    public function getImgRequest($pid){
        $uri = '/getimg?tid=' . $pid;
        return self::requestGet($uri);
    }

    public function buildContent($width, $height, $prompt,$imgCount ){

        $content = [];
        $content['chang_face'] = 0; //換臉 0: 不換\
        $content['source_image'] = ""; // 換臉原圖 "": 沒有
        $content['width'] = $width;
        $content['height'] = $height;
        $content['bak_url'] = "null";
        $content['img_data'] = [];
        $contentImgData = [];
        $contentImgData['prompt'] = $prompt;
        $contentImgData['negative_prompt'] = ""; // 反向提示詞
        $contentImgData['bak_data'] = "tid=20&pid=2"; // 文件沒寫 先帶這樣 反正也可以生 但是最好不要不帶
        $contentImgData['img_count'] = $imgCount;
        $content['img_data'][] = $contentImgData;
        return $content;
    }

    /**
     * 先分開寫 以後擴充使用
     */
    public function requestGet($uri){
        $url = env('GEN_API_URL') . env('GEN_API_PREFIX'). $uri;
        $res = [];
        $res['url'] = $url;
        $res['res'] = RequestService::get($url);
        return $res;
    }

    /**
     * 先分開寫 以後擴充使用
     */
    public function requestJson($content, $uri) {
        $url = env('GEN_API_URL') . env('GEN_API_PREFIX') . $uri;

        $headers = [
            'Content-Type' => 'application/json'
        ];
        return RequestService::postJson($url, $content, $headers);
    }

}
