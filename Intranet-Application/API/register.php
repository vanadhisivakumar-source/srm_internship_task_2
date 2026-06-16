<?php
// Start session
session_start();

// Database connection
$servername = "localhost";
$username   = "root";
$password   = "";
$dbname     = "users_db";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Process registration form
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $reg_name     = trim($_POST['name']);
    $reg_email    = trim($_POST['email']);
    $reg_password = trim($_POST['password']);
    $confirm_pass = trim($_POST['confirm_password']);

    if (!empty($reg_name) && !empty($reg_email) && !empty($reg_password) && !empty($confirm_pass)) {
        if ($reg_password === $confirm_pass) {
            // Check if email already exists
            $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
            $stmt->bind_param("s", $reg_email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows === 0) {
                // Hash password
                $hashedPassword = password_hash($reg_password, PASSWORD_DEFAULT);

                // Insert new user
                $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
                $stmt->bind_param("sss", $reg_name, $reg_email, $hashedPassword);

                if ($stmt->execute()) {
                    echo "Registration successful! You can now log in.";
                    // Optionally redirect to login
                    // header("Location: login.php");
                    // exit();
                } else {
                    echo "Error: Could not register user.";
                }
            } else {
                echo "Email already registered.";
            }
            $stmt->close();
        } else {
            echo "Passwords do not match.";
        }
    } else {
        echo "Please fill in all fields.";
    }
}

$conn->close();
?>
