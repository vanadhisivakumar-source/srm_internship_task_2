<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/role.php';
require_role('ADMIN');

if ($_SESSION['role'] !== 'ADMIN') {
    header("Location: unauthorized.php");
    exit();
}

$result = $conn->query("SELECT id, name, email, role, status FROM users");
?>
<!DOCTYPE html>
<html>
<head><title>Manage Users</title></head>
<body>
<h2>User Management</h2>
<table border="1" cellpadding="8">
  <tr>
    <th>Name</th><th>Email</th><th>Role</th><th>Status</th><th>Actions</th>
  </tr>
  <?php while ($row = $result->fetch_assoc()): ?>
    <tr>
      <td><?php echo htmlspecialchars($row['name']); ?></td>
      <td><?php echo htmlspecialchars($row['email']); ?></td>
      <td><?php echo htmlspecialchars($row['role']); ?></td>
      <td><?php echo htmlspecialchars($row['status']); ?></td>
      <td>
        <a href="delete-user.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Delete this user?');">Delete</a>
      </td>
    </tr>
  <?php endwhile; ?>
</table>
</body>
</html>
