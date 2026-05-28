<?php
ini_set('display_errors', 1);
ini_set('display_errors_startup', 1);
error_reporting(E_ALL);

// Mantenemos la inclusión por si otras partes del archivo la necesitan
require_once __DIR__ . '/../../../control/ControlLogin.php';

$error = null;

if (isset($_POST['btnIniciarSesion'])) {
    $email = $_POST['email'] ?? '';
    $contrasena = $_POST['contrasena'] ?? '';
    
    // 1. Validar campos vacíos primero
    if (empty($email) || empty($contrasena)) {
        $error = "Por favor, ingresa correo y contraseña.";
    } 
    // 2. Control de accesos locales (Usuarios de prueba de tu plantilla)
    elseif (($email === 'docente@correo.com' && $contrasena === 'docente123') || 
            ($email === 'admin@correo.com' && $contrasena === 'admin123')) {
        
        // Iniciamos la sesión de PHP de forma segura
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Guardamos los datos simulados en la sesión
        $_SESSION['usuario'] = $email;
        $_SESSION['rol'] = ($email === 'admin@correo.com') ? 'admin' : 'docente';
        
        // Redirección directa al Dashboard del sistema
        header("Location: ../../../index.php?page=dashboard");
        exit();
    } 
    // 3. Si las credenciales no coinciden con los usuarios de prueba
    else {
        $error = "Usuario o contraseña incorrectos.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistema de Innovación Curricular</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
            padding: 40px;
            max-width: 400px;
            width: 100%;
        }
        .login-icon {
            font-size: 4rem;
            color: #667eea;
        }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="text-center mb-4">
            <i class="bi bi-mortarboard login-icon"></i>
            <h3 class="mt-3">Innovación Curricular</h3>
            <p class="text-muted">Ingresa tus credenciales</p>
        </div>

        <?php if (!empty($error)): ?>
    <div class="alert alert-danger d-flex align-items-center gap-2">
        <i class="bi bi-exclamation-triangle"></i>
        <span><?= $error ?></span>
    </div>
<?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label for="email" class="form-label">Correo Electrónico</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                    <input type="email" class="form-control" id="email" name="email" required autofocus>
                </div>
            </div>

            <div class="mb-3">
                <label for="contrasena" class="form-label">Contraseña</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-lock"></i></span>
                    <input type="password" class="form-control" id="contrasena" name="contrasena" required>
                </div>
            </div>

            <button type="submit" name="btnIniciarSesion" class="btn btn-primary w-100">
                <i class="bi bi-box-arrow-in-right"></i> Iniciar Sesión
            </button>
        </form>

        <div class="text-center mt-4">
            <small class="text-muted">
                Usuarios de prueba:<br>
                docente@correo.com / docente123<br>
                admin@correo.com / admin123
            </small>
        </div>
    </div>
</body>
</html>