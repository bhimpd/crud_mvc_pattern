<?php

include_once __DIR__ . "/../models/UserRegisterModel.php";

class UserRegisterController{

    public static function registerUser(){
        $user_register = UserRegisterModel::registerUserDetails();
        echo json_encode($user_register);
    }
}