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
    $name = trim($_POST['product_name']);
    $description = trim($_POST['product_description']);
    $url = trim($_POST['product_url']);

    if (empty($name)) {
        echo "Product name is required.";
        exit;
    }

    echo "Product created successfully: " . htmlspecialchars($name, ENT_QUOTES, 'UTF-8');
}

$conn->close();
?>