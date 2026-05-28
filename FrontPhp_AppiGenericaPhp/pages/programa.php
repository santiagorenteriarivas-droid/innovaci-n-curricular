<?php

require_once __DIR__ . '/../services/ApiService.php';

$api = new ApiService();
$tabla = 'programa';
$clave = 'id';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    session_start();
    $accionPost = $_POST['accion_post'] ?? '';

    if ($accionPost === 'crear') {
        $datos = [
            'id'               => $_POST['id'] ?? '',
            'nombre'           => $_POST['nombre'] ?? '',
            'tipo'             => $_POST['tipo'] ?? '',
            'nivel'            => $_POST['nivel'] ?? '',
            'fecha_creacion'   => $_POST['fecha_creacion'] ?? '',
            'fecha_cierre'     => !empty($_POST['fecha_cierre']) ? $_POST['fecha_cierre'] : null,
            'numero_cohortes'  => !empty($_POST['numero_cohortes']) ? $_POST['numero_cohortes'] : 0,
            'cant_graduados'   => !empty($_POST['cant_graduados']) ? $_POST['cant_graduados'] : 0,
            'fecha_actualizacion' => date('Y-m-d H:i:s'),
            'ciudad'           => $_POST['ciudad'] ?? '',
            'facultad'         => !empty($_POST['facultad']) ? $_POST['facultad'] : null
        ];
        $resultado = $api->crear($tabla, $datos);
        $_SESSION['mensaje'] = $resultado['mensaje'];
        $_SESSION['tipo'] = $resultado['exito'] ? 'success' : 'danger';
    }

    if ($accionPost === 'actualizar') {
        $valor = $_POST['id'] ?? '';
        $datos = [
            'nombre'           => $_POST['nombre'] ?? '',
            'tipo'             => $_POST['tipo'] ?? '',
            'nivel'            => $_POST['nivel'] ?? '',
            'fecha_creacion'   => $_POST['fecha_creacion'] ?? '',
            'fecha_cierre'     => !empty($_POST['fecha_cierre']) ? $_POST['fecha_cierre'] : null,
            'numero_cohortes'  => !empty($_POST['numero_cohortes']) ? $_POST['numero_cohortes'] : 0,
            'cant_graduados'   => !empty($_POST['cant_graduados']) ? $_POST['cant_graduados'] : 0,
            'fecha_actualizacion' => date('Y-m-d H:i:s'),
            'ciudad'           => $_POST['ciudad'] ?? '',
            'facultad'         => !empty($_POST['facultad']) ? $_POST['facultad'] : null
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

    header('Location: programa.php');
    exit;
}

$paginaActual = 'programa';
$tituloPagina = 'Programas';
require __DIR__ . '/../includes/header.php';

$accion = $_GET['accion'] ?? '';
$valorClave = $_GET['clave'] ?? '';

$registros = $api->listar($tabla);
$facultades = $api->listar('facultad');

$mostrarFormulario = in_array($accion, ['nuevo', 'editar']);
$editando = $accion === 'editar';

$registro = null;
if ($editando && $valorClave) {
    foreach ($registros as $r) {
        if (($r[$clave] ?? '') == $valorClave) { $registro = $r; break; }
    }
}

$mapaFacultades = [];
foreach ($facultades as $f) { $mapaFacultades[$f['id'] ?? ''] = $f['nombre'] ?? 'Sin nombre'; }
?>

<div class="container mt-4">
    <h3>Programas</h3>

    <?php if (!$mostrarFormulario): ?>
        <a href="programa.php?accion=nuevo" class="btn btn-primary mb-3">Nuevo Programa</a>
    <?php endif; ?>

    <?php if ($mostrarFormulario): ?>
        <div class="card mb-3">
            <div class="card-header"><?= $editando ? "Editar Programa" : "Nuevo Programa" ?></div>
            <div class="card-body">
                <form method="POST" action="programa.php"
                      onsubmit="<?= $editando ? "return confirm('¿Está seguro de actualizar el Programa?')" : '' ?>">
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
                            <label class="form-label">Tipo</label>
                            <input class="form-control" type="text" name="tipo" value="<?= $registro['tipo'] ?? '' ?>" required />
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nivel</label>
                            <input class="form-control" type="text" name="nivel" value="<?= $registro['nivel'] ?? '' ?>" required />
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Fecha de Creación</label>
                            <input class="form-control" type="date" name="fecha_creacion" value="<?= $registro['fecha_creacion'] ?? '' ?>" required />
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Fecha de Cierre</label>
                            <input class="form-control" type="date" name="fecha_cierre" value="<?= $registro['fecha_cierre'] ?? '' ?>" />
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Número de Cohortes</label>
                            <input class="form-control" type="number" name="numero_cohortes" value="<?= $registro['numero_cohortes'] ?? 0 ?>" min="0" />
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Cantidad de Graduados</label>
                            <input class="form-control" type="number" name="cant_graduados" value="<?= $registro['cant_graduados'] ?? 0 ?>" min="0" />
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Ciudad</label>
                            <input class="form-control" type="text" name="ciudad" value="<?= $registro['ciudad'] ?? '' ?>" required />
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Facultad</label>
                            <select class="form-select" name="facultad" required>
                                <option value="">-- Seleccione Facultad --</option>
                                <?php foreach ($facultades as $f): ?>
                                    <option value="<?= $f['id'] ?>" <?= ($registro && ($registro['facultad'] ?? '') == $f['id']) ? 'selected' : '' ?>>
                                        [<?= $f['id'] ?>] <?= $f['nombre'] ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <button class="btn btn-success me-2" type="submit">Guardar</button>
                    <a href="programa.php" class="btn btn-secondary">Cancelar</a>
                </form>
            </div>
        </div>
    <?php endif; ?>

    <?php if (!empty($registros)): ?>
        <table class="table table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Tipo</th>
                    <th>Nivel</th>
                    <th>Fecha Creación</th>
                    <th>Fecha Cierre</th>
                    <th>Cohortes</th>
                    <th>Graduados</th>
                    <th>Ciudad</th>
                    <th>Facultad</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($registros as $reg): ?>
                <tr>
                    <td><?= $reg['id'] ?></td>
                    <td><?= $reg['nombre'] ?></td>
                    <td><?= $reg['tipo'] ?></td>
                    <td><?= $reg['nivel'] ?></td>
                    <td><?= $reg['fecha_creacion'] ?></td>
                    <td><?= $reg['fecha_cierre'] ?? 'N/A' ?></td>
                    <td><?= $reg['numero_cohortes'] ?></td>
                    <td><?= $reg['cant_graduados'] ?></td>
                    <td><?= $reg['ciudad'] ?></td>
                    <td><?= $mapaFacultades[$reg['facultad'] ?? ''] ?? $reg['facultad'] ?></td>
                    <td>
                        <a href="programa.php?accion=editar&clave=<?= $reg['id'] ?>" class="btn btn-warning btn-sm me-1">Editar</a>
                        <form method="POST" action="programa.php" style="display:inline" onsubmit="return confirm('¿Eliminar Programa #<?= $reg['id'] ?>?')">
                            <input type="hidden" name="accion_post" value="eliminar" />
                            <input type="hidden" name="id" value="<?= $reg['id'] ?>" />
                            <button class="btn btn-danger btn-sm" type="submit">Eliminar</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="alert alert-warning">No se encontraron registros en la tabla programa.</div>
    <?php endif; ?>
</div>

<?php require __DIR__ . '/../includes/footer.php'; ?>