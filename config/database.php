<?php
class Database {
    private $host = "localhost";
    private $dbname = "users";
    private $user = "postgres";
    private $password = "Root";
    public $conn;

    public function __construct() {
        try {
            $this->conn = new PDO("pgsql:host=$this->host;dbname=$this->dbname", $this->user, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            // echo 'Connected';
        } catch (PDOException $e) {
            die("Database Connection Failed: " . $e->getMessage());
        }
    }
}
?>

<!-- CREATE TABLE user_images (
    id SERIAL PRIMARY KEY,
    user_id INT REFERENCES userdetails(id),
    image_path VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
); -->


