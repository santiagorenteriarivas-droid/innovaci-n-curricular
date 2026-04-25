<?php

require_once __DIR__ . '/../services/ApiService.php';

$api = new ApiService();
$tabla = 'programa_ci';
$clave = 'programa';
$claveSecundaria = 'car_innovacion';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    session_start();
    $accionPost = $_POST['accion_post'] ?? '';

    if ($accionPost === 'crear') {
        $datos = [
            'programa'      => !empty($_POST['programa']) ? $_POST['programa'] : null,
            'car_innovacion' => !empty($_POST['car_innovacion']) ? $_POST['car_innovacion'] : null
        ];
        $resultado = $api->crear($tabla, $datos);
        $_SESSION['mensaje'] = $resultado['mensaje'];
        $_SESSION['tipo'] = $resultado['exito'] ? 'success' : 'danger';
    }

    if ($accionPost === 'actualizar') {
        $valor = $_POST['programa'] ?? '';
        $valorSecundario = $_POST['car_innovacion_anterior'] ?? '';
        $datos = [
            'programa'      => !empty($_POST['programa']) ? $_POST['programa'] : null,
            'car_innovacion' => !empty($_POST['car_innovacion']) ? $_POST['car_innovacion'] : null
        ];
        $resultado = $api->actualizar($tabla, $clave, $valor, $datos);
        $_SESSION['mensaje'] = $resultado['mensaje'];
        $_SESSION['tipo'] = $resultado['exito'] ? 'success' : 'danger';
    }

    if ($accionPost === 'eliminar') {
        $valor = $_POST['programa'] ?? '';
        $valorSecundario = $_POST['car_innovacion'] ?? '';
        $resultado = $api->eliminar($tabla, $clave, $valor);
        $_SESSION['mensaje'] = $resultado['mensaje'];
        $_SESSION['tipo'] = $resultado['exito'] ? 'success' : 'danger';
    }

    header('Location: programa_ci.php');
    exit;
}

$paginaActual = 'programa_ci';
$tituloPagina = 'Programas - Características de Innovación';
require __DIR__ . '/../includes/header.php';

$accion = $_GET['accion'] ?? '';
$valorClave = $_GET['clave'] ?? '';
$valorClaveSecundaria = $_GET['clave_secundaria'] ?? '';

$registros = $api->listar($tabla);
$programas = $api->listar('programa');
$caracteristicasInnovacion = $api->listar('car_innovacion');

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

$mapaProgramas = [];
foreach ($programas as $p) { $mapaProgramas[$p['id'] ?? ''] = $p['nombre'] ?? 'Sin nombre'; }

$mapaCaracteristicasInnovacion = [];
foreach ($caracteristicasInnovacion as $ci) { $mapaCaracteristicasInnovacion[$ci['id'] ?? ''] = $ci['nombre'] ?? 'Sin nombre'; }
?>

<div class="container mt-4">
    <h3>Relación Programas - Características de Innovación</h3>

    <?php if (!$mostrarFormulario): ?>
        <a href="programa_ci.php?accion=nuevo" class="btn btn-primary mb-3">Nueva Relación</a>
    <?php endif; ?>

    <?php if ($mostrarFormulario): ?>
        <div class="card mb-3">
            <div class="card-header"><?= $editando ? "Editar Relación" : "Nueva Relación" ?></div>
            <div class="card-body">
                <form method="POST" action="programa_ci.php"
                      onsubmit="<?= $editando ? "return confirm('¿Está seguro de actualizar la Relación?')" : '' ?>">
                    <input type="hidden" name="accion_post" value="<?= $editando ? 'actualizar' : 'crear' ?>" />
                    <?php if ($editando): ?>
                        <input type="hidden" name="car_innovacion_anterior" value="<?= $registro['car_innovacion'] ?? '' ?>" />
                    <?php endif; ?>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Programa <span class="text-danger">*</span></label>
                            <select class="form-select" name="programa" required <?= $editando ? 'disabled' : '' ?>>
                                <option value="">-- Seleccione Programa --</option>
                                <?php foreach ($programas as $p): ?>
                                    <option value="<?= $p['id'] ?>" <?= ($registro && ($registro['programa'] ?? '') == $p['id']) ? 'selected' : '' ?>>
                                        [<?= $p['id'] ?>] <?= $p['nombre'] ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <?php if ($editando): ?>
                                <input type="hidden" name="programa" value="<?= $registro['programa'] ?? '' ?>" />
                            <?php endif; ?>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Característica de Innovación <span class="text-danger">*</span></label>
                            <select class="form-select" name="car_innovacion" required>
                                <option value="">-- Seleccione Característica de Innovación --</option>
                                <?php foreach ($caracteristicasInnovacion as $ci): ?>
                                    <option value="<?= $ci['id'] ?>" <?= ($registro && ($registro['car_innovacion'] ?? '') == $ci['id']) ? 'selected' : '' ?>>
                                        [<?= $ci['id'] ?>] <?= $ci['nombre'] ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <button class="btn btn-success me-2" type="submit">Guardar</button>
                    <a href="programa_ci.php" class="btn btn-secondary">Cancelar</a>
                </form>
            </div>
        </div>
    <?php endif; ?>

    <?php if (!empty($registros)): ?>
        <table class="table table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th>Programa</th>
                    <th>Característica de Innovación</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($registros as $reg): ?>
                <tr>
                    <td><?= $mapaProgramas[$reg['programa'] ?? ''] ?? $reg['programa'] ?></td>
                    <td><?= $mapaCaracteristicasInnovacion[$reg['car_innovacion'] ?? ''] ?? $reg['car_innovacion'] ?></td>
                    <td>
                        <a href="programa_ci.php?accion=editar&clave=<?= $reg['programa'] ?>&clave_secundaria=<?= $reg['car_innovacion'] ?>" class="btn btn-warning btn-sm me-1">Editar</a>
                        <form method="POST" action="programa_ci.php" style="display:inline" onsubmit="return confirm('¿Eliminar esta Relación?')">
                            <input type="hidden" name="accion_post" value="eliminar" />
                            <input type="hidden" name="programa" value="<?= $reg['programa'] ?>" />
                            <input type="hidden" name="car_innovacion" value="<?= $reg['car_innovacion'] ?>" />
                            <button class="btn btn-danger btn-sm" type="submit">Eliminar</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="alert alert-warning">No se encontraron relaciones en la tabla programa_ci.</div>
    <?php endif; ?>
</div>

<?php require __DIR__ . '/../includes/footer.php'; ?>
