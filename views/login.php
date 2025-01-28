<?php

session_start();
include __DIR__ . '/../config/database.php';
include __DIR__ . '/../classes/user.php';

$user = new User();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $admin = $user->getAdminByEmail($email);

    // Password validation
    $passwordPattern = "/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/";
    if (!preg_match($passwordPattern, $password)) {
        echo "Error: Password must be at least 8 characters long, include at least one uppercase letter, one lowercase letter, one number, and one special character.";
    } else {
        $admin = $user->getAdminByEmail($email);

        if ($admin && password_verify($password, $admin['password'])) {
            $_SESSION['user_id'] = $admin['id'];
            $_SESSION['user_name'] = $admin['name'];
            header("Location: ../index.php");
        } else {
            echo "Error: Invalid email or password.";
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
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

        .login-container {
            background-color: #fff;
            padding: 50px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 300px;
        }

        .login-container h2 {
            margin-bottom: 20px;
            text-align: center;
        }

        .login-container label {
            display: block;
            margin-bottom: 5px;
        }

        .login-container input {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .login-container button {
            width: 106%;
            padding: 10px;
            background-color: #007bff;
            border: none;
            border-radius: 4px;
            color: #fff;
            font-size: 16px;
            cursor: pointer;
        }

        .login-container button:hover {
            background-color: #0056b3;
        }

        .login-container .signup-link {
            margin-top: 10px;
            text-align: center;
        }

        .login-container .signup-link a {
            color: #007bff;
            text-decoration: none;
        }

        .login-container .signup-link a:hover {
            text-decoration: underline;
        }


        .login-container .login-link {
            margin-top: 10px;
            text-align: center;
        }

        .login-container .login-link a {
            color: #007bff;
            text-decoration: none;
        }

        .login-container .login-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <div class="login-container">
        <h2>Admin Login</h2>
        <form method="POST">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>

            <button type="submit">Login</button>
            <div class="login-link">
                Don't have an account? <a href="signup.php">Register here</a>
            </div>
        </form>
    </div>
</body>

</html>