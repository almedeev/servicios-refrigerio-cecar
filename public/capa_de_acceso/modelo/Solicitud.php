<?php
// ============================================================
// CAPA DE ACCESO - Modelo: Solicitud
// ============================================================

class Solicitud {
    public int    $id;
    public string $numero_radicado;
    public int    $usuario_id;
    public int    $dependencia_id;
    public int    $estado_id;
    public string $fecha_solicitud;
    public string $justificacion;
    public float  $valor_total;

    public function __construct(array $datos = []) {
        $this->id              = $datos['id']              ?? 0;
        $this->numero_radicado = $datos['numero_radicado'] ?? '';
        $this->usuario_id      = $datos['usuario_id']      ?? 0;
        $this->dependencia_id  = $datos['dependencia_id']  ?? 0;
        $this->estado_id       = $datos['estado_id']       ?? 1;
        $this->fecha_solicitud = $datos['fecha_solicitud'] ?? '';
        $this->justificacion   = $datos['justificacion']   ?? '';
        $this->valor_total     = $datos['valor_total']     ?? 0.0;
    }
}
