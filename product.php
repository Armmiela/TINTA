<?php
// Connect to the database
$host = 'localhost';
$db   = 'tinta_store';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
    $stmt = $pdo->query("SELECT * FROM Products ORDER BY created_at DESC");
    $products = $stmt->fetchAll();
} catch (PDOException $e) {
    echo "Database connection failed: " . $e->getMessage();
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>TINTA Products</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <style>
    /* Paste your full CSS here */
    body {
      background: #f9fafb;
      background-image:
        radial-gradient(circle, rgba(0,0,0,0.03) 1px, transparent 1px),
        radial-gradient(circle, rgba(0,0,0,0.03) 1px, transparent 1px);
      background-position: 0 0, 25px 25px;
      background-size: 50px 50px;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      margin: 0;
      padding-top: 70px;
      color: #333;
      overflow-x: hidden;
    }

    .product-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
      gap: 25px;
      max-width: 1200px;
      margin: 0 auto;
      padding: 20px;
    }

    .product-card {
      background: #fff;
      border-radius: 16px;
      box-shadow: 0 6px 18px rgba(111, 66, 193, 0.08);
      padding: 20px;
      text-align: center;
      transition: all 0.3s ease;
      height: 100%;
    }
    .product-card:hover {
      transform: translateY(-6px);
      box-shadow: 0 10px 24px rgba(111, 66, 193, 0.2);
    }
    .product-card img {
      height: 220px;
      object-fit: cover;
      border-radius: 12px;
      margin-bottom: 15px;
      width: 100%;
    }
    .product-name {
      color: #6f42c1;
      font-size: 1.15rem;
      font-weight: bold;
    }
    .product-price {
      font-size: 1.05rem;
      margin-top: 5px;
    }
    h2.section-title {
      font-weight: 800;
      color: #6f42c1;
      margin-bottom: 30px;
      border-bottom: 3px solid #e4c2ff;
      text-align: center;
    }
  </style>
</head>
<body>

  <h2 class="section-title">Our TINTA Lipstick Collection</h2>

  <div class="product-grid">
    <?php if (count($products) === 0): ?>
      <p>No products available.</p>
    <?php else: ?>
      <?php foreach ($products as $product): ?>
        <div class="product-card">
          <?php if (!empty($product['image_url'])): ?>
            <img src="<?= htmlspecialchars($product['image_url']) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
          <?php else: ?>
            <div style="width:100%;height:220px;background:#eee;border-radius:12px;display:flex;align-items:center;justify-content:center;">No Image</div>
          <?php endif; ?>
          <div class="product-name"><?= htmlspecialchars($product['name']) ?></div>
          <div class="product-price">₱<?= number_format($product['price'], 2) ?></div>
          <div class="text-muted">Shade: <?= htmlspecialchars($product['shade']) ?></div>
          <div class="text-muted">Rating: <?= (int)$product['rating'] ?>%</div>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>
  </div>

</body>
</html>
