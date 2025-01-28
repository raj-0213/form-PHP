<?php

session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ./views/login.php");
    exit;
}

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
    <!-- <link rel="stylesheet" href="./css/style.css"> -->
    <style>
        form {
            background: #fff;
            border-radius: 8px;
            font-family: sans-serif;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        body {
            font-family: sans-serif;
        }

        .actions {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
            margin-bottom: 20px;
            font-family: sans-serif
        }

        #searchForm {
            display: flex;
            align-items: center;
        }

        #searchForm input[type="text"] {
            padding: 5px;
            margin-right: 10px;
            border-radius: 5px
        }

        #searchForm button {
            padding: 8px 10px;
            background-color: #2196F3;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 8px
        }

        #searchForm button:hover {
            background-color: #1976D2;
        }

        .btn-home {
            padding: 8px 20px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border: none;
            border-radius: 8px;
            cursor: pointer;
        }

        .btn-home:hover {
            background-color: #0056b3;
        }

        .btn {
            padding: 5px 10px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border: none;
            border-radius: 17px;
            cursor: pointer;
            margin-right: 5px;
        }

        .btn:hover {
            background-color: #0056b3;
        }

        .btn-delete {
            background-color: #dc3545;
        }

        .btn-delete:hover {
            background-color: #c82333;
        }

        table {
            width: 80%;
            margin: 20px auto;
            border-collapse: collapse;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            font-family: sans-serif
        }

        th,
        td {
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

        input:checked+.slider {
            background-color: #2196F3;
        }

        input:checked+.slider:before {
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

    <h2 style="text-align: center; font-family:sans-serif">All Users List</h2>
    <div class="actions">
        <form id="searchForm">
            <input type="text" id="searchQuery" placeholder="Search by ID or Name">
            <button type="button" onclick="searchUsers()">Search</button>
        </form>
        <a href="./views/read.php" class="btn-home">Add New User</a>
        <a href="./views/logout.php" class="btn-home">Logout</a>
    </div>
    <?php if (isset($_SESSION['user_name'])): ?>
        <p style="text-align: center; font-family:sans-serif;font-weight:Bolder;font-size:25px">Welcome, <?= htmlspecialchars($_SESSION['user_name']) ?></p>
    <?php endif; ?>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>User Status</th>
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
                    <?php if ($user['isactive']): ?>
                        <a href="./views/show.php?id=<?= $user['id'] ?>" class="btn btn-show">Show</a>
                        <a href="./views/update.php?id=<?= $user['id'] ?>" class="btn btn-edit">Edit</a>
                        <a href="./views/delete.php?id=<?= $user['id'] ?>" class="btn btn-delete" onclick="return confirm('Are you sure?')">Delete</a>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
    <script src="script.js"></script>
</body>

</html>