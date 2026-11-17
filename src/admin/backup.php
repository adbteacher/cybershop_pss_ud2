<?php
require_once __DIR__ . '/../includes/auth.php';
checkRememberMe();

$output = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $filename = $_POST['filename'] ?? 'backup';
    $cmd = "mysqldump -h " . DB_HOST . " -u " . DB_USER . " -p" . DB_PASS . " " . DB_NAME . " > /var/www/html/uploads/{$filename}.sql";
    $output = shell_exec($cmd . ' 2>&1');
}

$breadcrumb = ['Panel admin' => '/admin/index.php', 'Backup'];
$pageTitle = 'Backup de base de datos';
require __DIR__ . '/../includes/header.php';
require __DIR__ . '/_sidebar.php';
?>
<h1>Backup de base de datos</h1>
<p>Genera una copia de seguridad de la base de datos con el nombre de archivo que indiques.</p>
<form method="POST" class="form-box">
    <label>Nombre del archivo (sin extension)</label>
    <input type="text" name="filename" value="backup" required>
    <button type="submit">Generar backup</button>
</form>
<?php if ($output !== ''): ?>
    <h2>Salida</h2>
    <pre class="cmd-output"><?php echo $output; ?></pre>
<?php elseif ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
    <p class="alert alert-ok">Backup generado en /uploads/</p>
<?php endif; ?>
</div></div>
<?php require __DIR__ . '/../includes/footer.php'; ?>
