<?php

function requireLogin($isApi = false) {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if(empty($_SESSION['user_id'])) {
        if ($isApi) {
            http_response_code(401);
            header('Content-Type: application: application/json');
            echo json_encode(["success" => false, "message" => "Not authenticated. Please login."]);
        } else {
            header('Location: login.php');
        }
        exit;
    }
}
?>