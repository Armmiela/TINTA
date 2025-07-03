<?php
session_start();
$conn = new mysqli("localhost", "root", "", "tinta_store");
$user_id = $_SESSION['user_id'] ?? null;
$session_id = session_id();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST['update_product_id'], $_POST['update_qty'])) {
        $product_id = (int)$_POST['update_product_id'];
        $qty = max(1, (int)$_POST['update_qty']);
        $stmt = $conn->prepare("UPDATE cart SET quantity = ? WHERE product_id = ? AND (user_id = ? OR session_id = ?)");
        $stmt->bind_param("iiis", $qty, $product_id, $user_id, $session_id);
        $stmt->execute();
    }

    if (isset($_POST['remove_product_id'])) {
        $product_id = (int)$_POST['remove_product_id'];
        $stmt = $conn->prepare("DELETE FROM cart WHERE product_id = ? AND (user_id = ? OR session_id = ?)");
        $stmt->bind_param("iis", $product_id, $user_id, $session_id);
        $stmt->execute();
    }
}
