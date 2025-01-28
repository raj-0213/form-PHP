<?php
require  __DIR__ . '/../config/database.php'; 
require  __DIR__ .'/../classes/user.php'; 

$user = new User();

$searchQuery = $_GET['query'];

$users = $user->searchUsers($searchQuery);

echo json_encode($users);
?>