<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function require_role($role) {
    if (empty($_SESSION['role'])) {
        header('Location: /login.php');
        exit();
    }

    $expected = is_array($role) ? array_map('strtoupper', $role) : strtoupper($role);
    $current = strtoupper($_SESSION['role']);

    if (is_array($expected)) {
        if (!in_array($current, $expected, true)) {
            header('Location: /unauthorized.php');
            exit();
        }
    } else {
        if ($current !== $expected) {
            header('Location: /unauthorized.php');
            exit();
        }
    }
}

?>
