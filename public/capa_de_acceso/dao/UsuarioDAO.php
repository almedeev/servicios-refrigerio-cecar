<?php
// ============================================================
// CAPA DE ACCESO - DAO: UsuarioDAO
// ============================================================

require_once __DIR__ . '/../conexion.php';
require_once __DIR__ . '/../modelo/Usuario.php';

class UsuarioDAO {

    private PDO $pdo;

    public function __construct() {
        $this->pdo = getConexion();
    }

    // Buscar usuario por email (para login)
    public function buscarPorEmail(string $email): ?Usuario {
        $stmt = $this->pdo->prepare(
            "SELECT id, nombre, apellido, email, password_hash, cargo, activo
             FROM usuarios
             WHERE email = :email AND activo = 1
             LIMIT 1"
        );
        $stmt->bindValue(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        $fila = $stmt->fetch();

        if (!$fila) return null;

        return new Usuario($fila);
    }

    // Buscar usuario por ID
    public function buscarPorId(int $id): ?Usuario {
        $stmt = $this->pdo->prepare(
            "SELECT id, nombre, apellido, email, password_hash, cargo, activo
             FROM usuarios WHERE id = :id LIMIT 1"
        );
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $fila = $stmt->fetch();

        if (!$fila) return null;

        return new Usuario($fila);
    }

    // Insertar nuevo usuario
    public function insertar(Usuario $u): int {
        $stmt = $this->pdo->prepare(
            "INSERT INTO usuarios (nombre, apellido, email, password_hash, cargo)
             VALUES (:nombre, :apellido, :email, :password_hash, :cargo)"
        );
        $stmt->bindValue(':nombre',        $u->nombre,        PDO::PARAM_STR);
        $stmt->bindValue(':apellido',      $u->apellido,      PDO::PARAM_STR);
        $stmt->bindValue(':email',         $u->email,         PDO::PARAM_STR);
        $stmt->bindValue(':password_hash', $u->password_hash, PDO::PARAM_STR);
        $stmt->bindValue(':cargo',         $u->cargo,         PDO::PARAM_STR);
        $stmt->execute();

        return (int) $this->pdo->lastInsertId();
    }

    // Listar todos los usuarios activos
    public function listar(): array {
        $stmt = $this->pdo->prepare(
            "SELECT id, nombre, apellido, email, cargo, activo FROM usuarios WHERE activo = 1"
        );
        $stmt->execute();

        return array_map(fn($f) => new Usuario($f), $stmt->fetchAll());
    }
}
