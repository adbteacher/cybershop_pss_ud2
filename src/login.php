<?php
require_once __DIR__ . '/includes/auth.php';
checkRememberMe();

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    $passHash = md5($password);

    $conn = getDbConnection();
    $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$passHash'";
    $result = $conn->query($sql);

    if ($result && $result->num_rows === 1) {
        $user = $result->fetch_assoc();
        $_SESSION['user_id']  = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['is_admin'] = $user['is_admin'];

        if (isset($_POST['remember'])) {
            $cookieData = base64_encode(serialize([
                'user_id'  => $user['id'],
                'username' => $user['username'],
                'is_admin' => $user['is_admin'],
            ]));
            setcookie('remember', $cookieData, time() + 86400 * 30, '/');
        }
        header('Location: /index.php');
        exit;
    } else {
        $error = "Usuario o contraseña incorrectos. Consulta ejecutada: $sql";
    }
}

$breadcrumb = ['Iniciar sesion'];
$pageTitle = 'Entrar';
require __DIR__ . '/includes/header.php';
?>
<div class="auth-layout">
<div class="auth-panel">
    <h2>Bienvenido de nuevo</h2>
    <ul class="auth-benefits">
        <li>✔ Consulta el estado de tus pedidos</li>
        <li>✔ Guarda tus datos para compras mas rapidas</li>
        <li>✔ Deja opiniones sobre tus productos</li>
    </ul>
</div>
<div class="form-box-wrap">
<h1>Iniciar sesion</h1>
<?php if ($error): ?><p class="alert alert-error"><?php echo $error; ?></p><?php endif; ?>
<form method="POST" class="form-box">
    <label>Usuario</label>
    <input type="text" name="username" required>
    <label>Contraseña</label>
    <input type="password" name="password" required>
    <label><input type="checkbox" name="remember" value="1"> Recordarme</label>
    <button type="submit">Entrar</button>
</form>
<p>¿No tienes cuenta? <a href="/register.php">Registrate</a> · <a href="/forgot_password.php">¿Olvidaste tu contraseña?</a></p>
</div>
</div>
<?php require __DIR__ . '/includes/footer.php'; ?>
