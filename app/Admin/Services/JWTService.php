<?php
/**
 * JWT服務
 *
 */
declare(strict_types=1);

namespace App\Http\Services;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;


class JWTService
{
    public static function getToken($username, $name, $userId) {
        $time    = time();
        $JWT_TTL = env('JWT_TTL');
        $payload = [
            "username" => $username,
            "name" => $name,
            "user_id" => $userId,
            "iat" => $time, // 签发时间
            "exp" => $time + ($JWT_TTL > 3600 ? $JWT_TTL : 3600) // 到期时间，这里设置为1小时后
        ];
        return JWT::encode($payload, env('JWT_SECRET'), 'HS256');
    }

    public static function parseToken($token) {
        $decode = JWt::decode($token,  new Key(env('JWT_SECRET'), 'HS256'));
        return $decode;
    }
}
