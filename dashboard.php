<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
require_once 'includes/db.php'; // database connection
require_once 'includes/auth.php'; // session + role check
?>
<!DOCTYPE html>
<html>
<head>
  <title>Dashboard</title>
</head>
<body>
  <!-- Sidebar -->
  <ul>
    <li><a href="dashboard.php">Dashboard</a></li>
    <li><a href="products.php">Products</a></li>
    <li><a href="profile.php">Profile</a></li>
    <li><a href="logout.php">Logout</a></li>
    <?php if ($_SESSION['role'] === 'ADMIN'): ?>
      <li><a href="settings.php">Settings</a></li>
      <li><a href="users.php">User Management</a></li>
      <li><a href="create-product.php">Create Product</a></li>
    <?php endif; ?>
  </ul>

  <!-- Products Section -->
  <h2>Products</h2>
  <?php
  $result = mysqli_query($conn, "SELECT * FROM products");
  while ($row = mysqli_fetch_assoc($result)) {
      echo "<h3>{$row['name']}</h3>";
      echo "<p>{$row['tool_url']}</p>";

      if ($row['status'] === 'Active') {
          echo "<a href='{$row['tool_url']}' class='btn btn-success'>Launch</a>";
      } else {
          echo "<button class='btn btn-secondary' disabled>Locked</button>";
      }

      if ($_SESSION['role'] === 'ADMIN') {
          echo "<a href='edit-product.php?id={$row['id']}'>Edit</a>";
          echo "<a href='delete-product.php?id={$row['id']}'>Delete</a>";
      }
  }
  ?>
</body>
</html>
