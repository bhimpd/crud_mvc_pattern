<?php

require_once __DIR__ . "/../config/Database.php";

$db = new Database();

class DatabaseInsertion
{

    public static function insertData($data)
    {
        global $db;

        // Convert object to associative array
        $data_array = (array) $data;

        // Extract keys and values from the array
        $columns = implode(',', array_keys($data_array));
       
        $values = "'" . implode("','", array_values($data_array)) . "'";
      
        // $table_name = self::register();
        // Create the SQL query
        $sql = "INSERT INTO TABLE ($columns) VALUES ($values)";

        // Execute the query
        $result = $db->conn->query($sql);

        // Check if the query was successful
        if (!$result) {
            return false;
        }
        return true;
    }
    
}
