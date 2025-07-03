<?php
session_start();
$user = $_SESSION['user'] ?? null;



$featuredProducts = [
  ["name" => "Matte Lipstick - Bold Berry", "price" => 399, "sale_price" => 299, "image" => "images_url/L8.jpg"],
  ["name" => "Glossy Shine - Peach Pop", "price" => 299, "image" => "images_url/L3.jpg", "new" => true],
];

$bestSellers = [
  ["name" => "Velvet Lipstick - Crimson Red", "price" => 349, "image" => "images_url/L14.jpg"],
  ["name" => "Sheer Tint - Coral Kiss", "price" => 279, "image" => "images_url/L9.jpg"],
  ["name" => "Matte Lipstick - Nude Blush", "price" => 399, "sale_price" => 349, "image" => "images_url/L12.jpg"],
];

$categories = [
  ["name" => "Lipsticks", "image" => "images_url/featured.jpg", "link" => "shop.php?category=lipsticks"],
  ["name" => "Glosses", "image" => "images_url/L4.jpg", "link" => "shop.php?category=glosses"],
  ["name" => "Lip Care", "image" => "images_url/L5.jpg", "link" => "shop.php?category=lipcare"],
];

$reviews = [
  ["name" => "Jessica M.", "text" => "The bold berry lipstick is my go-to! Lasts all day."],
  ["name" => "Mark D.", "text" => "Glossy Shine gave me the perfect peachy look, love it!"],
  ["name" => "Anna K.", "text" => "Great quality and lovely shades. Highly recommend."],
];

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Armmiela Beauty - Lip Makeup</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
<style>
body {
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  margin: 0;
  padding-top: 70px;
  color: #333;
  overflow-x: hidden;

  background-color: #f9f1ff;
  background-image:
    linear-gradient(rgba(111, 66, 193, 0.08), rgba(255, 255, 255, 0.85)),
    radial-gradient(circle at 1px 1px, rgba(111, 66, 193, 0.05) 1px, transparent 0),
    url('https://www.roseinc.com/cdn/shop/files/about-sustainability-header_1350x.jpg?v=1739038200'); /* Replace with lipstick-themed image */
  background-size:
    cover,
    40px 40px,
    cover;
  background-repeat:
    no-repeat,
    repeat,
    no-repeat;
  background-position:
    center,
    0 0,
    center;
  background-attachment: fixed;
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
  color: #f8e3ff !important;
  font-weight: 600;
  border-bottom: 2px solid #f8e3ff;
}

.hero {
  padding: 100px 30px 70px;
  background: rgba(255, 255, 255, 0.2); /* Light translucent white */
  text-align: center;
  max-width: 1000px;
  margin: 0 auto 70px;
  border-radius: 20px;
  box-shadow: 0 15px 35px rgba(111, 66, 193, 0.25);
  backdrop-filter: blur(12px); /* Glass effect */
  -webkit-backdrop-filter: blur(12px); /* Safari support */
  border: 1px solid rgba(255, 255, 255, 0.3); /* Optional border for glass effect */
}
.hero h1 {
  font-size: 3.25rem;
  color: #6f42c1;
  font-weight: 800;
  margin-bottom: 15px;
}
.hero p.lead {
  font-size: 1.25rem;
  color: #3f2a6d;
  margin-bottom: 30px;
}
.hero .btn {
  margin-top: 10px;
}


.btn-primary {
  background-color: #6f42c1;
  color: white;
  font-weight: bold;
  border: none;
  padding: 14px 42px;
  font-size: 1.15rem;
  border-radius: 50px;
  box-shadow: 0 5px 20px rgba(111, 66, 193, 0.3);
}
.btn-primary:hover {
  background-color: #4b2b91;
  box-shadow: 0 7px 30px rgba(75, 43, 145, 0.4);
}
.btn-secondary {
  background-color: #f3dcff;
  color: #6f42c1;
  font-weight: 600;
  padding: 12px 36px;
  font-size: 1.1rem;
  border-radius: 50px;
  border: none;
}
.btn-secondary:hover {
  background-color: #e4c2ff;
}

