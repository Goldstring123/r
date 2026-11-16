<?php
require_once __DIR__ . '/../config/config.php';

class Database {
    private ?PDO $conn = null;

    public function connect(): PDO {
        if ($this->conn === null) {
            try {
                $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
                $this->conn = new PDO($dsn, DB_USER, DB_PASS, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                ]);
            } catch (PDOException $e) {
                // Log the real error; show visitors a generic message.
                error_log('DB connection failed: ' . $e->getMessage());
                die('Sorry, the site is unavailable right now. Please try again later.');
            }
        }
        return $this->conn;
    }
}
?>
