<?php

require_once __DIR__ . "/../config/Database.php";

class VerifyToken
{
    public function verifyToken()
    {
        $db = new Database();

        // Fetch token data from the database
        $tokenQuery = "SELECT token, expired_at FROM token";
        $tokenResult = $db->conn->query($tokenQuery);

        // Check if any tokens are found
        if ($tokenResult->num_rows === 0) {
            http_response_code(404);
            echo json_encode([
                "status" => false,
                "message" => "No data found in the token table"
            ]);
            exit();
        }

        // Fetch all token data
        $tokenData = $tokenResult->fetch_all(MYSQLI_ASSOC);
        $tokensInDB = array_column($tokenData, 'token');

        // Retrieve the token from the HTTP headers
        $getToken = isset($_SERVER['HTTP_AUTHORIZATION']) ? $_SERVER['HTTP_AUTHORIZATION'] : null;

        // Check if token is provided
        if (!$getToken) {
            http_response_code(401);
            echo json_encode([
                "status" => false,
                "message" => "Unauthenticated. No token provided."
            ]);
            exit();
        }

        // Remove 'Bearer ' from the token string
        $getToken = str_replace('Bearer ', '', $getToken);

        // Check if the provided token exists in the database
        if (!in_array($getToken, $tokensInDB)) {
            http_response_code(401);
            echo json_encode([
                "status" => false,
                "message" => "Unauthenticated. Token does not match any in the database."
            ]);
            exit();
        }

        // Retrieve the expiration time of the token from the database
        $expiredAt = null;
        foreach ($tokenData as $tokenRow) {
            if ($tokenRow['token'] === $getToken) {
                $expiredAt = new DateTime($tokenRow['expired_at'], new DateTimeZone('Asia/Kathmandu'));
                break;
            }
        }

        // Get the current time in Asia/Kathmandu timezone
        $currentTime = new DateTime('now', new DateTimeZone('Asia/Kathmandu'));

        // Check if the token is expired
        if ($currentTime > $expiredAt) {
            http_response_code(401);
            echo json_encode([
                "status" => false,
                "message" => "Token has expired."
            ]);
            exit();
        }

        // Token is valid
        return true;
    }
}
?>
