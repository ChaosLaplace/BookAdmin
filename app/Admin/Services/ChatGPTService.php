<?php
/**
 * chatgpt服務
 *
 */
declare(strict_types=1);

namespace App\Http\Services;

use App\Constants\LanguageConstant;
use App\Constants\StyleConstant;
use App\Http\Services\CommonService;
use Illuminate\Support\Facades\Log;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface;

class ChatGPTService
{


    public function translate($translateLanguate, $inputText){

        $res = [
            'status' => false,
            'msg' => '網路錯誤，請稍後再試！',
            'data' => '',
        ];

        $content = '請幫我把以下翻譯成';
        $content.= LanguageConstant::$translateSentence[$translateLanguate];
        $content.= ', 只需要返回翻譯後的文字即可, 不需返回多餘的敘述: ' . $inputText;

        $response =  $this->request($content);

        $responseJson = null;

        try{
            $responseJson = json_decode($response->getBody()->getContents(), true);
        } catch(\Exception $e) {
            $res['msg'] = '網路錯誤，請稍後再試！!';
            return $res;
        }

        if(empty($responseJson)){
            $res['msg'] = '網路錯誤，請稍後再試！';
            return $res;
        }

        if(!isset($responseJson['choices'][0]['message']['content'])){
            $res['msg'] = '網路錯誤，請稍後再試！！！';
            return $res;
        }

        $responseContent =$responseJson['choices'][0]['message']['content'];

        $res['status'] = true;
        $res['msg'] = '操作成功！';
        $res['data'] = ['translate_text' => $responseContent];

        return $res;
    }

    public function textToStory($userInput) {
        $res = [
            'status' => false,
            'msg' => '網路錯誤，麻煩稍後再試！',
            'data' => '',
        ];
        $content = '請幫我把冒號後的文字內容變得更通順, 並請只返回結果, 限制字數為20字. 注意! 請不要回簡體中文, 請回傳繁體中文 : ';
        $content.= $userInput;

        $response =  $this->request($content);

        $responseJson = null;

        try{
            $responseJson = json_decode($response->getBody()->getContents(), true);
        } catch(\Exception $e) {
            $res['msg'] = '網路錯誤，請稍後再試！!';
            return $res;
        }

        if(empty($responseJson)){
            $res['msg'] = '網路錯誤，請稍後再試！';
            return $res;
        }

        if(!isset($responseJson['choices'][0]['message']['content'])){
            $res['msg'] = '網路錯誤，請稍後再試！！！';
            return $res;
        }

        $responseContent =$responseJson['choices'][0]['message']['content'];

        $res['status'] = true;
        $res['msg'] = '操作成功！';
        $res['data'] = ['ch_story_ai' => $responseContent];

        return $res;
    }

