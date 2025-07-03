<?php
session_start();
require_once 'config.php';

// Ensure user is logged in
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user']['user_id'];

// ✅ Use POST method now
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'])) {
    $productId = (int) $_POST['product_id'];
    $quantity = isset($_POST['qty']) ? (int) $_POST['qty'] : 1;

    // Check if product exists
    $stmtProduct = $pdo->prepare("SELECT * FROM Products WHERE product_id = ?");
    $stmtProduct->execute([$productId]);
    $product = $stmtProduct->fetch(PDO::FETCH_ASSOC);

    if ($product) {
        // Check if product already in cart
        $stmtCheck = $pdo->prepare("SELECT * FROM Cart WHERE user_id = ? AND product_id = ?");
        $stmtCheck->execute([$user_id, $productId]);

        if ($stmtCheck->rowCount() > 0) {
            // Update quantity
            $stmtUpdate = $pdo->prepare("UPDATE Cart SET quantity = quantity + ? WHERE user_id = ? AND product_id = ?");
            $stmtUpdate->execute([$quantity, $user_id, $productId]);
        } else {
            // Insert new item
            $stmtInsert = $pdo->prepare("INSERT INTO Cart (user_id, product_id, quantity) VALUES (?, ?, ?)");
            $stmtInsert->execute([$user_id, $productId, $quantity]);
        }
    }
}

header('Location: shop.php'); // or any other page
exit();
