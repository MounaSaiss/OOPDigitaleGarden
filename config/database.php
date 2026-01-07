<?php
class DatabaseConnection
{
    private PDO $pdo;
        private $host = "localhost";
        private $db_name = "GardenJardin";
        private $username = "root";
        private $password = "";

    public function __construct() {
        $this->pdo = new PDO("mysql:host=$this->host;dbname=$this->db_name", $this->username, $this->password, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]);
        if (!$this->pdo) {
            die('ne pas connnection rasute');
        }
    }

    public function getConnection() {
        return $this->pdo;
    }
}
