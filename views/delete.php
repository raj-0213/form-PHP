<?php
require_once __DIR__ . '/../classes/User.php';
$user = new User();

if (isset($_GET['id'])) {
    $user->deleteUser($_GET['id']);
    header("Location: ../index.php");
} else {
    die("Invalid request!");
}
?>
