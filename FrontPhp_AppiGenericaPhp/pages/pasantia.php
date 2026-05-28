<?php

require_once __DIR__ . '/../services/ApiService.php';

$api = new ApiService();
$tabla = 'pasantia';
$clave = 'id';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    session_start();
    $accionPost = $_POST['accion_post'] ?? '';

    if ($accionPost === 'crear') {
        $datos = [
            'id'            => $_POST['id'] ?? '',
            'nombre'        => $_POST['nombre'] ?? '',
            'pais'          => $_POST['pais'] ?? '',
            'empresa'       => $_POST['empresa'] ?? '',
            'descripcion'   => $_POST['descripcion'] ?? '',
            'programa'      => !empty($_POST['programa']) ? $_POST['programa'] : null
        ];
        $resultado = $api->crear($tabla, $datos);
        $_SESSION['mensaje'] = $resultado['mensaje'];
        $_SESSION['tipo'] = $resultado['exito'] ? 'success' : 'danger';
    }

    if ($accionPost === 'actualizar') {
        $valor = $_POST['id'] ?? '';
        $datos = [
            'nombre'        => $_POST['nombre'] ?? '',
            'pais'          => $_POST['pais'] ?? '',
            'empresa'       => $_POST['empresa'] ?? '',
            'descripcion'   => $_POST['descripcion'] ?? '',
            'programa'      => !empty($_POST['programa']) ? $_POST['programa'] : null
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

    header('Location: pasantia.php');
    exit;
}

$paginaActual = 'pasantia';
$tituloPagina = 'Pasantías';
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
    <h3>Pasantías</h3>

    <?php if (!$mostrarFormulario): ?>
        <a href="pasantia.php?accion=nuevo" class="btn btn-primary mb-3">Nueva Pasantía</a>
    <?php endif; ?>

    <?php if ($mostrarFormulario): ?>
        <div class="card mb-3">
            <div class="card-header"><?= $editando ? "Editar Pasantía" : "Nueva Pasantía" ?></div>
            <div class="card-body">
                <form method="POST" action="pasantia.php"
                      onsubmit="<?= $editando ? "return confirm('¿Está seguro de actualizar la Pasantía?')" : '' ?>">
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
                            <label class="form-label">País</label>
                            <input class="form-control" type="text" name="pais" value="<?= $registro['pais'] ?? '' ?>" required />
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Empresa</label>
                            <input class="form-control" type="text" name="empresa" value="<?= $registro['empresa'] ?? '' ?>" required />
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Descripción</label>
                            <textarea class="form-control" name="descripcion" rows="3" required><?= $registro['descripcion'] ?? '' ?></textarea>
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
                    <a href="pasantia.php" class="btn btn-secondary">Cancelar</a>
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
                    <th>País</th>
                    <th>Empresa</th>
                    <th>Descripción</th>
                    <th>Programa</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($registros as $reg): ?>
                <tr>
                    <td><?= $reg['id'] ?></td>
                    <td><?= $reg['nombre'] ?></td>
                    <td><?= $reg['pais'] ?></td>
                    <td><?= $reg['empresa'] ?></td>
                    <td>
                        <span title="<?= htmlspecialchars($reg['descripcion'] ?? '') ?>">
                            <?= substr($reg['descripcion'] ?? '', 0, 50) . (strlen($reg['descripcion'] ?? '') > 50 ? '...' : '') ?>
                        </span>
                    </td>
                    <td><?= $mapaProgramas[$reg['programa'] ?? ''] ?? $reg['programa'] ?></td>
                    <td>
                        <a href="pasantia.php?accion=editar&clave=<?= $reg['id'] ?>" class="btn btn-warning btn-sm me-1">Editar</a>
                        <form method="POST" action="pasantia.php" style="display:inline" onsubmit="return confirm('¿Eliminar Pasantía #<?= $reg['id'] ?>?')">
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
        <div class="alert alert-warning">No se encontraron registros en la tabla pasantia.</div>
    <?php endif; ?>
</div>

<?php require __DIR__ . '/../includes/footer.php'; ?>
