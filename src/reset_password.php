<?php
require_once __DIR__ . '/includes/auth.php';
checkRememberMe();

$token = $_GET['token'] ?? $_POST['token'] ?? '';
$conn = getDbConnection();
$reset = $conn->query("SELECT * FROM password_resets WHERE token='" . $conn->real_escape_string($token) . "'")->fetch_assoc();

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $reset) {
    $newPass = md5($_POST['password'] ?? '');
    $conn->query("UPDATE users SET password='$newPass' WHERE id=" . intval($reset['user_id']));
    $message = 'Contraseña actualizada. Ya puedes iniciar sesion.';
}

$breadcrumb = ['Restablecer contraseña'];
$pageTitle = 'Restablecer contraseña';
require __DIR__ . '/includes/header.php';
?>
<h1>Restablecer contraseña</h1>
<?php if (!$reset): ?>
    <p class="alert alert-error">Token invalido o caducado.</p>
<?php elseif ($message): ?>
    <p class="alert alert-ok"><?php echo $message; ?></p>
    <a href="/login.php">Ir a iniciar sesion</a>
<?php else: ?>
<form method="POST" class="form-box">
    <input type="hidden" name="token" value="<?php echo $token; ?>">
    <label>Nueva contraseña</label>
    <input type="password" name="password" required>
    <button type="submit">Cambiar contraseña</button>
</form>
<?php endif; ?>
<?php require __DIR__ . '/includes/footer.php'; ?>
