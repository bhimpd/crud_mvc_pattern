<?php
include_once __DIR__ . "/../controllers/EmployeeController.php";

$request = $_SERVER['REQUEST_URI'];
$url = explode("/", $request);
$end = "/".$url[2];

switch ($end) {
    case '/create':
        employeeController::create();
        break;
    case '/read':
        employeeController::read();
        break;
    case '/delete':     
        employeeController::delete();
        break;
    case '/update':
        EmployeeController::update();
        break;
    default:
        http_response_code(404);
        echo json_encode(
            [
                "message" => "Not Found"
            ]
        );
        break;
}
