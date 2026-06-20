<?php
$servername = "localhost";
$username   = "root";
$password   = "";
$dbname     = "users_db";

function getConnection() {
    static $conn = null;
    if ($conn !== null) {
        return $conn;
    }

    global $servername, $username, $password, $dbname;
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        http_response_code(500);
        die(json_encode(["success" => false, "message" => "Database connection failed. Error: " . $conn->connect_error]));
    }
    $conn->set_charset("utf8mb4");
    return $conn;
}
?>