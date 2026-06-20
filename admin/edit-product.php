<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/role.php';
require_role('ADMIN');

$id = $_GET['id'];
$result = $conn->query("SELECT * FROM products WHERE id=$id");
$product = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $tool_url = $_POST['tool_url'];
    $status = $_POST['status'];

    $stmt = $conn->prepare("UPDATE products SET name=?, description=?, tool_url=?, status=? WHERE id=?");
    $stmt->bind_param("ssssi", $name, $description, $tool_url, $status, $id);
    $stmt->execute();

    header("Location: products.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head><title>Edit Product</title></head>
<body>
<h2>Edit Product</h2>
<form method="POST">
  <input type="text" name="name" value="<?php echo $product['name']; ?>" required><br>
  <textarea name="description" required><?php echo $product['description']; ?></textarea><br>
  <input type="url" name="tool_url" value="<?php echo $product['tool_url']; ?>" required><br>
  <select name="status">
    <option value="Active" <?php if($product['status']=='Active') echo 'selected'; ?>>Active</option>
    <option value="On Hold" <?php if($product['status']=='On Hold') echo 'selected'; ?>>On Hold</option>
  </select><br>
  <button type="submit">Update</button>
</form>
</body>
</html>
