<?php
// ============================================================
// CAPA DE NEGOCIO - Servicio de Solicitudes
// ============================================================

require_once __DIR__ . '/../capa_de_acceso/dao/SolicitudDAO.php';
require_once __DIR__ . '/../capa_de_acceso/dao/ArchivoDAO.php';
require_once __DIR__ . '/../capa_de_acceso/modelo/Solicitud.php';

class SolicitudService {

    private SolicitudDAO $solicitudDAO;
    private ArchivoDAO   $archivoDAO;

    // Estados de la BD (deben coincidir con tabla estados_solicitud)
    const ESTADO_PENDIENTE = 1;
    const ESTADO_REVISION  = 2;
    const ESTADO_APROBADA  = 3;
    const ESTADO_RECHAZADA = 4;

    public function __construct() {
        $this->solicitudDAO = new SolicitudDAO();
        $this->archivoDAO   = new ArchivoDAO();
    }

    /**
     * Crear nueva solicitud con validaciones de negocio.
     */
    public function crear(array $datos, array $archivo = []): array {
        // Validaciones de negocio
        $errores = $this->validarDatos($datos);
        if (!empty($errores)) {
            return ['status' => 'error', 'mensaje' => implode(' | ', $errores)];
        }

        // Generar número de radicado único
        $radicado = 'RAD-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -6));

        $solicitud = new Solicitud([
            'numero_radicado' => $radicado,
            'usuario_id'      => (int) $datos['usuario_id'],
            'dependencia_id'  => (int) $datos['dependencia_id'],
            'estado_id'       => self::ESTADO_PENDIENTE,
            'fecha_solicitud' => $datos['fecha_solicitud'],
            'justificacion'   => trim($datos['justificacion'] ?? ''),
            'valor_total'     => (float) ($datos['valor_total'] ?? 0),
        ]);

        $id = $this->solicitudDAO->insertar($solicitud);

        // Guardar archivo si viene
        if (!empty($archivo) && $archivo['error'] === 0) {
            $resultadoArchivo = $this->guardarArchivo($id, $archivo);
            if (!$resultadoArchivo['ok']) {
                return ['status' => 'error', 'mensaje' => $resultadoArchivo['mensaje']];
            }
        }

        return [
            'status'   => 'ok',
            'mensaje'  => 'Solicitud registrada correctamente.',
            'radicado' => $radicado,
            'id'       => $id,
        ];
    }

    public function listarTodas(): array {
        return $this->solicitudDAO->listar();
    }

    public function listarPorUsuario(int $usuario_id): array {
        return $this->solicitudDAO->listarPorUsuario($usuario_id);
    }

    public function buscarPorId(int $id): ?array {
        return $this->solicitudDAO->buscarPorId($id);
    }

    public function actualizar(int $id, int $estado_id, string $justificacion): array {
        if ($id <= 0) {
            return ['status' => 'error', 'mensaje' => 'ID de solicitud inválido.'];
        }

        $ok = $this->solicitudDAO->actualizar($id, $estado_id, trim($justificacion));

        return $ok
            ? ['status' => 'ok',    'mensaje' => 'Solicitud actualizada correctamente.']
            : ['status' => 'error', 'mensaje' => 'No se encontró la solicitud o no hubo cambios.'];
    }

    public function eliminar(int $id): array {
        if ($id <= 0) {
            return ['status' => 'error', 'mensaje' => 'ID de solicitud inválido.'];
        }

        $ok = $this->solicitudDAO->eliminar($id);

        return $ok
            ? ['status' => 'ok',    'mensaje' => 'Solicitud eliminada correctamente.']
            : ['status' => 'error', 'mensaje' => 'No se encontró la solicitud.'];
    }

    public function contarPorEstado(): array {
        $filas = $this->solicitudDAO->contarPorEstado();
        $resultado = ['total' => 0, 'pendiente' => 0, 'revision' => 0, 'aprobada' => 0, 'rechazada' => 0];

        foreach ($filas as $f) {
            $estado = strtolower($f['estado']);
            $total  = (int) $f['total'];
            $resultado['total'] += $total;

            if (str_contains($estado, 'pendiente'))  $resultado['pendiente']  = $total;
            if (str_contains($estado, 'revisi'))     $resultado['revision']   = $total;
            if (str_contains($estado, 'aprobad'))    $resultado['aprobada']   = $total;
            if (str_contains($estado, 'rechazad'))   $resultado['rechazada']  = $total;
        }

        return $resultado;
    }

    // ---- Métodos privados ----

    private function validarDatos(array $d): array {
        $errores = [];

        if (empty($d['fecha_solicitud']))  $errores[] = 'La fecha de solicitud es obligatoria.';
        if (empty($d['dependencia_id']))   $errores[] = 'Debes seleccionar una dependencia.';
        if (empty($d['usuario_id']))       $errores[] = 'Usuario no identificado.';

        if (!empty($d['fecha_solicitud']) && !strtotime($d['fecha_solicitud'])) {
            $errores[] = 'Fecha de solicitud inválida.';
        }

        if (!empty($d['valor_total']) && !is_numeric($d['valor_total'])) {
            $errores[] = 'El valor total debe ser numérico.';
        }

        return $errores;
    }

    private function guardarArchivo(int $solicitud_id, array $archivo): array {
        $extensionesPermitidas = ['pdf'];
        $maxTamano = 5 * 1024 * 1024; // 5MB

        $extension = strtolower(pathinfo($archivo['name'], PATHINFO_EXTENSION));

        if (!in_array($extension, $extensionesPermitidas)) {
            return ['ok' => false, 'mensaje' => 'Solo se permiten archivos PDF.'];
        }

        if ($archivo['size'] > $maxTamano) {
            return ['ok' => false, 'mensaje' => 'El archivo no puede superar 5MB.'];
        }

        $mime = mime_content_type($archivo['tmp_name']);
        if ($mime !== 'application/pdf') {
            return ['ok' => false, 'mensaje' => 'Tipo de archivo inválido.'];
        }

        // Nombre único para evitar sobrescritura
        $nombreUnico = uniqid('arch_', true) . '.' . $extension;
        $carpeta     = __DIR__ . '/../uploads/';

        if (!is_dir($carpeta)) mkdir($carpeta, 0755, true);

        $rutaFinal = $carpeta . $nombreUnico;

        if (!move_uploaded_file($archivo['tmp_name'], $rutaFinal)) {
            return ['ok' => false, 'mensaje' => 'Error al guardar el archivo en el servidor.'];
        }

        $this->archivoDAO->insertar(
            $solicitud_id,
            $archivo['name'],
            'uploads/' . $nombreUnico,
            $mime,
            $archivo['size']
        );

        return ['ok' => true];
    }
}
