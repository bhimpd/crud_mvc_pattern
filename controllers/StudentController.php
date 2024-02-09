<?php
include_once __DIR__ . "/../models/StudentModel.php";

class StudentController
{

    public static function create()
    {
        $student = StudentModel::createStudent();
        echo json_encode($student);
    }

    public static function readAll()
    {
        $students = StudentModel::readAllStudents();
        echo json_encode($students);
    }

    public static function readSingle()
    {
        $student = StudentModel::readSingleStudent();
        echo json_encode($student);
    }

    public static function delete()
    {
        $student = StudentModel::deleteStudent();
        echo json_encode($student);
    }

    public static function update()
    {
        $student = StudentModel::updateStudent();
        echo json_encode($student);
    }
}