    public function storyToPrompt($where, $who, $what) {
        $res = [
            'status' => false,
            'msg' => '網路錯誤，麻煩稍後再試！',
            'data' => '',
        ];

        $content = '請使用[]內的的關鍵字, 使用生成 dalli2 的 prompt 關鍵字 [';
        $content.= ' 地點:' . $where;
        $content.= ' 人物:' . $who;
        $content.= ' 發生的事:' . $what;
        $content.= '], 並翻譯成英文返回. 注意, 只要返回結果, 其他的不要返回';

        $response =  $this->request($content);

        $responseJson = null;

        try{
            $responseJson = json_decode($response->getBody()->getContents(), true);
        } catch(\Exception $e) {
            $res['msg'] = '網路錯誤，請稍後再試！!';
            return $res;
        }

        if(empty($responseJson)){
            $res['msg'] = '網路錯誤，請稍後再試！';
            return $res;
        }

        if(!isset($responseJson['choices'][0]['message']['content'])){
            $res['msg'] = '網路錯誤，請稍後再試！！！';
            return $res;
        }

        $responseContent =$responseJson['choices'][0]['message']['content'];
        $response1 = $responseContent;

        // 去掉所有雞婆換行
        $responseContent = str_replace("\r","",$responseContent);
        $responseContent = str_replace("\n","",$responseContent);
        $responseContent = str_replace("\n\r","",$responseContent);
        $responseContent = str_replace("\\n","",$responseContent);

        // 正則去除中文
        preg_match('/(\[地點).*?(])/', $responseContent, $matches , 0, 0);
        if(!empty($matches)){
	        $responseContent = str_replace($matches[0],'',$responseContent); // 去掉正則比對出來的中文請求 [.....]
        }

        preg_match('/.*(發生的事).*?(])/', $responseContent, $matches , 0, 0);
        if(!empty($matches)){
	        $responseContent = str_replace($matches[0],'',$responseContent); // 去掉正則比對出來的中文請求 [.....]
        }

        preg_match('/.*(發生的事).*?(】)/', $responseContent, $matches , 0, 0);
        if(!empty($matches)){
	        $responseContent = str_replace($matches[0],'',$responseContent); // 去掉正則比對出來的中文請求 [.....]
        }

        preg_match('/.*(回答：)/', $responseContent, $matches , 0, 0);
        if(!empty($matches)){
	        $responseContent = str_replace($matches[0],'',$responseContent); // 去掉正則比對出來的中文請求 [.....]
        }


        // 去掉我的問題(英文版)
        preg_match('/.*(please).*?(:)/', $responseContent, $matches , 0, 0);
        if(!empty($matches)){
	        $responseContent = str_replace($matches[0],'',$responseContent);
        }
        // 去掉我的問題(英文版2)
        preg_match('/(please).*?(\.)/i', $responseContent, $matches , 0, 0);
        if(!empty($matches)){
	        $responseContent = str_replace($matches[0],'',$responseContent);
        }

        preg_match('/.*(MentionedEntity).*?(\])/i', $responseContent, $matches , 0, 0); // 去掉雞婆的提示字
        if(!empty($matches)){
	        $responseContent = str_replace($matches[0],'',$responseContent);
        }

        preg_match('/.*(Mentioned).*?(\])/i', $responseContent, $matches , 0, 0); // 去掉雞婆的提示字
        if(!empty($matches)){
	        $responseContent = str_replace($matches[0],'',$responseContent);
        }

        preg_match('/.*(Translation:)/', $responseContent, $matches , 0, 0); // 去掉雞婆的提示字
        if(!empty($matches)){
	        $responseContent = str_replace($matches[0],'',$responseContent);
        }

        preg_match('/.*(Translated:)/', $responseContent, $matches , 0, 0); // 去掉雞婆的提示字
        if(!empty($matches)){
	        $responseContent = str_replace($matches[0],'',$responseContent);
        }

        // 取得真正的內容 into English .... :
        preg_match('/.*(into english).*?(\:)/i', $responseContent, $matches , 0, 0);
        if(!empty($matches)){
            $responseContent = str_replace($matches[0],'',$responseContent);
        }

        // 把一些不必要的東西去掉 into English ... "
        preg_match('/.*( into English).*?(\")/i', $responseContent, $matches , 0, 0);
        if(!empty($matches)){
            $responseContent = str_replace($matches[0],'',$responseContent);
        }

        // 把一些不必要的東西去掉 into English
        preg_match('/.*(into English).*?/i', $responseContent, $matches , 0, 0);
        if(!empty($matches)){
            $responseContent = str_replace($matches[0],'',$responseContent);
        }

        // 去掉 openai 的雞婆叮嚀
        preg_match('/(Note the translation).*/i', $responseContent, $matches , 0, 0);
        if(!empty($matches)){
            $responseContent = str_replace($matches[0],'',$responseContent);
        }

        // 去掉 openai 的雞婆叮嚀
        preg_match('/(Note).*?(\.)/i', $responseContent, $matches , 0, 0);
        if(!empty($matches)){
            $responseContent = str_replace($matches[0],'',$responseContent);
        }

        // 去掉 openai 的雞婆叮嚀
        preg_match('/.*(As a reminder).*?(\.)/i', $responseContent, $matches , 0, 0);
        if(!empty($matches)){
            $responseContent = str_replace($matches[0],'',$responseContent);
        }

        // 去掉自吹自擂的廢話
        preg_match('/.*(However).*?(\.)/i', $responseContent, $matches , 0, 0);
        if(!empty($matches)){
            $responseContent = str_replace($matches[0],'',$responseContent);
        }


        // 去掉 openai 的預防針-> As an AI ...... information about events
        preg_match('/.*(As an AI).*?(information about events)/i', $responseContent, $matches , 0, 0);
        if(!empty($matches)){
            $responseContent = str_replace($matches[0],'',$responseContent);
        }
        preg_match('/(As an AI).*?(\.)/i', $responseContent, $matches , 0, 0);
        if(!empty($matches)){
            $responseContent = str_replace($matches[0],'',$responseContent);
        }
        preg_match('/(I understand).*?(\.)/i', $responseContent, $matches , 0, 0);
        if(!empty($matches)){
            $responseContent = str_replace($matches[0],'',$responseContent);
        }

        preg_match('/.*(I am an AI).*?(\.)/i', $responseContent, $matches , 0, 0);
        if(!empty($matches)){
            $responseContent = str_replace($matches[0],'',$responseContent);
        }

        preg_match('/.*(translates to)/i', $responseContent, $matches , 0, 0);
        if(!empty($matches)){
            $responseContent = str_replace($matches[0],'',$responseContent);
        }

        // 去掉 openai 的哀求
        preg_match('/(Could you please ).*/i', $responseContent, $matches , 0, 0);
        if(!empty($matches)){
            $responseContent = str_replace($matches[0],'',$responseContent);
        }

        // 去掉雞婆的維基百科
        preg_match('/(\[).*(是).*(。)/', $responseContent, $matches , 0, 0);
        if(!empty($matches)){
            $responseContent = str_replace($matches[0],'',$responseContent);
        }

        preg_match('/(來源：).*/', $responseContent, $matches , 0, 0);
        if(!empty($matches)){
            $responseContent = str_replace($matches[0],'',$responseContent);
        }


        // 去除發問的英文, 中文, 冒號, 中括號
        $responseContent = str_ireplace("location:", " ", $responseContent);
        $responseContent = str_ireplace("character:", " ", $responseContent);
        $responseContent = str_ireplace("Person:", " ", $responseContent);
        $responseContent = str_ireplace("Personality:", " ", $responseContent);
        $responseContent = str_ireplace("Incident:", " ", $responseContent);
        $responseContent = str_ireplace("place:", " ", $responseContent);
        $responseContent = str_ireplace("event:", " ", $responseContent);
        $responseContent = str_replace("地點", " ", $responseContent);
        $responseContent = str_replace("人物", " ", $responseContent);
        $responseContent = str_replace("發生的事", " ", $responseContent);
        $responseContent = str_replace("事件", " ", $responseContent);
        $responseContent = str_ireplace("As Gen Dalli2", " ", $responseContent);
        $responseContent = str_ireplace("dalli2", "", $responseContent);
        $responseContent = str_ireplace("prompt", " ", $responseContent);
        $responseContent = str_ireplace("translated to English is:", " ", $responseContent);
        $responseContent = str_ireplace("translated", " ", $responseContent);
        $responseContent = str_ireplace("translate", " ", $responseContent);
        $responseContent = str_replace("[", " ", $responseContent);
        $responseContent = str_replace("]", " ", $responseContent);
        $responseContent = str_replace(":", " ", $responseContent);
        $responseContent = str_ireplace("translating to English.", " ", $responseContent);
        $responseContent = str_ireplace("Translation", "", $responseContent);
        $res['status'] = true;
        $res['msg'] = '操作成功！';
        $res['data'] = ['story_pic_prompt' => $responseContent, 'response1' => $response1];

        return $res;
    }

