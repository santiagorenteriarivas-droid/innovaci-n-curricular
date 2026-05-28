<?php

$paginaActual = 'home';              
$tituloPagina = 'Sistema de Innovación Curricular';     
require __DIR__ . '/../includes/header.php';       
require __DIR__ . '/../services/ApiService.php';   

$apiInfo = null;
$ch = curl_init(API_BASE_URL . '/');               
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 3);              
$respuesta = curl_exec($ch);                        
curl_close($ch);
if ($respuesta) {
    $apiInfo = json_decode($respuesta, true);       
}

?>

<div class="container mt-4">

    <h1>Sistema de Innovación Curricular - CRUD</h1>

    <p class="lead">
        Plataforma de gestión integrada que consume la API genérica
        <strong>ApiGenericaPhp</strong> (PHP vanilla + MySQL/MariaDB).
    </p>

    <div class="alert alert-info">
        <strong>Proyecto 1 - Tablas Independientes:</strong> Área de Conocimiento, Universidad, Aspecto Normativo, Práctica Estrategia, Enfoque, Característica Innovación, Aliado.
        <br />
        <strong>Proyecto 2 - Tablas Relacionadas:</strong> Facultad, Programa, Acreditación, Registro Calificado, Actividad Académica, Pasantía, Premio, Programa AC, Programa PE, Programa CI, Análisis Programa, Enfoque RC, AA RC, Docente Departamento, Alianza.
        <br />
        Use el menú lateral para navegar a cada tabla.
    </div>

    <div class="alert alert-warning">
        <strong>Sin Stored Procedures:</strong> Este frontend utiliza solo CRUD genérico (GET, POST, PUT, DELETE).
        La gestión de datos complejos se realiza mediante múltiples llamadas al controlador de entidades.
        Los <strong>triggers de MariaDB</strong> calculan automáticamente valores derivados y mantienen la integridad relacional.
    </div>

    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card border-primary">
                <div class="card-header bg-primary bg-opacity-10 text-primary py-2">
                    <small><strong>📋 Proyecto 1: Entidades Base</strong></small>
                </div>
                <div class="card-body py-3">
                    <p class="text-muted mb-2"><small>Tablas independientes del sistema:</small></p>
                    <ul class="list-unstyled small">
                        <li>✓ Área de Conocimiento</li>
                        <li>✓ Universidad</li>
                        <li>✓ Aspecto Normativo</li>
                        <li>✓ Práctica Estrategia</li>
                        <li>✓ Enfoque</li>
                        <li>✓ Característica Innovación</li>
                        <li>✓ Aliado</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card border-success">
                <div class="card-header bg-success bg-opacity-10 text-success py-2">
                    <small><strong>🔗 Proyecto 2: Entidades Relacionadas</strong></small>
                </div>
                <div class="card-body py-3">
                    <p class="text-muted mb-2"><small>Tablas con relaciones complejas:</small></p>
                    <ul class="list-unstyled small">
                        <li>✓ Facultad</li>
                        <li>✓ Programa</li>
                        <li>✓ Acreditación</li>
                        <li>✓ Registro Calificado</li>
                        <li>✓ Actividad Académica</li>
                        <li>✓ Pasantía</li>
                        <li>✓ Premio</li>
                        <li>✓ Programa AC / PE / CI</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <?php if ($apiInfo): ?>
        <div class="card mt-4 border-secondary">
            <div class="card-header bg-secondary bg-opacity-10 text-muted py-2">
                <small><strong>✅ API conectada</strong></small>
            </div>
            <div class="card-body py-2">
                <table class="table table-sm table-borderless mb-0" style="font-size: 0.85rem;">
                    <tbody>
                        <tr>
                            <td class="text-muted" style="width:160px">API</td>
                            <td><strong><?= $apiInfo['Mensaje'] ?? 'ApiGenericaPhp' ?></strong></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Versión</td>
                            <td><?= $apiInfo['Version'] ?? '?' ?></td>
                        </tr>
                        <tr>
                            <td class="text-muted">URL</td>
                            <td><a href="<?= API_BASE_URL ?>" target="_blank"><?= API_BASE_URL ?></a></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Documentación</td>
                            <td><a href="<?= API_BASE_URL ?>/docs" target="_blank"><?= API_BASE_URL ?>/docs</a></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    <?php else: ?>
        <div class="alert alert-danger mt-4">
            <strong>⚠️ API no disponible.</strong> Verifique que ApiGenericaPhp esté corriendo en
            <code><?= API_BASE_URL ?></code>
            <br />
            <small>Ejecutar: <code>php -S localhost:8000 -t public</code> en la carpeta de ApiGenericaPhp</small>
        </div>
    <?php endif; ?>

</div>

<?php require __DIR__ . '/../includes/footer.php'; ?>
