<?php
// Menu lateral puramente visual del panel de administracion. No implementa
// ningun control de acceso: cada pagina admin/*.php decide por si misma si
// llama a requireLogin()/isAdmin() antes de incluir este fichero (o no).
$adminCurrent = basename($_SERVER['SCRIPT_NAME'] ?? '');
$adminLinks = [
    'index.php'               => ['label' => 'Panel', 'icon' => '📊'],
    'products.php'             => ['label' => 'Productos', 'icon' => '📦'],
    'users.php'                => ['label' => 'Usuarios', 'icon' => '👥'],
    'upload.php'                => ['label' => 'Subir imagen', 'icon' => '🖼️'],
    'import_xml.php'            => ['label' => 'Importar catalogo (XML)', 'icon' => '📄'],
    'import_product_url.php'    => ['label' => 'Importar desde URL', 'icon' => '🔗'],
    'backup.php'                => ['label' => 'Backup base de datos', 'icon' => '🗄️'],
];
?>
<div class="admin-layout">
<aside class="admin-sidebar">
    <p class="admin-sidebar-title">Panel de administracion</p>
    <nav>
        <?php foreach ($adminLinks as $file => $info): ?>
            <a href="/admin/<?php echo $file; ?>"<?php echo $adminCurrent === $file ? ' class="active"' : ''; ?>>
                <?php echo $info['icon']; ?> <?php echo $info['label']; ?>
            </a>
        <?php endforeach; ?>
    </nav>
</aside>
<div class="admin-content">