section h2 {
  font-weight: 800;
  color: #6f42c1;
  margin-bottom: 30px;
  border-bottom: 3px solid #e4c2ff;
  text-align: center;
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
.badge {
  font-size: 0.75rem;
  padding: 5px 12px;
  border-radius: 20px;
}

/* Category Cards */
.category-card {
  background: #fff;
  border-radius: 20px;
  overflow: hidden;
  box-shadow: 0 5px 18px rgba(111, 66, 193, 0.1);
  cursor: pointer;
  transition: transform 0.3s ease;
  height: 280px;
  display: flex;
  flex-direction: column;
  justify-content: flex-end;
  color: white;
  text-shadow: 0 2px 6px rgba(0,0,0,0.6);
  position: relative;
}
.category-card:hover {
  transform: scale(1.05);
}
.category-card img {
  position: absolute;
  top: 0; left: 0;
  width: 100%;
  height: 100%;
  object-fit: cover;
  z-index: 0;
}
.category-card .category-name {
  position: relative;
  z-index: 1;
  background: rgba(111, 66, 193, 0.75);
  padding: 15px 20px;
  border-radius: 0 0 20px 20px;
  font-size: 1.5rem;
  font-weight: 700;
  text-align: center;
}

/* Reviews */
.review-slide {
  background: #fff;
  border-radius: 16px;
  padding: 25px;
  box-shadow: 0 6px 18px rgba(111, 66, 193, 0.1);
  text-align: center;
  font-style: italic;
  color: #4b2b91;
  min-height: 140px;
  display: flex;
  flex-direction: column;
  justify-content: center;
}
.review-slide .review-text {
  font-size: 1.1rem;
  margin-bottom: 15px;
}
.review-slide .reviewer {
  font-weight: 600;
  font-size: 1rem;
  color: #6f42c1;
}

/* Newsletter */
.newsletter {
  background: #6f42c1;
  color: white;
  border-radius: 20px;
  padding: 40px 20px;
  text-align: center;
  max-width: 700px;
  margin: 0 auto 60px;
  box-shadow: 0 10px 30px rgba(111, 66, 193, 0.3);
}
.newsletter h2 {
  font-weight: 800;
  font-size: 2rem;
  margin-bottom: 20px;
}
.newsletter input[type=email] {
  max-width: 350px;
  border-radius: 50px;
  border: none;
  padding: 12px 20px;
  font-size: 1rem;
  margin-right: 12px;
}
.newsletter button {
  border-radius: 50px;
  padding: 12px 30px;
  font-weight: 700;
  font-size: 1rem;
  border: none;
  background-color: #f3dcff;
  color: #6f42c1;
  cursor: pointer;
  transition: background-color 0.3s ease;
}
.newsletter button:hover {
  background-color: #e4c2ff;
}

/* Info Banner */
.info-banner {
  background: #f3dcff;
  color: #6f42c1;
  text-align: center;
  font-weight: 700;
  padding: 15px 10px;
  margin-bottom: 60px;
  border-radius: 15px;
  max-width: 900px;
  margin-left: auto;
  margin-right: auto;
  box-shadow: 0 5px 15px rgba(111, 66, 193, 0.1);
}

/* Footer */
footer {
  padding: 20px 0;
  text-align: center;
  color: white;
  font-size: 0.95rem;
}

@media (max-width: 768px) {
  .hero h1 {
    font-size: 2.5rem;
  }
  .btn {
    width: 100%;
    margin-bottom: 10px;
  }
  .category-card {
    height: 220px;
  }
  .newsletter input[type=email] {
    width: 100%;
    margin: 0 0 15px 0;
  }
  .newsletter button {
    width: 100%;
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
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" 
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon bg-light"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link" href="about.php">About</a></li>
        <li class="nav-item"><a class="nav-link" href="shop.php">What We Offer</a></li>
        <?php if ($user): ?>
          <li class="nav-item"><a class="nav-link" href="welcome.php">Account</a></li>
          <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
        <?php else: ?>
          <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
          <li class="nav-item"><a class="nav-link" href="register.php">Register</a></li>
        <?php endif; ?>
        <li class="nav-item"><a class="nav-link active" href="index.php">Home</a></li>
      </ul>
    </div>
  </div>
</nav>

<section class="hero" role="banner" aria-label="Welcome hero">
  <h1>Glow With TINTA a tint that talks</h1>
  <p class="lead">Discover luxurious lip makeup products crafted for bold beauty and lasting confidence.</p>
  <a href="shop.php" class="btn btn-primary">Shop Now</a>
  <?php if (!$user): ?>
    <a href="register.php" class="btn btn-secondary">Join Us</a>
  <?php endif; ?>
</section>

<!-- Info Banner -->
<div class="info-banner" role="region" aria-label="Free shipping and returns info">
  FREE SHIPPING on orders ₱1,500+ | EASY RETURNS within 30 days | SECURE PAYMENT
</div>

<!-- Featured Categories -->
<section aria-labelledby="categories-heading" class="mb-5">
  <div class="container">
    <h2 id="categories-heading">Shop By Category</h2>
    <div class="row g-4 justify-content-center">
      <?php foreach ($categories as $cat): ?>
        <div class="col-md-4 col-sm-6">
          <a href="<?= htmlspecialchars($cat['link']) ?>" class="category-card text-decoration-none" tabindex="0">
            <img src="<?= htmlspecialchars($cat['image']) ?>" alt="<?= htmlspecialchars($cat['name']) ?> category image" />
            <div class="category-name"><?= htmlspecialchars($cat['name']) ?></div>
          </a>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<section aria-labelledby="promo-heading" class="mb-5">
  <div class="container">
    <h2 id="promo-heading" class="text-center mb-4 text-purple fw-bold">✨ Promotions & New Arrivals</h2>
    <div class="row g-4 justify-content-center">
      <?php foreach ($featuredProducts as $product): ?>
        <div class="col-md-4 col-sm-6">
          <div class="product-card position-relative border border-warning">
            <?php if (!empty($product['sale_price'])): ?>
              <span class="badge bg-danger position-absolute top-0 start-0 m-2">Sale</span>
            <?php elseif (!empty($product['new'])): ?>
              <span class="badge bg-success position-absolute top-0 start-0 m-2">New</span>
            <?php endif; ?>
            <img src="<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>" class="img-fluid rounded mb-3" />
            <div class="product-name"><?= htmlspecialchars($product['name']) ?></div>
            <div class="product-price">
              <?php if (!empty($product['sale_price'])): ?>
                <span class="text-muted text-decoration-line-through">₱<?= number_format($product['price'], 2) ?></span>
                <span class="text-danger fw-bold ms-2">₱<?= number_format($product['sale_price'], 2) ?></span>
              <?php else: ?>
                ₱<?= number_format($product['price'], 2) ?>
              <?php endif; ?>
            </div>
            <a href="shop.php" class="btn btn-sm btn-primary mt-2">Shop Now</a>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- Best Sellers Carousel -->
<section aria-label="Best Sellers" class="mb-5">
  <div class="container">
    <h2>Best Sellers</h2>
    <div id="bestSellersCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="4000">
      <div class="carousel-inner">
        <?php foreach ($bestSellers as $index => $item): ?>
          <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
            <div class="product-card mx-auto" style="max-width: 350px;">
              <img src="<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['name']) ?>" />
              <div class="product-name"><?= htmlspecialchars($item['name']) ?></div>
              <div class="product-price">
                <?php if (!empty($item['sale_price'])): ?>
                  <span class="text-muted text-decoration-line-through">₱<?= number_format($item['price'], 2) ?></span>
                  <span class="text-danger fw-bold ms-2">₱<?= number_format($item['sale_price'], 2) ?></span>
                <?php else: ?>
                  ₱<?= number_format($item['price'], 2) ?>
                <?php endif; ?>
              </div>
              <a href="shop.php" class="btn btn-sm btn-primary mt-2">Shop Now</a>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
      <button class="carousel-control-prev" type="button" data-bs-target="#bestSellersCarousel" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Previous</span>
      </button>
      <button class="carousel-control-next" type="button" data-bs-target="#bestSellersCarousel" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Next</span>
      </button>
    </div>
  </div>
</section>

<!-- Customer Reviews -->
<section aria-label="Customer reviews" class="mb-5">
  <div class="container">
    <h2>What Our Customers Say</h2>
    <div id="reviewsCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="5000">
      <div class="carousel-inner">
        <?php foreach ($reviews as $index => $review): ?>
          <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
            <div class="review-slide mx-auto" style="max-width: 600px;">
              <p class="review-text">&ldquo;<?= htmlspecialchars($review['text']) ?>&rdquo;</p>
              <p class="reviewer">&mdash; <?= htmlspecialchars($review['name']) ?></p>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
      <button class="carousel-control-prev" type="button" data-bs-target="#reviewsCarousel" data-bs-slide="prev" aria-label="Previous review">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
      </button>
      <button class="carousel-control-next" type="button" data-bs-target="#reviewsCarousel" data-bs-slide="next" aria-label="Next review">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
      </button>
    </div>
  </div>
</section>

<!-- Newsletter Signup -->
<section aria-label="Newsletter signup" class="newsletter mb-5">
  <h2>Stay Updated with TINTA</h2>
  <form action="newsletter_signup.php" method="POST" class="d-flex justify-content-center flex-wrap" novalidate>
    <label for="newsletter-email" class="visually-hidden">Email address</label>
    <input type="email" name="email" id="newsletter-email" placeholder="Enter your email address" required aria-required="true" />
    <button type="submit">Subscribe</button>
  </form>
</section>

<footer>
  &copy; <?= date("Y") ?> TINTA by Armmiela Beauty. All rights reserved.
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
