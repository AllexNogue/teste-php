<?php
 
class db {

    /**
     * Conexão com o banco
     */
    public function connect()
    {
        
        require '../config.php';

        $mysql = new mysqli($config['db_host'], $config['db_user'], $config['db_pass'], $config['db_name']);

        if ($mysql->connect_error) {
            die("Connection failed: " . $mysql->connect_error);
        }

        return $mysql;

    }

    /**
     * Método para inserção
     */
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

    /**
     * Método para busca
     */
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

    /**
     * Método para executar SQL puro
     */
    public function pure($sql)
    {
        $conn = $this->connect();
        
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

    /**
     * Método retorna sempre 1 linha da consulta
     */
    public function getRow($table, $fields = '*', $where = null)
    {
        $conn = $this->connect();
        
        $sql = "SELECT ". $fields . " FROM " . $table . " " . $where . " LIMIT 1";
       
        $result = $conn->query($sql);
        $data = [];
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $data = $row;
            }
            return json_encode($data);
        } else {
            return json_encode([]);
        }
    }

    /**
     * Método para atualização
     */
    public function update($id, $table, $data)
    {
        $conn = $this->connect();
        $campos_valores = '';

        foreach($data as $coluna => $valor){
            
            $campos_valores .= $coluna . ' = "' . $valor . '", ';
        }
        $campos_valores = substr($campos_valores, 0, -2);
        $sql = "UPDATE " .$table. " SET ".$campos_valores."
        WHERE id = ".$id;

        if ($conn->query($sql) === TRUE) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Método para remoção
     */
    public function delete($id, $table)
    {
        $conn = $this->connect();
        
        $sql = "DELETE FROM " . $table . " WHERE id = " . $id;

        if ($conn->query($sql) === TRUE) {
            return true;
        } else {
            return false;
        }
    }

}

?>