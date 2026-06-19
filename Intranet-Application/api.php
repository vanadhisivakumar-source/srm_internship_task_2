<?php
session_start();

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit();
}

require_once 'includes/db.php';

$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? '';

switch ($method) {
    case 'GET':
        if ($action === 'getUsers') {
            getUsers();
        } else {
            http_response_code(400);
            echo json_encode(["error" => "Invalid action for GET request"]);
        }
        break;

    case 'POST':
        switch ($action) {
            case 'login':
                loginUser();
                break;
            case 'register':
                registerUser();
                break;
            // case 'createUser':
            //     createUser();
            //     break;
            case 'forgetPassword':
                forgetPassword();
                break;
            default:
                http_response_code(400);
                echo json_encode(["success" => false, "message" => "Invalid action for POST request"]);
        }
        break;

    default:
        http_response_code(405);
        echo json_encode(["error" => "Method not allowed"]);
        break;
}

function loginUser() {
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (empty($email) || empty($password)) {
        echo json_encode(["success" => false, "message" => "Email and password are required."]);
        return;
    }

    $conn = getConnection();
    $stmt = $conn->prepare("SELECT id, name, email, password, role FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();

    if (!$user || !password_verify($password, $user['password'])) {
        echo json_encode(["success" => false, "message" => "Invalid email or password."]);
        return;
    }

    $_SESSION['user_id'] = $user['id'];
    $_SESSION['user_name'] = $user['name'];
    $_SESSION['user_email'] = $user['email'];
    $_SESSION['user_role'] = $user['role'];

    echo json_encode([
        "success" => true,
        "message" => "Login successful.",
        "role" => $user['role']
    ]);
}

function forgetPassword(){
    $email = trim($_POST['email'] ?? '');
    $newPassword = trim($_POST['newPassword'] ?? ($_POST['password'] ?? ''));
        
    if (empty($email) || empty($newPassword)) {
        echo json_encode(["success" =>  false, "message" => "Email and New Password are required."]);
        return;
    }

    if (strlen($newPassword) < 6) {
        echo json_encode(["success" => false, "message" => "Password must be at least 6 characters. "]);
        return;
    }

    $conn = getConnection();
    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
    $update = $conn->prepare("UPDATE users SET password = ? WHERE email = ?");
    $update->bind_param("ss", $hashedPassword, $email);

    if ($update->execute() && $update->affected_rows>0){
        echo json_encode(["success" => true, "message" => "Password reset successfully. You can now login."]);
    } else {
        echo json_encode(["success" => false, "message" => "No account found with this email."]);
    }
    $update->close();
}

function getUsers() {
    $conn = getConnection();
    $result = $conn->query("SELECT id, username, email, role FROM users");
    $users = [];

    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }

    echo json_encode($users);
    $conn->close();
}

function createUser() {
    $data = json_decode(file_get_contents('php://input'), true);
    $username = $data['username'] ?? '';
    $email = $data['email'] ?? '';
    $password = $data['password'] ?? '';

    if (empty($username) || empty($email)) {
        http_response_code(400);
        echo json_encode(["error" => "Username and email are required"]);
        return;
    }

    $conn = getConnection();
    $stmt = $conn->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, 'user')");
    $stmt->bind_param("ss", $username, $email, $password);

    if ($stmt->execute()) {
        echo json_encode(["message" => "User created successfully", "id" => $stmt->insert_id]);
    } else {
        http_response_code(500);
        echo json_encode(["error" => "Failed to create user: " . $stmt->error]);
    }

    $stmt->close();
    $conn->close();
}

function registerUser() {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '' );
    $password = trim($_POST['password'] ?? '');
    $confirmPassword = trim($_POST['confirm_password'] ?? '');

    if (empty($name) || empty($email) || empty($password) || empty($confirmPassword)) {
        echo json_encode(["success" => false, "message" => "Please fill in all fields."]);
        return;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(["success" => false, "message" => "Please enter a valid email address."]);
        return;
    }

    if ($password !== $confirmPassword) {
        echo json_encode(["success" => false, "message" => "Passwords do not match."]);
        return;
    }

    if (strlen($password) < 6) {
        echo json_encode(["success" => false, "message" => "Password must be at least 6 characters."]);
        return;
    }

    $conn = getConnection();
    $check = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    $check->store_result();
    if ($check->num_rows > 0) {
        $check->close();
        echo json_encode(["success" => false, "message" => "An account with this email already exists."]);
        return;
    }
    
    $check->close();
    
    $countResult = $conn->query("SELECT COUNT(*) AS total FROM users");
    $row = $countResult->fetch_assoc();
    $role = ($row['total'] == 0) ? 'admin' : 'user';
    $stmt = $conn->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
    $hashPassword = password_hash($password, PASSWORD_DEFAULT);
    $stmt->bind_param("ssss", $name, $email, $hashPassword, $role);

    if ($stmt->execute()) {
        echo json_encode([
            "success" => true,
            "message" => "Registration successful. You can now login."
        ]);
    } else {
        http_response_code(500);
        echo json_encode(["success" => false, "message" => "Registration failed: " . $stmt->error]);
    }

    $stmt->close();
}

?>