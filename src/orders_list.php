<?php
require_once __DIR__ . '/includes/auth.php';
checkRememberMe();
requireLogin();

$conn = getDbConnection();
$orders = $conn->query("SELECT * FROM orders WHERE user_id=" . intval($_SESSION['user_id']) . " ORDER BY id DESC");

$breadcrumb = ['Mis pedidos'];
$pageTitle = 'Mis pedidos';
require __DIR__ . '/includes/header.php';
?>
<h1>Mis pedidos</h1>
<?php if ($orders->num_rows === 0): ?>
    <div class="empty-state">
        <p class="empty-state-icon">📦</p>
        <p>Todavia no has realizado ningun pedido.</p>
        <a href="/index.php" class="hero-cta">Empezar a comprar</a>
    </div>
<?php else: ?>
<table class="cart-table">
    <tr><th>Pedido</th><th>Fecha</th><th>Total</th><th>Estado</th><th></th></tr>
    <?php while ($o = $orders->fetch_assoc()): ?>
    <tr>
        <td>#<?php echo $o['id']; ?></td>
        <td><?php echo !empty($o['created_at']) ? date('d/m/Y', strtotime($o['created_at'])) : '-'; ?></td>
        <td><?php echo number_format((float) $o['total'], 2); ?> &euro;</td>
        <td><span class="status-badge status-<?php echo preg_replace('/[^a-z]/', '', strtolower($o['status'])); ?>"><?php echo $o['status']; ?></span></td>
        <td><a href="/orders.php?id=<?php echo $o['id']; ?>">Ver detalle</a></td>
    </tr>
    <?php endwhile; ?>
</table>
<?php endif; ?>
<?php require __DIR__ . '/includes/footer.php'; ?>
