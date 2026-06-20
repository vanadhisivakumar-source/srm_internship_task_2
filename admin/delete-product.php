<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/role.php';
require_role('ADMIN');

if ($_SESSION['role'] !== 'ADMIN') {
    header("Location: unauthorized.php");
    exit();
}

$id = $_GET['id'];
$stmt = $conn->prepare("DELETE FROM products WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();

header("Location: products.php");
exit();
?>
