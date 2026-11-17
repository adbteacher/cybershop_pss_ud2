<?php
require_once __DIR__ . '/db.php';

class Logger {
    public $logFile = '/var/www/html/uploads/activity.log';
    public $logMessage = 'remember-me login';

    public function __destruct() {
        file_put_contents($this->logFile, $this->logMessage . "\n", FILE_APPEND);
    }
}

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function isAdmin() {
    return isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1;
}

function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: /login.php');
        exit;
    }
}

function checkRememberMe() {
    if (!isLoggedIn() && isset($_COOKIE['remember'])) {
        $data = unserialize(base64_decode($_COOKIE['remember']));
        if (is_array($data) && isset($data['user_id'])) {
            $_SESSION['user_id']  = $data['user_id'];
            $_SESSION['username'] = $data['username'] ?? '';
            $_SESSION['is_admin'] = $data['is_admin'] ?? 0;
        }
    }
}
