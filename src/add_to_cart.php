<?php
require_once __DIR__ . '/includes/auth.php';
checkRememberMe();

$productId = intval($_POST['product_id'] ?? 0);
$price = $_POST['price'] ?? '0';
$name = $_POST['name'] ?? '';
$qty = max(1, intval($_POST['qty'] ?? 1));

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}
$_SESSION['cart'][] = [
    'id' => $productId,
    'name' => $name,
    'price' => (float) $price,
    'qty' => $qty,
];

header('Location: /cart.php');
exit;
