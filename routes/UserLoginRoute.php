<?php
include_once __DIR__ . "/../controllers/UserLoginController.php";

class UserLogin {
    public static function login(){
        UserLoginController::loginUser();
    }
}
UserLogin::login();

