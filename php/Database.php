<?php

class Database {
    // private $host = 'srv2.grupofsolucoes.com.br';
    // private $db_name = 'versatiles_asos';
    // private $username = 'versatiles_versatiles';
    // private $password = 'A01d02c03b$';
    // private $conn;

    private $host = 'localhost';
    private $db_name = 'agenda';
    private $username = 'root';
    private $password = '';
    private $conn;

    public function getConnection() {
        $this->conn = null;

        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $exception) {
            echo "Connection error: " . $exception->getMessage();
        }

        return $this->conn;
    }
}
