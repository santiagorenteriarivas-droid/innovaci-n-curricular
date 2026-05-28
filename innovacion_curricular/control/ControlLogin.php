<?php

require_once __DIR__ . '/Validador.php';

class ControlLogin {

    /**
     * Procesa el login del usuario.
     * Valida credenciales contra la API y gestiona la sesión.
     *
     * @return array Respuesta con estado y mensaje
     */
    public static function procesarLogin(): array {
        // Verificar que sea una petición POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return [
                'estado' => false,
                'mensaje' => 'Método de petición inválido.',
                'error' => $error ?? null
            ];
        }

        // Obtener datos del formulario
        $email = $_POST['email'] ?? '';
        $contrasena = $_POST['contrasena'] ?? '';

        // Validar que los campos no estén vacíos
        if (empty($email) || empty($contrasena)) {
            return [
                'estado' => false,
                'mensaje' => 'Por favor, ingresa correo y contraseña.',
                'error' => null
            ];
        }

        // Validar formato del email
        if (!Validador::validarEmail($email)) {
            return [
                'estado' => false,
                'mensaje' => 'El correo ingresado no tiene un formato válido.',
                'error' => null
            ];
        }

        // Sanitizar email (seguridad adicional)
        $email = Validador::sanitizarString($email);

        // Llamar a la API para verificar credenciales
        $respuestaAPI = self::verificarCredencialesEnAPI($email, $contrasena);

        // Si la API no responde correctamente
        if (!$respuestaAPI['exito']) {
            return [
                'estado' => false,
                'mensaje' => $respuestaAPI['mensaje'] ?? 'Error al conectar con el servidor.',
                'error' => $respuestaAPI['detalles'] ?? null
            ];
        }

        // Si las credenciales son válidas, crear sesión
        if ($respuestaAPI['credencialesValidas']) {
            return self::crearSesion($respuestaAPI['datosUsuario']);
        }

        return [
            'estado' => false,
            'mensaje' => 'Correo o contraseña incorrectos.',
            'error' => null
        ];
    }

    /**
     * Realiza la petición POST a la API para verificar credenciales.
     *
     * @param string $email Email del usuario
     * @param string $contrasena Contraseña en texto plano
     * @return array Respuesta con estado de la verificación
     */
    private static function verificarCredencialesEnAPI(string $email, string $contrasena): array {
        try {
            // Obtener la URL base de la API (ajusta según tu configuración)
            $urlAPI = $_ENV['API_URL'] ?? 'http://localhost/ApiGenericaPhp/public';
            $endpoint = $urlAPI . '/api/verificarContrasena';

            // Armar el JSON con la estructura exacta esperada
            $datosJSON = json_encode([
                'tabla' => 'usuario',
                'datos' => [
                    'campoUsuario' => 'correo',
                    'campoContrasena' => 'contrasena',
                    'valorUsuario' => $email,
                    'valorContrasena' => $contrasena
                ]
            ]);

            // Configurar la petición cURL
            $ch = curl_init();
            curl_setopt_array($ch, [
                CURLOPT_URL => $endpoint,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_TIMEOUT => 10,
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => $datosJSON,
                CURLOPT_HTTPHEADER => [
                    'Content-Type: application/json',
                    'Content-Length: ' . strlen($datosJSON)
                ]
            ]);

            // Ejecutar la petición
            $respuesta = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $error = curl_error($ch);
            curl_close($ch);

            // Manejar errores de conexión
            if ($error) {
                return [
                    'exito' => false,
                    'mensaje' => 'Error de conexión con el servidor.',
                    'detalles' => $error
                ];
            }

            // Parsear respuesta JSON
            $datosRespuesta = json_decode($respuesta, true);

            // Verificar código HTTP
            if ($httpCode === 200 && !empty($datosRespuesta['datos'])) {
                return [
                    'exito' => true,
                    'credencialesValidas' => true,
                    'datosUsuario' => $datosRespuesta['datos'][0] ?? $datosRespuesta['datos']
                ];
            } elseif ($httpCode === 401) {
                return [
                    'exito' => true,
                    'credencialesValidas' => false,
                    'mensaje' => 'Credenciales no válidas.'
                ];
            } else {
                return [
                    'exito' => false,
                    'mensaje' => 'Error en la respuesta del servidor.',
                    'detalles' => $datosRespuesta['mensaje'] ?? 'Desconocido'
                ];
            }

        } catch (\Throwable $e) {
            return [
                'exito' => false,
                'mensaje' => 'Error al procesar la solicitud.',
                'detalles' => $e->getMessage()
            ];
        }
    }

    /**
     * Crea la sesión del usuario si las credenciales son válidas.
     *
     * @param array $datosUsuario Datos del usuario desde la API
     * @return array Respuesta con estado de la sesión
     */
    private static function crearSesion(array $datosUsuario): array {
        try {
            // Iniciar sesión si no está iniciada
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }

            // Guardar datos en la sesión
            $_SESSION['usuario_id'] = $datosUsuario['id'] ?? null;
            $_SESSION['usuario_correo'] = Validador::sanitizarString($datosUsuario['correo'] ?? '');
            $_SESSION['usuario_rol'] = Validador::sanitizarString($datosUsuario['rol'] ?? 'usuario');
            $_SESSION['usuario_logueado'] = true;

            return [
                'estado' => true,
                'mensaje' => 'Login exitoso. Redirigiendo...',
                'error' => null,
                'redirigir' => 'base.php'
            ];

        } catch (\Throwable $e) {
            return [
                'estado' => false,
                'mensaje' => 'Error al crear la sesión.',
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Cierra la sesión del usuario.
     *
     * @return array Respuesta con estado del cierre de sesión
     */
    public static function cerrarSesion(): array {
        try {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }

            session_destroy();

            return [
                'estado' => true,
                'mensaje' => 'Sesión cerrada correctamente.',
                'error' => null
            ];

        } catch (\Throwable $e) {
            return [
                'estado' => false,
                'mensaje' => 'Error al cerrar sesión.',
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Verifica si el usuario está autenticado.
     *
     * @return bool True si está autenticado, false en caso contrario
     */
    public static function estaAutenticado(): bool {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        return isset($_SESSION['usuario_logueado']) && $_SESSION['usuario_logueado'] === true;
    }

    /**
     * Obtiene los datos del usuario autenticado.
     *
     * @return array|null Array con datos del usuario o null si no está autenticado
     */
    public static function obtenerDatosUsuario(): ?array {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!self::estaAutenticado()) {
            return null;
        }

        return [
            'id' => $_SESSION['usuario_id'] ?? null,
            'correo' => $_SESSION['usuario_correo'] ?? null,
            'rol' => $_SESSION['usuario_rol'] ?? null
        ];
    }
}

// Procesar login si es POST
$respuestaLogin = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $respuestaLogin = ControlLogin::procesarLogin();

    // Si el login fue exitoso, redirigir
    if ($respuestaLogin['estado'] === true && !empty($respuestaLogin['redirigir'])) {
        header('Location: ' . $respuestaLogin['redirigir']);
        exit;
    }
}

?>