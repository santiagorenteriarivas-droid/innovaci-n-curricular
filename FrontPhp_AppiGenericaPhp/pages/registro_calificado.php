<?php

require_once __DIR__ . '/../services/ApiService.php';

$api = new ApiService();
$tabla = 'registro_calificado';
$clave = 'codigo';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    session_start();
    $accionPost = $_POST['accion_post'] ?? '';

    if ($accionPost === 'crear') {
        $datos = [
            'cant_creditos'     => !empty($_POST['cant_creditos']) ? $_POST['cant_creditos'] : 0,
            'hora_acom'         => !empty($_POST['hora_acom']) ? $_POST['hora_acom'] : 0,
            'hora_ind'          => !empty($_POST['hora_ind']) ? $_POST['hora_ind'] : 0,
            'metodologia'       => $_POST['metodologia'] ?? '',
            'fecha_inicio'      => $_POST['fecha_inicio'] ?? '',
            'fecha_fin'         => !empty($_POST['fecha_fin']) ? $_POST['fecha_fin'] : null,
            'duracion_anios'    => !empty($_POST['duracion_anios']) ? $_POST['duracion_anios'] : 0,
            'duracion_semestres' => !empty($_POST['duracion_semestres']) ? $_POST['duracion_semestres'] : 0,
            'tipo_titulacion'   => $_POST['tipo_titulacion'] ?? '',
            'programa'          => !empty($_POST['programa']) ? $_POST['programa'] : null
        ];
        $resultado = $api->crear($tabla, $datos);
        $_SESSION['mensaje'] = $resultado['mensaje'];
        $_SESSION['tipo'] = $resultado['exito'] ? 'success' : 'danger';
    }

    if ($accionPost === 'actualizar') {
        $valor = $_POST['codigo'] ?? '';
        $datos = [
            'cant_creditos'     => !empty($_POST['cant_creditos']) ? $_POST['cant_creditos'] : 0,
            'hora_acom'         => !empty($_POST['hora_acom']) ? $_POST['hora_acom'] : 0,
            'hora_ind'          => !empty($_POST['hora_ind']) ? $_POST['hora_ind'] : 0,
            'metodologia'       => $_POST['metodologia'] ?? '',
            'fecha_inicio'      => $_POST['fecha_inicio'] ?? '',
            'fecha_fin'         => !empty($_POST['fecha_fin']) ? $_POST['fecha_fin'] : null,
            'duracion_anios'    => !empty($_POST['duracion_anios']) ? $_POST['duracion_anios'] : 0,
            'duracion_semestres' => !empty($_POST['duracion_semestres']) ? $_POST['duracion_semestres'] : 0,
            'tipo_titulacion'   => $_POST['tipo_titulacion'] ?? '',
            'programa'          => !empty($_POST['programa']) ? $_POST['programa'] : null
        ];
        $resultado = $api->actualizar($tabla, $clave, $valor, $datos);
        $_SESSION['mensaje'] = $resultado['mensaje'];
        $_SESSION['tipo'] = $resultado['exito'] ? 'success' : 'danger';
    }

    if ($accionPost === 'eliminar') {
        $valor = $_POST['codigo'] ?? '';
        $resultado = $api->eliminar($tabla, $clave, $valor);
        $_SESSION['mensaje'] = $resultado['mensaje'];
        $_SESSION['tipo'] = $resultado['exito'] ? 'success' : 'danger';
    }

    header('Location: registro_calificado.php');
    exit;
}

