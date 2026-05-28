<?php

require_once __DIR__ . '/../services/ApiService.php';

$api = new ApiService();
$tabla = 'activ_academica';
$clave = 'id';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    session_start();
    $accionPost = $_POST['accion_post'] ?? '';

    if ($accionPost === 'crear') {
        $datos = [
            'id'            => $_POST['id'] ?? '',
            'nombre'        => $_POST['nombre'] ?? '',
            'num_creditos'  => !empty($_POST['num_creditos']) ? $_POST['num_creditos'] : 0,
            'tipo'          => $_POST['tipo'] ?? '',
            'area_formacion' => $_POST['area_formacion'] ?? '',
            'h_acom'        => !empty($_POST['h_acom']) ? $_POST['h_acom'] : 0,
            'h_indep'       => !empty($_POST['h_indep']) ? $_POST['h_indep'] : 0,
            'idioma'        => $_POST['idioma'] ?? '',
            'espejo'        => !empty($_POST['espejo']) ? $_POST['espejo'] : 0,
            'entidad_espejo' => !empty($_POST['entidad_espejo']) ? $_POST['entidad_espejo'] : null,
            'pais_espejo'   => !empty($_POST['pais_espejo']) ? $_POST['pais_espejo'] : null,
            'disenio'       => !empty($_POST['disenio']) ? $_POST['disenio'] : null
        ];
        $resultado = $api->crear($tabla, $datos);
        $_SESSION['mensaje'] = $resultado['mensaje'];
        $_SESSION['tipo'] = $resultado['exito'] ? 'success' : 'danger';
    }

    if ($accionPost === 'actualizar') {
        $valor = $_POST['id'] ?? '';
        $datos = [
            'nombre'        => $_POST['nombre'] ?? '',
            'num_creditos'  => !empty($_POST['num_creditos']) ? $_POST['num_creditos'] : 0,
            'tipo'          => $_POST['tipo'] ?? '',
            'area_formacion' => $_POST['area_formacion'] ?? '',
            'h_acom'        => !empty($_POST['h_acom']) ? $_POST['h_acom'] : 0,
            'h_indep'       => !empty($_POST['h_indep']) ? $_POST['h_indep'] : 0,
            'idioma'        => $_POST['idioma'] ?? '',
            'espejo'        => !empty($_POST['espejo']) ? $_POST['espejo'] : 0,
            'entidad_espejo' => !empty($_POST['entidad_espejo']) ? $_POST['entidad_espejo'] : null,
            'pais_espejo'   => !empty($_POST['pais_espejo']) ? $_POST['pais_espejo'] : null,
            'disenio'       => !empty($_POST['disenio']) ? $_POST['disenio'] : null
        ];
        $resultado = $api->actualizar($tabla, $clave, $valor, $datos);
        $_SESSION['mensaje'] = $resultado['mensaje'];
        $_SESSION['tipo'] = $resultado['exito'] ? 'success' : 'danger';
    }

    if ($accionPost === 'eliminar') {
        $valor = $_POST['id'] ?? '';
        $resultado = $api->eliminar($tabla, $clave, $valor);
        $_SESSION['mensaje'] = $resultado['mensaje'];
        $_SESSION['tipo'] = $resultado['exito'] ? 'success' : 'danger';
    }

    header('Location: activ_academica.php');
    exit;
}

