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


<!-- 
CREATE TABLE userdetails (
    id SERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    phone_number VARCHAR(10) NOT NULL,
    dob DATE NOT NULL,
    color_code VARCHAR(10) ,
    profile_picture TEXT NOT NULL,
    gender VARCHAR(10) ,
    country VARCHAR(50) ,
    hobbies TEXT, 
    bdaymonth VARCHAR(10) ,
    week VARCHAR(10) ,
    quantity INT ,
    time TIME ,
    url TEXT,
    terms BOOLEAN 
); -->
