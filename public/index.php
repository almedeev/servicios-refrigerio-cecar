<?php
// ============================================================
// PRESENTACIÓN - Inicio
// ============================================================
session_start();

require_once __DIR__ . '/negocio/AutenticacionService.php';

$auth = new AutenticacionService();
$auth->requerirAutenticacion('authme/login.php');

$nombreUsuario = $_SESSION['usuario_nombre'] ?? $_SESSION['usuario_email'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700;800&family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link rel="shortcut icon" href="assets/imgs/favicon.ico" type="image/x-icon">
    <meta charset="UTF-8">
    <link rel="stylesheet" href="presentacion/css/style.css">
    <title>Gestión de servicios de refrigeración - CECAR</title>
</head>
<body>

    <header class="header">
        <div class="logo">
            <img src="assets/imgs/logo-cecar.png" alt="Logo CECAR">
        </div>
        <div class="menu">
            <span class="menu-option">
                <img src="assets/icons/home.svg" alt="home logo">
                <a href="index.php">Inicio</a>
            </span>
            <span class="menu-option">
                <img src="assets/icons/panel.svg" alt="panel logo">
                <a href="dashboard/panel.php">Panel</a>
            </span>
            <span class="menu-option">
                <img src="assets/icons/solicitudes.svg" alt="solicitudes logo">
                <a href="solicitud/nueva_solicitud.php">Mis Solicitudes</a>
            </span>
            <span class="menu-option">
                <img src="assets/icons/reportes.svg" alt="reportes logo">
                <a href="solicitud/listado.php">Reportes</a>
            </span>
        </div>
        <a class="logout" href="authme/logout.php">Cerrar sesión</a>
    </header>

    <section class="service-management-info">
        <div class="info">
            <h1>
                Gestión de <br/><span class="decoration">Servicios</span> de<br/>
                Refrigeración
            </h1>
            <p>
                Administra, supervisa y optimiza los servicios técnicos de refrigeración de manera eficiente.
                Desde este panel de la <span class="stronger">Corporación Universitaria del Caribe CECAR,</span>
                podrás gestionar información clave, mejorar procesos y garantizar un control completo en tiempo real.
            </p>
            <div class="buttons">
                <a href="solicitud/nueva_solicitud.php" class="btn btn-primary">+ Nueva solicitud</a>
                <a href="solicitud/listado.php" class="btn btn-secondary">Ver listado</a>
            </div>
        </div>
        <img class="u1-image" src="assets/imgs/u1-privada.png" alt="La U privada #1 de Sucre">
    </section>

    <span class="welcome">Bienvenido, <?php echo htmlspecialchars($nombreUsuario); ?> 👋</span>

    <main class="dashboard">
        <section class="main-content">
            <header class="main-header">
                <h2>Mis Solicitudes</h2>
            </header>

            <div class="content active" id="dynamic-content">
                <nav class="status-header">
                    <div class="status active" data-tab="all">Todas</div>
                    <div class="status review" data-tab="review">En revisión</div>
                    <div class="status approved" data-tab="approved">Aprobadas</div>
                    <div class="status pending" data-tab="pending">Pendientes</div>
                </nav>

                <div class="status-dynamic" id="status-box">
                    <h3 id="status-title">Todas</h3>
                    <p id="status-description">Aquí van todas las solicitudes.</p>
                </div>
            </div>
        </section>

        <aside class="sidebar-right">
            <div class="sidebar-card">
                <h3 class="activity-recienty">Actividad reciente</h3>
                <div id="actividades-recientes" class="activities">
                    <!-- Se llena con AJAX -->
                </div>
            </div>
        </aside>

        <script src="presentacion/js/script.js"></script>
    </main>

</body>
</html>
