<?php
require_once __DIR__ . '/includes/auth.php';
checkRememberMe();

$cart = $_SESSION['cart'] ?? [];
$total = 0;
foreach ($cart as $item) {
    $total += $item['price'] * $item['qty'];
}

$breadcrumb = ['Carrito'];
$pageTitle = 'Carrito';
require __DIR__ . '/includes/header.php';
?>
<h1>Tu carrito</h1>
<?php if (empty($cart)): ?>
    <div class="empty-state">
        <p class="empty-state-icon">🛒</p>
        <p>Tu carrito esta vacio.</p>
        <a href="/index.php" class="hero-cta">Ver productos</a>
    </div>
<?php else: ?>
<div class="cart-layout">
<table class="cart-table">
    <tr><th>Producto</th><th>Precio</th><th>Cantidad</th><th>Subtotal</th></tr>
    <?php foreach ($cart as $item): ?>
    <tr>
        <td><?php echo $item['name']; ?></td>
        <td><?php echo number_format($item['price'], 2); ?> &euro;</td>
        <td><?php echo $item['qty']; ?></td>
        <td><?php echo number_format($item['price'] * $item['qty'], 2); ?> &euro;</td>
    </tr>
    <?php endforeach; ?>
</table>
<aside class="cart-summary">
    <h3>Resumen del pedido</h3>
    <p class="cart-summary-row"><span>Subtotal</span><span><?php echo number_format($total, 2); ?> &euro;</span></p>
    <p class="cart-summary-row"><span>Envio</span><span><?php echo $total >= 49 ? 'Gratis' : 'Calculado en el siguiente paso'; ?></span></p>
    <p class="total">Total: <?php echo number_format($total, 2); ?> &euro;</p>
    <?php if (isLoggedIn()): ?>
    <form action="/checkout.php" method="POST">
        <button type="submit">Finalizar compra</button>
    </form>
    <?php else: ?>
    <p><a href="/login.php">Inicia sesion</a> para finalizar la compra.</p>
    <?php endif; ?>
    <p class="product-trust">🔒 Pago seguro · ↩️ Devolucion 30 dias</p>
</aside>
</div>
<?php endif; ?>
<?php require __DIR__ . '/includes/footer.php'; ?>
