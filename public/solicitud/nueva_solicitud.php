<?php
// ============================================================
// PRESENTACIÓN - Nueva Solicitud
// ============================================================
session_start();

require_once __DIR__ . '/../negocio/AutenticacionService.php';

$auth = new AutenticacionService();
$auth->requerirAutenticacion('../authme/login.php');

$nombreCookie = $_COOKIE['nombre_usuario'] ?? '';
$tipoCookie   = $_COOKIE['tipo_solicitud'] ?? '';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700;800&family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link rel="shortcut icon" href="../assets/imgs/favicon.ico" type="image/x-icon">
    <meta charset="UTF-8">
    <title>Nueva Solicitud - CECAR</title>
    <link rel="stylesheet" href="../presentacion/css/nueva_solicitud.css">
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
            <a href="../dashboard/panel.php">Panel</a>
        </span>
        <span class="menu-option">
            <img src="../assets/icons/solicitudes.svg" alt="solicitudes logo">
            <a href="nueva_solicitud.php">Mis Solicitudes</a>
        </span>
        <span class="menu-option">
            <img src="../assets/icons/reportes.svg" alt="reportes logo">
            <a href="listado.php">Reportes</a>
        </span>
    </div>
    <a class="logout" href="../authme/logout.php">Cerrar sesión</a>
</header>

