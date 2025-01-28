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
                    gender, country, hobbies, bdaymonth, week, quantity, time, url, editorContent, terms
                  ) 
                  VALUES (
                    :name, :email, :password, :phone_number, :dob, :color_code, :profile_picture, 
                    :gender, :country, :hobbies, :bdaymonth, :week, :quantity, :time, :url, :editorContent,:terms
                  )";
        
        $stmt = $this->db->prepare($query);
        if ($stmt->execute($data)) {
            return $this->db->lastInsertId();
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
                    editorContent=:editorContent,
                    terms=:terms ,
                    updatedAt=NOW() 
                  WHERE id=:id";
        
        $stmt = $this->db->prepare($query);
        return $stmt->execute($data);
    }
    

    public function deleteUser($id) {
        $query = "UPDATE userdetails SET 
                    isActive = false, 
                    deletedAt = NOW() 
                  WHERE id = ?";
        
        $stmt = $this->db->prepare($query);
        return $stmt->execute([$id]);
    }

    public function updateStatus($id, $isactive) {
        $deletedAt = $isactive ? NULL : "NOW()";
        $sql = "UPDATE userdetails SET isactive = ?, deletedAt = ? WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$isactive, $deletedAt, $id]);
    }

    public function addImage($userId, $imagePath) {
        try {
            echo $userId;
            echo $imagePath;
            $query = "INSERT INTO user_images (user_id, image_path) VALUES (:user_id, :image_path)";
            $stmt = $this->db->prepare($query);
            $stmt->execute([
                ':user_id' => $userId,
                ':image_path' => $imagePath
            ]);
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function getImages($userId) {
        $query = "SELECT * FROM user_images WHERE user_id = :user_id";
        $stmt = $this->db->prepare($query);
        $stmt->execute(['user_id' => $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function softDeleteImage($imageId) {
        $query = "UPDATE user_images SET isActive = false  WHERE id = :id";
        $stmt = $this->db->prepare($query);
        return $stmt->execute(['id' => $imageId]);
    }
    

    public function deleteImage($imageId) {
        $query = "DELETE FROM user_images WHERE id = :id";
        $stmt = $this->db->prepare($query);
        return $stmt->execute(['id' => $imageId]);
    }
}
?>

