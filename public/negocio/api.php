<?php
// ============================================================
// CAPA DE NEGOCIO - API AJAX (endpoint centralizado)
// ============================================================
session_start();
header('Content-Type: application/json');

require_once __DIR__ . '/../negocio/AutenticacionService.php';
require_once __DIR__ . '/../negocio/SolicitudService.php';
require_once __DIR__ . '/../capa_de_acceso/conexion.php';

$auth = new AutenticacionService();

// Verificar autenticación
if (!$auth->estaAutenticado()) {
    http_response_code(401);
    echo json_encode(['status' => 'error', 'mensaje' => 'No autenticado.']);
    exit();
}

$service = new SolicitudService();
$method  = $_SERVER['REQUEST_METHOD'];

// ---- GET: listar solicitudes y dependencias ----
if ($method === 'GET') {
    $tipo = $_GET['tipo'] ?? '';

    if ($tipo === 'solicitudes') {
        $usuarioId = $_SESSION['usuario_id'];
        $data = $service->listarPorUsuario($usuarioId);
        echo json_encode(['status' => 'ok', 'data' => $data]);
        exit();
    }

    if ($tipo === 'todas') {
        $data = $service->listarTodas();
        echo json_encode(['status' => 'ok', 'data' => $data]);
        exit();
    }

    if ($tipo === 'dependencias') {
        $pdo  = getConexion();
        $stmt = $pdo->prepare("SELECT id, nombre FROM dependencias WHERE activo = 1 ORDER BY nombre");
        $stmt->execute();
        echo json_encode(['status' => 'ok', 'data' => $stmt->fetchAll()]);
        exit();
    }

    if ($tipo === 'conteos') {
        echo json_encode(['status' => 'ok', 'data' => $service->contarPorEstado()]);
        exit();
    }

    echo json_encode(['status' => 'error', 'mensaje' => 'Parámetro tipo no reconocido.']);
    exit();
}

// ---- POST: crear / actualizar / eliminar ----
if ($method === 'POST') {
    // Crear solicitud (multipart/form-data)
    if (isset($_POST['accion']) && $_POST['accion'] === 'crear') {
        $datos = [
            'usuario_id'      => $_SESSION['usuario_id'],
            'dependencia_id'  => (int) ($_POST['dependencia_id'] ?? 0),
            'fecha_solicitud' => trim($_POST['fecha_solicitud'] ?? ''),
            'justificacion'   => trim($_POST['justificacion'] ?? ''),
            'valor_total'     => $_POST['valor_total'] ?? 0,
        ];

        $archivo = $_FILES['archivo'] ?? [];
        $resultado = $service->crear($datos, $archivo);

        // Guardar cookie con nombre de solicitante
        if ($resultado['status'] === 'ok' && !empty($_POST['nombre_completo'])) {
            setcookie('nombre_usuario', $_POST['nombre_completo'], time() + (7 * 24 * 60 * 60), '/');
        }

        echo json_encode($resultado);
        exit();
    }

    // Acciones JSON (actualizar / eliminar)
    $body = json_decode(file_get_contents('php://input'), true);

    if (!$body) {
        echo json_encode(['status' => 'error', 'mensaje' => 'Cuerpo de petición inválido.']);
        exit();
    }

    $accion = $body['accion'] ?? '';

    if ($accion === 'actualizar') {
        $id           = (int) ($body['id'] ?? 0);
        $estado_id    = (int) ($body['estado_id'] ?? 1);
        $justificacion = $body['justificacion'] ?? '';
        echo json_encode($service->actualizar($id, $estado_id, $justificacion));
        exit();
    }

    if ($accion === 'eliminar') {
        $id = (int) ($body['id'] ?? 0);
        echo json_encode($service->eliminar($id));
        exit();
    }

    echo json_encode(['status' => 'error', 'mensaje' => 'Acción no reconocida.']);
    exit();
}

echo json_encode(['status' => 'error', 'mensaje' => 'Método no permitido.']);
