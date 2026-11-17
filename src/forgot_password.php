<?php
require_once __DIR__ . '/includes/auth.php';
checkRememberMe();

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $conn = getDbConnection();
    $user = $conn->query("SELECT * FROM users WHERE email='" . $conn->real_escape_string($email) . "'")->fetch_assoc();

    if ($user) {
        $token = md5($user['id'] . $user['email'] . date('Y-m-d'));
        $conn->query("INSERT INTO password_resets (user_id, token) VALUES (" . intval($user['id']) . ", '$token')");
        $message = "Se ha generado un enlace de recuperacion (simulado, en produccion se enviaria por email): "
                  . "<a href='/reset_password.php?token=$token'>Restablecer contraseña</a>";
    } else {
        $message = "Si el email existe en nuestro sistema, recibiras un enlace de recuperacion.";
    }
}

$breadcrumb = ['Recuperar contraseña'];
$pageTitle = 'Recuperar contraseña';
require __DIR__ . '/includes/header.php';
?>
<h1>Recuperar contraseña</h1>
<p class="form-hint">Introduce el email de tu cuenta y te enviaremos un enlace para restablecer tu contraseña.</p>
<?php if ($message): ?><p class="alert alert-ok"><?php echo $message; ?></p><?php endif; ?>
<form method="POST" class="form-box">
    <label>Email de tu cuenta</label>
    <input type="email" name="email" required>
    <button type="submit">Enviar enlace</button>
</form>
<?php require __DIR__ . '/includes/footer.php'; ?>
