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
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $currentPassword = trim($_POST['current_password']);
    $newPassword = trim($_POST['new_password']);

    if (empty($name) || empty($email)) {
        echo "Please complete all profile fields.";
        exit;
    }

    $userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
    if (!$userId) {
        echo "User not logged in.";
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Please enter a valid email address.";
        exit;
    }

    $stmt = $conn->prepare("SELECT password FROM users WHERE id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();

    if ($currentPassword && !password_verify($currentPassword, $row['password'])) {
        echo "Current password is incorrect.";
        exit;
    }

    if ($newPassword) {
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE users SET name = ?, email = ?, password = ? WHERE id = ?");
        $stmt->bind_param("sssi", $name, $email, $hashedPassword, $userId);
    } else {
        $stmt = $conn->prepare("UPDATE users SET name = ?, email = ? WHERE id = ?");
        $stmt->bind_param("ssi", $name, $email, $userId);
    }

    if ($stmt->execute()) {
        echo "Profile updated successfully.";
    } else {
        echo "Unable to update profile.";
    }

    $stmt->close();
}

$conn->close();
?>