    public function promptToImg($storyPicPrompt, $style) {
        $res = [
            'status' => false,
            'msg' => '網路錯誤，麻煩稍後再試！',
            'data' => '',
        ];

        $style = StyleConstant::$styles[$style];

        $content = [];
        $content['prompt'] = $storyPicPrompt . $style . ' without any text'; // 不要有對話
        $content['n'] = 4; // num of pic
        $content['size'] = '1024x1024';
        //$content['response_format'] = 'b64_json';

        $response =  $this->imageRequest($content);

        $responseJson = null;

        try{
            $responseJson = json_decode($response->getBody()->getContents(), true);
        } catch(\Exception $e) {
            $res['msg'] = '網路錯誤，請稍後再試！!';
            return $res;
        }

        if(empty($responseJson)){
            $res['msg'] = '網路錯誤，請稍後再試！';
            return $res;
        }

        if(!isset($responseJson['data'])){
            $res['msg'] = '網路錯誤，請稍後再試！！！';
            return $res;
        }
        $responseArray = [];
        foreach($responseJson['data'] as $value){
            // 存圖
            $res = CommonService::downloadPicToLocal($value['url'], 'images/');
            if($res['state']){
                $responseArray[] = env('APP_URL') .'/'. $res['filePath'];
            }
        }


        $res['status'] = true;
        $res['msg'] = '操作成功！';
        $res['data'] = ['story_pic_ai' => $responseArray];

        return $res;
    }

