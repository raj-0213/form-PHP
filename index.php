<?php
require_once 'classes/User.php';
$user = new User();

$users = $user->getUsers();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User List</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        h2 {
            text-align: center;
            margin-top: 20px;
        }

        .btn {
            display: inline-block;
            padding: 10px 20px;
            margin: 10px;
            font-size: 16px;
            text-decoration: none;
            color: #fff;
            background-color:rgb(0, 115, 238);
            border: none;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .btn:hover {
            background-color: #0056b3;
        }

        .btn-home {
            display: block;
            width: 200px;
            margin: 20px auto;
            text-align: center;
        }

        table {
            width: 80%;
            margin: 20px auto;
            border-collapse: collapse;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #007bff;
            color: #fff;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        .switch {
            position: relative;
            display: inline-block;
            width: 60px;
            height: 34px;
        }

        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            transition: .4s;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 26px;
            width: 26px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            transition: .4s;
        }

        input:checked + .slider {
            background-color: #2196F3;
        }

        input:checked + .slider:before {
            transform: translateX(26px);
        }

        .slider.round {
            border-radius: 34px;
        }

        .slider.round:before {
            border-radius: 50%;
        }
    </style>
</head>
<body>

<h2>All Users List</h2>
<a href="./views/read.php" class="btn btn-home">Add New User</a>
<table border="1">
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>User Stauts</th>
        <th>Actions</th>
    </tr>
    <?php foreach ($users as $user): ?>
    <tr id="user-<?= $user['id'] ?>">
        <td><?= htmlspecialchars($user['id']) ?></td>
        <td><?= htmlspecialchars($user['name']) ?></td>
        <td>
            <label class="switch">
            <input type="checkbox" id="statusButton_<?= $user['id'] ?>" <?= $user['isactive'] ? 'checked' : '' ?>
                onclick="changeStatus(<?= $user['id'] ?>, this.checked ? 1 : 0, event)"> 
                <span class="slider round"></span> 
            </label>
        </td>
        <td>
            <a href="./views/show.php?id=<?= $user['id'] ?>" class="btn btn-show">Show</a> | 
            <a href="./views/update.php?id=<?= $user['id'] ?>" class="btn btn-edit">Edit</a> | 
            <a href="./views/delete.php?id=<?= $user['id'] ?>" class="btn btn-delete" onclick="return confirm('Are you sure?')">Delete</a>
        </td>
    </tr>
    <?php endforeach; ?>
</table>
<script src="script.js"></script>
</body>
</html>
