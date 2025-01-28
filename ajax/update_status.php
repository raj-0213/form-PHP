<?php
require_once __DIR__ . '/../classes/user.php';

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["id"], $_POST["isactive"])) {
    $id = (int)$_POST["id"];
    $isactive = (int)$_POST["isactive"];
    $user = new User();
    $result = $user->updateStatus($id, $isactive);  
    
    if($result){
        echo json_encode(["success" => true]);
    }else{
        echo json_encode(["success" => false]);
    }
}
