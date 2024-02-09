<?php

include_once __DIR__ . "/../config/Database.php";
require_once __DIR__ . "/../config/TokenGenerator.php";

$db = new Database();

class UserLoginModel
{
    public static function loginUserDetails()
    {
        $method = $_SERVER['REQUEST_METHOD'];

        if ($method !== "POST") {
            return [
                "status" => false,
                "statusCode" => 405,
                "message" => " Only POST method is allowed. "
            ];
        }
        global $db;
        $data = json_decode(file_get_contents("php://input"));
        $email = $data->email;
        $password = $data->password;

        $sql = "SELECT * FROM register WHERE email = '$email'";
        $result = $db->conn->query($sql);
        $user = $result->fetch_assoc();

        if ($result->num_rows === 0) {
            http_response_code(404);
            return [
                "status" => false,
                "message" => "User does not exist. Invalid email."
            ];
        }

        if (!password_verify($password, $user['password'])) {
            http_response_code(401);
            return [
                "status" => false,
                "message" => "Authentication failed. Incorrect password."
            ];
        }

        $user_id = $user['id'];
        $jwt = TokenGenerator::generateToken($user['id'], $user['email']);
        $current_time = date('Y-m-d H:i:s');
        $expiration_time = date('Y-m-d H:i:s', strtotime('+1 hour', strtotime($current_time)));

        // Check if a token already exists for the user
        $checkTokenQuery = "SELECT * FROM token WHERE user_id = '$user_id'";
        $checkTokenResult = $db->conn->query($checkTokenQuery);

        if ($checkTokenResult->num_rows > 0) {
            $updateTokenQuery = "UPDATE token SET token = '$jwt', created_at = '$current_time', expired_at = '$expiration_time' WHERE user_id = '$user_id'";
        //    var_dump($current_time);die;
            $db->conn->query($updateTokenQuery);
        } else {
            $insertTokenQuery = "INSERT INTO token (user_id, token, created_at, expired_at) VALUES ('$user_id', '$jwt', '$current_time', '$expiration_time')";
            $db->conn->query($insertTokenQuery);
        }

        http_response_code(200);
        return [
            "status" => true,
            "message" => "Authentication successful.",
            "user" => $user,
            "jwt_token" => $jwt
        ];
    }
}
