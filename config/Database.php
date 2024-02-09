<?php

class Database
{
    private $servername = "localhost";
    private $username = "root";
    private $password = "";
    private $dbname = "blog";
    public $conn;

    public function __construct()
    {
        $this->conn = new mysqli($this->servername, $this->username, $this->password, $this->dbname);
        if (!$this->conn) {
            echo "database not connected" . $this->conn->connect_error;
           return false;
        }
        // echo "database connected.";
        return true;
    }
}

// $db = new Database();