<div class="layout">

    <!-- LADO IZQUIERDO -->
    <section class="left-panel">
        <div class="left-panel-header">
            <span class="left-panel-icon">
                <img src="../assets/application-resources/form-icons/file-plus.svg" alt="File Plus icon">
            </span>
            <h1 class="left-panel-h1">Nueva<br/>
                <span class="left-panel-span">Solicitud</span>
            </h1>
        </div>

        <p class="left-panel-description">
            Este formulario permite registrar la información general de la solicitud. Aquí debes ingresar los datos básicos del solicitante,
            incluyendo su nombre, área o dependencia y el tipo de solicitud que desea realizar. Esta información es fundamental para identificar
            y clasificar correctamente cada registro dentro del sistema.
        </p>

        <div class="icon-container">
            <img class="icon icon1" src="../assets/application-resources/forms-bro.svg" alt="Ilustración">
            <img class="icon icon2" src="../assets/application-resources/fill-out-bro.svg" alt="Fill Out Bro">
            <img class="icon icon3" src="../assets/application-resources/id-card-bro.svg" alt="File Upload Bro">
        </div>
    </section>

    <div class="form-card">

        <div class="form-header">
            <div class="form-header-icon">
                <img src="../assets/application-resources/form-icons/file-plus.svg">
            </div>
            <div class="form-header-text">
                <h2>Formulario de solicitud</h2>
                <p>Todos los campos marcados con * son obligatorios.</p>
            </div>
        </div>

        <form id="formSolicitud" enctype="multipart/form-data">

            <!-- PASO 1 -->
            <div class="step active">

                <div class="input-group">
                    <div class="input-group-icon">
                        <img src="../assets/application-resources/form-icons/calendar.svg">
                    </div>
                    <label>Fecha de solicitud *
                        <div class="input-form">
                            <input type="date" name="fecha_solicitud" required>
                        </div>
                    </label>
                </div>

                <div class="input-group">
                    <div class="input-group-icon">
                        <img src="../assets/application-resources/form-icons/found.svg">
                    </div>
                    <label>Fondo *
                        <div class="input-form">
                            <select name="fondo" required>
                                <option value="" disabled selected>Selecciona un fondo</option>
                                <option value="3">Fondo 3</option>
                                <option value="4">Fondo 4</option>
                                <option value="7">Fondo 7</option>
                            </select>
                        </div>
                    </label>
                </div>

                <div class="input-group">
                    <div class="input-group-icon">
                        <img src="../assets/application-resources/form-icons/cost-center.svg">
                    </div>
                    <label>Centro de costos *
                        <div class="input-form">
                            <input type="text" name="centro_costo" placeholder="Ingresa el centro de costo" required>
                        </div>
                    </label>
                </div>

                <div class="input-group">
                    <div class="input-group-icon">
                        <img src="../assets/application-resources/form-icons/number.svg">
                    </div>
                    <label>N° centro de costos *
                        <div class="input-form">
                            <input type="number" name="numero_centro_costo" placeholder="Número de centro de costo" min="0" step="1" required>
                        </div>
                    </label>
                </div>

                <div class="input-group">
                    <div class="input-group-icon">
                        <img src="../assets/application-resources/form-icons/function.svg">
                    </div>
                    <label>Función *
                        <div class="input-form">
                            <input type="text" name="funcion" placeholder="Ingresa la función" required>
                        </div>
                    </label>
                </div>

                <div class="input-group">
                    <div class="input-group-icon">
                        <img src="../assets/application-resources/form-icons/number-function.svg">
                    </div>
                    <label>N° función *
                        <div class="input-form">
                            <input type="number" name="numero_funcion" placeholder="Número de función" min="0" step="1" required>
                        </div>
                    </label>
                </div>

                <div class="input-group">
                    <div class="input-group-icon">
                        <img src="../assets/application-resources/form-icons/money.svg">
                    </div>
                    <label>Disponibilidad presupuestal *
                        <div class="input-form">
                            <span class="prefix">$</span>
                            <input type="text" id="valor_total" name="valor_total" placeholder="Ingresa la cantidad" required>
                        </div>
                    </label>
                </div>

                <button type="button" class="btn-primary next">Siguiente</button>
            </div>

            <!-- PASO 2 -->
            <div class="step">

                <div class="input-group">
                    <div class="input-group-icon">
                        <img src="../assets/application-resources/form-icons/user.svg">
                    </div>
                    <label>Nombre completo *
                        <div class="input-form">
                            <input type="text" name="nombre_completo" placeholder="Ingresa tu nombre completo" required>
                        </div>
                    </label>
                </div>

                <div class="input-group">
                    <div class="input-group-icon">
                        <img src="../assets/application-resources/form-icons/email.svg">
                    </div>
                    <label>E-mail *
                        <div class="input-form">
                            <input type="email" name="email" placeholder="ejemplo@cecar.edu.co" required>
                        </div>
                    </label>
                </div>

                <div class="input-group">
                    <div class="input-group-icon">
                        <img src="../assets/application-resources/form-icons/phone.svg">
                    </div>
                    <label>Teléfono de contacto *
                        <div class="input-form">
                            <input type="tel" name="telefono" placeholder="Ingresa tu teléfono" pattern="[0-9]{7,15}" required>
                        </div>
                    </label>
                </div>

                <div class="input-group">
                    <div class="input-group-icon">
                        <img src="../assets/application-resources/form-icons/cargo.svg">
                    </div>
                    <label>Cargo *
                        <div class="input-form">
                            <input type="text" name="cargo" placeholder="Ingresa tu cargo" required>
                        </div>
                    </label>
                </div>

                <div class="input-group">
                    <div class="input-group-icon">
                        <img src="../assets/application-resources/form-icons/notes.svg">
                    </div>
                    <label>Dependencia *
                        <div class="input-form">
                            <select name="dependencia_id" required>
                                <option value="" disabled selected>Selecciona una dependencia</option>
                                <!-- Cargado dinámicamente por AJAX -->
                            </select>
                        </div>
                    </label>
                </div>

                <button type="button" class="btn-secondary prev">Atrás</button>
                <button type="button" class="btn-primary next">Siguiente</button>
            </div>

            <!-- PASO 3 -->
            <div class="step">

                <div class="input-group">
                    <div class="input-group-icon">
                        <img src="../assets/application-resources/form-icons/description.svg">
                    </div>
                    <label>Nombre del evento *
                        <div class="input-form">
                            <input type="text" name="nombre_evento" placeholder="Ingresa el nombre del evento" required>
                        </div>
                    </label>
                </div>

                <div class="input-group">
                    <div class="input-group-icon">
                        <img src="../assets/application-resources/form-icons/location.svg">
                    </div>
                    <label>Lugar del evento *
                        <div class="input-form">
                            <input type="text" name="lugar_evento" placeholder="Ingresa el lugar del evento" required>
                        </div>
                    </label>
                </div>

                <div class="input-group">
                    <div class="input-group-icon">
                        <img src="../assets/application-resources/form-icons/number.svg">
                    </div>
                    <label>Días del evento *
                        <div class="input-form">
                            <input type="number" name="dias_evento" placeholder="Número de días" min="1" step="1" required>
                        </div>
                    </label>
                </div>

                <div class="input-group">
                    <div class="input-group-icon">
                        <img src="../assets/application-resources/form-icons/calendar.svg">
                    </div>
                    <label>Fecha de inicio *
                        <div class="input-form">
                            <input type="date" name="fecha_inicio" required>
                        </div>
                    </label>
                </div>

                <div class="input-group">
                    <div class="input-group-icon">
                        <img src="../assets/application-resources/form-icons/calendar.svg">
                    </div>
                    <label>Fecha de finalización *
                        <div class="input-form">
                            <input type="date" name="fecha_finalizacion" required>
                        </div>
                    </label>
                </div>

                <button type="button" class="btn-secondary prev">Atrás</button>
                <button type="button" class="btn-primary next">Siguiente</button>
            </div>

            <!-- PASO 4 -->
            <div class="step">

                <div class="input-group">
                    <div class="input-group-icon">
                        <img src="../assets/application-resources/form-icons/archive.svg">
                    </div>
                    <label>Adjuntar PDF <span class="optional">(opcional)</span>
                        <div class="file-input">
                            <span>Subir archivo
                                <input type="file" id="archivo" name="archivo" accept=".pdf">
                            </span>
                        </div>
                    </label>
                </div>

                <div class="input-group">
                    <div class="input-group-icon">
                        <img src="../assets/application-resources/form-icons/calendar.svg">
                    </div>
                    <label>Día *
                        <div class="input-form">
                            <input type="date" name="dia" required>
                        </div>
                    </label>
                </div>

                <div class="input-group">
                    <div class="input-group-icon">
                        <img src="../assets/application-resources/form-icons/clock.svg">
                    </div>
                    <label>Hora *
                        <div class="input-form">
                            <input type="time" name="hora" required>
                        </div>
                    </label>
                </div>

                <div class="input-group">
                    <div class="input-group-icon">
                        <img src="../assets/application-resources/form-icons/number.svg">
                    </div>
                    <label>Cantidad *
                        <div class="input-form">
                            <input type="number" name="cantidad" placeholder="Ingresa la cantidad" min="1" step="1" required>
                        </div>
                    </label>
                </div>

                <div class="input-group">
                    <div class="input-group-icon">
                        <img src="../assets/application-resources/form-icons/description.svg">
                    </div>
                    <label>Alimentos *
                        <div class="input-form textarea">
                            <textarea name="alimentos" placeholder="Describe los alimentos requeridos" required></textarea>
                        </div>
                    </label>
                </div>

                <div class="input-group">
                    <div class="input-group-icon">
                        <img src="../assets/application-resources/form-icons/description.svg">
                    </div>
                    <label>Bebidas *
                        <div class="input-form textarea">
                            <textarea name="bebidas" placeholder="Describe las bebidas requeridas" required></textarea>
                        </div>
                    </label>
                </div>

                <div class="input-group">
                    <div class="input-group-icon">
                        <img src="../assets/application-resources/form-icons/list.svg">
                    </div>
                    <label>Tipo de solicitud *
                        <div class="input-form">
                            <select name="tipo_solicitud" required>
                                <option value="" disabled selected>Selecciona un tipo</option>
                                <option value="Desayuno">Desayuno</option>
                                <option value="Refrigerio">Refrigerio</option>
                                <option value="Almuerzo">Almuerzo</option>
                                <option value="Cena">Cena</option>
                            </select>
                        </div>
                    </label>
                </div>

                <div class="input-group">
                    <div class="input-group-icon">
                        <img src="../assets/application-resources/form-icons/user.svg">
                    </div>
                    <label>Requiere meseros *
                        <div class="input-form">
                            <select name="requiere_meseros" required>
                                <option value="" disabled selected>Selecciona una opción</option>
                                <option value="Si">Sí</option>
                                <option value="No">No</option>
                            </select>
                        </div>
                    </label>
                </div>

                <div class="input-group">
                    <div class="input-group-icon">
                        <img src="../assets/application-resources/form-icons/location.svg">
                    </div>
                    <label>Lugar de entrega *
                        <div class="input-form">
                            <input type="text" name="lugar_entrega" placeholder="Ingresa el lugar de entrega" required>
                        </div>
                    </label>
                </div>

                <div class="input-group">
                    <div class="input-group-icon">
                        <img src="../assets/application-resources/form-icons/description.svg">
                    </div>
                    <label>Justificación
                        <div class="input-form textarea">
                            <textarea name="justificacion" placeholder="Justificación adicional (opcional)"></textarea>
                        </div>
                    </label>
                </div>

                <button type="button" class="btn-secondary prev">Atrás</button>
                <button type="submit" class="btn-primary">
                    <img src="../assets/application-resources/form-icons/next.svg"> Enviar solicitud
                </button>
            </div>

            <div id="mensaje"></div>
        </form>
    </div>
</div>

<script src="../presentacion/js/resources.js"></script>
<script src="../presentacion/js/nueva_solicitud.js"></script>
</body>
</html>
