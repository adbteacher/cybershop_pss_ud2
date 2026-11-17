<?php
require_once __DIR__ . '/includes/auth.php';
checkRememberMe();

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    $conn = getDbConnection();
    $hash = md5($password);
    $stmt = $conn->prepare("INSERT INTO users (username,email,password,bio,credit_card,is_admin) VALUES (?,?,?,?,?,0)");
    $bio = '';
    $card = '';
    $stmt->bind_param('sssss', $username, $email, $hash, $bio, $card);

    if ($stmt->execute()) {
        header('Location: /login.php');
        exit;
    } else {
        $error = 'No se pudo crear la cuenta: ' . $conn->error;
    }
}

$breadcrumb = ['Crear cuenta'];
$pageTitle = 'Registro';
require __DIR__ . '/includes/header.php';
?>
<div class="auth-layout">
<div class="auth-panel">
    <h2>Unete a CyberShop</h2>
    <ul class="auth-benefits">
        <li>✔ Envios prioritarios en 24-48h</li>
        <li>✔ Historial de todos tus pedidos</li>
        <li>✔ Ofertas exclusivas para clientes registrados</li>
    </ul>
</div>
<div class="form-box-wrap">
<h1>Crear cuenta</h1>
<?php if ($error): ?><p class="alert alert-error"><?php echo $error; ?></p><?php endif; ?>
<form method="POST" class="form-box">
    <label>Usuario</label>
    <input type="text" name="username" required>
    <label>Email</label>
    <input type="email" name="email" required>
    <label>Contraseña</label>
    <input type="password" name="password" required>
    <button type="submit">Registrarse</button>
</form>
<p>¿Ya tienes cuenta? <a href="/login.php">Inicia sesion</a></p>
</div>
</div>
<?php require __DIR__ . '/includes/footer.php'; ?>
