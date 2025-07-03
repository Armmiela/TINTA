<?php
session_start();

$errors = [];

$fullname = $_POST["fullname"] ?? '';
$gender = $_POST["gender"] ?? '';
$dob = $_POST["dob"] ?? '';
$phone = $_POST["phone"] ?? '';
$email = $_POST["email"] ?? '';
$street = $_POST["street"] ?? '';
$city = $_POST["city"] ?? '';
$province = $_POST["province"] ?? '';
$zip = $_POST["zip"] ?? '';
$country = $_POST["country"] ?? '';
$username = $_POST["username"] ?? '';
$password = $_POST["password"] ?? '';
$confirm = $_POST["confirm"] ?? '';

// Connect to DB
$host = 'localhost';
$dbname = 'tinta_store';
$dbuser = 'root';
$dbpass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $dbuser, $dbpass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Validation
    if (!preg_match("/^[A-Za-z ]{3,}$/", $fullname)) {
        $errors['fullname'] = "Only letters & spaces, min 3 characters.";
    }

    if (empty($gender)) {
        $errors['gender'] = "Please select your gender.";
    }

    if (empty($dob)) {
        $errors['dob'] = "Date of birth is required.";
    } else {
        $dobDate = DateTime::createFromFormat('Y-m-d', $dob);
        $today = new DateTime();
        $age = $today->diff($dobDate)->y;

        if ($age < 18) {
            $errors['dob'] = "You must be at least 18 years old to register.";
        }
    }

    if (empty($phone)) {
        $errors['phone'] = "Phone number is required.";
    } elseif (!preg_match('/^09\d{9}$/', $phone)) {
        $errors['phone'] = "Phone number must be 11 digits and start with '09'.";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Invalid email format.";
    }

    foreach (['street', 'city', 'province', 'country'] as $field) {
        if (empty($$field)) {
            $errors[$field] = ucfirst($field) . " is required.";
        }
    }

    if (!preg_match("/^\d{4,6}$/", $zip)) {
        $errors['zip'] = "ZIP code must be 4–6 digits.";
    }

    if (strlen($username) < 4) {
        $errors['username'] = "Username must be at least 4 characters.";
    }

    if (empty($password)) {
        $errors['password'] = "Password is required.";
    } elseif (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/', $password)) {
        $errors['password'] = "Password must be at least 8 characters long and include an uppercase letter, a lowercase letter, a digit, and a special character.";
    }

    if (empty($confirm)) {
        $errors['confirm'] = "Please confirm your password.";
    } elseif ($password !== $confirm) {
        $errors['confirm'] = "Passwords do not match.";
    }

    // Check if username or email already exists
    if (empty($errors)) {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM Users WHERE username = :username OR email = :email");
        $stmt->execute(['username' => $username, 'email' => $email]);
        $exists = $stmt->fetchColumn();

        if ($exists) {
            $errors['username'] = "Username or email already exists.";
        }
    }

    // Save to database
    if (empty($errors)) {
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $pdo->prepare("
            INSERT INTO Users 
            (full_name, gender, dob, phone, email, street, city, province, zip, country, username, password)
            VALUES 
            (:full_name, :gender, :dob, :phone, :email, :street, :city, :province, :zip, :country, :username, :password)
        ");

        $stmt->execute([
            'full_name' => $fullname,
            'gender' => $gender,
            'dob' => $dob,
            'phone' => $phone,
            'email' => $email,
            'street' => $street,
            'city' => $city,
            'province' => $province,
            'zip' => $zip,
            'country' => $country,
            'username' => $username,
            'password' => $passwordHash
        ]);

        header("Location: login.php");
        exit();
    }
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Register - Armmiela's Site</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
<style>
  /* your existing styles (omitted here for brevity) */
  body {
    background: linear-gradient(135deg, #f9f4ff, #fdf9ff);
    font-family: 'Segoe UI', sans-serif;
    padding-top: 90px;
    color: #3e3e3e;
  }
  .navbar, footer {
    background-color: #6f42c1;
  }
  .navbar-brand,
  .navbar-nav .nav-link,
  footer {
    color: white !important;
  }
  .navbar-nav .nav-link:hover,
  .navbar-nav .nav-link.active {
    color: #d6b8ff !important;
    font-weight: 600;
    border-bottom: 2px solid #d6b8ff;
    padding-bottom: 4px;
  }
  .register-section {
    background: #fff;
    padding: 40px 45px;
    border-radius: 16px;
    max-width: 650px;
    margin: 40px auto 80px;
    box-shadow:
      0 6px 15px rgba(111, 66, 193, 0.1),
      0 10px 30px rgba(111, 66, 193, 0.15);
    transition: box-shadow 0.3s ease;
  }
  .register-section:hover {
    box-shadow:
      0 8px 22px rgba(111, 66, 193, 0.15),
      0 14px 45px rgba(111, 66, 193, 0.25);
  }
  h4, h5 {
    color: #6f42c1;
    text-align: center;
    font-weight: 700;
    letter-spacing: 1.1px;
  }
  h5 {
    margin-bottom: 1.75rem;
  }
  .form-label {
    color: #6f42c1;
    font-weight: 600;
  }
  input.form-control,
  select.form-select {
    border-radius: 10px;
    border: 1.8px solid #ddd;
    padding: 0.625rem 0.85rem;
    font-size: 1rem;
    transition: border-color 0.3s ease, box-shadow 0.3s ease;
  }
  input.form-control:focus,
  select.form-select:focus {
    border-color: #6f42c1;
    box-shadow: 0 0 10px rgba(111, 66, 193, 0.3);
    outline: none;
  }
  .btn-purple {
    background-color: #6f42c1;
    color: white;
    font-weight: 700;
    font-size: 1.1rem;
    padding: 14px 50px;
    border-radius: 12px;
    box-shadow: 0 6px 16px rgba(111, 66, 193, 0.3);
    border: none;
    transition: background-color 0.3s ease, box-shadow 0.3s ease;
  }
  .btn-purple:hover,
  .btn-purple:focus {
    background-color: #5a35a0;
    box-shadow: 0 8px 25px rgba(90, 53, 160, 0.5);
    color: white;
  }
  .login-link {
    text-align: center;
    margin-top: 20px;
  }
  .login-link a {
    color: #6f42c1;
    font-weight: 600;
    text-decoration: none;
    transition: color 0.3s ease, text-decoration 0.3s ease;
  }
  .login-link a:hover,
  .login-link a:focus {
    color: #5a35a0;
    text-decoration: underline;
  }
  @media (max-width: 576px) {
    .register-section {
      padding: 30px 20px;
      margin: 20px 15px 60px;
    }
  }

  /* Error message styling */
  .error-msg {
    background-color: #f8d7da;
    color: #842029;
    border: 1px solid #f5c2c7;
    padding: 10px 15px;
    border-radius: 8px;
    margin-bottom: 20px;
    font-weight: 600;
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
        <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
        <li class="nav-item"><a class="nav-link" href="#">About Us</a></li>
        <li class="nav-item"><a class="nav-link" href="#">What We Offer</a></li>
      </ul>
    </div>
  </div>
</nav>

<!-- Registration Form -->
<section class="register-section shadow">
  <h4 class="mb-4">Create Your Account</h4>

  <!-- Show errors if any -->
  <?php if (!empty($errors)): ?>
    <div class="error-msg">
      <ul class="mb-0">
        <?php foreach ($errors as $err): ?>
          <li><?= htmlspecialchars($err) ?></li>
        <?php endforeach; ?>
      </ul>
    </div>
  <?php endif; ?>

  <form method="POST" action="register.php" novalidate>
    <h5>Personal Information</h5>
    <div class="row g-3 mb-4">
      <div class="col-md-6">
        <label for="fullname" class="form-label">Full Name</label>
        <input
          type="text"
          id="fullname"
          name="fullname"
          class="form-control"
          placeholder="Enter your full name"
          required
          autocomplete="name"
          value="<?= htmlspecialchars($fullname ?? '') ?>"
        />
      </div>
      <div class="col-md-6">
        <label for="gender" class="form-label">Gender</label>
        <select id="gender" name="gender" class="form-select" required>
          <option value="" disabled <?= empty($gender) ? 'selected' : '' ?>>Select Gender</option>
          <option <?= (isset($gender) && $gender === 'Male') ? 'selected' : '' ?>>Male</option>
          <option <?= (isset($gender) && $gender === 'Female') ? 'selected' : '' ?>>Female</option>
          <option <?= (isset($gender) && $gender === 'Other') ? 'selected' : '' ?>>Other</option>
          <option <?= (isset($gender) && $gender === 'Prefer not to say') ? 'selected' : '' ?>>Prefer not to say</option>
        </select>
      </div>
      <div class="col-md-6">
  <label for="dob" class="form-label">Date of Birth</label>
  <input
    type="date"
    id="dob"
    name="dob"
    class="form-control"
    required
    value="<?= htmlspecialchars($dob ?? '') ?>"
  />
  <script>
    // Set max date to today - 18 years
    const dobInput = document.getElementById('dob');
    const today = new Date();
    const eighteenYearsAgo = new Date(today.getFullYear() - 18, today.getMonth(), today.getDate());
    dobInput.max = eighteenYearsAgo.toISOString().split('T')[0];
  </script>
</div>

     <div class="col-md-6">
  <label for="phone" class="form-label">Phone Number</label>
  <input
    type="tel"
    id="phone"
    name="phone"
    class="form-control"
    placeholder="Enter phone number"
    required
    autocomplete="tel"
    pattern="09\d{9}"
    title="Phone number must start with 09 and be 11 digits"
    value="<?= htmlspecialchars($phone ?? '') ?>"
  />
</div>

      <div class="col-12">
        <label for="email" class="form-label">Email Address</label>
        <input
          type="email"
          id="email"
          name="email"
          class="form-control"
          placeholder="Enter email"
          required
          autocomplete="email"
          value="<?= htmlspecialchars($email ?? '') ?>"
        />
      </div>
    </div>

    <h5>Address Details</h5>
    <div class="row g-3 mb-4">
      <div class="col-12">
        <label for="street" class="form-label">Street</label>
        <input
          type="text"
          id="street"
          name="street"
          class="form-control"
          placeholder="Street"
          required
          autocomplete="street-address"
          value="<?= htmlspecialchars($street ?? '') ?>"
        />
      </div>
      <div class="col-md-4">
        <label for="city" class="form-label">City</label>
        <input
          type="text"
          id="city"
          name="city"
          class="form-control"
          placeholder="City"
          required
          autocomplete="address-level2"
          value="<?= htmlspecialchars($city ?? '') ?>"
        />
      </div>
      <div class="col-md-4">
        <label for="province" class="form-label">Province/State</label>
        <input
          type="text"
          id="province"
          name="province"
          class="form-control"
          placeholder="Province/State"
          required
          autocomplete="address-level1"
          value="<?= htmlspecialchars($province ?? '') ?>"
        />
      </div>
      <div class="col-md-4">
        <label for="zip" class="form-label">Zip Code</label>
        <input
          type="text"
          id="zip"
          name="zip"
          class="form-control"
          placeholder="Zip Code"
          required
          autocomplete="postal-code"
          value="<?= htmlspecialchars($zip ?? '') ?>"
        />
      </div>
      <div class="col-md-6">
        <label for="country" class="form-label">Country</label>
        <input
          type="text"
          id="country"
          name="country"
          class="form-control"
          placeholder="Country"
          required
          autocomplete="country-name"
          value="<?= htmlspecialchars($country ?? '') ?>"
        />
      </div>
    </div>

    <h5>Account Details</h5>
    <div class="row g-3 mb-4">
      <div class="col-md-6">
        <label for="username" class="form-label">Username</label>
        <input
          type="text"
          id="username"
          name="username"
          class="form-control"
          placeholder="Choose a username"
          required
          autocomplete="username"
          value="<?= htmlspecialchars($username ?? '') ?>"
        />
      </div>
      <div class="col-md-6">
  <label for="password" class="form-label">Password</label>
  <input
    type="password"
    id="password"
    name="password"
    class="form-control"
    placeholder="Enter password"
    required
    autocomplete="new-password"
    pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$"
    title="Password must be at least 8 characters and include an uppercase letter, a lowercase letter, a digit, and a special character."
  />
</div>

<div class="col-md-6">
  <label for="confirm" class="form-label">Confirm Password</label>
  <input
    type="password"
    id="confirm"
    name="confirm"
    class="form-control"
    placeholder="Confirm password"
    required
    autocomplete="new-password"
  />
</div>

    </div>

    <div class="text-center mb-3">
      <button type="submit" class="btn btn-purple px-5">Register</button>
    </div>

    <div class="login-link">
      <p>Already have an account? <a href="login.php">Log in here</a></p>
    </div>
  </form>
</section>

<!-- Footer -->
<footer class="text-center text-white mt-5">
  <div class="container">
    <p class="mb-0">© 2025 Armmiela Shylle Feliciano. All rights reserved.</p>
  </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
