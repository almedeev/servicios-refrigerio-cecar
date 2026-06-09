<?php
// ============================================================
// CAPA DE ACCESO - Modelo: Usuario
// ============================================================

class Usuario {
    public int    $id;
    public string $nombre;
    public string $apellido;
    public string $email;
    public string $password_hash;
    public string $cargo;
    public int    $activo;

    public function __construct(array $datos = []) {
        $this->id            = $datos['id']            ?? 0;
        $this->nombre        = $datos['nombre']        ?? '';
        $this->apellido      = $datos['apellido']      ?? '';
        $this->email         = $datos['email']         ?? '';
        $this->password_hash = $datos['password_hash'] ?? '';
        $this->cargo         = $datos['cargo']         ?? '';
        $this->activo        = $datos['activo']        ?? 1;
    }
}
