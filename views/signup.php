<?php

include __DIR__ . '/../config/database.php';
include __DIR__ . '/../classes/user.php';

$user = new User();


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Password validation
    $passwordPattern = "/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/";
    if (!preg_match($passwordPattern, $password)) {
        echo "Error: Password must be at least 8 characters long, include at least one uppercase letter, one lowercase letter, one number, and one special character.";
    } else {
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        $adminId = $user->createAdmin($name, $email, $hashedPassword);
        if ($adminId) {
            header("Location: login.php");
        } else {
            echo "Error: Unable to create admin.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Signup</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .signup-container {
            background-color: #fff;
            padding: 50px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 300px;
        }

        .signup-container h2 {
            margin-bottom: 20px;
            text-align: center;
        }

        .signup-container label {
            display: block;
            margin-bottom: 5px;
        }

        .signup-container input {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .signup-container button {
            width: 106%;
            padding: 10px;
            background-color: #007bff;
            border: none;
            border-radius: 4px;
            color: #fff;
            font-size: 16px;
            cursor: pointer;
        }

        .signup-container button:hover {
            background-color: #0056b3;
        }

        .signup-container .login-link {
            margin-top: 10px;
            text-align: center;
        }

        .signup-container .login-link a {
            color: #007bff;
            text-decoration: none;
        }

        .signup-container .login-link a:hover {
            text-decoration: underline;
        }
    </style>

</head>

<body>
    <div class="signup-container">
        <h2>Admin Signup</h2>
        <form method="POST">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>

            <button type="submit">Signup</button>
        </form>
        <div class="login-link">
            Already have an account? <a href="login.php">Login here</a>
        </div>
    </div>
</body>

</html>