<?php
session_start();

$user = $_SESSION['user'] ?? null;

if (!$user) {
    header('Location: login.php');
    exit;
}

$username = $user['username'] ?? '';
$passwordLength = 8;
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Welcome - Armmiela's Site</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
<style>
  body {
    background: linear-gradient(to bottom right, #f3e9ff, #fdf9ff);
    font-family: 'Segoe UI', sans-serif;
    padding-top: 80px;
  }

  .navbar, footer {
    background-color: #6f42c1;
  }

  .navbar-brand,
  .navbar-nav .nav-link,
  footer {
    color: white !important;
  }

  .welcome-container {
    max-width: 800px;
    margin: 0 auto;
    padding: 40px 30px;
    background: #fff;
    border-radius: 16px;
    box-shadow:
      0 4px 10px rgba(111, 66, 193, 0.1),
      0 8px 20px rgba(111, 66, 193, 0.2);
    animation: fadeIn 0.6s ease-out;
  }

  @keyframes fadeIn {
    from {
      opacity: 0;
      transform: translateY(20px);
    }
    to {
      opacity: 1;
      transform: translateY(0);
    }
  }

  h1 {
    color: #6f42c1;
    font-weight: 700;
    margin-bottom: 1.5rem;
    text-align: center;
  }

  .user-info li {
    margin-bottom: 10px;
    font-size: 1rem;
    color: #333;
  }

  .user-info strong {
    color: #6f42c1;
    display: inline-block;
    width: 150px;
  }

  .btn-purple {
    background-color: #6f42c1;
    color: #fff;
    border: none;
    padding: 12px 30px;
    font-size: 1rem;
    font-weight: 600;
    border-radius: 8px;
    transition: background-color 0.3s, box-shadow 0.3s;
    margin-top: 20px;
  }

  .btn-purple:hover {
    background-color: #5a35a0;
    box-shadow: 0 4px 12px rgba(90, 53, 160, 0.4);
    color: #fff;
  }

  footer {
    padding: 20px 0;
    font-size: 0.9rem;
    letter-spacing: 0.5px;
  }
</style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg fixed-top shadow-sm">
  <div class="container">
    <a class="navbar-brand" href="index.php">
      <img src="logo.jpg" 
           alt="TINTA Logo" 
           width="40" 
           height="40" 
           class="d-inline-block align-text-top rounded-circle me-2">
      TINTA
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" 
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon bg-light"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">
        <?php if ($user): ?>
          <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
        <?php else: ?>
          <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
          <li class="nav-item"><a class="nav-link" href="register.php">Register</a></li>
        <?php endif; ?>
        <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
      </ul>
    </div>
  </div>
</nav>

<!-- Welcome Section -->
<div class="container my-5">
  <div class="welcome-container">
    <h1>Welcome, <?= htmlspecialchars($user['full_name']) ?>!</h1>
    <p class="text-center mb-4">Thank you for logging in to <strong>TINTA'S Site</strong>. Here's your account information:</p>

    <ul class="list-unstyled user-info">
      <li><strong>Full Name:</strong> <?= htmlspecialchars($user['full_name']) ?></li>
      <li><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></li>
      <li><strong>Username:</strong> <?= htmlspecialchars($user['username']) ?></li>
      <li><strong>Address:</strong>
  <?= htmlspecialchars($user['street'] ?? '') ?>,
  <?= htmlspecialchars($user['city'] ?? '') ?>,
  <?= htmlspecialchars($user['province'] ?? '') ?>,
  <?= htmlspecialchars($user['zip'] ?? '') ?>,
  <?= htmlspecialchars($user['country'] ?? '') ?>
</li>
<li><strong>Account Created:</strong> <?= htmlspecialchars($user['created_at'] ?? '') ?></li>

      <li><strong>Password:</strong> <?= str_repeat('*', $passwordLength) ?> (hidden)</li>
    </ul>

    <div class="text-center">
      <a href="logout.php" class="btn btn-purple">Logout</a>
    </div>
  </div>
</div>

<!-- Footer -->
<footer class="text-center text-white mt-5">
  <div class="container">
    <p class="mb-0">© 2025 Armmiela Shylle Feliciano. All rights reserved.</p>
  </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
