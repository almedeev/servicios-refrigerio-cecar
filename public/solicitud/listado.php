<?php
// ============================================================
// PRESENTACIÓN - Listado de Solicitudes
// ============================================================
session_start();

require_once __DIR__ . '/../negocio/AutenticacionService.php';
require_once __DIR__ . '/../negocio/SolicitudService.php';

$auth = new AutenticacionService();
$auth->requerirAutenticacion('../authme/login.php');

$service     = new SolicitudService();
$solicitudes = $service->listarTodas();
$nombreUsuario = $_SESSION['usuario_nombre'] ?? $_SESSION['usuario_email'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700;800&family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link rel="shortcut icon" href="../assets/imgs/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="../presentacion/css/panel.css">
    <link rel="stylesheet" href="../presentacion/css/listado.css">
    <title>Listado de Solicitudes - CECAR</title>
</head>
<body>

<header class="header">
    <div class="logo">
        <img src="../assets/imgs/logo-cecar.png" alt="Logo CECAR">
    </div>
    <div class="menu">
        <span class="menu-option">
            <img src="../assets/icons/home.svg"><a href="../index.php">Inicio</a>
        </span>
        <span class="menu-option">
            <img src="../assets/icons/panel.svg"><a href="../dashboard/panel.php">Panel</a>
        </span>
        <span class="menu-option">
            <img src="../assets/icons/solicitudes.svg"><a href="nueva_solicitud.php">Mis Solicitudes</a>
        </span>
        <span class="menu-option">
            <img src="../assets/icons/reportes.svg"><a href="listado.php">Reportes</a>
        </span>
    </div>
    <a class="logout" href="../authme/logout.php">Cerrar sesión</a>
</header>

<main style="padding: 2rem;">
    <h2>LISTADO DE SOLICITUDES</h2>

    <!-- Buscador dinámico -->
    <div style="margin-bottom: 1rem;">
        <input type="text" id="buscador" placeholder="🔍 Buscar por solicitante, estado o dependencia..."
               style="padding: 0.5rem 1rem; width: 350px; border-radius: 8px; border: 1px solid #ccc; font-family: 'Poppins', sans-serif;">
    </div>

    <div id="mensaje-accion" style="display:none; padding: 0.5rem 1rem; border-radius: 8px; margin-bottom: 1rem;"></div>

    <div style="overflow-x:auto;">
    <table id="tablaSolicitudes" border="1" cellpadding="10" cellspacing="0" style="width:100%; border-collapse:collapse;">
        <thead>
            <tr class="tr-head">
                <th>#</th>
                <th>Radicado</th>
                <th>Solicitante</th>
                <th>Dependencia</th>
                <th>Fecha</th>
                <th>Estado</th>
                <th>Valor Total</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody id="cuerpoTabla">
        <?php if (!empty($solicitudes)): ?>
            <?php foreach ($solicitudes as $s): ?>
            <tr data-id="<?php echo $s['id']; ?>">
                <td><?php echo $s['id']; ?></td>
                <td><?php echo htmlspecialchars($s['numero_radicado']); ?></td>
                <td><?php echo htmlspecialchars($s['solicitante']); ?></td>
                <td><?php echo htmlspecialchars($s['dependencia']); ?></td>
                <td><?php echo htmlspecialchars($s['fecha_solicitud']); ?></td>
                <td>
                    <span class="estado-badge estado-<?php echo strtolower(str_replace(' ', '-', $s['estado'])); ?>">
                        <?php echo htmlspecialchars($s['estado']); ?>
                    </span>
                </td>
                <td>$ <?php echo number_format($s['valor_total'], 2); ?></td>
                <td>
                    <button class="btn-editar" onclick="abrirModalEditar(<?php echo $s['id']; ?>, '<?php echo htmlspecialchars($s['estado']); ?>', '<?php echo htmlspecialchars(addslashes($s['justificacion'])); ?>')">
                        Editar
                    </button>
                    <button class="btn-eliminar" onclick="eliminarSolicitud(<?php echo $s['id']; ?>)">
                        Eliminar
                    </button>
                </td>
            </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr><td colspan="8" style="text-align:center;">No hay solicitudes registradas.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
    </div>
</main>

<!-- Modal Editar -->
<div id="modalEditar" style="display:none; position:fixed; top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.5);z-index:1000;">
    <div style="background:#fff; margin:10% auto; padding:2rem; border-radius:12px; max-width:450px; font-family:'Poppins',sans-serif;">
        <h3>Editar Solicitud</h3>
        <input type="hidden" id="edit_id">

        <label>Estado:</label>
        <select id="edit_estado" style="width:100%; padding:0.5rem; margin-bottom:1rem; border-radius:6px; border:1px solid #ccc;">
            <option value="1">Pendiente</option>
            <option value="2">En revision</option>
            <option value="3">Aprobada</option>
            <option value="4">Rechazada</option>
        </select>

        <label>Justificación:</label>
        <textarea id="edit_justificacion" rows="4" style="width:100%; padding:0.5rem; border-radius:6px; border:1px solid #ccc; margin-bottom:1rem;"></textarea>

        <button onclick="guardarEdicion()" style="background:#2563eb;color:#fff;padding:0.5rem 1.5rem;border:none;border-radius:8px;cursor:pointer;">Guardar</button>
        <button onclick="cerrarModal()" style="margin-left:1rem;padding:0.5rem 1rem;border:none;border-radius:8px;cursor:pointer;">Cancelar</button>
    </div>
</div>

<script>
// Buscador dinámico (AJAX)
document.getElementById('buscador').addEventListener('input', function() {
    const filtro = this.value.toLowerCase();
    const filas = document.querySelectorAll('#cuerpoTabla tr');
    filas.forEach(fila => {
        const texto = fila.textContent.toLowerCase();
        fila.style.display = texto.includes(filtro) ? '' : 'none';
    });
});

// Eliminar solicitud
function eliminarSolicitud(id) {
    if (!confirm('¿Estás seguro de eliminar esta solicitud? Esta acción no se puede deshacer.')) return;

    fetch('../negocio/api.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({ accion: 'eliminar', id: id })
    })
    .then(r => r.json())
    .then(data => {
        mostrarMensaje(data.mensaje, data.status === 'ok' ? 'green' : 'red');
        if (data.status === 'ok') {
            document.querySelector(`tr[data-id="${id}"]`)?.remove();
        }
    })
    .catch(() => mostrarMensaje('Error de conexión', 'red'));
}

