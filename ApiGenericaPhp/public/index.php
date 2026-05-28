<?php

require_once __DIR__ . '/../vendor/autoload.php';

use ApiGenericaPhp\Conexion\ProveedorConexion;                    
use ApiGenericaPhp\Repositorios\RepositorioLecturaMysqlMariaDB;   
use ApiGenericaPhp\Politicas\PoliticaTablasProhibidasDesdeConfig;  
use ApiGenericaPhp\Servicios\ServicioCrud;                         
use ApiGenericaPhp\Controllers\EntidadesController;                

// 1. Cargar configuración
$config = require __DIR__ . '/../config/config.php';

// 2. Inicializar proveedor de conexión
$proveedorConexion = new ProveedorConexion($config);
$proveedorBD = strtolower($proveedorConexion->getProveedorActual());

// 3. Seleccionar repositorio según motor de BD
switch ($proveedorBD) {
    case 'mariadb':  
    case 'mysql':    
        $repositorio = new RepositorioLecturaMysqlMariaDB($proveedorConexion);
        break;       

    default:
        http_response_code(500);   
        echo json_encode([
            'estado'  => 500,
            'mensaje' => "Proveedor de base de datos '{$proveedorBD}' no soportado.",
            'sugerencia' => "Proveedores soportados: MariaDB, MySQL. Verificar 'DatabaseProvider' en config/config.php",
        ]);
        exit;  
}

// 4. Políticas de seguridad
$politicaTablas = new PoliticaTablasProhibidasDesdeConfig($config);

// 5. Servicio CRUD genérico
$servicioCrud   = new ServicioCrud($repositorio, $politicaTablas);

// 6. Controlador genérico
$controlador    = new EntidadesController($servicioCrud);

// 7. Configuración CORS
$cors = $config['Cors'] ?? [];  
header('Access-Control-Allow-Origin: '  . ($cors['AllowedOrigins'] ?? '*'));
header('Access-Control-Allow-Methods: ' . ($cors['AllowedMethods'] ?? 'GET, POST, PUT, DELETE, OPTIONS'));
header('Access-Control-Allow-Headers: ' . ($cors['AllowedHeaders'] ?? 'Content-Type, Authorization'));

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);  
    exit;                     
}

// 8. Interpretar petición
$metodo = $_SERVER['REQUEST_METHOD']; 
$uri    = $_SERVER['REQUEST_URI'];    

$urlParts = parse_url($uri);
$path     = $urlParts['path'] ?? '/';  
$path     = rtrim($path, '/');
if (empty($path)) $path = '/';

$queryParams = [];
if (isset($urlParts['query'])) {
    parse_str($urlParts['query'], $queryParams);
}

$segmentos = array_values(array_filter(explode('/', $path)));
$segmentos = array_map('urldecode', $segmentos);

// Alinear segmentos si se corre bajo un subdirectorio en Apache (ej: /ApiGenericaPhp/public/api/...)
$apiIndex = array_search('api', $segmentos);
if ($apiIndex !== false && $apiIndex > 0) {
    $segmentos = array_slice($segmentos, $apiIndex);
    $path = '/' . implode('/', $segmentos);
}

$bodyJson = null;
if (in_array($metodo, ['POST', 'PUT'])) {        
    $rawBody = file_get_contents('php://input');   
    if (!empty($rawBody)) {                        
        $bodyJson = json_decode($rawBody, true);   
        if (json_last_error() !== JSON_ERROR_NONE) {
            http_response_code(400);               
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode([                     
                'estado'  => 400,
                'mensaje' => 'El body de la petición no es JSON válido.',
                'detalle' => json_last_error_msg(), 
            ], JSON_UNESCAPED_UNICODE);             
            exit;
        }
    }
}

// Parámetros adicionales
$esquema         = $queryParams['esquema'] ?? null;                              
$limite          = isset($queryParams['limite']) ? (int)$queryParams['limite'] : null; 
$camposEncriptar = $queryParams['camposEncriptar'] ?? null;                       

// 9. Rutas especiales
if ($path === '/' && $metodo === 'GET') {
    $controlador->inicio();  
    exit;                    
}

if ($path === '/docs' && $metodo === 'GET') {
    readfile(__DIR__ . '/docs.html');  
    exit;
}

if (count($segmentos) < 2 || $segmentos[0] !== 'api') {
    http_response_code(404);  
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode([
        'estado'    => 404,
        'mensaje'   => 'Ruta no encontrada.',
        'sugerencia' => 'Use /api/{tabla} para consultar tablas. Visite / para más información.',
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

// 10. CRUD genérico
$tabla = $segmentos[1];
$numSegmentos = count($segmentos);

switch ($metodo) {
    case 'GET':
        if ($numSegmentos === 2) {
            $controlador->listarAsync($tabla, $esquema, $limite);
        } elseif ($numSegmentos === 4) {
            $nombreClave = $segmentos[2];  
            $valor       = $segmentos[3];  
            $controlador->obtenerPorClaveAsync($tabla, $nombreClave, $valor, $esquema);
        } else {
            http_response_code(400);  
            echo json_encode(['estado' => 400, 'mensaje' => 'Formato de ruta GET inválido.'], JSON_UNESCAPED_UNICODE);
        }
        break;

    case 'POST':
        if ($numSegmentos === 3 && ($segmentos[2] === 'verificar-contrasena' || $segmentos[2] === 'verificarContrasena')) {
            $controlador->verificarContrasenaAsync($tabla, $bodyJson ?? [], $esquema);
        } elseif ($numSegmentos === 2) {
            $controlador->crearAsync($tabla, $bodyJson ?? [], $esquema, $camposEncriptar);
        } else {
            http_response_code(400);
            echo json_encode(['estado' => 400, 'mensaje' => 'Formato de ruta POST inválido.'], JSON_UNESCAPED_UNICODE);
        }
        break;

    case 'PUT':
        if ($numSegmentos === 4) {
            $nombreClave = $segmentos[2];  
            $valorClave  = $segmentos[3];  
            $controlador->actualizarAsync($tabla, $nombreClave, $valorClave, $bodyJson ?? [], $esquema, $camposEncriptar);
        } else {
            http_response_code(400);
            echo json_encode(['estado' => 400, 'mensaje' => 'Formato de ruta PUT inválido. Use: PUT /api/{tabla}/{clave}/{valor}'], JSON_UNESCAPED_UNICODE);
        }
        break;

    case 'DELETE':
        if ($numSegmentos === 4) {
            $nombreClave = $segmentos[2]; 
            $valorClave  = $segmentos[3];  
            $controlador->eliminarAsync($tabla, $nombreClave, $valorClave, $esquema);
        } else {
            http_response_code(400);
            echo json_encode(['estado' => 400, 'mensaje' => 'Formato de ruta DELETE inválido. Use: DELETE /api/{tabla}/{clave}/{valor}'], JSON_UNESCAPED_UNICODE);
        }
        break;

    default:
        http_response_code(405);  
        echo json_encode([
            'estado'  => 405,
            'mensaje' => "Método HTTP '{$metodo}' no permitido.",
            'metodosPermitidos' => ['GET', 'POST', 'PUT', 'DELETE'],
        ], JSON_UNESCAPED_UNICODE);
        break;
}

