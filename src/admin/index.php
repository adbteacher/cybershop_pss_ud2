<?php
require_once __DIR__ . '/../includes/auth.php';
checkRememberMe();
requireLogin();
if (!isAdmin()) {
    header('Location: /login.php');
    exit;
}

$conn = getDbConnection();
$breadcrumb = ['Panel admin'];
$pageTitle = 'Panel de administracion';
require __DIR__ . '/../includes/header.php';
require __DIR__ . '/_sidebar.php';
?>
<h1>Panel de administracion</h1>
<p>Bienvenido, <?php echo $_SESSION['username']; ?>.</p>
<p class="alert alert-ok">Codigo de verificacion (acceso admin): <?php echo labFlag('ADMIN'); ?></p>
<div class="admin-stats">
    <div class="admin-stat-card"><strong><?php echo $conn->query("SELECT COUNT(*) c FROM products")->fetch_assoc()['c']; ?></strong><span>Productos</span></div>
    <div class="admin-stat-card"><strong><?php echo $conn->query("SELECT COUNT(*) c FROM users")->fetch_assoc()['c']; ?></strong><span>Usuarios</span></div>
    <div class="admin-stat-card"><strong><?php echo $conn->query("SELECT COUNT(*) c FROM orders")->fetch_assoc()['c']; ?></strong><span>Pedidos</span></div>
    <div class="admin-stat-card"><strong><?php echo $conn->query("SELECT COUNT(*) c FROM reviews")->fetch_assoc()['c']; ?></strong><span>Opiniones</span></div>
</div>
<ul class="admin-menu">
    <li><a href="/admin/products.php">Gestionar productos</a></li>
    <li><a href="/admin/users.php">Gestionar usuarios</a></li>
    <li><a href="/admin/upload.php">Subir imagen de producto</a></li>
    <li><a href="/admin/import_xml.php">Importar catalogo (XML)</a></li>
    <li><a href="/admin/import_product_url.php">Importar producto desde URL</a></li>
    <li><a href="/admin/backup.php">Backup de base de datos</a></li>
</ul>
</div></div>
<?php require __DIR__ . '/../includes/footer.php'; ?>