// Abrir modal editar
function abrirModalEditar(id, estado, justificacion) {
    document.getElementById('edit_id').value = id;
    document.getElementById('edit_justificacion').value = justificacion;

    const mapaEstados = {'Pendiente':'1','En revision':'2','Aprobada':'3','Rechazada':'4'};
    document.getElementById('edit_estado').value = mapaEstados[estado] || '1';

    document.getElementById('modalEditar').style.display = 'block';
}

function cerrarModal() {
    document.getElementById('modalEditar').style.display = 'none';
}

function guardarEdicion() {
    const id           = document.getElementById('edit_id').value;
    const estado_id    = document.getElementById('edit_estado').value;
    const justificacion= document.getElementById('edit_justificacion').value;

    fetch('../negocio/api.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({ accion: 'actualizar', id: parseInt(id), estado_id: parseInt(estado_id), justificacion })
    })
    .then(r => r.json())
    .then(data => {
        mostrarMensaje(data.mensaje, data.status === 'ok' ? 'green' : 'red');
        if (data.status === 'ok') {
            cerrarModal();
            setTimeout(() => location.reload(), 1000);
        }
    })
    .catch(() => mostrarMensaje('Error de conexión', 'red'));
}

function mostrarMensaje(texto, color) {
    const div = document.getElementById('mensaje-accion');
    div.textContent = texto;
    div.style.background = color === 'green' ? '#d1fae5' : '#fee2e2';
    div.style.color = color === 'green' ? '#065f46' : '#991b1b';
    div.style.display = 'block';
    setTimeout(() => div.style.display = 'none', 3000);
}
</script>

</body>
</html>
