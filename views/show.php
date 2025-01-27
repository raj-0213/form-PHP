<?php
require_once __DIR__ . '/../classes/User.php';
$user = new User();

if (isset($_GET['id'])) {
    $userData = $user->getUserById($_GET['id']);
    // var_dump($userData);
    if (!$userData) {
        die("User not found!");
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style.css">
    
</head>
<body>

<h2>User Details</h2>
<a href="../index.php" class="btn btn-home">Back to Home</a>
<table border="1">
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Email</th>
        <th>Phone</th>
        <th>DOB</th>
        <th>Hobbies</th>
        <th>Color</th>
        <th>Gender</th>
        <th>Profile Picture</th>
        <th>Country</th>
        <th>Month</th>
        <th>Week</th>
        <th>Quantity</th>
        <th>Time</th>
        <th>URL</th>
        <th>Editor Content</th>

    </tr>

    <?php if (isset($userData) && is_array($userData)): ?>
    <tr>
        <td><?= htmlspecialchars($userData['id']) ?></td>
        <td><?= htmlspecialchars($userData['name']) ?></td>
        <td><?= htmlspecialchars($userData['email']) ?></td>
        <td><?= htmlspecialchars($userData['phone_number']) ?></td>
        <td><?= htmlspecialchars($userData['dob']) ?></td>
        <td><?= htmlspecialchars($userData['hobbies']) ?></td>
        <td>
            <div style="width: 30px; height: 30px; background-color: <?= htmlspecialchars($userData['color_code']) ?>; border: 1px solid #000;"></div>
        </td>
        <td><?= htmlspecialchars($userData['gender']) ?></td>
        <td><img src="http://localhost/Task/views/uploads/<?= htmlspecialchars($userData['profile_picture']) ?>" width="50" height="50" alt="Profile Picture"></td>
        <td><?= htmlspecialchars($userData['country']) ?></td>
        <td><?= htmlspecialchars($userData['bdaymonth']) ?></td>
        <td><?= htmlspecialchars($userData['week']) ?></td>
        <td><?= htmlspecialchars($userData['quantity']) ?></td>
        <td><?= htmlspecialchars($userData['time']) ?></td>
        <td><a href="<?= htmlspecialchars($userData['url']) ?>" target="_blank"><?= htmlspecialchars($userData['url']) ?></a></td>
        <td>
            <div class="editor-content" style="border: 1px solid #ddd; padding: 10px;">
                <?= htmlspecialchars_decode($userData['editorcontent']) ?> 
            </div>
        </td> 
        
    </tr>
    <?php else: ?>
    <tr><td colspan="16">No user data available</td></tr>
    <?php endif; ?>
</table>

</body>
</html>
