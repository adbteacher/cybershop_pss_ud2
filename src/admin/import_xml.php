<?php
require_once __DIR__ . '/../includes/auth.php';
checkRememberMe();

$result = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['xmlfile'])) {
    $xmlContent = file_get_contents($_FILES['xmlfile']['tmp_name']);

    libxml_set_external_entity_loader(function ($public, $system, $context) {
        if ($system) {
            return @fopen($system, 'r');
        }
        return null;
    });

    $dom = new DOMDocument();
    $ok = @$dom->loadXML($xmlContent, LIBXML_NOENT | LIBXML_DTDLOAD | LIBXML_DTDATTLIST);

    if ($ok) {
        $product = simplexml_import_dom($dom);
        $result = 'Nombre: ' . (string) $product->name . ' | Descripcion: ' . (string) $product->description;
    } else {
        $error = 'No se pudo procesar el XML.';
    }
}

$breadcrumb = ['Panel admin' => '/admin/index.php', 'Importar catalogo'];
$pageTitle = 'Importar catalogo';
require __DIR__ . '/../includes/header.php';
require __DIR__ . '/_sidebar.php';
?>
<h1>Importar producto desde XML</h1>
<p>Sube un fichero XML con la estructura <code>&lt;product&gt;&lt;name&gt;...&lt;/name&gt;&lt;description&gt;...&lt;/description&gt;&lt;/product&gt;</code>.</p>
<form method="POST" enctype="multipart/form-data" class="form-box">
    <input type="file" name="xmlfile" required>
    <button type="submit">Importar</button>
</form>
<?php if ($result): ?><p class="alert alert-ok"><?php echo $result; ?></p><?php endif; ?>
<?php if ($error): ?><p class="alert alert-error"><?php echo $error; ?></p><?php endif; ?>
</div></div>
<?php require __DIR__ . '/../includes/footer.php'; ?>
