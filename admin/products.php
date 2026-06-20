<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
require_once __DIR__ . '/../includes/db.php'; // DB connection

// Fetch products from DB
$sql = "SELECT id, name, description, tool_url, status FROM products";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Company | Products</title>
  <style>
    body {font-family:'Segoe UI',Arial,sans-serif;background:#e0fdfd;margin:0;padding:20px;}
    h2 {color:#006666;}
    table {width:100%;border-collapse:collapse;margin-bottom:30px;}
    th, td {border:1px solid #ccc;padding:10px;text-align:left;}
    th {background:#20b2aa;color:#fff;}
    .card {background:#fff;padding:20px;margin:15px;border-radius:8px;box-shadow:0 2px 8px rgba(0,0,0,0.1);}
    h3 {color:#008b8b;margin-bottom:10px;}
    p {color:#333;font-size:14px;margin-bottom:15px;}
    .btn {background:aquamarine;color:#000;padding:10px 15px;border-radius:6px;text-decoration:none;font-weight:600;}
    .btn-locked {background:#ccc;color:#666;padding:10px 15px;border-radius:6px;text-decoration:none;font-weight:600;}
    .admin-actions {margin-top:10px;}
    .admin-actions a {margin-right:10px;color:#20b2aa;text-decoration:none;font-size:13px;}
  </style>
</head>
<body>
  <h2>Products Table View</h2>
  <?php if ($result->num_rows > 0): ?>
    <table>
      <tr>
        <th>Product Name</th>
        <th>Tool URL</th>
        <th>Status</th>
      </tr>
      <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
          <td><?php echo htmlspecialchars($row['name']); ?></td>
          <td><a href="<?php echo htmlspecialchars($row['tool_url']); ?>" target="_blank">Launch</a></td>
          <td><?php echo htmlspecialchars($row['status']); ?></td>
        </tr>
      <?php endwhile; ?>
    </table>
  <?php else: ?>
    <p>No products found.</p>
  <?php endif; ?>

  <h2>My Authorized Products (Card View)</h2>
  <?php
  // Reset pointer to loop again
  $result->data_seek(0);
  while ($row = $result->fetch_assoc()):
  ?>
    <div class="card">
      <h3><?php echo htmlspecialchars($row['name']); ?></h3>
      <p><?php echo htmlspecialchars($row['description']); ?></p>

      <?php if ($row['status'] === 'Active'): ?>
        <a href="<?php echo htmlspecialchars($row['tool_url']); ?>" target="_blank" class="btn">Launch</a>
      <?php else: ?>
        <span class="btn-locked">Locked</span>
      <?php endif; ?>

      <!-- Show admin-only actions -->
      <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'ADMIN'): ?>
        <div class="admin-actions">
          <a href="edit-product.php?id=<?php echo $row['id']; ?>">Edit</a>
          <a href="delete-product.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Delete this product?');">Delete</a>
        </div>
      <?php endif; ?>
    </div>
  <?php endwhile; ?>
</body>
</html>
