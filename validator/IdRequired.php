<?php
class IdRequired{
    public static function checkId($data,$key){
        if (!isset($data->$key) || empty($data->$key)) {
            return "Student ID is required to read a single student.";
        }
        return null;
    }
}