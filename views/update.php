<?php
require_once __DIR__ . '/../classes/User.php';
$user = new User();

if (isset($_GET['id'])) {
    $userData = $user->getUserById($_GET['id']);
    if (!$userData) {
        die("User not found!");
    }
}

// Handle form submission for updating user
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $updateData = [
        'id' => $_POST['id'],
        'name' => trim($_POST['name']),
        'email' => filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) ? $_POST['email'] : '',
        'phone_number' => preg_match('/^[0-9]{10}$/', $_POST['phone_number']) ? $_POST['phone_number'] : '',
        'dob' => $_POST['dob'],
        'color_code' => $_POST['color_code'],
        'gender' => $_POST['gender'],
        'country' => $_POST['country'],
        'hobbies' => isset($_POST['hobbies']) ? implode(',', $_POST['hobbies']) : '',

        'bdaymonth' => $_POST['bdaymonth'],
        'week' => $_POST['week'],
        'quantity' => $_POST['quantity'],
        'time' => $_POST['time'],
        'url' => filter_var($_POST['url'], FILTER_VALIDATE_URL) ? $_POST['url'] : '',
        'terms' => isset($_POST['terms']) ? true : false

    ];

    $user->updateUser($updateData);
    header("Location: ../index.php");
}

// Convert stored hobbies string to an array
$selectedHobbies = !empty($userData['hobbies']) ? explode(',', $userData['hobbies']) : [];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    
<h2>Edit User</h2>
<a href="../index.php" class="btn btn-home">Back to Home</a>
<form action="" method="post">
    <input type="hidden" name="id" value="<?= htmlspecialchars($userData['id']) ?>">
    Name: <input type="text" name="name" value="<?= htmlspecialchars($userData['name']) ?>" required> <br>
    Email: <input type="email" name="email" value="<?= htmlspecialchars($userData['email']) ?>" required> <br>
    Phone Number: <input type="text" name="phone_number" value="<?= htmlspecialchars($userData['phone_number']) ?>" required> <br>
    Date of Birth: <input type="date" name="dob" value="<?= htmlspecialchars($userData['dob']) ?>" required> <br>
    Favorite Color: <input type="color" name="color_code" value="<?= htmlspecialchars($userData['color_code']) ?>" required> <br>
    Gender: 
    <input type="radio" name="gender" value="Male" <?= ($userData['gender'] == 'Male') ? 'checked' : '' ?>> Male
    <input type="radio" name="gender" value="Female" <?= ($userData['gender'] == 'Female') ? 'checked' : '' ?>> Female
    <input type="radio" name="gender" value="Other" <?= ($userData['gender'] == 'Other') ? 'checked' : '' ?>> Other <br>
    Country:
    <select name="country" required>
        <option value="India" <?= ($userData['country'] == 'India') ? 'selected' : '' ?>>India</option>
        <option value="USA" <?= ($userData['country'] == 'USA') ? 'selected' : '' ?>>USA</option>
        <option value="UK" <?= ($userData['country'] == 'UK') ? 'selected' : '' ?>>UK</option>
    </select> <br>
    Hobbies:<br>
    <input type="checkbox" name="hobbies[]" value="Reading" <?= in_array("Reading", $selectedHobbies) ? 'checked' : '' ?>> Reading
    <input type="checkbox" name="hobbies[]" value="Gaming" <?= in_array("Gaming", $selectedHobbies) ? 'checked' : '' ?>> Gaming
    <input type="checkbox" name="hobbies[]" value="Traveling" <?= in_array("Traveling", $selectedHobbies) ? 'checked' : '' ?>> Traveling
    <input type="checkbox" name="hobbies[]" value="Music" <?= in_array("Music", $selectedHobbies) ? 'checked' : '' ?>> Music
    <input type="checkbox" name="hobbies[]" value="Sports" <?= in_array("Sports", $selectedHobbies) ? 'checked' : '' ?>> Sports <br>

    Birth Month: <input type="month" name="bdaymonth" value="<?= htmlspecialchars($userData['bdaymonth']) ?>" required> <br>
    Week: <input type="week" name="week" value="<?= htmlspecialchars($userData['week']) ?>" required> <br>

    Quantity: <input type="range" name="quantity" value="<?= htmlspecialchars($userData['quantity']) ?>" required> <br>

    Time: <input type="time" name="time" value="<?= htmlspecialchars($userData['time']) ?>" required> <br>
    URL: <input type="url" name="url" value="<?= htmlspecialchars($userData['url']) ?>" required> <br>
    <input type="checkbox" name="terms" <?= ($userData['terms'] == true) ? 'checked' : '' ?>> I accept the terms and conditions <br>

    <input type="submit" value="Update">
</form>

</body>
</html>
