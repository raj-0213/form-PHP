<?php

session_start(); 

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

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
            font-family: sans-serif;
        }


        form {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            width: 350px;
            font-family: 'Arial', sans-serif;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .btn-home {
            display: inline-block;
            margin-bottom: 20px;
            padding: 10px 20px;
            background: green;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .btn-home:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 6px rgba(137, 255, 1, 0.1);
        }


        input,
        select {
            width: 100%;
            padding: 8px;
            margin: 5px 0 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 14px;
            font-family: 'Arial', sans-serif;
            transition: border-color 0.3s ease-in-out;
        }

        input:focus,
        select:focus {
            border-color: #28a745;
        }

        input[type="radio"],
        input[type="checkbox"] {
            width: auto;
        }

        input[type="submit"] {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 10px;
            font-size: 16px;
            cursor: pointer;
            width: 100%;
            font-family: 'Arial', sans-serif;
            transition: background-color 0.3s ease-in-out;
        }

        input[type="submit"]:hover {
            background-color: #218838;
        }

        input[type="color"] {
            color: white;
            border: none;
            height: 40px;
            font-size: 16px;
            width: 50%;
            font-family: 'Arial', sans-serif;
        }

        h2 {
            text-align: center;
            font-family: 'Arial', sans-serif;
        }

        label {
            font-weight: bold;
            font-family: 'Arial', sans-serif;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            font-family: 'Arial', sans-serif;
        }

        table th,
        table td {
            padding: 10px;
            border: 1px solid #ccc;
            text-align: left;
            transition: background-color 0.3s ease-in-out;
        }

        table tr:hover {
            background-color: #f1f1f1;
        }

        table th {
            background-color: #28a745;
            color: white;
        }

        table td {
            background-color: #fff;
        }


        .btn-show {
            background-color: #28a745;
        }

        .btn-edit {
            background-color: #ffc107;
        }

        .btn-delete {
            background-color: #dc3545;
        }

        .btn-show:hover {
            background-color: #218838;
        }

        .btn-edit:hover {
            background-color: #e0a800;
        }

        .btn-delete:hover {
            background-color: #c82333;
        }

        .user-details,
        .user-images,
        .upload-form {
            margin-bottom: 20px;
        }

        .user-details table {
            width: 100%;
            border-collapse: collapse;
        }

        .user-details th,
        .user-details td {
            border: 1px solid #ddd;
            padding: 8px;
        }

        .user-details th {
            background-color: green;
        }
    </style>
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

