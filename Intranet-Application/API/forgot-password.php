<?php
// Start session
session_start();

$servername = "localhost";
$username   = "root";
$password   = "";
$dbname     = "users_db";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);

    if (empty($email)) {
        echo "Please enter your email.";
        exit;
    }

    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        echo "If this email exists, a password reset link has been sent.";
    } else {
        echo "No account found with that email.";
    }

    $stmt->close();
}

$conn->close();
?>