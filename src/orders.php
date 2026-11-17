<?php
require_once __DIR__ . '/includes/auth.php';
checkRememberMe();
requireLogin();

$conn = getDbConnection();
$id = intval($_GET['id'] ?? 0);
$order = $conn->query("SELECT * FROM orders WHERE id=$id")->fetch_assoc();
$items = $conn->query("SELECT oi.*, p.name FROM order_items oi JOIN products p ON oi.product_id = p.id WHERE order_id=$id");

$breadcrumb = ['Mis pedidos' => '/orders_list.php', 'Pedido #' . $id];
$pageTitle = 'Pedido #' . $id;
require __DIR__ . '/includes/header.php';
?>
<h1>Pedido #<?php echo $id; ?></h1>
<?php if (!$order): ?>
    <p class="alert alert-error">Pedido no encontrado.</p>
<?php else: ?>
<div class="profile-box order-summary-box">
    <p><strong>Cliente (user_id):</strong> <?php echo $order['user_id']; ?></p>
    <p><strong>Fecha:</strong> <?php echo !empty($order['created_at']) ? date('d/m/Y H:i', strtotime($order['created_at'])) : '-'; ?></p>
    <p><strong>Estado:</strong> <span class="status-badge status-<?php echo preg_replace('/[^a-z]/', '', strtolower($order['status'])); ?>"><?php echo $order['status']; ?></span></p>
    <p><strong>Tarjeta usada:</strong> <?php echo $order['card_snapshot']; ?></p>
    <p><strong>Total:</strong> <?php echo number_format((float) $order['total'], 2); ?> &euro;</p>
</div>
<?php if ($order['total'] < 5): ?>
    <p class="alert alert-ok">Total anormalmente bajo detectado. Codigo de verificacion (logica de negocio / manipulacion de precio): <?php echo labFlag('BIZLOGIC'); ?></p>
<?php endif; ?>
<table class="cart-table">
    <tr><th>Producto</th><th>Cantidad</th><th>Precio unidad</th></tr>
    <?php while ($it = $items->fetch_assoc()): ?>
    <tr>
        <td><?php echo $it['name']; ?></td>
        <td><?php echo $it['quantity']; ?></td>
        <td><?php echo $it['unit_price']; ?> &euro;</td>
    </tr>
    <?php endwhile; ?>
</table>
<p><a href="/invoice.php?file=plantilla.txt">Descargar factura generica</a></p>
<?php endif; ?>
<?php require __DIR__ . '/includes/footer.php'; ?>
