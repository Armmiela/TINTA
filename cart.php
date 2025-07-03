<?php
session_start();
$conn = new mysqli("localhost", "root", "", "tinta_store");
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user_id = $_SESSION['user_id'] ?? null;
$session_id = session_id();

$cartItems = [];
$stmt = $conn->prepare("
    SELECT p.product_id, p.name, p.price, p.image_url, c.quantity
    FROM cart c
    JOIN products p ON c.product_id = p.product_id
    WHERE (c.user_id = ? OR (c.user_id IS NULL AND c.session_id = ?))
");
$stmt->bind_param("is", $user_id, $session_id);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $cartItems[] = $row;
}

$countStmt = $conn->prepare("SELECT SUM(quantity) AS cart_count FROM cart WHERE (user_id = ? OR session_id = ?)");
$countStmt->bind_param("is", $user_id, $session_id);
$countStmt->execute();
$cartCount = $countStmt->get_result()->fetch_assoc()['cart_count'] ?? 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>TINTA Cart</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <style>
   body {
  font-family: 'Segoe UI', sans-serif;
  background-color: #f8f3fc;
  padding-top: 80px;
}

.navbar {
  background: linear-gradient(to right, #6a0dad, #b980f0);
}

.navbar .nav-link, .navbar-brand {
  color: white !important;
  font-weight: 600;
}

.navbar .badge {
  font-size: 0.7rem;
}

.cart-container {
  max-width: 900px;
  margin: auto;
  background: #ffffff;
  border-radius: 12px;
  box-shadow: 0 0 20px rgba(106, 13, 173, 0.15);
  padding: 30px;
}

.cart-item img {
  width: 100px;
  border-radius: 10px;
}

.qty-input {
  width: 60px;
  border-radius: 10px;
  text-align: center;
  border: 1px solid #ccc;
}

.btn-outline-secondary {
  border-color: #6a0dad;
  color: #6a0dad;
}

.btn-outline-secondary:hover {
  background-color: #6a0dad;
  color: white;
}

.btn-outline-danger {
  border-radius: 20px;
}

.total-box {
  text-align: right;
  font-size: 1.2rem;
  font-weight: bold;
  padding-top: 20px;
  color: #6a0dad;
}

.btn-checkout {
  background-color: #6a0dad;
  border: none;
  color: white;
  padding: 10px 20px;
  border-radius: 25px;
  font-weight: 600;
  transition: background-color 0.3s ease;
}

.btn-checkout:hover {
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
      <img src="logo.jpg" alt="Logo" width="40" height="40" class="rounded-circle me-2">
      TINTA
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon bg-light"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link" href="shop.php">What We Offer</a></li>
        <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
        <li class="nav-item"><a class="nav-link" href="welcome.php">Account</a></li>
        <li class="nav-item position-relative">
          <a class="nav-link active" href="cart.php">
            Cart
            <?php if ($cartCount > 0): ?>
              <span class="position-absolute top-0 start-100 translate-middle badge bg-danger">
                <?= $cartCount ?>
              </span>
            <?php endif; ?>
          </a>
        </li>
      </ul>
    </div>
  </div>
</nav>

<div class="cart-container mt-4">
  <h2 class="text-center mb-4">Your Shopping Cart</h2>

  <?php if (!empty($cartItems)): ?>
    <?php foreach ($cartItems as $item): ?>
      <div class="row align-items-center mb-4 cart-item" data-product-id="<?= $item['product_id'] ?>">
        <div class="col-md-2">
          <img src="<?= htmlspecialchars($item['image_url']) ?>" alt="<?= htmlspecialchars($item['name']) ?>">
        </div>
        <div class="col-md-4">
          <h5><?= htmlspecialchars($item['name']) ?></h5>
          <p class="text-muted">₱<?= number_format($item['price'], 2) ?></p>
        </div>
        <div class="col-md-3 d-flex align-items-center gap-2">
          <input type="number" class="form-control qty-input" min="1" value="<?= $item['quantity'] ?>">
          <button class="btn btn-outline-secondary btn-sm btn-update" data-id="<?= $item['product_id'] ?>"><i class="bi bi-arrow-repeat"></i></button>
        </div>
        <div class="col-md-3 text-end">
          <p class="fw-bold subtotal">₱<?= number_format($item['price'] * $item['quantity'], 2) ?></p>
          <button class="btn btn-outline-danger btn-sm btn-remove" data-id="<?= $item['product_id'] ?>"><i class="bi bi-trash"></i></button>
        </div>
        <input type="hidden" class="item-price" value="<?= $item['price'] ?>">
      </div>
    <?php endforeach; ?>

    <div class="total-box">
      <span>Total: <span id="total-amount">₱<?= number_format(array_sum(array_map(fn($i) => $i['price'] * $i['quantity'], $cartItems)), 2) ?></span></span>
    </div>

    <div class="text-end mt-4">
      <a href="checkout.php" class="btn btn-checkout">Proceed to Checkout</a>
    </div>
  <?php else: ?>
    <p class="text-center text-muted">Your cart is currently empty.</p>
  <?php endif; ?>
</div>

<footer>
  <div class="container">
    <p class="mb-0">© 2025 Armmiela Beauty. All rights reserved.</p>
  </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.querySelectorAll('.btn-update').forEach(button => {
  button.addEventListener('click', () => {
    const item = button.closest('.cart-item');
    const productId = button.dataset.id;
    const qty = item.querySelector('.qty-input').value;

    fetch('update_cart.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: `update_product_id=${productId}&update_qty=${qty}`
    }).then(() => updatePrices());
  });
});

document.querySelectorAll('.btn-remove').forEach(button => {
  button.addEventListener('click', () => {
    if (!confirm('Remove this item?')) return;

    const productId = button.dataset.id;
    fetch('update_cart.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: `remove_product_id=${productId}`
    }).then(() => location.reload());
  });
});

function updatePrices() {
  let total = 0;
  document.querySelectorAll('.cart-item').forEach(item => {
    const price = parseFloat(item.querySelector('.item-price').value);
    const qty = parseInt(item.querySelector('.qty-input').value);
    const subtotal = price * qty;
    item.querySelector('.subtotal').textContent = '₱' + subtotal.toFixed(2);
    total += subtotal;
  });
  document.getElementById('total-amount').textContent = '₱' + total.toFixed(2);
}
</script>
</body>
</html>
