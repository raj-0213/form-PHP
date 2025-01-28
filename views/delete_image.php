<?php
require_once __DIR__ . '/../classes/User.php';
$user = new User();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $imageId = (int)$_POST['id'];
    $result = $user->softDeleteImage($imageId);
    if($result){
        echo json_encode(["success" => true]);
    }else{
        echo json_encode(["success" => false]);
    }
    // echo $imageId; 
}