<?php
session_start();
$conn = new mysqli("localhost", "root", "", "tinta_store");

// Enable MySQLi exceptions for debugging
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user_id = $_SESSION['user_id'] ?? null;
$session_id = session_id();

// Handle Add to Cart
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["product_id"])) {
    $product_id = (int)$_POST["product_id"];
    $qty = max(1, (int)$_POST["qty"]);

    // Check if the product is already in cart for the session or user
    $check = $conn->prepare("SELECT product_id FROM cart WHERE (user_id = ? OR (user_id IS NULL AND session_id = ?)) AND product_id = ?");
    if (!$check) {
        die("Prepare failed: (" . $conn->errno . ") " . $conn->error);
    }
    $check->bind_param("isi", $user_id, $session_id, $product_id);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        // Update quantity if exists
        $update = $conn->prepare("UPDATE cart SET quantity = quantity + ? WHERE (user_id = ? OR (user_id IS NULL AND session_id = ?)) AND product_id = ?");
        $update->bind_param("iisi", $qty, $user_id, $session_id, $product_id);
        $update->execute();
    } else {
        // Insert new cart entry
        $insert = $conn->prepare("INSERT INTO cart (user_id, session_id, product_id, quantity) VALUES (?, ?, ?, ?)");
        $insert->bind_param("isii", $user_id, $session_id, $product_id, $qty);
        $insert->execute();
    }

    header("Location: shop.php?added=Product");
    exit;
}

// Sorting logic
$sort = $_GET['sort'] ?? 'newest';
$order = "ORDER BY date_added DESC";
if ($sort === 'cheapest') {
    $order = "ORDER BY price ASC";
} elseif ($sort === 'recommended') {
    $order = "ORDER BY recommended DESC";
}

// Fetch products
$products = $conn->query("SELECT * FROM products $order");
if (!$products) {
    die("SQL Error: " . $conn->error);
}

$countStmt = $conn->prepare("SELECT SUM(quantity) AS cart_count FROM cart WHERE (user_id = ? OR session_id = ?)");
$countStmt->bind_param("is", $user_id, $session_id);
$countStmt->execute();
$countResult = $countStmt->get_result();
$cartCount = $countResult->fetch_assoc()['cart_count'] ?? 0;
?>

