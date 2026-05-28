<?php

require_once __DIR__ . '/../services/ApiService.php';

$api = new ApiService();
$tabla = 'acreditacion';
$clave = 'resolucion';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    session_start();
    $accionPost = $_POST['accion_post'] ?? '';

    if ($accionPost === 'crear') {
        $datos = [
            'resolucion'    => $_POST['resolucion'] ?? '',
            'tipo'          => $_POST['tipo'] ?? '',
            'calificacion'  => $_POST['calificacion'] ?? '',
            'fecha_inicio'  => $_POST['fecha_inicio'] ?? '',
            'fecha_fin'     => !empty($_POST['fecha_fin']) ? $_POST['fecha_fin'] : null,
            'programa'      => !empty($_POST['programa']) ? $_POST['programa'] : null
        ];
        $resultado = $api->crear($tabla, $datos);
        $_SESSION['mensaje'] = $resultado['mensaje'];
        $_SESSION['tipo'] = $resultado['exito'] ? 'success' : 'danger';
    }

    if ($accionPost === 'actualizar') {
        $valor = $_POST['resolucion'] ?? '';
        $datos = [
            'tipo'          => $_POST['tipo'] ?? '',
            'calificacion'  => $_POST['calificacion'] ?? '',
            'fecha_inicio'  => $_POST['fecha_inicio'] ?? '',
            'fecha_fin'     => !empty($_POST['fecha_fin']) ? $_POST['fecha_fin'] : null,
            'programa'      => !empty($_POST['programa']) ? $_POST['programa'] : null
        ];
        $resultado = $api->actualizar($tabla, $clave, $valor, $datos);
        $_SESSION['mensaje'] = $resultado['mensaje'];
        $_SESSION['tipo'] = $resultado['exito'] ? 'success' : 'danger';
    }

    if ($accionPost === 'eliminar') {
        $valor = $_POST['resolucion'] ?? '';
        $resultado = $api->eliminar($tabla, $clave, $valor);
        $_SESSION['mensaje'] = $resultado['mensaje'];
        $_SESSION['tipo'] = $resultado['exito'] ? 'success' : 'danger';
    }

    header('Location: acreditacion.php');
    exit;
}

$paginaActual = 'acreditacion';
$tituloPagina = 'Acreditaciones';
require __DIR__ . '/../includes/header.php';

$accion = $_GET['accion'] ?? '';
$valorClave = $_GET['clave'] ?? '';

$registros = $api->listar($tabla);
$programas = $api->listar('programa');

$mostrarFormulario = in_array($accion, ['nuevo', 'editar']);
$editando = $accion === 'editar';

$registro = null;
if ($editando && $valorClave) {
    foreach ($registros as $r) {
        if (($r[$clave] ?? '') == $valorClave) { $registro = $r; break; }
    }
}

$mapaProgramas = [];
foreach ($programas as $p) { $mapaProgramas[$p['id'] ?? ''] = $p['nombre'] ?? 'Sin nombre'; }
?>

<div class="container mt-4">
    <h3>Acreditaciones</h3>

    <?php if (!$mostrarFormulario): ?>
        <a href="acreditacion.php?accion=nuevo" class="btn btn-primary mb-3">Nueva Acreditación</a>
    <?php endif; ?>

    <?php if ($mostrarFormulario): ?>
        <div class="card mb-3">
            <div class="card-header"><?= $editando ? "Editar Acreditación" : "Nueva Acreditación" ?></div>
            <div class="card-body">
                <form method="POST" action="acreditacion.php"
                      onsubmit="<?= $editando ? "return confirm('¿Está seguro de actualizar la Acreditación?')" : '' ?>">
                    <input type="hidden" name="accion_post" value="<?= $editando ? 'actualizar' : 'crear' ?>" />
                    <?php if ($editando): ?>
                        <input type="hidden" name="resolucion" value="<?= $registro['resolucion'] ?? '' ?>" />
                    <?php endif; ?>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Resolución</label>
                            <input class="form-control" type="number" name="resolucion" value="<?= $registro['resolucion'] ?? '' ?>" <?= $editando ? 'readonly' : 'required' ?> />
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tipo</label>
                            <input class="form-control" type="text" name="tipo" value="<?= $registro['tipo'] ?? '' ?>" required />
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Calificación</label>
                            <input class="form-control" type="text" name="calificacion" value="<?= $registro['calificacion'] ?? '' ?>" required />
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Fecha de Inicio</label>
                            <input class="form-control" type="date" name="fecha_inicio" value="<?= $registro['fecha_inicio'] ?? '' ?>" required />
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Fecha de Fin</label>
                            <input class="form-control" type="date" name="fecha_fin" value="<?= $registro['fecha_fin'] ?? '' ?>" />
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Programa</label>
                            <select class="form-select" name="programa" required>
                                <option value="">-- Seleccione Programa --</option>
                                <?php foreach ($programas as $p): ?>
                                    <option value="<?= $p['id'] ?>" <?= ($registro && ($registro['programa'] ?? '') == $p['id']) ? 'selected' : '' ?>>
                                        [<?= $p['id'] ?>] <?= $p['nombre'] ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <button class="btn btn-success me-2" type="submit">Guardar</button>
                    <a href="acreditacion.php" class="btn btn-secondary">Cancelar</a>
                </form>
            </div>
        </div>
    <?php endif; ?>

    <?php if (!empty($registros)): ?>
        <table class="table table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th>Resolución</th>
                    <th>Tipo</th>
                    <th>Calificación</th>
                    <th>Fecha Inicio</th>
                    <th>Fecha Fin</th>
                    <th>Programa</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($registros as $reg): ?>
                <tr>
                    <td><?= $reg['resolucion'] ?></td>
                    <td><?= $reg['tipo'] ?></td>
                    <td><?= $reg['calificacion'] ?></td>
                    <td><?= $reg['fecha_inicio'] ?></td>
                    <td><?= $reg['fecha_fin'] ?? 'N/A' ?></td>
                    <td><?= $mapaProgramas[$reg['programa'] ?? ''] ?? $reg['programa'] ?></td>
                    <td>
                        <a href="acreditacion.php?accion=editar&clave=<?= $reg['resolucion'] ?>" class="btn btn-warning btn-sm me-1">Editar</a>
                        <form method="POST" action="acreditacion.php" style="display:inline" onsubmit="return confirm('¿Eliminar Acreditación #<?= $reg['resolucion'] ?>?')">
                            <input type="hidden" name="accion_post" value="eliminar" />
                            <input type="hidden" name="resolucion" value="<?= $reg['resolucion'] ?>" />
                            <button class="btn btn-danger btn-sm" type="submit">Eliminar</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="alert alert-warning">No se encontraron registros en la tabla acreditacion.</div>
    <?php endif; ?>
</div>

<?php require __DIR__ . '/../includes/footer.php'; ?>