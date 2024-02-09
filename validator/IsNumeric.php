<?php

class IsNumeric
{
    public static function checkNumeric($data, $key)
    {
        if (!isset($data->$key) || !is_numeric($data->$key)) {
            return "Only numeric value allowed for $key.";
        }
        return null;
    }
}
