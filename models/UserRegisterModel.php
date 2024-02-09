<?php

include_once __DIR__ . "/../config/Database.php";
include_once __DIR__ .  "/../validator/Validation.php";

$db = new Database();

class UserRegisterModel
{
    const TABLE = 'register';

    public static function registerUserDetails()
    {
        $method = $_SERVER["REQUEST_METHOD"];

        if ($method !== "POST") {
            http_response_code(405);
            return [
                "status" => false,
                "message" => "Only POST method is allowed."
            ];
        }

        $data = json_decode(file_get_contents("php://input"));

        $keys = [
            "username" => ['required', 'minlength:3', 'maxlength:25'],
            "email" => ['required', 'email_format'],
            "password" => ['required', 'password_format'],
            "confirm_password" => ['required'],
        ];

        $validation_result = Validation::validate($data, $keys);

        if (!$validation_result['validate']) {
            http_response_code(400);
            return $validation_result;
        }

        $username = $data->username;
        $email = $data->email;
        $password = $data->password;
        $confirm_password = $data->confirm_password;

        if ($password != $confirm_password) {
            http_response_code(400);
            return [
                "status" => false,
                "message" => "Passwords did not match."
            ];
        }

        global $db;
        $emailCheck = "SELECT COUNT(*) FROM register WHERE email='$email'";
        $emailResult = $db->conn->query($emailCheck);
        $emailCount = $emailResult->fetch_assoc()['COUNT(*)'];

        if ($emailCount > 0) {
            http_response_code(400);
            return [
                "status" => false,
                "message" => "Email already exists."
            ];
        } else {

            $data->password = password_hash($data->password, PASSWORD_DEFAULT);
            unset($data->confirm_password);

            $inserted = self::create($data);
            if (!$inserted) {
                http_response_code(500);
                return [
                    "status" => false,
                    "message" => "Error occurred."
                ];
            }
            $data = [
                "username"  => $username,
                "email" => $email,

            ];

            http_response_code(200);
            return [
                "status" => true,
                "message" => "User registered successfully.",
                "data" => $data
            ];
        }
    }
    private static function create($data)
    {
        global $db;

        // Convert object to associative array
        $data_array = (array) $data;

        // Extract keys and values from the array
        $columns = implode(',', array_keys($data_array));

        $values = "'" . implode("','", array_values($data_array)) . "'";

        // Create the SQL query
        $sql = "INSERT INTO " . self::TABLE . " ($columns) VALUES ($values)";

        // Execute the query
        $result = $db->conn->query($sql);

        // Check if the query was successful
        if (!$result) {
            return false;
        }
        return true;
    }
}
