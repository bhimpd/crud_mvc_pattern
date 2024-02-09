<?php

require_once __DIR__ . "/../vendor/autoload.php";

use \Firebase\JWT\JWT;

class TokenGenerator
{
    public static function generateToken($user_id,$user_email)
    {
        $key = "intuji";
        $expiration_time = time() + 3600;
        $payload = [
            "user_id" => $user_id,
            "email" => $user_email,
            "expired" => $expiration_time
        ];
        return JWT::encode($payload, $key, 'HS256');

    }
}
