<?php
require_once __DIR__ .  '/../classes/User.php';
$user = new User();

$nameErr = $emailErr = $passwordErr = $phoneErr = $dobErr = $colorErr = $genderErr = $countryErr = $profileErr = $quantityErr = $timeErr=$bdaymonthErr=$weekErr=$urlErr=$termsErr = "";
$name = $email = $password = $phone_number = $dob = $color_code = $gender = $country = $profile_picture = $quantity = $time = $url = $bdaymonth = $week = $terms = $hobbies = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Name Validation
    if (empty($_POST['name'])) {
        $nameErr = "Name is required";
    } else {
        $name = test_input($_POST['name']);
    }

    // Email Validation
    if (empty($_POST['email']) || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $emailErr = "Valid email is required";
    } else {
        $email = test_input($_POST['email']);
    }

    // Password Validation
    if (empty($_POST['password'])) {
        $passwordErr = "Password is required";
    } else {
        $password = password_hash(test_input($_POST['password']), PASSWORD_DEFAULT);
    }

    // Phone Number Validation
    if (empty($_POST['phone_number'])) {
        $phoneErr = "Valid 10-digit phone number required";
    } else {
        $phone_number = test_input($_POST['phone_number']);
    }

    // Date of Birth Validation
    if (empty($_POST['dob'])) {
        $dobErr = "Date of birth is required";
    } else {
        $dob = test_input($_POST['dob']);
    }

    // Color Code Validation
    if (empty($_POST['color_code'])) {
        $colorErr = "Color selection is required";
    } else {
        $color_code = test_input($_POST['color_code']);
    }

    // Gender Validation
    if (empty($_POST['gender'])) {
        $genderErr = "Gender is required";
    } else {
        $gender = test_input($_POST['gender']);
    }

    // Country Validation
    if (empty($_POST['country'])) {
        $countryErr = "Country selection is required";
    } else {
        $country = test_input($_POST['country']);
    }

    // Terms and Conditions Validation
    if (!isset($_POST['terms'])) {
        $termsErr = "You must accept the terms and conditions";
    }

    // Birthday Month Validation
    if (empty($_POST['bdaymonth'])) {
        $bdaymonthErr = "Birthday month is required";
    } else {
        $bdaymonth = test_input($_POST['bdaymonth']);
    }

    // Week Selection Validation
    if (empty($_POST['week'])) {
        $weekErr = "Week selection is required";
    } else {
        $week = test_input($_POST['week']);
    }

    // Quantity Validation
    if (!isset($_POST['quantity']) || $_POST['quantity'] < 1 || $_POST['quantity'] > 50) {
        $quantityErr = "Quantity must be between 1 and 50";
    } else {
        $quantity = test_input($_POST['quantity']);
    }

    // Time Validation
    if (empty($_POST['time'])) {
        $timeErr = "Time selection is required";
    } else {
        $time = test_input($_POST['time']);
    }

    // URL Validation
    if (empty($_POST['url']) || !filter_var($_POST['url'], FILTER_VALIDATE_URL)) {
        $urlErr = "Valid URL is required";
    } else {
        $url = test_input($_POST['url']);
    }


    // File Upload Handling
    if (!empty($_FILES['profile_picture']['name'])) {
        $upload_dir = 'uploads/';

         // Ensure the uploads directory exists
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    // Allowed file types
    $allowed_types = ['image/jpeg', 'image/png', 'image/webp'];
    if (!in_array($_FILES['profile_picture']['type'], $allowed_types)) {
        die("Only JPG, PNG, and webp files are allowed.");
    }

    // Limit file size (2MB max)
    $max_size = 2 * 1024 * 1024; // 2MB
    if ($_FILES['profile_picture']['size'] > $max_size) {
        die("File size exceeds 2MB limit.");
    }

        $file_name = basename($_FILES['profile_picture']['name']);
        $file_tmp = $_FILES['profile_picture']['tmp_name'];
        $profile_picture = $upload_dir . $file_name;

        // Move file to uploads directory
        if (!move_uploaded_file($file_tmp, $profile_picture)) {
            die("Error uploading file.");
        }
    } else {
        $profile_picture = null;
    }

    $hobbies = isset($_POST['hobbies']) ? implode(',', $_POST['hobbies']) : null;

    $terms = isset($_POST['terms']) ? 'true' : 'false';

    // $editorContent = $_POST['editorContent']; 
    $editorContent = isset($_POST['editorContent']) ? test_editor_input($_POST['editorContent']) : '';

   
    // Ensure all required fields are filled
    if ($name && $email && $password && $phone_number && $dob && $color_code && $gender && $country) {

        $result = $user->createUser([
            'name' => $name,
            'email' => $email,
            'password' => $password,
            'phone_number' => $phone_number,
            'dob' => $dob,
            'color_code' => $color_code,
            'profile_picture' => $file_name,
            'gender' => $gender,
            'country' => $country,
            'hobbies' => $hobbies,
            'bdaymonth' => $bdaymonth,
            'week' => $week,
            'quantity' => $quantity,
            'time' => $time,
            'url' => $url,
            'editorContent'=>$editorContent,
            'terms' => $terms,
        ]);


if ($result) {
    $userId = $result; // Capture inserted user ID

    // Handle multiple image uploads
    if (!empty($_FILES['other_images']['name'][0])) {
        $upload_dir = 'uploads/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        foreach ($_FILES['other_images']['name'] as $key => $name) {
            $file_tmp = $_FILES['other_images']['tmp_name'][$key];
            $file_name = basename($name);
            $file_path = $upload_dir . $file_name;

            if (move_uploaded_file($file_tmp, $file_path)) {
                $user->addImage($userId, $file_path); // Pass userId to addImage function
            } else {
                die("Error uploading file: $name");
            }
        }
    }

    header("Location: ../index.php");
    exit;
} else {
    echo "Failed to insert data into the database.";
}

       }
    // } else {
    //     echo "Please fill in all required fields.";
    // }
}


