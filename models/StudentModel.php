<?php

include_once __DIR__ . "/../config/Database.php";
include_once __DIR__ . "/../validator/Validation.php";

$db = new Database();

class StudentModel
{
    const TABLE = 'student';

    public static function createStudent()
    {
        $method = $_SERVER['REQUEST_METHOD'];

        if ($method !== "POST") {
            http_response_code(405);
            return [
                "status" => false,
                "message" => " Only POST method is allowed. "
            ];
        }

        $data = json_decode(file_get_contents("php://input"));

        $keys = [
            "name" => ['required', 'minlength:3', 'maxlength:25'],
            "address" => ['required', 'minlength:4', 'maxlength:40'],
            "email" => ['required', 'email_format'],
            "age" => ['required', 'numeric'],
        ];

        $validation_result = Validation::validate($data, $keys);

        if (!$validation_result['validate']) {
            http_response_code(400);
            return $validation_result;
        }

        $name = $data->name;
        $address = $data->address;
        $email = $data->email;
        $age = $data->age;

        global $db;
        $emailCheck = "SELECT COUNT(*) FROM student WHERE email='$email'";
        $emailResult = $db->conn->query($emailCheck);
        $emailCount = $emailResult->fetch_assoc();

        if ($emailCount['COUNT(*)'] > 0) {
            http_response_code(400);
            return [
                "status" => false,
                "message" => "email already exists."
            ];
        }

        $inserted = self::create($data);

        if ($inserted) {
            $data = [
                "name"    => $name,
                "address" => $address,
                "email"   => $email,
                "age"  => $age,
            ];

            http_response_code(200);
            return [
                "status" => true,
                "message" => "data inserted successfully.",
                "data" => $data
            ];
        }
    }

    public static function readAllStudents()
    {
        $method = $_SERVER['REQUEST_METHOD'];

        if ($method !== "GET") {
            http_response_code(405);
            return [
                "status" => false,
                "message" => "Only GET method is allowed."
            ];
        }

        $result = self::readAll();

        if (!$result) {
            http_response_code(404);
            return [
                "status" => false,
                "message" => "no data to fetch",
            ];
        }

        $data = $result->fetch_all(MYSQLI_ASSOC);
        $total_num_of_data = $result->num_rows;

        http_response_code(200);
        return [
            "status" => true,
            "message" => "all data fetched successfully.",
            "data" => [
                "totalNumberOfData" => $total_num_of_data,
                "data" => $data
            ]
        ];
    }

    public static function readSingleStudent()
    {
        $method = $_SERVER["REQUEST_METHOD"];

        if ($method !== "GET") {
            http_response_code(405);
            return
                [
                    "status" => false,
                    "message" => "Only GET method is allowed."
                ];
        }

        $url = explode("/", $_SERVER["REQUEST_URI"]);
        $id = end($url);

        $idObject = new stdClass();
        $idObject->id = $id;

        $keys = [
            "id" => ['id_required', 'id_numeric'],

        ];

        $validation_result = Validation::validate($idObject, $keys);

        if (!$validation_result['validate']) {
            http_response_code(400);
            return $validation_result;
        }

        $result = self::read($id);

        if ($result->num_rows === 0) {
            http_response_code(404);
            return [
                "status" => false,
                "message" => "ID $id does not exist in the database."
            ];
        }

        $data = $result->fetch_assoc();
        http_response_code(200);
        return [
            "status" => true,
            "message" => "Data fetched successfully.",
            "data" => $data
        ];
    }