<?php if (isset($_GET['added'])): ?>
  <div id="success-alert" class="alert alert-success alert-dismissible fade show position-fixed top-0 start-50 translate-middle-x mt-4 shadow" role="alert" style="z-index: 9999; width: auto; max-width: 400px;">
    ✔ <?= htmlspecialchars($_GET['added']) ?> added to your cart!
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
<?php endif; ?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>What We Offer - Armmiela Beauty</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
 <style>
  body {
    padding-top: 70px;
    background: linear-gradient(135deg, #f9f1ff, #ffffff);
    font-family: 'Segoe UI', sans-serif;
    color: #333;
  }

  .navbar {
    background-color: #6f42c1;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
  }

  .navbar a {
    color: white !important;
    font-weight: 600;
    transition: color 0.3s;
  }

  .navbar a:hover {
    color: #d6b8ff !important;
  }

  h1, h2, h3 {
    color: #6f42c1;
    font-weight: 700;
  }

  .sort-bar {
    margin-bottom: 30px;
    padding-top: 20px;
  }

  .product-card {
    border: none;
    border-radius: 16px;
    padding: 20px;
    text-align: center;
    background: #fff;
    box-shadow: 0 8px 20px rgba(111, 66, 193, 0.12);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    position: relative;
  }

  .product-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 12px 28px rgba(111, 66, 193, 0.25);
  }

  .product-card img {
    width: 100%;
    height: 200px;
    object-fit: cover;
    border-radius: 12px;
    margin-bottom: 10px;
  }

  .product-name {
    font-weight: 600;
    color: #6f42c1;
    font-size: 1.15rem;
    margin-bottom: 6px;
  }

  .product-price {
    color: #444;
    font-size: 1rem;
    margin-bottom: 15px;
  }

  .btn-primary {
    background-color: #6f42c1;
    border: none;
    font-weight: 600;
    padding: 10px 18px;
    border-radius: 25px;
    transition: background-color 0.3s ease;
  }

  .btn-primary:hover {
    background-color: #532a8c;
  }

  .badge-recommended {
    position: absolute;
    top: 15px;
    right: 15px;
    background-color: #d6b8ff;
    color: #6f42c1;
    font-weight: 600;
    padding: 5px 10px;
    border-radius: 8px;
    font-size: 0.8rem;
  }

  footer {
    text-align: center;
    padding: 20px;
    background-color: #6f42c1;
    color: white;
    margin-top: 60px;
    font-size: 0.95rem;
  }

  .form-section {
    background-color: #fff;
    padding: 25px;
    border-radius: 16px;
    box-shadow: 0 8px 24px rgba(111, 66, 193, 0.1);
    margin-bottom: 30px;
  }

  .form-label {
    font-weight: 600;
    color: #6f42c1;
  }

  .form-control:focus {
    border-color: #b88bff;
    box-shadow: 0 0 0 0.2rem rgba(185, 143, 255, 0.25);
  }

  .btn-checkout {
    background-color: #9b59b6;
    color: #fff;
    font-weight: 600;
    border-radius: 25px;
    padding: 12px 25px;
    border: none;
    transition: background-color 0.3s ease;
  }

  .btn-checkout:hover {
    background-color: #8e44ad;
  }

  @media (max-width: 576px) {
    .product-card img {
      height: 130px;
    }

    .btn-checkout {
      width: 100%;
      padding: 10px;
    }
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
       <li class="nav-item position-relative">
  <a class="nav-link active d-inline-block" href="cart.php" style="position: relative;">
    Cart
    <?php if (!empty($cartCount) && $cartCount > 0): ?>
      <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
        <?= $cartCount ?>
      </span>
    <?php endif; ?>
  </a>
</li>

      </ul>
    </div>
  </div>
</nav>


  <div class="sort-bar d-flex justify-content-end">
    <form method="get" class="d-flex align-items-center">
      <label for="sort" class="me-2 fw-semibold text-secondary">Sort by:</label>
      <select name="sort" id="sort" onchange="this.form.submit()" class="form-select w-auto">
        <option value="newest" <?= $sort === 'newest' ? 'selected' : '' ?>>Newest</option>
        <option value="cheapest" <?= $sort === 'cheapest' ? 'selected' : '' ?>>Cheapest</option>
        <option value="recommended" <?= $sort === 'recommended' ? 'selected' : '' ?>>Recommended</option>
      </select>
    </form>
  </div>

  <div class="row row-cols-1 row-cols-sm-2 row-cols-md-4 g-4 mb-5">
  <?php while ($row = $products->fetch_assoc()): ?>
    <div class="col">
      <div class="product-card h-100 position-relative">
        <?php if (isset($row['recommended']) && $row['recommended']): ?>
          <div class="badge-recommended">Recommended</div>
        <?php endif; ?>

        <img src="<?= htmlspecialchars($row['image_url']) ?>" alt="<?= htmlspecialchars($row['name']) ?>">
        <div class="product-name"><?= htmlspecialchars($row['name']) ?></div>
        <div class="product-price">₱<?= number_format($row['price'], 2) ?></div>

        <form method="POST" class="d-grid gap-2">
          <input type="hidden" name="product_id" value="<?= (int)$row['product_id'] ?>">
          <input type="hidden" name="qty" value="1">
          <button type="submit" class="btn btn-primary">Add to Cart</button>
        </form>
      </div>
    </div>
  <?php endwhile; ?>
</div>




<footer>
  <p>© 2025 Armmiela Beauty. All rights reserved.</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
  // Auto-dismiss alert after 3 seconds
  setTimeout(() => {
    const alert = document.getElementById('success-alert');
    if (alert) {
      alert.classList.remove('show');
      alert.classList.add('fade');
      setTimeout(() => alert.remove(), 500);
    }
  }, 3000);
</script>

</body>
</html>