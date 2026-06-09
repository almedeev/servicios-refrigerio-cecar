<?php
// ============================================================
// CAPA DE ACCESO - DAO: SolicitudDAO
// ============================================================

require_once __DIR__ . '/../conexion.php';
require_once __DIR__ . '/../modelo/Solicitud.php';

class SolicitudDAO {

    private PDO $pdo;

    public function __construct() {
        $this->pdo = getConexion();
    }

    // CREATE
    public function insertar(Solicitud $s): int {
        $stmt = $this->pdo->prepare(
            "INSERT INTO solicitudes
                (numero_radicado, usuario_id, dependencia_id, estado_id, fecha_solicitud, justificacion, valor_total)
             VALUES
                (:radicado, :usuario_id, :dependencia_id, :estado_id, :fecha, :justificacion, :valor_total)"
        );
        $stmt->bindValue(':radicado',      $s->numero_radicado, PDO::PARAM_STR);
        $stmt->bindValue(':usuario_id',    $s->usuario_id,      PDO::PARAM_INT);
        $stmt->bindValue(':dependencia_id',$s->dependencia_id,  PDO::PARAM_INT);
        $stmt->bindValue(':estado_id',     $s->estado_id,       PDO::PARAM_INT);
        $stmt->bindValue(':fecha',         $s->fecha_solicitud, PDO::PARAM_STR);
        $stmt->bindValue(':justificacion', $s->justificacion,   PDO::PARAM_STR);
        $stmt->bindValue(':valor_total',   $s->valor_total);
        $stmt->execute();

        return (int) $this->pdo->lastInsertId();
    }

    // READ - listar todas con JOIN para obtener nombres
    public function listar(): array {
        $stmt = $this->pdo->prepare(
            "SELECT s.id, s.numero_radicado, s.fecha_solicitud, s.valor_total, s.justificacion,
                    CONCAT(u.nombre, ' ', u.apellido) AS solicitante,
                    u.email,
                    d.nombre AS dependencia,
                    e.nombre AS estado
             FROM solicitudes s
             INNER JOIN usuarios u         ON s.usuario_id      = u.id
             INNER JOIN dependencias d     ON s.dependencia_id  = d.id
             INNER JOIN estados_solicitud e ON s.estado_id       = e.id
             ORDER BY s.created_at DESC"
        );
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // READ - listar por usuario
    public function listarPorUsuario(int $usuario_id): array {
        $stmt = $this->pdo->prepare(
            "SELECT s.id, s.numero_radicado, s.fecha_solicitud, s.valor_total, s.justificacion,
                    d.nombre AS dependencia,
                    e.nombre AS estado
             FROM solicitudes s
             INNER JOIN dependencias d      ON s.dependencia_id = d.id
             INNER JOIN estados_solicitud e ON s.estado_id      = e.id
             WHERE s.usuario_id = :uid
             ORDER BY s.created_at DESC"
        );
        $stmt->bindValue(':uid', $usuario_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // READ - buscar por ID
    public function buscarPorId(int $id): ?array {
        $stmt = $this->pdo->prepare(
            "SELECT s.*, CONCAT(u.nombre,' ',u.apellido) AS solicitante,
                    d.nombre AS dependencia, e.nombre AS estado
             FROM solicitudes s
             INNER JOIN usuarios u          ON s.usuario_id     = u.id
             INNER JOIN dependencias d      ON s.dependencia_id = d.id
             INNER JOIN estados_solicitud e ON s.estado_id      = e.id
             WHERE s.id = :id LIMIT 1"
        );
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $fila = $stmt->fetch();
        return $fila ?: null;
    }

    // UPDATE - actualizar estado y justificacion
    public function actualizar(int $id, int $estado_id, string $justificacion): bool {
        $stmt = $this->pdo->prepare(
            "UPDATE solicitudes
             SET estado_id = :estado_id, justificacion = :justificacion
             WHERE id = :id"
        );
        $stmt->bindValue(':estado_id',    $estado_id,    PDO::PARAM_INT);
        $stmt->bindValue(':justificacion',$justificacion,PDO::PARAM_STR);
        $stmt->bindValue(':id',           $id,           PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }

    // DELETE
    public function eliminar(int $id): bool {
        $stmt = $this->pdo->prepare("DELETE FROM solicitudes WHERE id = :id");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }

    // Contar por estado
    public function contarPorEstado(): array {
        $stmt = $this->pdo->prepare(
            "SELECT e.nombre AS estado, COUNT(s.id) AS total
             FROM estados_solicitud e
             LEFT JOIN solicitudes s ON s.estado_id = e.id
             GROUP BY e.id, e.nombre"
        );
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
