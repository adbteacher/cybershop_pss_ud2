<?php
require_once __DIR__ . '/../includes/auth.php';
checkRememberMe();

$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['image'])) {
    $filename = basename($_FILES['image']['name']);
    $target = __DIR__ . '/../uploads/' . $filename;
    if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
        $msg = "Archivo subido correctamente: /uploads/$filename";
    } else {
        $msg = 'Error al subir el archivo.';
    }
}

$breadcrumb = ['Panel admin' => '/admin/index.php', 'Subir imagen'];
$pageTitle = 'Subir imagen de producto';
require __DIR__ . '/../includes/header.php';
require __DIR__ . '/_sidebar.php';
?>
<h1>Subir imagen de producto</h1>
<p class="form-hint">Formatos admitidos: JPG, PNG, WEBP. Tamaño maximo recomendado: 5MB.</p>
<?php if ($msg): ?><p class="alert alert-ok"><?php echo $msg; ?></p><?php endif; ?>
<form method="POST" enctype="multipart/form-data" class="form-box">
    <label>Selecciona la imagen del producto</label>
    <input type="file" name="image" required>
    <button type="submit">Subir</button>
</form>
</div></div>
<?php require __DIR__ . '/../includes/footer.php'; ?>
