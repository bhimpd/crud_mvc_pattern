<?php

$request = $_SERVER["REQUEST_URI"];
$path = explode("/", $request);
$end = $path[1];

switch ($end) {
    case 'employee':
        include_once __DIR__ . "/employeeRoute.php";
        break;

    case 'student':
        include_once __DIR__ . "/studentRoute.php";
        break;

    case 'register':
        include_once __DIR__ . "/userRegisterRoute.php";
        break;

    case 'login':
        include_once __DIR__ . "/userLoginRoute.php";
        break;

    default:
        http_response_code(404);
        echo json_encode(
            [
                "message" => "Route not found"
            ]
        );
        break;
}

