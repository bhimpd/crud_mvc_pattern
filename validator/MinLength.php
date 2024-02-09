<?php
class MinLength
{
    public static function checkMinLength($data, $key, $minLength)
    {
        if ((!isset($data->$key)) || (strlen($data->$key) < $minLength)) {
            return "$key should have a minimum length of $minLength characters.";
        }
        return null;
    }
}
