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
</head>
<body>

<h2>All Users List</h2>
<a href="./views/read.php" class="btn btn-home">Add New User</a>
<table border="1">
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Actions</th>
    </tr>
    <?php foreach ($users as $user): ?>
    <tr>
        <td><?= htmlspecialchars($user['id']) ?></td>
        <td><?= htmlspecialchars($user['name']) ?></td>
        <td>
            <a href="./views/show.php?id=<?= $user['id'] ?>">Show</a> | 
            <a href="./views/update.php?id=<?= $user['id'] ?>">Edit</a> | 
            <a href="./views/delete.php?id=<?= $user['id'] ?>" onclick="return confirm('Are you sure?')">Delete</a>
        </td>
    </tr>
    <?php endforeach; ?>
</table>

</body>
</html>
