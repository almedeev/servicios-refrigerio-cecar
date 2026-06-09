<?php
// ============================================================
// PRESENTACIÓN - Login
// ============================================================
session_start();

require_once __DIR__ . '/../negocio/AutenticacionService.php';

$auth  = new AutenticacionService();
$error = '';

// Si ya está autenticado, redirigir
if ($auth->estaAutenticado()) {
    header("Location: ../index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email    = trim($_POST['correo-institucional'] ?? '');
    $password = $_POST['contrasena'] ?? '';

    $resultado = $auth->login($email, $password);

    if ($resultado['ok']) {
        header("Location: ../index.php");
        exit();
    } else {
        $error = $resultado['mensaje'];
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../presentacion/css/style-login.css">
    <title>Login - CECAR</title>
</head>
<body>

    <header class="logo-cecar">
        <img src="../assets/imgs/logo-cecar.png" alt="Logo de Cecar">
    </header>

    <section class="login-box">
        <h2>Iniciar Sesión</h2>

        <?php if ($error): ?>
            <p class="error"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>

        <form class="form-informacion" method="POST">
            <div class="form-informacion-datos">
                <label for="correo-institucional">Correo Institucional</label>
                <input class="input-form" type="email" name="correo-institucional"
                       placeholder="example@cecar.edu.co" required>

                <label for="contrasena">Contraseña</label>
                <input class="input-form" type="password" name="contrasena"
                       placeholder="Contraseña" required>
            </div>
            <button class="btn-enviar" type="submit">Ingresar</button>
            <a class="olvidaste-clave" href="#">¿Olvidaste tu contraseña?</a>
        </form>
    </section>

</body>
</html>
