<?php
ini_set('display_errors', 1);
ini_set('display_errors_startup', 1);
error_reporting(E_ALL);

// 1. Traemos la conexión de la base de datos
require_once __DIR__ . '/../../control/ControlConexionPdo.php';

$titulo = 'Inicio';

// Agregamos TODAS las claves que tu HTML pide abajo para que no salten advertencias
$stats = [
    'aliado' => 0,
    'area_conocimiento' => 0,
    'aspecto_normativo' => 0,
    'car_innovacion' => 0,
    'enfoque' => 0,
    'practica_estrategia' => 0,
    'universidad' => 0
];

try {
    // 2. Instanciamos la conexión y abrimos la base de datos
    $conexion = new ControlConexionPdo();
    $conexion->abrirBd();
    
    // 3. Consultas de conteo en la base de datos
    $resAliado = $conexion->ejecutarSelect("SELECT COUNT(*) as total FROM universidad"); 
    $resArea   = $conexion->ejecutarSelect("SELECT COUNT(*) as total FROM area_conocimiento");
    $resNorma  = $conexion->ejecutarSelect("SELECT COUNT(*) as total FROM aspecto_normativo");
    
    // 4. Mapeamos los datos reales si existen
    if (!empty($resAliado)) {
        $stats['aliado']      = $resAliado[0]['total'];
        $stats['universidad'] = $resAliado[0]['total']; // Duplicamos en universidad por si lo pide con ambos nombres
    }
    if (!empty($resArea))   $stats['area_conocimiento'] = $resArea[0]['total'];
    if (!empty($resNorma))  $stats['aspecto_normativo'] = $resNorma[0]['total'];
    
    // 5. Cerramos la conexión
    $conexion->cerrarBd();

} catch (Exception $e) {
    error_log("Error cargando estadísticas: " . $e->getMessage());
}
?>

<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="card text-center p-4">
            <div class="display-4 text-primary mb-2"><i class="bi bi-people"></i></div>
            <h3><?= $stats['aliado'] ?></h3>
            <p class="text-muted mb-2">Aliado</p>
            <a href="index.php?ruta=aliado" class="btn btn-outline-primary btn-sm">Ver listado</a>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card text-center p-4">
            <div class="display-4 text-success mb-2"><i class="bi bi-book"></i></div>
            <h3><?= $stats['area_conocimiento'] ?></h3>
            <p class="text-muted mb-2">Área de Conocimiento</p>
            <a href="index.php?ruta=area_conocimiento" class="btn btn-outline-info btn-sm">Ver listado</a>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card text-center p-4">
            <div class="display-4 text-info mb-2"><i class="bi bi-journal-check"></i></div>
            <h3><?= $stats['aspecto_normativo'] ?></h3>
            <p class="text-muted mb-2">Aspecto Normativo</p>
            <a href="index.php?ruta=aspecto_normativo" class="btn btn-outline-warning btn-sm">Ver listado</a>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card text-center p-4">
            <div class="display-4 text-info mb-2"><i class="bi bi-lightbulb"></i></div>
            <h3><?= $stats['car_innovacion'] ?></h3>
            <p class="text-muted mb-2">Característica Innovación</p>
            <a href="index.php?ruta=car_innovacion" class="btn btn-outline-dark btn-sm">Ver listado</a>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card text-center p-4">
            <div class="display-4 text-info mb-2"><i class="bi bi-eye"></i></div>
            <h3><?= $stats['enfoque'] ?></h3>
            <p class="text-muted mb-2">Enfoque</p>
            <a href="index.php?ruta=enfoque" class="btn btn-outline-secondary btn-sm">Ver listado</a>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card text-center p-4">
            <div class="display-4 text-info mb-2"><i class="bi bi-layers"></i></div>
            <h3><?= $stats['practica_estrategia'] ?></h3>
            <p class="text-muted mb-2">Práctica Estrategia</p>
            <a href="index.php?ruta=practica_estrategia" class="btn btn-outline-danger btn-sm">Ver listado</a>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card text-center p-4">
            <div class="display-4 text-info mb-2"><i class="bi bi-building"></i></div>
            <h3><?= $stats['universidad'] ?></h3>
            <p class="text-muted mb-2">Universidad</p>
            <a href="index.php?ruta=universidad" class="btn btn-outline-success btn-sm">Ver listado</a>
        </div>
    </div>
</div>