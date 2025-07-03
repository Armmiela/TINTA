<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

require_once 'config.php';

// Correctly get user_id from session
$user_id = $_SESSION['user']['user_id'];

// Fetch user data
$stmt = $pdo->prepare("SELECT * FROM Users WHERE user_id = ?");
$stmt->execute([$user_id]);
$userData = $stmt->fetch(PDO::FETCH_ASSOC);

// Fetch cart items to calculate total
$cartItems = [];
$stmt = $pdo->prepare("
    SELECT p.product_id, p.name, p.price, p.image_url, c.quantity
    FROM Cart c
    JOIN Products p ON c.product_id = p.product_id
    WHERE c.user_id = ?
");
$stmt->execute([$user_id]);
$cartItems = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Calculate total
$total = 0;
foreach ($cartItems as $item) {
    $total += $item['price'] * $item['quantity'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Checkout - TINTA</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background: radial-gradient(circle at top left, #f8f3fc, #ffffff);
      padding-top: 80px;
    }

    .navbar {
      background: linear-gradient(to right, #6a0dad, #b980f0);
    }

    .navbar .nav-link, .navbar-brand {
      color: white !important;
      font-weight: 600;
    }

    .checkout-container {
      max-width: 720px;
      margin: auto;
      background: white;
      padding: 40px;
      border-radius: 12px;
      box-shadow: 0 0 25px rgba(106, 13, 173, 0.1);
    }

    h2, h4 {
      color: #6a0dad;
      font-weight: bold;
    }

    .form-label {
      font-weight: 600;
    }

    .btn-submit {
      background-color: #6a0dad;
      color: white;
      font-weight: 600;
      border: none;
      padding: 10px 25px;
      border-radius: 25px;
    }

    .btn-submit:hover {
      background-color: #5a0cae;
    }

    footer {
      text-align: center;
      background: #6a0dad;
      color: white;
      padding: 20px 0;
      margin-top: 40px;
    }
  </style>
</head>
<body>

<nav class="navbar navbar-expand-lg fixed-top shadow-sm">
  <div class="container">
    <a class="navbar-brand" href="index.php">
      <img src="logo.jpg" width="40" height="40" class="rounded-circle me-2" alt="TINTA Logo">
      TINTA
    </a>
  </div>
</nav>

<div class="checkout-container mt-5">
  <h2 class="mb-4 text-center">Checkout</h2>

<form method="POST" action="process_order.php">
    <div class="row">
      <h4>Billing Information</h4>

      <div class="mb-3">
        <label class="form-label">Full Name</label>
        <input type="text" name="full_name" class="form-control" value="<?= htmlspecialchars($userData['full_name'] ?? '') ?>" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Email</label>
        <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($userData['email'] ?? '') ?>" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Phone Number</label>
        <input type="text" name="phone" class="form-control" value="<?= htmlspecialchars($userData['phone'] ?? '') ?>" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Address</label>
        <textarea name="address" class="form-control" rows="3" required><?= htmlspecialchars($userData['address'] ?? '') ?></textarea>
      </div>

      <div class="mb-3">
        <label class="form-label">Payment Method</label>
        <select name="payment_method" class="form-select" required>
          <option value="">-- Select Payment Method --</option>
          <option value="Cash on Delivery">Cash on Delivery</option>
          <option value="GCash">GCash</option>
          <option value="Credit Card">Credit Card</option>
          <option value="PayPal">PayPal</option>
        </select>
      </div>

      <!-- Hidden input for total (still required by process_order.php) -->
      <input type="hidden" name="order_total" value="<?= htmlspecialchars($total) ?>">

      <div class="mt-4 text-end">
        <button type="submit" class="btn btn-submit">Confirm Order</button>
      </div>
    </div>
  </form>
</div>

<footer>
  <div class="container">
    <p class="mb-0">© 2025 Armmiela Beauty. All rights reserved.</p>
  </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
