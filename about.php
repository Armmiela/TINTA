<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>About Us - TINTA</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <style>
    body {
      padding-top: 70px;
      background: linear-gradient(135deg, #f9f1ff, #ffffff);
      font-family: 'Segoe UI', sans-serif;
      color: #4a4a4a;
    }

    .navbar {
      background-color: #6f42c1;
    }

    .navbar a {
      color: white !important;
    }

    .navbar a:hover {
      color: #d6b8ff !important;
    }

    h1, h2 {
      color: #6f42c1;
      font-weight: 700;
    }

    .content-section {
      max-width: 900px;
      margin: 0 auto;
      padding: 40px 20px;
      background: white;
      border-radius: 16px;
      box-shadow: 0 8px 20px rgba(111, 66, 193, 0.15);
      margin-bottom: 40px;
    }

    p {
      font-size: 1.1rem;
      line-height: 1.6;
      margin-bottom: 20px;
    }

    label {
      font-weight: 600;
      color: #6f42c1;
    }

    .form-control:focus {
      border-color: #6f42c1;
      box-shadow: 0 0 8px rgba(111, 66, 193, 0.3);
    }

    .btn-primary {
      background-color: #6f42c1;
      border: none;
      font-weight: 600;
    }

    .btn-primary:hover {
      background-color: #532a8c;
    }

    footer {
      text-align: center;
      padding: 20px;
      background-color: #6f42c1;
      color: white;
      margin-top: 60px;
    }

    @media (max-width: 576px) {
      .content-section {
        padding: 30px 15px;
      }
    }

    .founders-section {
  background: linear-gradient(to right, #fdf6ff, #fbeaff);
}

.section-title {
  color: #6f42c1;
  font-weight: 700;
}

.founder-card {
  background-color: white;
  border-radius: 16px;
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.founder-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 10px 30px rgba(111, 66, 193, 0.15);
}

.founder-img {
  width: 150px;
  height: 150px;
  object-fit: cover;
  border-radius: 50%;
  border: 4px solid #d6b8ff;
}

.text-purple {
  color: #6f42c1;
}

  </style>
</head>
<body>

<nav class="navbar navbar-expand-lg fixed-top shadow-sm">
  <div class="container">
    <a class="navbar-brand" href="index.php">
      <img src="logo.jpg" alt="TINTA Logo" width="40" height="40" class="d-inline-block align-text-top rounded-circle me-2">
      TINTA
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon bg-light"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link active" href="shop.php">What We Offer</a></li>
        <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
        <li class="nav-item"><a class="nav-link" href="welcome.php">Account</a></li>
        <li class="nav-item"><a class="nav-link" href="cart.php">Cart</a></li>
      </ul>
    </div>
  </div>
</nav>

<div class="container">
  <section class="content-section mt-5">
    <h1>About TINTA</h1>
    <p>
      Welcome to <strong>TINTA (A TINT THAT TALKS)</strong>, your trusted destination for high-quality, stylish, and affordable lip products. Founded by Hannah and Armmiela, we believe makeup is a form of self-expression and confidence.
    </p>
    <p>
      Our mission is to empower you to express your unique beauty every day with lip products designed to complement every skin tone, personality, and occasion.
    </p>

    <h2>Company Information</h2>
    <p>
      TINTA is committed to providing quality products crafted with care. We operate with integrity, transparency, and passion to bring you the best beauty experience possible.
    </p>

    <h2>Contact Us</h2>
    <p>If you have any questions or need assistance, please fill out the form below or reach out to us directly:</p>
    <ul>
      <li><strong>Email:</strong> support@tinta.com</li>
      <li><strong>Phone:</strong> +63 912 345 6789</li>
    </ul>

    <section class="founders-section py-5">
  <div class="container text-center">
    <h2 class="section-title mb-4">Meet the Founders</h2>
    <p class="mb-5 text-muted">The passionate minds behind <strong>TINTA by Armmiela and Hannah Beauty</strong>.</p>
    <div class="row justify-content-center g-4">
      <div class="col-md-5">
        <div class="founder-card p-4 shadow-sm rounded">
          <img src="https://scontent.fmnl31-1.fna.fbcdn.net/v/t39.30808-6/495566800_3981307295467143_4675660847829015977_n.jpg?_nc_cat=100&ccb=1-7&_nc_sid=6ee11a&_nc_eui2=AeHZKglI7HAAw0FtBtoMqdZEU6kVHdiVVhtTqRUd2JVWG7AZUhuLx9uLkknW869knbWTJQLyv_lDcC-dcMPYA8vw&_nc_ohc=MDUIrVyv_9UQ7kNvwFal_O8&_nc_oc=AdlZYWSGhtUmul9sqxsN0DXBtv-ngKqKTAGlBCc-0ZaJ7Y40Coh6WPFJMsnaxHYY2xE&_nc_zt=23&_nc_ht=scontent.fmnl31-1.fna&_nc_gid=ylM8dklbD-9y2NhO-W4LHg&oh=00_AfMRdOw7KZYPiNPLLqfb8J1Jp_PYK2jpVDXKf5I0CpFYDQ&oe=686C10DD" alt="Armmiela Shylle" class="founder-img mb-3">
          <h5 class="fw-bold text-purple">Armmiela Shylle</h5>
          <p class="text-muted small">Creative Director & Co-Founder</p>
        </div>
      </div>
      <div class="col-md-5">
        <div class="founder-card p-4 shadow-sm rounded">
          <img src="https://ue.instructure.com/files/9539912/download?download_frd=1&verifier=rKbFbwo18V9nsO4FjY5cfMZMBYjhfWszuRj3V419" alt="Hannah Brina" class="founder-img mb-3">
          <h5 class="fw-bold text-purple">Hannah Brina</h5>
          <p class="text-muted small">Operations Lead & Co-Founder</p>
        </div>
      </div>
    </div>
  </div>
</section>


    <form method="post" action="about_us.php" class="mt-4" style="max-width:600px;">
      <div class="mb-3">
        <label for="name" class="form-label">Your Name</label>
        <input type="text" class="form-control" id="name" name="name" required maxlength="100" />
      </div>
      <div class="mb-3">
        <label for="email" class="form-label">Your Email</label>
        <input type="email" class="form-control" id="email" name="email" required maxlength="100" />
      </div>
      <div class="mb-3">
        <label for="message" class="form-label">Your Message</label>
        <textarea class="form-control" id="message" name="message" rows="5" required maxlength="1000"></textarea>
      </div>
      <button type="submit" class="btn btn-primary">Send Message</button>
    </form>

    <?php
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
      $name = trim($_POST['name'] ?? '');
      $email = trim($_POST['email'] ?? '');
      $message = trim($_POST['message'] ?? '');

      if ($name && filter_var($email, FILTER_VALIDATE_EMAIL) && $message) {
        echo '<div class="alert alert-success mt-4" role="alert">';
        echo "Thank you, " . htmlspecialchars($name) . "! Your message has been received.";
        echo '</div>';
      } else {
        echo '<div class="alert alert-danger mt-4" role="alert">';
        echo "Please fill in all fields correctly.";
        echo '</div>';
      }
    }
    ?>
  </section>
</div>

<footer>
  <p>© 2025 TINTA by Hannah and Armmiela. All rights reserved.</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