function test_editor_input($data) {
    // Allow basic formatting tags and alignment styles
    $allowed_tags = '<b><i><u><strong><em><p><br><ul><ol><li><blockquote><div><span>';
    // Allow style attribute for alignment
    $data = strip_tags($data, $allowed_tags);
    $data = preg_replace('/class="[^"]*"/', '', $data); // Remove existing classes
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    return $data;
}

function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
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

<h2> User Registration form</h2>
<a href="../index.php" class="btn btn-home">Back to Home</a>

<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" enctype="multipart/form-data">

    Name: <input type="text" name="name"> 
    <span class="error"><?php echo "* ", $nameErr; ?></span><br>

    Email: <input type="email" name="email"> 
    <span class="error"><?php echo "* ", $emailErr; ?></span><br>

    <labelf for="editor">Your Bio</label><br>
    <div id="editor-container" class="text-editor" contenteditable="true"></div>
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


    Password: <input type="password" name="password"> 
    <span class="error"><?php echo "* ",$passwordErr; ?></span><br>

    Phone Number: <input type="text" name="phone_number"> 
    <span class="error"><?php echo "* ",$phoneErr; ?></span><br>

    Date of Birth: <input type="date" name="dob"> 
    <span class="error"><?php echo "* ",$dobErr; ?></span><br>

    Favorite Color: <input type="color" name="color_code"> 
    <span class="error"><?php echo "* ",$colorErr; ?></span><br>
    
    Gender: <input type="radio" name="gender" value="Male"> Male
            <input type="radio" name="gender" value="Female"> Female
            <input type="radio" name="gender" value="Other"> Other
    <span class="error"><?php echo "* ",$genderErr; ?></span><br>

    Hobbies:<br>
    <input type="checkbox" name="hobbies[]" value="Reading"> Reading
    <input type="checkbox" name="hobbies[]" value="Gaming"> Gaming
    <input type="checkbox" name="hobbies[]" value="Traveling"> Traveling
    <input type="checkbox" name="hobbies[]" value="Music"> Music
    <input type="checkbox" name="hobbies[]" value="Sports"> Sports <br>

    Profile Picture: <input type="file" name="profile_picture" > <br>

    Other Images : <input type="file" name="other_images[]" multiple > <br>

    Country: <select name="country">
        <option value="">Select Country</option>
        <option value="India">India</option>
        <option value="USA">USA</option>
        <option value="UK">UK</option>
    </select> 
    <span class="error"><?php echo "* ",$countryErr; ?></span><br>

    Birthday (month and year): <input type="month" name="bdaymonth"> 
    <span class="error"><?php echo "* ",$bdaymonthErr; ?></span><br>

    Choose a week: <input type="week" name="week"> 
    <span class="error"><?php echo "* ",$weekErr; ?></span><br>

    Quantity (between 1 and 50): <input type="range" name="quantity" min="1" max="50"> 
    <span class="error"><?php echo "* ",$quantityErr; ?></span><br>

    Select a time: <input type="time" name="time"> 
    <span class="error"><?php echo "* ",$timeErr; ?></span><br>

    Enter a URL: <input type="url" name="url"> 
    <span class="error"><?php echo "* ",$urlErr; ?></span><br>

    <input type="checkbox" name="terms"> I accept the terms and conditions 
    <span class="error">* <?php echo "* ",$termsErr; ?></span><br>

    <input type="submit" value="Submit">
</form>

</html>


<script>
        function formatText(command) {
            document.execCommand(command, false, null);
        }
            document.querySelector("form").addEventListener("submit", function() {
            document.getElementById("editorContent").value = document.getElementById("editor-container").innerHTML;
        });
</script>