<?php
 
class db {

    public function connect()
    {
        
        require '../config.php';

        $mysql = new mysqli($config['db_host'], $config['db_user'], $config['db_pass'], $config['db_name']);

        if ($mysql->connect_error) {
            die("Connection failed: " . $mysql->connect_error);
        }

        return $mysql;

    }

    public function insert($table, $data){

        $conn = $this->connect();
        $campos = implode(", ", array_keys($data));
        $valores = "'". implode("', '", array_values ($data)) . "'";
        
        $sql = "INSERT INTO " .$table. " (".$campos.")
        VALUES (".$valores.")";
       
        if ($conn->query($sql) === TRUE) {
            return $conn->insert_id;
        } else {
            return false;
        }

    }

    public function select($table, $fields = '*', $where = null)
    {
        $conn = $this->connect();
        
        $sql = "SELECT ". $fields . " FROM " . $table . " " . $where;
       
        $result = $conn->query($sql);
        $data = [];
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
            return json_encode($data);
        } else {
            return json_encode([]);
        }
        

    }
}

?>