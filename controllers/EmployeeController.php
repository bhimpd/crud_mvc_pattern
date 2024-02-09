<?php

require_once __DIR__ . "/../models/EmployeeModel.php";

class EmployeeController
{
    public static function create()
    {
        $employee = EmployeeModel::createEmployee();

        //use http_response_code() to send status code as response

        http_response_code($employee["statusCode"]);
        unset ($employee["statusCode"]);
        echo json_encode($employee);
    }

    public static function read()
    {
    
        $employees = EmployeeModel::getAllEmployees();
        http_response_code($employees["statusCode"]);
        unset ($employees["statusCode"]);
        echo json_encode($employees);
    }

    public static function delete()
    {
        $employee = EmployeeModel::deleteEmployee();
        http_response_code($employee["statusCode"]);
        unset ($employee["statusCode"]);
        echo json_encode($employee);
    }
    
    public static function update()
    {
        $employee =EmployeeModel::updateEmployee();
        http_response_code($employee["statusCode"]);
        unset ($employee["statusCode"]);
        echo json_encode($employee);
    }
}
