<?php
class DatabaseConnection
{
    private PDO $conn;

    public function __construct(
        private $host = "localhost",
        private $db_name = "paiement",
        private $username = "root",
        private $password = ""
    ) {
        $this->conn = new PDO("mysql:host=$host;dbname=$db_name", $username, $password, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]);
        if (!$this->conn) {
            die('ne pas connnection rasute');
        }
    }

    public function getConnection() {
        return $this->conn;
    }
}