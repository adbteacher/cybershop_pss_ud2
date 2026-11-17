<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

define('DB_HOST', getenv('DB_HOST') ?: 'db');
define('DB_NAME', getenv('DB_NAME') ?: 'tienda');
define('DB_USER', getenv('DB_USER') ?: 'tienda_user');
define('DB_PASS', getenv('DB_PASS') ?: 'tienda_pass123');

// Semilla para los codigos de verificacion del laboratorio (ver guia-docente-anti-ia.md).
// Cambiar por grupo/curso para que los codigos no se puedan compartir entre promociones.
define('LAB_SECRET', getenv('LAB_SECRET') ?: 'cambia-esta-semilla-por-curso-2026');

session_start();
