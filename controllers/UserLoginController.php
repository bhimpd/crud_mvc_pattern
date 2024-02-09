<?php
include_once __DIR__ . "/../models/UserLoginModel.php";

class UserLoginController{
    public static function loginUser(){
        $user_login_details = userLoginModel::loginUserDetails();
        echo json_encode($user_login_details);
    }
}
