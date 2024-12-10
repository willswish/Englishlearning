<?php
session_start();

// Database connection (Update with your actual database credentials)
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "englishlearn";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if POST data is set
if (isset($_POST['username']) && isset($_POST['email']) && isset($_POST['password'])) {
    // Get user input
    $inputUsername = $_POST['username'];
    $inputEmail = $_POST['email'];
    $inputPassword = $_POST['password'];
    
    // Handle file upload
    $profileImage = NULL;
    if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] == UPLOAD_ERR_OK) {
        $uploadDir = 'uploads/';
        $uploadFile = $uploadDir . basename($_FILES['profile_image']['name']);
        
        // Ensure the uploads directory exists
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        
        // Move the uploaded file to the server
        if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $uploadFile)) {
            $profileImage = $uploadFile;
        } else {
            echo "Error uploading file.";
        }
    }

    // Prepare SQL query to prevent SQL injection
    $stmt = $conn->prepare("INSERT INTO users (username, email, password, profile_image, user_type) VALUES (?, ?, ?, ?, 'student')");
    $stmt->bind_param("ssss", $inputUsername, $inputEmail, $inputPassword, $profileImage);

    if ($stmt->execute()) {
        // Redirect to login page with success query parameter
        header("Location: loginsignup_page.php?success=1");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>
