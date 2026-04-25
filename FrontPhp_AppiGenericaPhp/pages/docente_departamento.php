<?php

require_once __DIR__ . '/../services/ApiService.php';

$api = new ApiService();
$tabla = 'docente_departamento';
$clave = 'docente';
$claveSecundaria = 'departamento';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    session_start();
    $accionPost = $_POST['accion_post'] ?? '';

    if ($accionPost === 'crear') {
        $datos = [
            'docente'       => !empty($_POST['docente']) ? $_POST['docente'] : null,
            'departamento'  => !empty($_POST['departamento']) ? $_POST['departamento'] : null,
            'dedicacion'    => $_POST['dedicacion'] ?? '',
            'modalidad'     => $_POST['modalidad'] ?? '',
            'fecha_ingreso' => $_POST['fecha_ingreso'] ?? '',
            'fecha_salida'  => !empty($_POST['fecha_salida']) ? $_POST['fecha_salida'] : null
        ];
        $resultado = $api->crear($tabla, $datos);
        $_SESSION['mensaje'] = $resultado['mensaje'];
        $_SESSION['tipo'] = $resultado['exito'] ? 'success' : 'danger';
    }

    if ($accionPost === 'actualizar') {
        $valor = $_POST['docente'] ?? '';
        $valorSecundario = $_POST['departamento_anterior'] ?? '';
        $datos = [
            'docente'       => !empty($_POST['docente']) ? $_POST['docente'] : null,
            'departamento'  => !empty($_POST['departamento']) ? $_POST['departamento'] : null,
            'dedicacion'    => $_POST['dedicacion'] ?? '',
            'modalidad'     => $_POST['modalidad'] ?? '',
            'fecha_ingreso' => $_POST['fecha_ingreso'] ?? '',
            'fecha_salida'  => !empty($_POST['fecha_salida']) ? $_POST['fecha_salida'] : null
        ];
        $resultado = $api->actualizar($tabla, $clave, $valor, $datos);
        $_SESSION['mensaje'] = $resultado['mensaje'];
        $_SESSION['tipo'] = $resultado['exito'] ? 'success' : 'danger';
    }

    if ($accionPost === 'eliminar') {
        $valor = $_POST['docente'] ?? '';
        $resultado = $api->eliminar($tabla, $clave, $valor);
        $_SESSION['mensaje'] = $resultado['mensaje'];
        $_SESSION['tipo'] = $resultado['exito'] ? 'success' : 'danger';
    }

    header('Location: docente_departamento.php');
    exit;
}

$paginaActual = 'docente_departamento';
$tituloPagina = 'Docentes - Departamentos';
require __DIR__ . '/../includes/header.php';

$accion = $_GET['accion'] ?? '';
$valorClave = $_GET['clave'] ?? '';
$valorClaveSecundaria = $_GET['clave_secundaria'] ?? '';

$registros = $api->listar($tabla);
$docentes = $api->listar('docente');
$departamentos = $api->listar('departamento');

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

$mapaDocentes = [];
foreach ($docentes as $d) { $mapaDocentes[$d['id'] ?? ''] = $d['nombre'] ?? 'Sin nombre'; }

$mapaDepartamentos = [];
foreach ($departamentos as $dp) { $mapaDepartamentos[$dp['id'] ?? ''] = $dp['nombre'] ?? 'Sin nombre'; }
?>

