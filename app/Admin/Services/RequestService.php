<?php
/**
 * http request服務
 *
 */
declare(strict_types=1);

namespace App\Http\Services;
use App\Constants\RequestConstant;


class RequestService
{
    private static $client;

    public static function getClient() {
        if(empty(self::$client)){
            self::$client =new \GuzzleHttp\Client();
        }
        return self::$client;
    }

    public static function get($url) {
        $client = self::getClient();
        return  $client->get( $url);
    }

    public static function postJson($url, $body, $headers) {
        return self::post($url, $body, $headers);
    }

    public static function postBody($url, $body, $headers) {
        return self::post($url, $body, $headers, RequestConstant::PAYLOAD_TYPE_BODY);
    }

    public static function postForm($url, $body, $headers) {
        return self::post($url, $body, $headers, RequestConstant::PAYLOAD_TYPE_FORM);
    }

    public static function post($url, $body, $headers = [], $payloadType = RequestConstant::PAYLOAD_TYPE_JSON) {
        $client = self::getClient();

        switch($payloadType){
            case RequestConstant::PAYLOAD_TYPE_JSON:
                $body = ['json' => $body];
                break;
            case RequestConstant::PAYLOAD_TYPE_BODY:
                $body = ['body' => $body];
                break;
            case RequestConstant::PAYLOAD_TYPE_FORM:
                $body = ['form_params' => $body];
                break;
        }

        if(!empty($headers)){
            $body['headers'] = $headers;
        }

        return  $client->post( $url, $body);
    }
}
