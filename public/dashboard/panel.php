<?php
// ============================================================
// PRESENTACIÓN - Panel de Control
// ============================================================
session_start();

require_once __DIR__ . '/../negocio/AutenticacionService.php';
require_once __DIR__ . '/../negocio/SolicitudService.php';

$auth = new AutenticacionService();
$auth->requerirAutenticacion('../authme/login.php');

$service   = new SolicitudService();
$conteos   = $service->contarPorEstado();
$nombreUsuario = $_SESSION['usuario_nombre'] ?? $_SESSION['usuario_email'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700;800&family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link rel="shortcut icon" href="../assets/imgs/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="../presentacion/css/panel.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel De Control - CECAR</title>
</head>
<body>

    <header class="header">
        <div class="logo">
            <img src="../assets/imgs/logo-cecar.png" alt="Logo CECAR">
        </div>
        <div class="menu">
            <span class="menu-option">
                <img src="../assets/icons/home.svg" alt="home logo">
                <a href="../index.php">Inicio</a>
            </span>
            <span class="menu-option">
                <img src="../assets/icons/panel.svg" alt="panel logo">
                <a href="panel.php">Panel</a>
            </span>
            <span class="menu-option">
                <img src="../assets/icons/solicitudes.svg" alt="solicitudes logo">
                <a href="../solicitud/nueva_solicitud.php">Mis Solicitudes</a>
            </span>
            <span class="menu-option">
                <img src="../assets/icons/reportes.svg" alt="reportes logo">
                <a href="../solicitud/listado.php">Reportes</a>
            </span>
        </div>
        <a class="logout" href="../authme/logout.php">Cerrar sesión</a>
    </header>

    <main>
        <h1 class="title">
            <span class="decoration">Panel</span> de control
        </h1>

        <h2 class="welcome">Bienvenido, <?php echo htmlspecialchars($nombreUsuario); ?> 👋</h2>

        <p class="p-init">Gestiona las solicitudes de servicios de refrigeración desde el panel</p>

        <div class="metrics-grid">

            <div class="mcard white">
                <div class="mcard-top">
                    <div class="mcard-icon">
                        <img src="../assets/application-resources/form-icons/list.svg">
                    </div>
                    <span class="mcard-name">Solicitudes totales</span>
                </div>
                <p class="mcard-num"><?php echo $conteos['total']; ?></p>
            </div>

            <div class="mcard yellow">
                <div class="mcard-top">
                    <div class="mcard-icon">
                        <img src="../assets/application-resources/form-icons/clock.svg">
                    </div>
                    <span class="mcard-name">En revisión</span>
                </div>
                <p class="mcard-num"><?php echo $conteos['revision']; ?></p>
            </div>

            <div class="mcard green">
                <div class="mcard-top">
                    <div class="mcard-icon">
                        <img src="../assets/icons/check-icon.svg">
                    </div>
                    <span class="mcard-name">Aprobadas</span>
                </div>
                <p class="mcard-num"><?php echo $conteos['aprobada']; ?></p>
            </div>

            <div class="mcard red">
                <div class="mcard-top">
                    <div class="mcard-icon">
                        <img src="../assets/icons/block.svg">
                    </div>
                    <span class="mcard-name">Pendientes</span>
                </div>
                <p class="mcard-num"><?php echo $conteos['pendiente']; ?></p>
            </div>

        </div>
    </main>

</body>
</html>