<div class="container mt-4">
    <h3>Relación Docentes - Departamentos</h3>

    <?php if (!$mostrarFormulario): ?>
        <a href="docente_departamento.php?accion=nuevo" class="btn btn-primary mb-3">Nueva Relación</a>
    <?php endif; ?>

    <?php if ($mostrarFormulario): ?>
        <div class="card mb-3">
            <div class="card-header"><?= $editando ? "Editar Relación" : "Nueva Relación" ?></div>
            <div class="card-body">
                <form method="POST" action="docente_departamento.php"
                      onsubmit="<?= $editando ? "return confirm('¿Está seguro de actualizar la Relación?')" : '' ?>">
                    <input type="hidden" name="accion_post" value="<?= $editando ? 'actualizar' : 'crear' ?>" />
                    <?php if ($editando): ?>
                        <input type="hidden" name="departamento_anterior" value="<?= $registro['departamento'] ?? '' ?>" />
                    <?php endif; ?>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Docente <span class="text-danger">*</span></label>
                            <select class="form-select" name="docente" required <?= $editando ? 'disabled' : '' ?>>
                                <option value="">-- Seleccione Docente --</option>
                                <?php foreach ($docentes as $d): ?>
                                    <option value="<?= $d['id'] ?>" <?= ($registro && ($registro['docente'] ?? '') == $d['id']) ? 'selected' : '' ?>>
                                        [<?= $d['id'] ?>] <?= $d['nombre'] ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <?php if ($editando): ?>
                                <input type="hidden" name="docente" value="<?= $registro['docente'] ?? '' ?>" />
                            <?php endif; ?>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Departamento <span class="text-danger">*</span></label>
                            <select class="form-select" name="departamento" required>
                                <option value="">-- Seleccione Departamento --</option>
                                <?php foreach ($departamentos as $dp): ?>
                                    <option value="<?= $dp['id'] ?>" <?= ($registro && ($registro['departamento'] ?? '') == $dp['id']) ? 'selected' : '' ?>>
                                        [<?= $dp['id'] ?>] <?= $dp['nombre'] ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Dedicación <span class="text-danger">*</span></label>
                            <input class="form-control" type="text" name="dedicacion" value="<?= $registro['dedicacion'] ?? '' ?>" required />
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Modalidad <span class="text-danger">*</span></label>
                            <input class="form-control" type="text" name="modalidad" value="<?= $registro['modalidad'] ?? '' ?>" required />
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Fecha de Ingreso <span class="text-danger">*</span></label>
                            <input class="form-control" type="date" name="fecha_ingreso" value="<?= $registro['fecha_ingreso'] ?? '' ?>" required />
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Fecha de Salida</label>
                            <input class="form-control" type="date" name="fecha_salida" value="<?= $registro['fecha_salida'] ?? '' ?>" />
                        </div>
                    </div>
                    <button class="btn btn-success me-2" type="submit">Guardar</button>
                    <a href="docente_departamento.php" class="btn btn-secondary">Cancelar</a>
                </form>
            </div>
        </div>
    <?php endif; ?>

    <?php if (!empty($registros)): ?>
        <table class="table table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th>Docente</th>
                    <th>Departamento</th>
                    <th>Dedicación</th>
                    <th>Modalidad</th>
                    <th>Fecha Ingreso</th>
                    <th>Fecha Salida</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($registros as $reg): ?>
                <tr>
                    <td><?= $mapaDocentes[$reg['docente'] ?? ''] ?? $reg['docente'] ?></td>
                    <td><?= $mapaDepartamentos[$reg['departamento'] ?? ''] ?? $reg['departamento'] ?></td>
                    <td><?= $reg['dedicacion'] ?></td>
                    <td><?= $reg['modalidad'] ?></td>
                    <td><?= $reg['fecha_ingreso'] ?></td>
                    <td>
                        <?php if (!empty($reg['fecha_salida'])): ?>
                            <span class="badge bg-danger"><?= $reg['fecha_salida'] ?></span>
                        <?php else: ?>
                            <span class="badge bg-success">Activo</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <a href="docente_departamento.php?accion=editar&clave=<?= $reg['docente'] ?>&clave_secundaria=<?= $reg['departamento'] ?>" class="btn btn-warning btn-sm me-1">Editar</a>
                        <form method="POST" action="docente_departamento.php" style="display:inline" onsubmit="return confirm('¿Eliminar esta Relación?')">
                            <input type="hidden" name="accion_post" value="eliminar" />
                            <input type="hidden" name="docente" value="<?= $reg['docente'] ?>" />
                            <input type="hidden" name="departamento" value="<?= $reg['departamento'] ?>" />
                            <button class="btn btn-danger btn-sm" type="submit">Eliminar</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="alert alert-warning">No se encontraron relaciones en la tabla docente_departamento.</div>
    <?php endif; ?>
</div>

<?php require __DIR__ . '/../includes/footer.php'; ?>
