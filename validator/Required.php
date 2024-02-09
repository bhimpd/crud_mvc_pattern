<?php

class Required
{
    public static function checkRequired($data, $key)
    {
        if (!isset($data->$key) || empty($data->$key)) {
            return "$key is a required field!";
        }
        return null; 
    }
}

