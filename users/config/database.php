<?php
// Өгөгдлийн сангийн тохиргоо

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', ''); // Өөрийн нууц үгээ оруулна уу
define('DB_NAME', 'companydatabase');

class Database {
    private $host = DB_HOST;
    private $user = DB_USER;
    private $pass = DB_PASS;
    private $dbname = DB_NAME;
    private $conn;
    
    public function connect() {
        $this->conn = null;
        
        try {
            $this->conn = new PDO(
                'mysql:host=' . $this->host . ';dbname=' . $this->dbname . ';charset=utf8mb4',
                $this->user,
                $this->pass,
                array(
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false
                )
            );
        } catch(PDOException $e) {
            echo 'Холболтын алдаа: ' . $e->getMessage();
        }
        
        return $this->conn;
    }
}
?>