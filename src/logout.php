<?php
require_once __DIR__ . '/includes/db.php';
$_SESSION = [];
session_destroy();
setcookie('remember', '', time() - 3600, '/');
header('Location: /index.php');
exit;
