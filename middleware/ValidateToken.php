<?php

require_once __DIR__ . "/../config/TokenGenerator.php";

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class ValidateToken
{
    private $token;
    public function __construct($token)
    {
        $this->token = $token;
    }
       
    public function validateToken($token)
   
    {
        // var_dump($token);
        if (!$token) {
            http_response_code(401);
            echo json_encode([
                "status" => false,
                "message" => "Unauthenticate. Missing token"
            ]);
            exit();
        }

        $key = "intuji";
        try {
            $decoded = JWT::decode($this->token, new Key($key, 'HS256'));
            return $decoded;
        } catch (Exception $e) {
            error_log($e->getMessage());
            http_response_code(401);
            echo json_encode([
                "status" => false,
                "message" => "Unauthenticate."
            ]);
            exit();
        }
    }
}
