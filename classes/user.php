<?php
require_once __DIR__ . '/../config/database.php';

class User {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->conn;
    }

    public function createUser($data) {
    try {
        $query = "INSERT INTO userdetails (
                    name, email, password, phone_number, dob, color_code, profile_picture, 
                    gender, country, hobbies, bdaymonth, week, quantity, time, url, terms
                  ) 
                  VALUES (
                    :name, :email, :password, :phone_number, :dob, :color_code, :profile_picture, 
                    :gender, :country, :hobbies, :bdaymonth, :week, :quantity, :time, :url, :terms
                  )";
        
        $stmt = $this->db->prepare($query);
        if ($stmt->execute($data)) {
            return true;
        } else {
            echo "Failed to insert into database!";
            print_r($stmt->errorInfo()); 
            exit;
        }
    } catch (PDOException $e) {
        die("Error: " . $e->getMessage()); 
    }
}

    

    public function getUsers() {
        $stmt = $this->db->query("SELECT * FROM userdetails ORDER BY id DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getUserById($id) {
        $stmt = $this->db->prepare("SELECT * FROM userdetails WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateUser($data) {
        $query = "UPDATE userdetails SET 
                    name=:name, 
                    email=:email, 
                    phone_number=:phone_number, 
                    dob=:dob, 
                    color_code=:color_code, 
                    gender=:gender,
                    hobbies=:hobbies, 
                    country=:country,
                    bdaymonth=:bdaymonth, 
                    week=:week, 
                    quantity=:quantity, 
                    time=:time, 
                    url=:url, 
                    terms=:terms 
                  WHERE id=:id";
        
        $stmt = $this->db->prepare($query);
        return $stmt->execute($data);
    }
    

    public function deleteUser($id) {
        $stmt = $this->db->prepare("DELETE FROM userdetails WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
?>

