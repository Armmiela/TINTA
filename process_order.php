<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

error_reporting(E_ALL);
ini_set('display_errors', 1);


require_once 'config.php';

$user_id = $_SESSION['user']['user_id'];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Sanitize input
    $full_name = trim($_POST['full_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $payment_method = trim($_POST['payment_method'] ?? '');

    // Validate required fields
    if (empty($full_name) || empty($email) || empty($phone) || empty($address) || empty($payment_method)) {
        echo "Please fill out all required fields.";
        exit;
    }

    // ✅ Fetch cart items
    $stmt = $pdo->prepare("
        SELECT p.product_id, p.name, p.price, p.image_url, c.quantity
        FROM Cart c
        JOIN Products p ON c.product_id = p.product_id
        WHERE c.user_id = ?
    ");
    $stmt->execute([$user_id]);
    $cartItems = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($cartItems)) {
        echo "Your cart is empty. Please add items before checking out.";
        exit;
    }

    // ✅ Calculate total
    $order_total = 0;
    foreach ($cartItems as $item) {
        $order_total += $item['price'] * $item['quantity'];
    }

    try {
        // Begin transaction
        $pdo->beginTransaction();

        // ✅ Insert into Orders table
        $stmtOrder = $pdo->prepare("
            INSERT INTO Orders (user_id, full_name, email, phone, address, total_amount)
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        $stmtOrder->execute([$user_id, $full_name, $email, $phone, $address, $order_total]);
        $order_id = $pdo->lastInsertId();

        // ✅ Insert into OrderDetails
        $stmtDetails = $pdo->prepare("
            INSERT INTO OrderDetails (order_id, product_id, quantity, price)
            VALUES (?, ?, ?, ?)
        ");
        foreach ($cartItems as $item) {
            $stmtDetails->execute([
                $order_id,
                $item['product_id'],
                $item['quantity'],
                $item['price']
            ]);
        }

        // ✅ Insert payment record
        $stmtPayment = $pdo->prepare("
            INSERT INTO Payments (order_id, payment_method, payment_status)
            VALUES (?, ?, 'Pending')
        ");
        $stmtPayment->execute([$order_id, $payment_method]);

        // ✅ Clear cart
        $stmtClear = $pdo->prepare("DELETE FROM Cart WHERE user_id = ?");
        $stmtClear->execute([$user_id]);

        // Commit transaction
        $pdo->commit();

        // ✅ Redirect with loading animation (optional delay)
        echo "<!DOCTYPE html>
        <html>
        <head>
            <meta charset='UTF-8'>
            <meta http-equiv='refresh' content='2;url=order_success.php'>
            <title>Processing...</title>
            <style>
                body {
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    height: 100vh;
                    background: #f8f0ff;
                    font-family: Arial, sans-serif;
                }
                .loader {
                    border: 6px solid #e0cfff;
                    border-top: 6px solid #7a4aa0;
                    border-radius: 50%;
                    width: 50px;
                    height: 50px;
                    animation: spin 1s linear infinite;
                }
                @keyframes spin {
                    0% { transform: rotate(0deg); }
                    100% { transform: rotate(360deg); }
                }
                .text {
                    margin-top: 20px;
                    font-size: 1.2rem;
                    color: #7a4aa0;
                    text-align: center;
                }
            </style>
        </head>
        <body>
            <div>
                <div class='loader'></div>
                <div class='text'>Processing your order...</div>
            </div>
        </body>
        </html>";
        exit;

    } catch (Exception $e) {
        $pdo->rollBack();
        echo "Error processing your order. Please try again later.";
    }

} else {
    echo "Invalid request method.";
}
