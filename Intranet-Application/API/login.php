<?php
// Start session
session_start();

// Database connection settings
$servername = "localhost";   // usually localhost
$username   = "root";        // your MySQL username
$password   = "";            // your MySQL password
$dbname     = "users_db";     // your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Process login form
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $login_username = trim($_POST['email']);
    $login_password = trim($_POST['password']);

    if (!empty($login_username) && !empty($login_password)) {
        // Prepare statement to prevent SQL injection
        $stmt = $conn->prepare("SELECT id, name, password FROM users WHERE email = ?");
        $stmt->bind_param("s", $login_username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $row = $result->fetch_assoc();

            // Verify password (assuming password stored with password_hash)
            if (password_verify($login_password, $row['password'])) {
                // Login success
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['username'] = $row['name'];

                echo "Login successful! Welcome, " . $row['name'];
                // Redirect to dashboard
                // header("Location: dashboard.php");
                // exit();
            } else {
                echo "Invalid password.";
            }
        } else {
            echo "No account found with that email.";
        }

        $stmt->close();
    } else {
        echo "Please enter both username and password.";
    }
}

$conn->close();
?>
