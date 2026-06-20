<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
require_once __DIR__ . '/../includes/db.php';

// Role check: only ADMIN can delete users
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'ADMIN') {
    header("Location: unauthorized.php");
    exit();
}

// Get user ID from query string
$id = $_GET['id'];

// Prevent accidental self-deletion (optional safeguard)
if ($id == $_SESSION['user_id']) {
    echo "You cannot delete your own account.";
    exit();
}

// Delete user from DB
$stmt = $conn->prepare("DELETE FROM users WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();

// Redirect back to users.php
header("Location: users.php");
exit();
?>