$paginaActual = 'registro_calificado';
$tituloPagina = 'Registros Calificados';
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
    <h3>Registros Calificados</h3>

    <?php if (!$mostrarFormulario): ?>
        <a href="registro_calificado.php?accion=nuevo" class="btn btn-primary mb-3">Nuevo Registro Calificado</a>
    <?php endif; ?>

    <?php if ($mostrarFormulario): ?>
        <div class="card mb-3">
            <div class="card-header"><?= $editando ? "Editar Registro Calificado" : "Nuevo Registro Calificado" ?></div>
            <div class="card-body">
                <form method="POST" action="registro_calificado.php"
                      onsubmit="<?= $editando ? "return confirm('¿Está seguro de actualizar el Registro Calificado?')" : '' ?>">
                    <input type="hidden" name="accion_post" value="<?= $editando ? 'actualizar' : 'crear' ?>" />
                    <?php if ($editando): ?>
                        <input type="hidden" name="codigo" value="<?= $registro['codigo'] ?? '' ?>" />
                    <?php endif; ?>
                    <div class="row">
                        <?php if ($editando): ?>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Código</label>
                            <input class="form-control" value="<?= $registro['codigo'] ?? '' ?>" readonly />
                        </div>
                        <?php endif; ?>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Cantidad de Créditos</label>
                            <input class="form-control" type="number" name="cant_creditos" value="<?= $registro['cant_creditos'] ?? 0 ?>" min="0" required />
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Horas de Acompañamiento</label>
                            <input class="form-control" type="number" name="hora_acom" value="<?= $registro['hora_acom'] ?? 0 ?>" min="0" required />
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Horas Independiente</label>
                            <input class="form-control" type="number" name="hora_ind" value="<?= $registro['hora_ind'] ?? 0 ?>" min="0" required />
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Metodología</label>
                            <input class="form-control" type="text" name="metodologia" value="<?= $registro['metodologia'] ?? '' ?>" required />
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
                            <label class="form-label">Duración en Años</label>
                            <input class="form-control" type="number" name="duracion_anios" value="<?= $registro['duracion_anios'] ?? 0 ?>" min="0" step="0.1" required />
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Duración en Semestres</label>
                            <input class="form-control" type="number" name="duracion_semestres" value="<?= $registro['duracion_semestres'] ?? 0 ?>" min="0" required />
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tipo de Titulación</label>
                            <input class="form-control" type="text" name="tipo_titulacion" value="<?= $registro['tipo_titulacion'] ?? '' ?>" required />
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
                    <a href="registro_calificado.php" class="btn btn-secondary">Cancelar</a>
                </form>
            </div>
        </div>
    <?php endif; ?>

    <?php if (!empty($registros)): ?>
        <table class="table table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th>Código</th>
                    <th>Créditos</th>
                    <th>Horas Acompañamiento</th>
                    <th>Horas Independiente</th>
                    <th>Metodología</th>
                    <th>Fecha Inicio</th>
                    <th>Fecha Fin</th>
                    <th>Duración (años)</th>
                    <th>Duración (semestres)</th>
                    <th>Tipo Titulación</th>
                    <th>Programa</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($registros as $reg): ?>
                <tr>
                    <td><?= $reg['codigo'] ?></td>
                    <td><?= $reg['cant_creditos'] ?></td>
                    <td><?= $reg['hora_acom'] ?></td>
                    <td><?= $reg['hora_ind'] ?></td>
                    <td><?= $reg['metodologia'] ?></td>
                    <td><?= $reg['fecha_inicio'] ?></td>
                    <td><?= $reg['fecha_fin'] ?? 'N/A' ?></td>
                    <td><?= $reg['duracion_anios'] ?></td>
                    <td><?= $reg['duracion_semestres'] ?></td>
                    <td><?= $reg['tipo_titulacion'] ?></td>
                    <td><?= $mapaProgramas[$reg['programa'] ?? ''] ?? $reg['programa'] ?></td>
                    <td>
                        <a href="registro_calificado.php?accion=editar&clave=<?= $reg['codigo'] ?>" class="btn btn-warning btn-sm me-1">Editar</a>
                        <form method="POST" action="registro_calificado.php" style="display:inline" onsubmit="return confirm('¿Eliminar Registro Calificado #<?= $reg['codigo'] ?>?')">
                            <input type="hidden" name="accion_post" value="eliminar" />
                            <input type="hidden" name="codigo" value="<?= $reg['codigo'] ?>" />
                            <button class="btn btn-danger btn-sm" type="submit">Eliminar</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="alert alert-warning">No se encontraron registros en la tabla registro_calificado.</div>
    <?php endif; ?>
</div>

<?php require __DIR__ . '/../includes/footer.php'; ?>