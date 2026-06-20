<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/role.php';

// 🔒 Restrict access to admins only
require_role('ADMIN');

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name        = trim($_POST['name']);
    $description = trim($_POST['description']);
    $tool_url    = trim($_POST['tool_url']);
    $status      = $_POST['status'];

    if (!empty($name) && !empty($tool_url) && !empty($status)) {
        $stmt = $conn->prepare("INSERT INTO products (name, description, tool_url, status) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $name, $description, $tool_url, $status);

        if ($stmt->execute()) {
            header("Location: products.php?msg=Product+created+successfully");
            exit();
        } else {
            $error = "Error creating product: " . $conn->error;
        }
    } else {
        $error = "Please fill in all required fields.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Create Product</title>
  <style>
    body {font-family:'Segoe UI',Arial,sans-serif;background:#e0fdfd;margin:0;padding:20px;}
    h2 {color:#006666;}
    form {background:#fff;padding:20px;border-radius:8px;box-shadow:0 2px 8px rgba(0,0,0,0.1);max-width:500px;}
    label {display:block;margin-top:10px;font-weight:600;}
    input, textarea, select {width:100%;padding:10px;margin-top:5px;border:1px solid #ccc;border-radius:6px;}
    button {margin-top:15px;background:#20b2aa;color:#fff;padding:10px 15px;border:none;border-radius:6px;font-weight:600;cursor:pointer;}
    button:hover {background:#008b8b;}
    .error {color:red;margin-top:10px;}
  </style>
</head>
<body>
  <h2>Create New Product</h2>

  <?php if (!empty($error)): ?>
    <p class="error"><?php echo htmlspecialchars($error); ?></p>
  <?php endif; ?>

  <form method="POST" action="">
    <label for="name">Product Name *</label>
    <input type="text" id="name" name="name" required>

    <label for="description">Description</label>
    <textarea id="description" name="description"></textarea>

    <label for="tool_url">Tool URL *</label>
    <input type="url" id="tool_url" name="tool_url" required>

    <label for="status">Status *</label>
    <select id="status" name="status" required>
      <option value="Active">Active</option>
      <option value="Inactive">Inactive</option>
    </select>

    <button type="submit">Create Product</button>
  </form>
</body>
</html>