$paginaActual = 'activ_academica';
$tituloPagina = 'Actividades Académicas';
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
    <h3>Actividades Académicas</h3>

    <?php if (!$mostrarFormulario): ?>
        <a href="activ_academica.php?accion=nuevo" class="btn btn-primary mb-3">Nueva Actividad Académica</a>
    <?php endif; ?>

    <?php if ($mostrarFormulario): ?>
        <div class="card mb-3">
            <div class="card-header"><?= $editando ? "Editar Actividad Académica" : "Nueva Actividad Académica" ?></div>
            <div class="card-body">
                <form method="POST" action="activ_academica.php"
                      onsubmit="<?= $editando ? "return confirm('¿Está seguro de actualizar la Actividad Académica?')" : '' ?>">
                    <input type="hidden" name="accion_post" value="<?= $editando ? 'actualizar' : 'crear' ?>" />
                    <?php if ($editando): ?>
                        <input type="hidden" name="id" value="<?= $registro['id'] ?? '' ?>" />
                    <?php endif; ?>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">ID</label>
                            <input class="form-control" type="number" name="id" value="<?= $registro['id'] ?? '' ?>" <?= $editando ? 'readonly' : 'required' ?> />
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nombre</label>
                            <input class="form-control" type="text" name="nombre" value="<?= $registro['nombre'] ?? '' ?>" required />
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Número de Créditos</label>
                            <input class="form-control" type="number" name="num_creditos" value="<?= $registro['num_creditos'] ?? 0 ?>" min="0" required />
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tipo</label>
                            <input class="form-control" type="text" name="tipo" value="<?= $registro['tipo'] ?? '' ?>" required />
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Área de Formación</label>
                            <input class="form-control" type="text" name="area_formacion" value="<?= $registro['area_formacion'] ?? '' ?>" required />
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Horas de Acompañamiento</label>
                            <input class="form-control" type="number" name="h_acom" value="<?= $registro['h_acom'] ?? 0 ?>" min="0" required />
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Horas Independiente</label>
                            <input class="form-control" type="number" name="h_indep" value="<?= $registro['h_indep'] ?? 0 ?>" min="0" required />
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Idioma</label>
                            <input class="form-control" type="text" name="idioma" value="<?= $registro['idioma'] ?? '' ?>" required />
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">¿Es Espejo?</label>
                            <select class="form-select" name="espejo" required>
                                <option value="0" <?= ($registro && ($registro['espejo'] ?? 0) == 0) ? 'selected' : '' ?>>No</option>
                                <option value="1" <?= ($registro && ($registro['espejo'] ?? 0) == 1) ? 'selected' : '' ?>>Sí</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Entidad Espejo</label>
                            <input class="form-control" type="text" name="entidad_espejo" value="<?= $registro['entidad_espejo'] ?? '' ?>" />
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">País Espejo</label>
                            <input class="form-control" type="text" name="pais_espejo" value="<?= $registro['pais_espejo'] ?? '' ?>" />
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Programa</label>
                            <select class="form-select" name="disenio" required>
                                <option value="">-- Seleccione Programa --</option>
                                <?php foreach ($programas as $p): ?>
                                    <option value="<?= $p['id'] ?>" <?= ($registro && ($registro['disenio'] ?? '') == $p['id']) ? 'selected' : '' ?>>
                                        [<?= $p['id'] ?>] <?= $p['nombre'] ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <button class="btn btn-success me-2" type="submit">Guardar</button>
                    <a href="activ_academica.php" class="btn btn-secondary">Cancelar</a>
                </form>
            </div>
        </div>
    <?php endif; ?>

    <?php if (!empty($registros)): ?>
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Créditos</th>
                        <th>Tipo</th>
                        <th>Área Formación</th>
                        <th>Horas Acompañamiento</th>
                        <th>Horas Independiente</th>
                        <th>Idioma</th>
                        <th>¿Es Espejo?</th>
                        <th>Entidad Espejo</th>
                        <th>País Espejo</th>
                        <th>Programa</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($registros as $reg): ?>
                    <tr>
                        <td><?= $reg['id'] ?></td>
                        <td><?= $reg['nombre'] ?></td>
                        <td><?= $reg['num_creditos'] ?></td>
                        <td><?= $reg['tipo'] ?></td>
                        <td><?= $reg['area_formacion'] ?></td>
                        <td><?= $reg['h_acom'] ?></td>
                        <td><?= $reg['h_indep'] ?></td>
                        <td><?= $reg['idioma'] ?></td>
                        <td>
                            <span class="badge <?= ($reg['espejo'] ?? 0) == 1 ? 'bg-success' : 'bg-secondary' ?>">
                                <?= ($reg['espejo'] ?? 0) == 1 ? 'Sí' : 'No' ?>
                            </span>
                        </td>
                        <td><?= $reg['entidad_espejo'] ?? 'N/A' ?></td>
                        <td><?= $reg['pais_espejo'] ?? 'N/A' ?></td>
                        <td><?= $mapaProgramas[$reg['disenio'] ?? ''] ?? $reg['disenio'] ?></td>
                        <td>
                            <a href="activ_academica.php?accion=editar&clave=<?= $reg['id'] ?>" class="btn btn-warning btn-sm me-1">Editar</a>
                            <form method="POST" action="activ_academica.php" style="display:inline" onsubmit="return confirm('¿Eliminar Actividad Académica #<?= $reg['id'] ?>?')">
                                <input type="hidden" name="accion_post" value="eliminar" />
                                <input type="hidden" name="id" value="<?= $reg['id'] ?>" />
                                <button class="btn btn-danger btn-sm" type="submit">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="alert alert-warning">No se encontraron registros en la tabla activ_academica.</div>
    <?php endif; ?>
</div>

<?php require __DIR__ . '/../includes/footer.php'; ?>