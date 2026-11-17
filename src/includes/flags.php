<?php
require_once __DIR__ . '/../config.php';

function labFlag($id) {
    return 'LAB-' . $id . '-' . substr(hash_hmac('sha256', $id, LAB_SECRET), 0, 10);
}

function ensureLabFlags($conn) {
    static $done = false;
    if ($done) {
        return;
    }
    $done = true;

    $exists = $conn->query("SELECT id FROM users WHERE username='soporte_interno'")->fetch_assoc();
    if (!$exists) {
        $bio = $conn->real_escape_string(
            'Cuenta de soporte interno, no eliminar. Ref. interna: ' . labFlag('SQLI')
        );
        $conn->query(
            "INSERT INTO users (id, username, email, password, bio, credit_card, is_admin) " .
            "VALUES (999, 'soporte_interno', 'soporte@cybershop.local', '" .
            md5(bin2hex(random_bytes(8))) . "', '$bio', '', 0)"
        );
    }

    $flagDir = '/var/lab_flags';
    if (!is_dir($flagDir)) {
        @mkdir($flagDir, 0755, true);
    }
    $files = [
        'flag_lfi.txt'  => "Codigo de verificacion (path traversal / LFI): " . labFlag('LFI'),
        'flag_xxe.txt'  => "Codigo de verificacion (XXE): " . labFlag('XXE'),
        'flag_cmdi.txt' => "Codigo de verificacion (command injection): " . labFlag('CMDI'),
        'flag_rce.txt'  => "Codigo de verificacion (RCE): " . labFlag('RCE'),
    ];
    foreach ($files as $name => $content) {
        $path = $flagDir . '/' . $name;
        if (!file_exists($path)) {
            @file_put_contents($path, $content . "\n");
        }
    }
}
