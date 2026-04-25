<?php

require_once __DIR__ . '/../services/ApiService.php';

$api = new ApiService();
$tabla = 'enfoque_rc';
$clave = 'enfoque';
$claveSecundaria = 'registro_calificado';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    session_start();
    $accionPost = $_POST['accion_post'] ?? '';

    if ($accionPost === 'crear') {
        $datos = [
            'enfoque'               => !empty($_POST['enfoque']) ? $_POST['enfoque'] : null,
            'registro_calificado'   => !empty($_POST['registro_calificado']) ? $_POST['registro_calificado'] : null
        ];
        $resultado = $api->crear($tabla, $datos);
        $_SESSION['mensaje'] = $resultado['mensaje'];
        $_SESSION['tipo'] = $resultado['exito'] ? 'success' : 'danger';
    }

    if ($accionPost === 'actualizar') {
        $valor = $_POST['enfoque'] ?? '';
        $valorSecundario = $_POST['registro_calificado_anterior'] ?? '';
        $datos = [
            'enfoque'               => !empty($_POST['enfoque']) ? $_POST['enfoque'] : null,
            'registro_calificado'   => !empty($_POST['registro_calificado']) ? $_POST['registro_calificado'] : null
        ];
        $resultado = $api->actualizar($tabla, $clave, $valor, $datos);
        $_SESSION['mensaje'] = $resultado['mensaje'];
        $_SESSION['tipo'] = $resultado['exito'] ? 'success' : 'danger';
    }

    if ($accionPost === 'eliminar') {
        $valor = $_POST['enfoque'] ?? '';
        $valorSecundario = $_POST['registro_calificado'] ?? '';
        $resultado = $api->eliminar($tabla, $clave, $valor);
        $_SESSION['mensaje'] = $resultado['mensaje'];
        $_SESSION['tipo'] = $resultado['exito'] ? 'success' : 'danger';
    }

    header('Location: enfoque_rc.php');
    exit;
}

$paginaActual = 'enfoque_rc';
$tituloPagina = 'Enfoques - Registros Calificados';
require __DIR__ . '/../includes/header.php';

$accion = $_GET['accion'] ?? '';
$valorClave = $_GET['clave'] ?? '';
$valorClaveSecundaria = $_GET['clave_secundaria'] ?? '';

$registros = $api->listar($tabla);
$enfoques = $api->listar('enfoque');
$registrosCalificados = $api->listar('registro_calificado');

$mostrarFormulario = in_array($accion, ['nuevo', 'editar']);
$editando = $accion === 'editar';

$registro = null;
if ($editando && $valorClave && $valorClaveSecundaria) {
    foreach ($registros as $r) {
        if (($r[$clave] ?? '') == $valorClave && ($r[$claveSecundaria] ?? '') == $valorClaveSecundaria) { 
            $registro = $r; 
            break; 
        }
    }
}

$mapaEnfoques = [];
foreach ($enfoques as $e) { $mapaEnfoques[$e['id'] ?? ''] = $e['nombre'] ?? 'Sin nombre'; }

$mapaRegistrosCalificados = [];
foreach ($registrosCalificados as $rc) { $mapaRegistrosCalificados[$rc['codigo'] ?? ''] = $rc['codigo'] ?? 'Sin código'; }
?>

<div class="container mt-4">
    <h3>Relación Enfoques - Registros Calificados</h3>

    <?php if (!$mostrarFormulario): ?>
        <a href="enfoque_rc.php?accion=nuevo" class="btn btn-primary mb-3">Nueva Relación</a>
    <?php endif; ?>

    <?php if ($mostrarFormulario): ?>
        <div class="card mb-3">
            <div class="card-header"><?= $editando ? "Editar Relación" : "Nueva Relación" ?></div>
            <div class="card-body">
                <form method="POST" action="enfoque_rc.php"
                      onsubmit="<?= $editando ? "return confirm('¿Está seguro de actualizar la Relación?')" : '' ?>">
                    <input type="hidden" name="accion_post" value="<?= $editando ? 'actualizar' : 'crear' ?>" />
                    <?php if ($editando): ?>
                        <input type="hidden" name="registro_calificado_anterior" value="<?= $registro['registro_calificado'] ?? '' ?>" />
                    <?php endif; ?>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Enfoque <span class="text-danger">*</span></label>
                            <select class="form-select" name="enfoque" required <?= $editando ? 'disabled' : '' ?>>
                                <option value="">-- Seleccione Enfoque --</option>
                                <?php foreach ($enfoques as $e): ?>
                                    <option value="<?= $e['id'] ?>" <?= ($registro && ($registro['enfoque'] ?? '') == $e['id']) ? 'selected' : '' ?>>
                                        [<?= $e['id'] ?>] <?= $e['nombre'] ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <?php if ($editando): ?>
                                <input type="hidden" name="enfoque" value="<?= $registro['enfoque'] ?? '' ?>" />
                            <?php endif; ?>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Registro Calificado <span class="text-danger">*</span></label>
                            <select class="form-select" name="registro_calificado" required>
                                <option value="">-- Seleccione Registro Calificado --</option>
                                <?php foreach ($registrosCalificados as $rc): ?>
                                    <option value="<?= $rc['codigo'] ?>" <?= ($registro && ($registro['registro_calificado'] ?? '') == $rc['codigo']) ? 'selected' : '' ?>>
                                        [<?= $rc['codigo'] ?>]
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <button class="btn btn-success me-2" type="submit">Guardar</button>
                    <a href="enfoque_rc.php" class="btn btn-secondary">Cancelar</a>
                </form>
            </div>
        </div>
    <?php endif; ?>

    <?php if (!empty($registros)): ?>
        <table class="table table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th>Enfoque</th>
                    <th>Registro Calificado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($registros as $reg): ?>
                <tr>
                    <td><?= $mapaEnfoques[$reg['enfoque'] ?? ''] ?? $reg['enfoque'] ?></td>
                    <td><?= $mapaRegistrosCalificados[$reg['registro_calificado'] ?? ''] ?? $reg['registro_calificado'] ?></td>
                    <td>
                        <a href="enfoque_rc.php?accion=editar&clave=<?= $reg['enfoque'] ?>&clave_secundaria=<?= $reg['registro_calificado'] ?>" class="btn btn-warning btn-sm me-1">Editar</a>
                        <form method="POST" action="enfoque_rc.php" style="display:inline" onsubmit="return confirm('¿Eliminar esta Relación?')">
                            <input type="hidden" name="accion_post" value="eliminar" />
                            <input type="hidden" name="enfoque" value="<?= $reg['enfoque'] ?>" />
                            <input type="hidden" name="registro_calificado" value="<?= $reg['registro_calificado'] ?>" />
                            <button class="btn btn-danger btn-sm" type="submit">Eliminar</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="alert alert-warning">No se encontraron relaciones en la tabla enfoque_rc.</div>
    <?php endif; ?>
</div>

<?php require __DIR__ . '/../includes/footer.php'; ?>
