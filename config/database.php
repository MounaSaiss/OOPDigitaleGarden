<?php
class DatabaseConnection
{
    private PDO $conn;

    public function __construct(
        private $host = "localhost",
        private $dbName = "gardenjardin",
        private $username = "root",
        private $password = ""
    ) {
        $this->conn = new PDO("mysql:host=$host;dbname=$dbName", $username, $password, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]);
    }

    public function getConnection() {
        return $this->conn;
    }
}
