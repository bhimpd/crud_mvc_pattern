<?php
class EmailValidation
{
    public static function checkEmail($data, $key)
    {
        if (!isset($data->$key) || !filter_var($data->$key, FILTER_VALIDATE_EMAIL)) {
            return  "Invalid email format.";
        }
        return null;
    }
}
