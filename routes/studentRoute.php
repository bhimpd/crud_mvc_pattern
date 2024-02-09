<?php
include_once __DIR__ . "/../controllers/StudentController.php";
// require_once __DIR__ . "/../middleware/validateToken.php";
require_once __DIR__ . "/../middleware/VerifyToken.php";

$request = $_SERVER['REQUEST_URI'];
$url = explode("/", $request);
$endpoint = "/" . $url[2];

$token = isset($_SERVER['HTTP_AUTHORIZATION']) ? $_SERVER['HTTP_AUTHORIZATION'] : null;

switch ($endpoint) {
    case '/create':
        StudentController::create();
        break;

    case '/readall':
        StudentController::readAll();
        break;

    case '/readsingle':
        StudentController::readSingle();
        break;

    case '/delete':
    case '/update':
        // Verify the token
        $verifyToken = new VerifyToken(); 
        $verifyToken->verifyToken($token); 

        // If the token is valid, proceed with the requested operation
        if ($endpoint === '/delete') {
            StudentController::delete();
        } elseif ($endpoint === '/update') {
            StudentController::update();
        }
        break;

    default:
        http_response_code(404);
        echo json_encode(
            [
                "status" => false,
                "message" => "Route does not match."
            ]
        );
        break;
}
?>
