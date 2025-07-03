<?php
session_start();

// Optional: Restrict to admin users only
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'admin') {
  header("Location: ../login.php");
  exit;
}

include '../db_connect.php'; // Make sure this connects to your tinta_store DB

// Fetch shipping info with order details
$sql = "SELECT s.*, o.user_id 
        FROM Shipping s
        JOIN Orders o ON s.order_id = o.order_id
        ORDER BY s.shipping_id DESC";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Shipping Management - Admin</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      padding: 50px;
      background: linear-gradient(to right, #f3e6ff, #ffffff);
      font-family: 'Segoe UI', sans-serif;
    }
    h2 {
      color: #6f42c1;
      font-weight: 700;
      margin-bottom: 30px;
    }
    table {
      background-color: white;
      border-radius: 10px;
      box-shadow: 0 8px 24px rgba(111, 66, 193, 0.1);
    }
    th {
      background-color: #d6b8ff;
      color: #532a8c;
    }
  </style>
</head>
<body>
  <div class="container">
    <h2>Shipping Management</h2>
    <table class="table table-bordered table-striped">
      <thead>
        <tr>
          <th>ID</th>
          <th>Order ID</th>
          <th>User ID</th>
          <th>Address</th>
          <th>Method</th>
          <th>Status</th>
          <th>Shipped</th>
          <th>Delivered</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
        <tr>
          <td><?= $row['shipping_id'] ?></td>
          <td><?= $row['order_id'] ?></td>
          <td><?= $row['user_id'] ?></td>
          <td><?= htmlspecialchars($row['shipping_address']) ?></td>
          <td><?= $row['shipping_method'] ?></td>
          <td><?= $row['shipping_status'] ?></td>
          <td><?= $row['shipped_date'] ?? '—' ?></td>
          <td><?= $row['delivery_date'] ?? '—' ?></td>
        </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>
</body>
</html>
