<?php
class MaxLength
{
    public static function checkMaxLength($data, $key, $maxLength)
    {
        if ((!isset($data->$key)) || (strlen($data->$key) > $maxLength)) {
            return "$key should have a maximum length of $maxLength characters.";
        }
        return null;
    }
}
