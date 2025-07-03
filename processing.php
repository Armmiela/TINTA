<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

// Store POST values in session temporarily
$_SESSION['checkout_data'] = $_POST;

// Automatically call process_order.php after a delay using JS redirect
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Processing Order - TINTA</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: radial-gradient(circle at top left, #f3eaff, #ffffff);
      display: flex;
      align-items: center;
      justify-content: center;
      height: 100vh;
      flex-direction: column;
      font-family: 'Segoe UI', sans-serif;
    }

    .loader {
      border: 6px solid #e9d9f8;
      border-top: 6px solid #6a0dad;
      border-radius: 50%;
      width: 60px;
      height: 60px;
      animation: spin 1s linear infinite;
      margin-bottom: 20px;
    }

    @keyframes spin {
      0% { transform: rotate(0deg); }
      100% { transform: rotate(360deg); }
    }

    h3 {
      color: #6a0dad;
      margin-bottom: 10px;
    }

    p {
      color: #555;
    }
  </style>
</head>
<body>

  <div class="loader"></div>
  <h3>Processing your order...</h3>
  <p>Please wait while we complete your checkout.</p>

  <script>
    // Redirect to process_order.php after 2.5 seconds
    setTimeout(function () {
      window.location.href = 'process_order.php';
    }, 2500);
  </script>
</body>
</html>
