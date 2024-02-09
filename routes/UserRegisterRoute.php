<?php

include_once __DIR__ . "/../controllers/UserRegisterController.php";

class UserRegister
{
    public static function register()
    {
        userRegisterController::registerUser();
    }
}
UserRegister::register();
