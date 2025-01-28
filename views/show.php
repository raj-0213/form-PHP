<?php
require_once __DIR__ . '/../classes/User.php';
$user = new User();

if (isset($_GET['id'])) {
    $userData = $user->getUserById($_GET['id']);
    // var_dump($userData);
    if (!$userData) {
        die("User not found!");
    }

    // Fetch user images
    $userImages = $user->getImages($_GET['id']);
    // var_dump($userImages);
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 20px;
        }
        .container {
            max-width: 800px;
            margin: auto;
        }
        .user-details, .user-images, .upload-form {
            margin-bottom: 20px;
        }
        .user-details table {
            width: 100%;
            border-collapse: collapse;
        }
        .user-details th, .user-details td {
            border: 1px solid #ddd;
            padding: 8px;
        }
        .user-details th {
            background-color: green;
        }
    </style>
    <script>
        function deleteImage(imageId) {
            if (confirm('Are you sure you want to delete this image?')) {
                var xhr = new XMLHttpRequest();
                xhr.open('POST', 'delete_image.php', true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhr.onreadystatechange = function () {
                    if (xhr.readyState == 4 && xhr.status == 200) {
                        var response = JSON.parse(xhr.responseText);
                        if (response.success) {
                            document.getElementById('image-' + imageId).remove();
                        } else {
                            alert('Failed to delete image.');
                        }
                    }
                };
                xhr.send('id=' + imageId);
            }
        }
    </script>
</head>
<body>

<div class="container">
    <h2>User Details</h2>
    <a href="../index.php" class="btn btn-home">Back to Home</a>
    <div class="user-details">
        <table>
            <tr>
                <th>ID</th>
                <td><?= htmlspecialchars($userData['id']) ?></td>
            </tr>
            <tr>
                <th>Name</th>
                <td><?= htmlspecialchars($userData['name']) ?></td>
            </tr>
            <tr>
                <th>Email</th>
                <td><?= htmlspecialchars($userData['email']) ?></td>
            </tr>
            <tr>
                <th>Phone</th>
                <td><?= htmlspecialchars($userData['phone_number']) ?></td>
            </tr>
            <tr>
                <th>DOB</th>
                <td><?= htmlspecialchars($userData['dob']) ?></td>
            </tr>
            <tr>
                <th>Hobbies</th>
                <td><?= htmlspecialchars($userData['hobbies']) ?></td>
            </tr>
            <tr>
                <th>Color</th>
                <td>
                    <div style="width: 30px; height: 30px; background-color: <?= htmlspecialchars($userData['color_code']) ?>; border: 1px solid #000;"></div>
                </td>
            </tr>
            <tr>
                <th>Gender</th>
                <td><?= htmlspecialchars($userData['gender']) ?></td>
            </tr>
            <tr>
                <th>Profile Picture</th>
                <td><img src="http://localhost/Task/views/uploads/<?= htmlspecialchars($userData['profile_picture']) ?>" width="50" height="50" alt="Profile Picture"></td>
            </tr>
            <tr>
                <th>Country</th>
                <td><?= htmlspecialchars($userData['country']) ?></td>
            </tr>
            <tr>
                <th>Month</th>
                <td><?= htmlspecialchars($userData['bdaymonth']) ?></td>
            </tr>
            <tr>
                <th>Week</th>
                <td><?= htmlspecialchars($userData['week']) ?></td>
            </tr>
            <tr>
                <th>Quantity</th>
                <td><?= htmlspecialchars($userData['quantity']) ?></td>
            </tr>
            <tr>
                <th>Time</th>
                <td><?= htmlspecialchars($userData['time']) ?></td>
            </tr>
            <tr>
                <th>URL</th>
                <td><?= htmlspecialchars($userData['url']) ?></td>
            </tr>
            <tr>
                <th>Editor Content</th>
                <td>
                <div class="editor-content" style="border: 1px solid #ddd; padding: 10px;">
                    <?= htmlspecialchars_decode($userData['editorcontent']) ?> 
                </div>
                </td>
            </tr>
            <tr>
                <th>User Images</th>
                <td>
                <div class="user-images">
                    <?php foreach ($userImages as $image): ?>
                    <div class="image-container" id="image-<?php echo $image['id']; ?>">
                    <img src="http://localhost/Task/views/<?php echo $image['image_path']; ?>" width="200" height="200" alt="User Image">
                </div>
                <?php endforeach; ?>
                </div>
                </td>
            </tr>
        </table>
    </div>

    

</div>

</body>
</html>

<!-- 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style.css">
    <style>
        .image-container {
            position: relative;
            display: inline-block;
            margin: 10px;
        }
        .image-container img {
            max-width: 200px;
            max-height: 200px;
        }
        .delete-badge {
            position: absolute;
            top: 5px;
            right: 5px;
            background: red;
            color: white;
            border-radius: 50%;
            padding: 5px;
            cursor: pointer;
        }
    </style>
    <script>
        function deleteImage(imageId) {
            if (confirm('Are you sure you want to delete this image?')) {
                var xhr = new XMLHttpRequest();
                xhr.open('POST', 'delete_image.php', true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhr.onreadystatechange = function () {
                    if (xhr.readyState == 4 && xhr.status == 200) {
                        var response = JSON.parse(xhr.responseText);
                        if (response.success) {
                            document.getElementById('image-' + imageId).remove();
                        } else {
                            alert('Failed to delete image.');
                        }
                    }
                };
                xhr.send('id=' + imageId);
            }
        }
    </script>
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
        <th>Status</th>
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
        <td><img src="http://localhost/form-PHP-main/views/uploads/<?= htmlspecialchars($userData['profile_picture']) ?>" width="50" height="50" alt="Profile Picture"></td>
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

        <td style="background-color: <?= $userData['isactive'] ? 'green' : 'red' ?>; color: white;">
            <?= $userData['isactive'] ? 'Active' : 'InActive' ?>
        </td>
        
    </tr>
    <?php else: ?>
    <tr><td colspan="16">No user data available</td></tr>
    <?php endif; ?>
</table>

<h3>User Images</h3>
<div>
    <?php foreach ($userImages as $image): ?>
        <div class="image-container" id="image-<?php echo $image['id']; ?>">
            <img src="http://localhost/form-PHP-main/views/<?php echo $image['image_path']; ?>" alt="User Image">
            <span class="delete-badge" onclick="deleteImage(<?php echo $image['id']; ?>)">X</span>
        </div>
    <?php endforeach; ?>
</div>

</body>
</html> -->
