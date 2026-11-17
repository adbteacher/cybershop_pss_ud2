<?php
require_once __DIR__ . '/includes/auth.php';
checkRememberMe();
requireLogin();

$file = $_GET['file'] ?? 'plantilla.txt';
$path = '/var/www/html/invoices/' . $file;

header('Content-Type: text/plain; charset=utf-8');
if (file_exists($path)) {
    readfile($path);
} else {
    http_response_code(404);
    echo "Archivo no encontrado.";
}
