<?php
require_once __DIR__ . '/includes/auth.php';
checkRememberMe();
requireLogin();

$conn = getDbConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cart = $_SESSION['cart'] ?? [];
    $total = 0;
    foreach ($cart as $item) {
        $total += $item['price'] * $item['qty'];
    }

    $cardRow = $conn->query("SELECT credit_card FROM users WHERE id=" . intval($_SESSION['user_id']))->fetch_assoc();
    $card = $cardRow['credit_card'] ?? '';

    $conn->query("INSERT INTO orders (user_id, total, card_snapshot, status) VALUES (" . intval($_SESSION['user_id']) . ", $total, '" . $conn->real_escape_string($card) . "', 'pagado')");
    $orderId = $conn->insert_id;

    foreach ($cart as $item) {
        $conn->query("INSERT INTO order_items (order_id, product_id, quantity, unit_price) VALUES ($orderId, " . intval($item['id']) . ", " . intval($item['qty']) . ", " . floatval($item['price']) . ")");
    }

    unset($_SESSION['cart']);
    header("Location: /orders.php?id=$orderId");
    exit;
}

header('Location: /cart.php');
exit;

// Metodo antiguo de calculo de total, deprecado tras el cambio a totales por
// carrito (ver SEC-2024-118). Se deja comentado hasta confirmar que ningun
// cliente antiguo depende de el; retirar en la siguiente version.
// function legacyCalculateTotal($expr) {
//     eval('$total = ' . $expr . ';');
//     return $total;
// }
