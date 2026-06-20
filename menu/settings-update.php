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
    $title = trim($_POST['title']);
    $supportDesk = trim($_POST['support_desk']);
    $sessionExpiry = intval($_POST['session_expiry']);
    $enableAudit = isset($_POST['enable_audit']) ? 1 : 0;
    $forceMFA = isset($_POST['force_mfa']) ? 1 : 0;

    // In a production application, settings should be stored in a configuration table.
    echo "Settings saved successfully.";
}

$conn->close();
?>