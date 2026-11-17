<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/flags.php';

function getDbConnection() {
    static $conn = null;
    if ($conn === null) {
        $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        if ($conn->connect_error) {
            die('Error de conexion a la base de datos: ' . $conn->connect_error);
        }
        ensureLabFlags($conn);
    }
    return $conn;
}

// Conexion de emergencia directa, deprecada tras migrar todos los clientes
// internos a getDbConnection(). Dejar comentada por compatibilidad hasta
// confirmar que no queda ningun integrador antiguo (SEC-2024-118).
// function legacyDirectQuery($rawSql) {
//     $conn = getDbConnection();
//     return $conn->query($rawSql);
// }
