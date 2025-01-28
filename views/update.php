<?php

// echo "Script started";
// ob_flush();
// flush();

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

    // print_r($_FILES);

    // Handle multiple image uploads
    if (!empty($_FILES['new_images']['name'][0])) {
        
        // echo "Called";
        // ob_flush();
        // flush();

        $upload_dir = 'uploads/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        foreach ($_FILES['new_images']['name'] as $key => $name) {
            $file_tmp = $_FILES['new_images']['tmp_name'][$key];
            $file_name = basename($name);
            $file_path = $upload_dir . $file_name;

            if (move_uploaded_file($file_tmp, $file_path)) {

                // echo $file_path;

                $user->addImage($_POST['id'], $file_path); 

            } else {
                die("Error uploading file: $name");
            }
        }
    }
    
    $user->updateUser($updateData);

    header("Location: ../index.php");
}

function test_editor_input($data) {
    // Allow basic formatting tags and alignment styles
    $allowed_tags = '<b><i><u><strong><em><p><br><ul><ol><li><blockquote><div><span>';
    // Allow style attribute for alignment
    $data = strip_tags($data, $allowed_tags);
    $data = preg_replace('/class="[^"]*"/', '', $data); 
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
            padding: 5px;
            background: #f1f1f1;
            display: inline-block;
            border: 1px solid #ccc;
        }
            
        .toolbar button {
            margin: 2px;
            padding: 5px;
            cursor: pointer;
        }

        .text-editor {
            min-height: 150px;
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            margin-top: 5px;
            outline: none;
            overflow-y: auto;
        }
     </style> 
</head>
<body>
    
<h2>Edit User</h2>
<a href="../index.php" class="btn btn-home">Back to Home</a>

<form action="" method="post" enctype="multipart/form-data">
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
        <button type="button" onclick="formatText('insertUnorderedList')">UL</button>
        <button type="button" onclick="formatText('insertOrderedList')">OL</button>
    </div>

    Favorite Color: <input type="color" name="color_code" value="<?= htmlspecialchars($userData['color_code']) ?>" required> <br>
    Gender: 
    <input type="radio" name="gender" value="Male" <?= ($userData['gender'] == 'Male') ? 'checked' : '' ?>> Male
    <input type="radio" name="gender" value="Female" <?= ($userData['gender'] == 'Female') ? 'checked' : '' ?>> Female
    <input type="radio" name="gender" value="Other" <?= ($userData['gender'] == 'Other') ? 'checked' : '' ?>> Other <br>

    <!-- Profile Picture -->
    <h3>Profile Picture</h3>
    <img src="http://localhost/Task/views/uploads/<?= htmlspecialchars($userData['profile_picture']) ?>" alt="Profile Picture" width="150"><br>

    <!-- Multiple Images -->
    <h3>Other Images</h3>
    <div id="images-container">
        <?php foreach ($userImages as $image): ?>
            <div class="image-wrapper" data-id="<?= $image['id'] ?>" style="position: relative; display: inline-block;
            <?= $image['isactive'] ? '' : 'opacity: 0.5; pointer-events: none;' ?>" >
                <img src="http://localhost/Task/views/<?= htmlspecialchars($image['image_path']) ?>" alt="User Image" width="150">
                <button type="button" class="delete-image"  onclick="deleteImage(<?php echo $image['id']; ?>)" style="position: absolute; top: 0; right: 0;">&times;</button>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Upload New Images -->
    <h3>Upload New Images</h3>
    <input type="file" name="new_images[]" id="new_images" multiple accept="image/*"><br>
    <div id="new-images-preview"></div>


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

        function deleteImage(imageId) {
            if (confirm('Are you sure you want to delete this image?')) {
                var xhr = new XMLHttpRequest();
                xhr.open('POST', 'delete_image.php', true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhr.onreadystatechange = function () {
                    if (xhr.readyState == 4 && xhr.status == 200) {
                        
                        // console.log(xhr);

                        var response = JSON.parse(xhr.responseText);

                        // console.log(response);

                        if (response.success) {
                            var imageWrapper = document.querySelector('.image-wrapper[data-id="' + imageId + '"]');
                        if (imageWrapper) {
                            imageWrapper.remove();
                        } else {
                            console.error('Image wrapper not found for image ID:', imageId);
                        }
                        } else {
                            alert('Failed to delete image.');
                        }
                    }
                };
                xhr.send('id=' + imageId);
            }
        }


    document.getElementById('new_images').addEventListener('change', function() {
        const previewContainer = document.getElementById('new-images-preview');
        previewContainer.innerHTML = '';
        Array.from(this.files).forEach(file => {
            const reader = new FileReader();
            reader.onload = function(e) {
                const imgWrapper = document.createElement('div');
                imgWrapper.style.position = 'relative';
                imgWrapper.style.display = 'inline-block';
                const img = document.createElement('img');
                img.src = e.target.result;
                img.width = 150;
                const deleteButton = document.createElement('button');
                deleteButton.innerHTML = '&times;';
                deleteButton.style.position = 'absolute';
                deleteButton.style.top = '0';
                deleteButton.style.right = '0';
                deleteButton.addEventListener('click', function() {
                    imgWrapper.remove();
                });
                imgWrapper.appendChild(img);
                imgWrapper.appendChild(deleteButton);
                previewContainer.appendChild(imgWrapper);
            };
            reader.readAsDataURL(file);
        });
    });

</script>
</body>
</html>