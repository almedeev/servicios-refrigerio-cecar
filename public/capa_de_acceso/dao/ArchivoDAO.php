<?php
// ============================================================
// CAPA DE ACCESO - DAO: ArchivoDAO
// ============================================================

require_once __DIR__ . '/../conexion.php';

class ArchivoDAO {

    private PDO $pdo;

    public function __construct() {
        $this->pdo = getConexion();
    }

    public function insertar(int $solicitud_id, string $nombre, string $ruta, string $mime, int $tamano): int {
        $stmt = $this->pdo->prepare(
            "INSERT INTO archivos_adjuntos (solicitud_id, nombre_archivo, ruta_archivo, tipo_mime, tamano_bytes)
             VALUES (:solicitud_id, :nombre, :ruta, :mime, :tamano)"
        );
        $stmt->bindValue(':solicitud_id', $solicitud_id, PDO::PARAM_INT);
        $stmt->bindValue(':nombre',       $nombre,       PDO::PARAM_STR);
        $stmt->bindValue(':ruta',         $ruta,         PDO::PARAM_STR);
        $stmt->bindValue(':mime',         $mime,         PDO::PARAM_STR);
        $stmt->bindValue(':tamano',       $tamano,       PDO::PARAM_INT);
        $stmt->execute();

        return (int) $this->pdo->lastInsertId();
    }

    public function listarPorSolicitud(int $solicitud_id): array {
        $stmt = $this->pdo->prepare(
            "SELECT id, nombre_archivo, ruta_archivo, tipo_mime, tamano_bytes, uploaded_at
             FROM archivos_adjuntos WHERE solicitud_id = :solicitud_id"
        );
        $stmt->bindValue(':solicitud_id', $solicitud_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
