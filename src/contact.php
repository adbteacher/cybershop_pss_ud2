<?php
require_once __DIR__ . '/includes/auth.php';
checkRememberMe();

$name = $_GET['name'] ?? $_POST['name'] ?? '';
$sent = ($_SERVER['REQUEST_METHOD'] === 'POST');

$breadcrumb = ['Contacto'];
$pageTitle = 'Contacto';
require __DIR__ . '/includes/header.php';
?>
<h1>Contacto</h1>
<div class="contact-layout">
<div class="contact-info">
    <h3>Atencion al cliente</h3>
    <p>📍 Carrer de la Foia de Sant Miquel, Mislata (Valencia)</p>
    <p>☎ 96 123 45 67</p>
    <p>✉ soporte@cybershop.local</p>
    <p>🕒 Lunes a viernes, 9:00 - 18:00</p>
</div>
<div class="form-box-wrap">
<?php if ($name !== ''): ?>
    <p class="alert alert-ok">Gracias <?php echo $name; ?>, hemos recibido tu mensaje.</p>
<?php endif; ?>
<form method="POST" class="form-box">
    <label>Nombre</label>
    <input type="text" name="name" required>
    <label>Mensaje</label>
    <textarea name="message" required></textarea>
    <button type="submit">Enviar</button>
</form>
</div>
</div>
<?php require __DIR__ . '/includes/footer.php'; ?>
