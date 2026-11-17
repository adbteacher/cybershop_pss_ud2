<?php
require_once __DIR__ . '/includes/auth.php';
checkRememberMe();
requireLogin();

$conn = getDbConnection();
$msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $bio = $_POST['bio'] ?? '';
    $email = $_POST['email'] ?? '';
    $targetId = $_POST['id'] ?? $_SESSION['user_id'];
    $conn->query("UPDATE users SET bio='" . $conn->real_escape_string($bio) . "', email='" . $conn->real_escape_string($email) . "' WHERE id=" . intval($targetId));
    $msg = 'Perfil actualizado.';
}

$viewId = $_GET['id'] ?? $_SESSION['user_id'];
$user = $conn->query("SELECT * FROM users WHERE id = " . intval($viewId))->fetch_assoc();

$breadcrumb = ['Mi perfil'];
$pageTitle = 'Mi perfil';
require __DIR__ . '/includes/header.php';
?>
<h1>Perfil de usuario</h1>
<?php if ($msg): ?><p class="alert alert-ok"><?php echo $msg; ?></p><?php endif; ?>
<?php if (!$user): ?>
    <p class="alert alert-error">Usuario no encontrado.</p>
<?php else: ?>
<div class="profile-box">
    <div class="profile-avatar"><?php echo strtoupper(substr($user['username'], 0, 1)); ?></div>
    <div>
        <p><strong>ID:</strong> <?php echo $user['id']; ?></p>
        <p><strong>Usuario:</strong> <?php echo $user['username']; ?></p>
        <p><strong>Miembro desde:</strong> <?php echo isset($user['created_at']) ? date('d/m/Y', strtotime($user['created_at'])) : '-'; ?></p>
        <p><strong>Tarjeta guardada:</strong> <?php echo $user['credit_card']; ?></p>
        <p><strong>Bio:</strong> <?php echo $user['bio']; ?></p>
    </div>
</div>

<form method="POST" class="form-box">
    <input type="hidden" name="id" value="<?php echo $user['id']; ?>">
    <label>Email</label>
    <input type="email" name="email" value="<?php echo $user['email']; ?>">
    <label>Bio</label>
    <textarea name="bio"><?php echo $user['bio']; ?></textarea>
    <button type="submit">Guardar cambios</button>
</form>
<?php endif; ?>
<?php require __DIR__ . '/includes/footer.php'; ?>
