<?php

class Database {
    private $host = "localhost";
    private $db_name = "api_db";
    private $user = "root";
    private $password = "";
    public $conn;

    public function getConnection() {
        $this->conn = null;

        try {
            $this->conn = new PDO("mysql:host=".$this->host.";dbname=".$this->db_name, $this->user, $this->password);
        } catch (PDOException $e) {
            echo "Connection error: ".$e->getMessage();
        }
        return $this->conn;
    }
}

?>