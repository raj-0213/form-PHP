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

        // $editorContent = $_POST['editorContent']; 
        'editorContent' => isset($_POST['editorContent']) ? test_editor_input($_POST['editorContent']) : ' ',

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

function test_editor_input($data) {
    // Allow only basic formatting tags and strip others
    $allowed_tags = '<b><i><u><strong><em><p><br><ul><ol><li><blockquote>';
    $data = strip_tags($data, $allowed_tags);
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    return $data;
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
    <style>
        .error {
            color: red;
        }
       
        .toolbar {
    background: #f1f1f1;
    padding: 5px;
    border: 1px solid #ccc;
    display: inline-block;
}

.toolbar button {
    padding: 5px;
    margin: 2px;
    cursor: pointer;
}

.text-editor {
    width: 100%;
    min-height: 150px;
    border: 1px solid #ccc;
    padding: 10px;
    margin-top: 5px;
    outline: none;
    overflow-y: auto;
}

     </style> 
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

    <label for="editor">Your Bio</label><br>
    <div id="editor-container" class="text-editor" contenteditable="true">
        <?= htmlspecialchars_decode($userData['editorcontent']) ?>
    </div>
    <input type="hidden" name="editorContent" id="editorContent">

    <div class="toolbar">
        <button type="button" onclick="formatText('bold')"><b>B</b></button>
        <button type="button" onclick="formatText('italic')"><i>I</i></button>
        <button type="button" onclick="formatText('underline')"><u>U</u></button>
        <button type="button" onclick="formatText('justifyLeft')">Left</button>
        <button type="button" onclick="formatText('justifyCenter')">Center</button>
        <button type="button" onclick="formatText('justifyRight')">Right</button>
        <button type="button" onclick="formatText('insertUnorderedList')">• List</button>
        <button type="button" onclick="formatText('insertOrderedList')">1. List</button>
    </div>

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


<script>
        function formatText(command) {
            document.getElementById("editor-container").focus();
            document.execCommand(command, false, null);
        }
            document.querySelector("form").addEventListener("submit", function() {
            document.getElementById("editorContent").value = document.getElementById("editor-container").innerHTML;
        });
</script>
</body>
</html>