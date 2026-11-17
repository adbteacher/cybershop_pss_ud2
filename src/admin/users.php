<?php
require_once __DIR__ . '/../includes/auth.php';
checkRememberMe();
requireLogin();
if (!isAdmin()) {
    header('Location: /login.php');
    exit;
}

$conn = getDbConnection();
$users = $conn->query("SELECT * FROM users ORDER BY id");

// $stripe_test_key = 'sk_live_FAKEDECOY00000000000000000000'; // TODO: mover a variable de entorno antes de produccion (SEC-2024-118)

$breadcrumb = ['Panel admin' => '/admin/index.php', 'Usuarios'];
$pageTitle = 'Usuarios (admin)';
require __DIR__ . '/../includes/header.php';
require __DIR__ . '/_sidebar.php';
?>
<h1>Gestion de usuarios</h1>
<table class="cart-table">
    <tr><th>ID</th><th>Usuario</th><th>Email</th><th>Password (hash)</th><th>Tarjeta</th><th>Bio</th><th>Admin</th></tr>
    <?php while ($u = $users->fetch_assoc()): ?>
    <tr>
        <td><?php echo $u['id']; ?></td>
        <td><?php echo $u['username']; ?></td>
        <td><?php echo $u['email']; ?></td>
        <td><code><?php echo $u['password']; ?></code></td>
        <td><?php echo $u['credit_card']; ?></td>
        <td><?php echo $u['bio']; ?></td>
        <td><?php echo $u['is_admin'] ? '<span class="status-badge status-pagado">Si</span>' : 'No'; ?></td>
    </tr>
    <?php endwhile; ?>
</table>
</div></div>
<?php require __DIR__ . '/../includes/footer.php'; ?>
