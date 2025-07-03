<?php
session_start();
if (!isset($_SESSION['user'])) {
  header("Location: login.php");
  exit();
}
$user = $_SESSION['user'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Order Success - TINTA</title>
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

    .success-container {
      max-width: 700px;
      margin: auto;
      background: white;
      padding: 50px;
      border-radius: 12px;
      box-shadow: 0 0 25px rgba(106, 13, 173, 0.1);
      text-align: center;
    }

    h2 {
      color: #6a0dad;
      font-weight: bold;
    }

    .thank-you-icon {
      font-size: 60px;
      color: #6a0dad;
      margin-bottom: 20px;
    }

    .btn-home {
      background-color: #6a0dad;
      color: white;
      font-weight: 600;
      padding: 10px 30px;
      border-radius: 25px;
      margin-top: 30px;
      text-decoration: none;
    }

    .btn-home:hover {
      background-color: #5a0cae;
      color: white;
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

<div class="success-container mt-5">
  <div class="thank-you-icon">💜</div>
  <h2>Thank You for Your Order!</h2>
  <p class="mt-3">Hi <?= htmlspecialchars($user['full_name']) ?>, your order has been successfully placed.</p>
  <p>You’ll receive a confirmation email shortly. Thank you for shopping at <strong>TINTA by Armmiela Beauty</strong>!</p>
  <a href="index.php" class="btn-home">Back to Home</a>
</div>

<footer>
  <div class="container">
    <p class="mb-0">© 2025 Armmiela Beauty. All rights reserved.</p>
  </div>
</footer>

</body>
</html>
