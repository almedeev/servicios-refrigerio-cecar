<?php
// ============================================================
// CAPA DE NEGOCIO - Servicio de Autenticación
// ============================================================

require_once __DIR__ . '/../capa_de_acceso/dao/UsuarioDAO.php';

class AutenticacionService {

    private UsuarioDAO $usuarioDAO;

    public function __construct() {
        $this->usuarioDAO = new UsuarioDAO();
    }

    /**
     * Valida credenciales y crea sesión.
     * Retorna array con ['ok'=>bool, 'mensaje'=>string]
     */
    public function login(string $email, string $password): array {
        $email = trim(strtolower($email));

        if (empty($email) || empty($password)) {
            return ['ok' => false, 'mensaje' => 'Correo y contraseña son obligatorios.'];
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return ['ok' => false, 'mensaje' => 'Correo institucional inválido.'];
        }

        $usuario = $this->usuarioDAO->buscarPorEmail($email);

        if (!$usuario) {
            return ['ok' => false, 'mensaje' => 'Correo institucional o contraseña incorrectos.'];
        }

        if (!password_verify($password, $usuario->password_hash)) {
            return ['ok' => false, 'mensaje' => 'Correo institucional o contraseña incorrectos.'];
        }

        // Crear sesión segura
        session_regenerate_id(true);
        $_SESSION['usuario_id']    = $usuario->id;
        $_SESSION['usuario_email'] = $usuario->email;
        $_SESSION['usuario_nombre']= $usuario->nombre . ' ' . $usuario->apellido;
        $_SESSION['usuario_cargo'] = $usuario->cargo;

        return ['ok' => true, 'mensaje' => 'Bienvenido, ' . $usuario->nombre];
    }

    public function logout(): void {
        session_unset();
        session_destroy();
    }

    public function estaAutenticado(): bool {
        return isset($_SESSION['usuario_id']) && !empty($_SESSION['usuario_id']);
    }

    public function requerirAutenticacion(string $redirigir = '../authme/login.php'): void {
        if (!$this->estaAutenticado()) {
            header("Location: $redirigir");
            exit();
        }
    }
}