    public function request($content) {
        $url = env('GPT_API_URL') . env('GPT_API_PREFIX') . '/chat/completions';

        $headers = [
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . env('GPT_API_KEY')
        ];

        $requestBody = [];
        $requestBody['model'] = env('GPT_API_MODEL');
        $requestBody['messages'] = [];
        $messages['role'] = env('GPT_API_ROLE');
        $messages['content'] = $content;
        $requestBody['messages'][] = $messages;

        return RequestService::postJson($url, $requestBody, $headers);
    }


    public function imageRequest($content) {
        $url = env('GPT_API_URL') . env('GPT_API_PREFIX') . '/images/generations';

        $headers = [
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . env('GPT_API_KEY')
        ];

        return RequestService::postJson($url, $content, $headers);
    }


    /**
     * 向 OpenAI 的 GPT 模型发送聊天样式的请求，并返回生成的响应。
     *
     * @param  string $label        哪一隻api呼叫它。
     * @param  integer $maxTokens   max_tokens值
     * @param  string $input1       聊天中用户的第一条信息。
     * @param  string $input2       聊天中用户的第二条信息。
     * @param  string|null $input3  可选的，聊天中用户的第三条信息。
     * @return string               来自 GPT 模型的响应，如果有错误则返回 JSON 格式的错误信息。
     */
    public function sendToChatGPT($label, $maxTokens, $input1, $input2 = null, $input3 = null): string
    {
        // Guzzle HTTP 客戶端實例化
        $client = new Client();

        // 組合請求 payload
        $prompt = [
            [
                "role" => "user",
                "content" => (string)$input1
            ]
        ];

        if ($input2 !== null) {
            $prompt[] = [
                "role" => "user",
                "content" => (string)$input2
            ];
        }

        if ($input3 !== null) {
            $prompt[] = [
                "role" => "user",
                "content" => (string)$input3
            ];
        }

        $payload = [
            'model' => 'gpt-3.5-turbo-1106',
            'messages' => $prompt,
            'temperature' => 0,
            'max_tokens' => $maxTokens,
            'frequency_penalty' => 0,
            'presence_penalty' => 0,
            'response_format' => ['type' => 'json_object'],
        ];

        // 自設超時時間（秒）
        $timeout = 10;

        // 最大重試次數
        $maxRetries = 6;
        $attempts = 0;

        while ($attempts < $maxRetries) {
            try {
                // 開始計時
                // $start_time = microtime(true);

                // 向 OpenAI 發送請求
                /** @var ResponseInterface $response */
                $response = $client->request('POST', 'https://api.openai.com/v1/chat/completions', [
                    'headers' => [
                        'Authorization' => 'Bearer ' . env('GPT_API_KEY'),
                        'Content-Type' => 'application/json'
                    ],
                    'json' => $payload,
                    'timeout' => $timeout
                ]);

                // 結束計時
                // $end_time = microtime(true);
                // 計算執行時間
                // $execution_time = $end_time - $start_time;
                // Log::info($label . ' 執行時間：' . $execution_time . ' 秒');
                // var_dump($label . " 執行時間： " . $execution_time . " 秒。");

                // 獲取嚮應內容
                $responseBody = $response->getBody()->getContents();

                // 解碼嚮應內容
                $response = json_decode($responseBody, true);

                if (json_last_error() === JSON_ERROR_NONE) {
                    // 檢查是否存在 "choices" key
                    if (isset($response['choices'][0]['message']['content'])) {
                        // 返回解碼後的内容
                        return $response['choices'][0]['message']['content'];
                    }
                }

            } catch (GuzzleException $e) {
                // 捕捉 Guzzle 異常
                Log::error($label . ' 請求失敗：' . $e->getMessage());
                
                sleep(1);
            }

            $attempts++;
            // Log::info($label . " 重試第 ". $attempts . " 次");
            // var_dump("重試第 ". $attempts . " 次");
        }

        return json_encode(['error' => 'Request failed after ' . $maxRetries . ' attempts']);
    }
}

       