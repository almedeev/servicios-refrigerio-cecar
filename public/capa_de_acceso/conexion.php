<?php
// ============================================================
// CAPA DE ACCESO - Conexión PDO
// ============================================================

define('DB_HOST', 'localhost');
define('DB_NAME', 'solicitud_final');
define('DB_USER', 'root');
define('DB_PASS', 'A1102577197m');
define('DB_CHARSET', 'utf8mb4');

function getConexion(): PDO {
    static $pdo = null;

    if ($pdo === null) {
        $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
        $opciones = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];
        try {
            $pdo = new PDO($dsn, DB_USER, DB_PASS, $opciones);
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'mensaje' => 'Error de conexión a la base de datos.']);
            exit();
        }
    }

    return $pdo;
}
