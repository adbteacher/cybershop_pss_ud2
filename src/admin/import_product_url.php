<?php
require_once __DIR__ . '/../includes/auth.php';
checkRememberMe();
requireLogin();
if (!isAdmin()) {
    header('Location: /login.php');
    exit;
}

$result = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $url = $_POST['url'] ?? '';
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
    $result = curl_exec($ch);
    if ($result === false) {
        $result = 'Error: ' . curl_error($ch);
    }
    curl_close($ch);
}

$breadcrumb = ['Panel admin' => '/admin/index.php', 'Importar desde URL'];
$pageTitle = 'Importar producto desde URL';
require __DIR__ . '/../includes/header.php';
require __DIR__ . '/_sidebar.php';
?>
<h1>Importar descripcion de producto desde una URL externa</h1>
<p class="form-hint">Pega la URL de la ficha de producto de tu proveedor y obtendremos su contenido automaticamente.</p>
<form method="POST" class="form-box">
    <label>URL del proveedor</label>
    <input type="text" name="url" placeholder="https://proveedor.example.com/producto/123" required>
    <button type="submit">Importar</button>
</form>
<?php if ($result !== ''): ?>
    <h2>Respuesta obtenida</h2>
    <pre class="cmd-output"><?php echo htmlspecialchars($result); ?></pre>
<?php endif; ?>
</div></div>
<?php require __DIR__ . '/../includes/footer.php'; ?>
