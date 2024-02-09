<?php
class PasswordFormat
{
    public static function checkPassword($data, $key)
    {
        if (!isset($data->$key) || !preg_match('/^[a-zA-Z0-9]{4,20}$/', $data->$key)) {
            return "$key should be alphanumeric and between 4 and 20 characters long.";
        }
        return null;
    }
}