    public static function deleteStudent()
    {
        $method = $_SERVER["REQUEST_METHOD"];

        if ($method !== "DELETE") {
            http_response_code(405);
            return [
                "status" => false,
                "message" => "Only DELETE method is allowed."
            ];
        }
        $url = explode("/", $_SERVER["REQUEST_URI"]);
        $id = end($url);

        $idObject = new stdClass();
        $idObject->id = $id;

        $keys = [
            "id" => ['id_required', 'id_numeric'],

        ];

        $validation_result = Validation::validate($idObject, $keys);

        if (!$validation_result['validate']) {
            http_response_code(400);
            return $validation_result;
        }

        $result = self::read($id);
        $data = $result->fetch_assoc();

        $dltresult = self::delete($id);
        global $db;

        if ($dltresult) {

            if ($db->conn->affected_rows === 0) {
                http_response_code(404);
                return [
                    "status" => false,
                    "statusCode" => 404,
                    "message" => "No data found with ID $id to delete."
                ];
            }

            http_response_code(200);
            return [
                "status" => true,
                "message" => "Data with ID $id deleted successfully.",
                "data" => $data
            ];
        }
    }

    public static function updateStudent()
    {
        $method = $_SERVER["REQUEST_METHOD"];

        if ($method !== "PUT") {
            http_response_code(405);
            return [
                "status"  => false,
                "message" => "Only PUT method is allowed."
            ];
        }

        $url = explode("/", $_SERVER['REQUEST_URI']);
        $id = end($url);

        if (empty($id) || !is_numeric($id)) {
            http_response_code(400);
            return [
                "status" => false,
                "message" => "id should be numeric and not null."
            ];
        }

        $data = json_decode(file_get_contents("php://input"));

        $keys = [
            "name" => ['required', 'minlength:3', 'maxlength:25'],
            "address" => ['required', 'minlength:4', 'maxlength:40'],
            "email" => ['required', 'email_format'],
            "age" => ['required', 'numeric'],
        ];

        $validation_result = Validation::validate($data, $keys);

        if (!$validation_result['validate']) {
            http_response_code(400);
            return $validation_result;
        }

        $name = $data->name;
        $address = $data->address;
        $email = $data->email;
        $age = $data->age;

        $result = self::read($id);
        if ($result->num_rows === 0) {
            http_response_code(404);

            return [
                "status" => false,
                "message" => "Employee with ID $id does not exist."
            ];
        }

        $updateresult = self::update($id, $data);
        if ($updateresult) {
            http_response_code(200);
            $updated_student = [
                "name" => $name,
                "address" => $address,
                "email" => $email,
                "age" => $age
            ];

            return [
                "status" => true,
                "message" => "Employee with ID $id updated successfully",
                "updated_data" => $updated_student
            ];
        }
    }

    private static function create($data)
    {
        global $db;
        $data_array = (array) $data;
        $columns = implode(',', array_keys($data_array));
        $values = "'" . implode("','", array_values($data_array)) . "'";
        $sql = "INSERT INTO " . self::TABLE . " ($columns) VALUES ($values)";
        $result = $db->conn->query($sql);

        if (!$result) {
            return false;
        }
        return true;
    }

    //fetch single student details
    private static function read($id)
    {
        global $db;
        $sql = "SELECT * FROM  " . self::TABLE . " WHERE id = $id";
        $result = $db->conn->query($sql);

        if (!$result) {
            return false;
        }
        return $result;
    }


    // fetching all the data from db
    private static function readAll()
    {
        global $db;
        $sql = "SELECT * FROM  " . self::TABLE;
        $result = $db->conn->query($sql);

        if (!$result) {
            return false;
        }
        return $result;
    }

    //delete single student details
    private static function delete($id)
    {
        global $db;
        $sql = "DELETE FROM  " . self::TABLE . " WHERE id = $id";
        $result = $db->conn->query($sql);

        if (!$result) {
            return false;
        }
        return $result;
    }

    private static function update($id, $data)
    {
        global $db;
        $data_array = (array) $data;

        // Extract data values for update
        $update_data = [];
        foreach ($data_array as $key => $value) {
            $update_data[] = "$key = '$value'";
        }

        $update_values = implode(', ', $update_data);
        $sqlupdate = "UPDATE " . self::TABLE . " SET $update_values WHERE id = $id";
        $updateresult = $db->conn->query($sqlupdate);

        if (!$updateresult) {
            return false;
        }
        return true;
    }
}
