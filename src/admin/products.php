<?php
require_once __DIR__ . '/../includes/auth.php';
checkRememberMe();
requireLogin();
if (!isAdmin()) {
    header('Location: /login.php');
    exit;
}

$conn = getDbConnection();
$filter = $_GET['filter'] ?? '';
$sql = "SELECT * FROM products WHERE name LIKE '%$filter%'";
$products = $conn->query($sql);

$breadcrumb = ['Panel admin' => '/admin/index.php', 'Productos'];
$pageTitle = 'Productos (admin)';
require __DIR__ . '/../includes/header.php';
require __DIR__ . '/_sidebar.php';
?>
<h1>Gestion de productos</h1>
<form method="GET" class="search-form">
    <input type="text" name="filter" value="<?php echo $filter; ?>" placeholder="Filtrar por nombre...">
    <button type="submit">Filtrar</button>
</form>
<?php if ($products === false): ?>
    <p class="alert alert-error">Error en la consulta: <?php echo $conn->error; ?></p>
<?php else: ?>
<table class="cart-table">
    <tr><th>ID</th><th>Nombre</th><th>Categoria</th><th>Precio</th><th>Stock</th></tr>
    <?php while ($p = $products->fetch_assoc()): ?>
    <tr>
        <td><?php echo $p['id']; ?></td>
        <td><?php echo $p['name']; ?></td>
        <td><?php echo catalogCategoryLabel($p['name']); ?></td>
        <td><?php echo number_format((float) $p['price'], 2); ?> &euro;</td>
        <td><?php echo $p['stock'] < 15 ? '<span class="badge-stock">' . $p['stock'] . '</span>' : $p['stock']; ?></td>
    </tr>
    <?php endwhile; ?>
</table>
<?php endif; ?>
</div></div>
<?php require __DIR__ . '/../includes/footer.php'; ?>
