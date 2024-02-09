<?php

$db = new Database();

class EmployeeModel extends Database
{
    public static function getAllEmployees()
    {
        $method = $_SERVER['REQUEST_METHOD'];

        if ($method !== "GET") {
            return [
                "status"  => false,
                "statusCode" => 405,
                "message" => "only get method is allowed."
            ];
            JSON_PRETTY_PRINT;
        }

        global $db;
        $sql = "SELECT * FROM employee";
        $result = $db->conn->query($sql);

        if ($result) {
            $data = $result->fetch_all(MYSQLI_ASSOC);
            $total_data = $result->num_rows;
            return [
                "status" => true,
                "statusCode" => 200,
                "message" => "Data fetched successfully",
                "data" => [
                    'total_data' => $total_data,
                    'data' => $data,
                ]
            ];
        } else {
            return [
                "status" => false,
                "statusCode" => 404,
                "message" => "Sorry! No data to fetch"
            ];
        }
    }
    public static function createEmployee(): array
    {
        $method = $_SERVER['REQUEST_METHOD'];

        if ($method !== "POST") {
            return [
                "status"  => false,
                "statusCode" => 405,
                "message" => "only post method is allowed."
            ];
            JSON_PRETTY_PRINT;
        }

        global $db;
        $data = json_decode(file_get_contents("php://input"));

        $name = $data->name;
        $email = $data->email;
        $address = $data->address;
        $salary = $data->salary;

        if (empty($name) || empty($email) || empty($address) || empty($salary) || !is_numeric($salary)) {
            return [
                "status" => false,
                "statusCode" => 400,
                "message" => "Data not inserted.All fields required and salary must be number...",
            ];
            exit();
        }

        $check_query = "SELECT COUNT(*) FROM employee WHERE email = '$email'";
        $check_result = $db->conn->query($check_query);
        $email_count_row = $check_result->fetch_assoc();

        if ($email_count_row['COUNT(*)'] > 0) {
            return [
                "status" => false,
                "statusCode" => 400,
                "message" => "data not inserted.email already exists."
            ];
            exit();
        }

        $sql = "INSERT INTO employee (name,email,address,salary) VALUES ('$name','$email','$address','$salary')";
        $result = $db->conn->query($sql);

        if ($result) {
            $data = [
                "name"    => $name,
                "email"   => $email,
                "address" => $address,
                "salary"  => $salary,
            ];
            return [
                "status" => true,
                "statusCode" => 200,
                "message" => "data inserted successfully.",
                "data" => $data
            ];
        }
    }

    public static function deleteEmployee()
    {
        global $db;
        $method = $_SERVER['REQUEST_METHOD'];

        if ($method !== "DELETE") {
            return [
                "status"  => false,
                "statusCode" => 405,
                "message" => "only delete method is used.."
            ];
            JSON_PRETTY_PRINT;
        }

        $url = explode("/", $_SERVER['REQUEST_URI']);
        $id = end($url);

        if (empty($id) || !is_numeric($id)) {
            return [
                "status" => false,
                "statusCode" => 400,
                "message" => "id must be numeric and non empty"
            ];
        }

        $sql = "SELECT * FROM employee WHERE id='$id'";
        $result = $db->conn->query($sql);
        $data = $result->fetch_assoc();

        $dltsql = "DELETE FROM employee WHERE id='$id'";
        $dltresult = $db->conn->query($dltsql);

        if ($dltresult) {
            if ($db->conn->affected_rows > 0) {
                return [
                    "status" => true,
                    "statusCode" => 200,
                    "message" => "Data with ID $id deleted successfully.",
                    "data" => $data
                ];
                JSON_PRETTY_PRINT;
            } else {
                return [
                    "status" => false,
                    "statusCode" => 404,
                    "message" => "No data found with ID $id to delete."
                ];
                JSON_PRETTY_PRINT;
            }
        }
    }
    public static function updateEmployee()
    {
        $method = $_SERVER["REQUEST_METHOD"];

        if ($method !== "PUT") {
            return [
                "status"  => false,
                "statusCode" => 405,
                "message" => "only put method is allowed."
            ];
            JSON_PRETTY_PRINT;
        }

        $url = explode("/", $_SERVER['REQUEST_URI']);
        $id = end($url);

        if (empty($id) || !is_numeric($id)) {
            return [
                "status" => false,
                "statusCode" => 400,
                "message" => "id should be numeric and not null."
            ];
        }

        global $db;
        $data = json_decode(file_get_contents("php://input"));
        $name = $data->name;
        $email = $data->email;
        $address = $data->address;
        $salary = $data->salary;

        // Check if the employee with the given ID exists
        $checkSql = "SELECT * FROM employee WHERE id='$id'";
        $checkResult = $db->conn->query($checkSql);

        if ($checkResult->num_rows === 0) {
            return [
                "status" => false,
                "statusCode" => 404,
                "message" => "Employee with ID $id does not exist."
            ];
        }

        // Update the employee record
        $sql = "UPDATE employee SET name='$name', email='$email', address='$address', salary='$salary' WHERE id='$id'";
        $result = $db->conn->query($sql);

        if ($result) {
            $updated_employee = [
                "name" => $name,
                "email" => $email,
                "address" => $address,
                "salary" => $salary
            ];
            return [
                "status" => true,
                "statusCode" => 200,
                "message" => "Employee with ID $id updated successfully",
                "updated_data" => $updated_employee
            ];
        } else {
            return [
                "status" => false,
                "statusCode" => 500,
                "message" => "Failed to update employee with ID $id."
            ];
        }
    }
}
