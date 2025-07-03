<?php
session_start();

// Database connection settings
$host = 'localhost';
$dbname = 'tinta_store';
$dbuser = 'root';
$dbpass = ''; // change if your DB has a password

$error = '';

// Connect to the database
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $dbuser, $dbpass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {
        $error = 'Please enter both username and password.';
    } else {
        $stmt = $pdo->prepare("SELECT * FROM Users WHERE username = :username LIMIT 1");
        $stmt->execute(['username' => $username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            // Store user data in session
            $_SESSION['user'] = [
                'user_id'    => $user['user_id'],
                'username'   => $user['username'],
                'email'      => $user['email'],
                'full_name'  => $user['full_name'],
                'gender'     => $user['gender'] ?? '',
                'dob'        => $user['dob'] ?? '',
                'phone'      => $user['phone'] ?? '',
                'street'     => $user['street'] ?? '',
                'city'       => $user['city'] ?? '',
                'province'   => $user['province'] ?? '',
                'zip'        => $user['zip'] ?? '',
                'country'    => $user['country'] ?? '',
                'created_at' => $user['created_at'] ?? ''
            ];

            $_SESSION['username'] = $user['username'];

            header('Location: welcome.php');
            exit;
        } else {
            $error = 'Invalid username or password.';
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Login - Armmiela's Site</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
<style>
  /* Base styles */
  body, html {
    height: 100%;
    background: linear-gradient(135deg, #f3e9ff, #fdf9ff);
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  }
  /* Navbar & footer */
  .navbar, footer {
    background-color: #6f42c1;
  }
  .navbar-brand,
  .navbar-nav .nav-link,
  footer {
    color: white !important;
  }
  .navbar-nav .nav-link.active {
    font-weight: 600;
    border-bottom: 2px solid #d6b8ff;
    padding-bottom: 5px;
  }
  /* Container flex for centering */
  .container-flex {
    min-height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 1.5rem;
  }
  /* Login card */
  .login-section {
    background: #fff;
    padding: 3rem 2.5rem;
    border-radius: 16px;
    box-shadow:
      0 4px 8px rgba(111, 66, 193, 0.1),
      0 12px 24px rgba(111, 66, 193, 0.15);
    max-width: 400px;
    width: 100%;
    animation: fadeInUp 0.8s ease forwards;
    transition: box-shadow 0.3s ease;
  }
  .login-section:hover {
    box-shadow:
      0 6px 16px rgba(111, 66, 193, 0.15),
      0 20px 40px rgba(111, 66, 193, 0.25);
  }
  @keyframes fadeInUp {
    from {
      opacity: 0;
      transform: translateY(20px);
    }
    to {
      opacity: 1;
      transform: translateY(0);
    }
  }
  /* Heading */
  h4 {
    text-align: center;
    color: #6f42c1;
    font-weight: 700;
    margin-bottom: 2rem;
    letter-spacing: 1.2px;
    text-transform: uppercase;
  }
  /* Labels */
  label {
    font-weight: 600;
    color: #444;
  }
  /* Input fields */
  input.form-control {
    border: 1.8px solid #ddd;
    border-radius: 8px;
    transition: border-color 0.3s, box-shadow 0.3s;
    font-size: 1rem;
    padding: 0.625rem 0.75rem;
  }
  input.form-control:focus {
    border-color: #6f42c1;
    box-shadow: 0 0 10px rgba(111, 66, 193, 0.3);
    outline: none;
  }
  /* Login button */
  .btn-purple {
    background-color: #6f42c1;
    color: white;
    font-weight: 700;
    padding: 14px 0;
    border-radius: 10px;
    font-size: 1.1rem;
    box-shadow: 0 4px 12px rgba(111, 66, 193, 0.3);
    transition: background-color 0.3s, box-shadow 0.3s;
    border: none;
  }
  .btn-purple:hover,
  .btn-purple:focus {
    background-color: #5a35a0;
    box-shadow: 0 6px 18px rgba(90, 53, 160, 0.5);
    color: white;
  }
  /* Register link */
  .register-link {
    display: block;
    margin-top: 1.25rem;
    text-align: center;
    font-size: 0.9rem;
    color: #6f42c1;
    font-weight: 600;
    transition: color 0.3s;
    text-decoration: none;
  }
  .register-link:hover,
  .register-link:focus {
    color: #5a35a0;
    text-decoration: underline;
  }
  /* Footer */
  footer {
    padding: 15px 0;
    font-size: 0.9rem;
    letter-spacing: 0.05em;
  }
  /* Error message styling */
  .error-msg {
    color: #dc3545;
    font-weight: 600;
    margin-bottom: 1rem;
    text-align: center;
  }
</style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg shadow-sm">
  <div class="container">
    <a class="navbar-brand" href="index.php">
  <img src="logo.jpg" 
       alt="TINTA Logo" 
       width="40" 
       height="40" 
       class="d-inline-block align-text-top rounded-circle me-2">
  TINTA
</a>
    <button
      class="navbar-toggler"
      type="button"
      data-bs-toggle="collapse"
      data-bs-target="#navbarNav"
      aria-controls="navbarNav"
      aria-expanded="false"
      aria-label="Toggle navigation"
    >
      <span class="navbar-toggler-icon bg-light"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link active" href="#">Home</a></li>
        <li class="nav-item"><a class="nav-link" href="#">About Us</a></li>
        <li class="nav-item"><a class="nav-link" href="#">What We Offer</a></li>
      </ul>
    </div>
  </div>
</nav>

<!-- Login Form -->
<div class="container-flex">
  <div class="login-section shadow">
    <h4>Login</h4>

    <?php if ($error): ?>
      <div class="error-msg"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST" action="login.php" novalidate>
      <div class="mb-4">
        <label for="username" class="form-label">Username</label>
        <input
          type="text"
          name="username"
          id="username"
          class="form-control"
          placeholder="Enter username"
          required
          autocomplete="username"
          autofocus
          spellcheck="false"
          autocorrect="off"
          autocapitalize="none"
          value="<?= htmlspecialchars($_POST['username'] ?? '') ?>"
        />
      </div>
      <div class="mb-5">
        <label for="password" class="form-label">Password</label>
        <input
          type="password"
          name="password"
          id="password"
          class="form-control"
          placeholder="Enter password"
          required
          autocomplete="current-password"
        />
      </div>
      <button type="submit" class="btn btn-purple w-100">Login</button>
    </form>
    <a href="register.php" class="register-link" tabindex="0">Don't have an account? Register here</a>
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
