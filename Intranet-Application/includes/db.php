<?php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', 'root123');
define('DB_NAME', 'student_db');

function getConnection() {
    static $conn = null;
    if ($conn !== null) {
        return $conn;
    }
    
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    if ($conn->connect_error) {
        http_response_code(500);
        die(json_encode(["success" => false, "message" => " Database connection failed. Error: " . $conn->connect_error]));
        exit;
    }
    $conn->set_charset("utf8mb4");
    return $conn;
}
?>