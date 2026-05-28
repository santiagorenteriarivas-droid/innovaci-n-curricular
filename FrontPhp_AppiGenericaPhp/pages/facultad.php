<?php

require_once __DIR__ . '/../services/ApiService.php';

$api = new ApiService();
$tabla = 'facultad';
$clave = 'id';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    session_start();
    $accionPost = $_POST['accion_post'] ?? '';

    if ($accionPost === 'crear') {
        $datos = [
            'id'          => $_POST['id'] ?? '',
            'nombre'      => $_POST['nombre'] ?? '',
            'tipo'        => $_POST['tipo'] ?? '',
            'fecha_fun'   => $_POST['fecha_fun'] ?? '',
            'universidad' => !empty($_POST['universidad']) ? $_POST['universidad'] : null
        ];
        $resultado = $api->crear($tabla, $datos);
        $_SESSION['mensaje'] = $resultado['mensaje'];
        $_SESSION['tipo'] = $resultado['exito'] ? 'success' : 'danger';
    }

    if ($accionPost === 'actualizar') {
        $valor = $_POST['id'] ?? '';
        $datos = [
            'nombre'      => $_POST['nombre'] ?? '',
            'tipo'        => $_POST['tipo'] ?? '',
            'fecha_fun'   => $_POST['fecha_fun'] ?? '',
            'universidad' => !empty($_POST['universidad']) ? $_POST['universidad'] : null
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

    header('Location: facultad.php');
    exit;
}

$paginaActual = 'facultad';
$tituloPagina = 'Facultades';
require __DIR__ . '/../includes/header.php';

$accion = $_GET['accion'] ?? '';
$valorClave = $_GET['clave'] ?? '';

// 1. Consumimos los datos desde el ApiService (que ya apunta a las rutas .php)
$respuestaRegistros = $api->listar($tabla);
$respuestaUniversidades = $api->listar('universidad');

// 2. Verificación inteligente para $registros (Tabla Facultad)
if (isset($respuestaRegistros['datos']) && is_array($respuestaRegistros['datos'])) {
    $registros = $respuestaRegistros['datos'];
} else {
    $registros = is_array($respuestaRegistros) ? $respuestaRegistros : [];
}

// 3. Verificación inteligente para $universidades (Requerida por tu HTML)
if (isset($respuestaUniversidades['datos']) && is_array($respuestaUniversidades['datos'])) {
    $universidades = $respuestaUniversidades['datos'];
} else {
    $universidades = is_array($respuestaUniversidades) ? $respuestaUniversidades : [];
}

$mostrarFormulario = in_array($accion, ['nuevo', 'editar']);
$editando = $accion === 'editar';

$registro = null;
if ($editando && $valorClave) {
    foreach ($registros as $r) {
        if (($r[$clave] ?? '') == $valorClave) { $registro = $r; break; }
    }
}

// 4. Mapeo para la columna "Universidad" en la tabla de abajo
$mapaUniversidades = [];
foreach ($universidades as $u) { 
    if (isset($u['id'])) {
        $mapaUniversidades[$u['id']] = $u['nombre'] ?? 'Sin nombre'; 
    }
}
?>

<div class="container mt-4">
    <h3>Facultades</h3>

    <?php if (!$mostrarFormulario): ?>
        <a href="facultad.php?accion=nuevo" class="btn btn-primary mb-3">Nueva Facultad</a>
    <?php endif; ?>

    <?php if ($mostrarFormulario): ?>
        <div class="card mb-3">
            <div class="card-header"><?= $editando ? "Editar Facultad" : "Nueva Facultad" ?></div>
            <div class="card-body">
                <form method="POST" action="facultad.php"
                      onsubmit="<?= $editando ? "return confirm('¿Está seguro de actualizar la Facultad?')" : '' ?>">
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
                            <label class="form-label">Fecha de Fundación</label>
                            <input class="form-control" type="date" name="fecha_fun" value="<?= $registro['fecha_fun'] ?? '' ?>" />
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Universidad</label>
                            <select class="form-select" name="universidad" required>
                                <option value="">-- Seleccione Universidad --</option>
                                <?php foreach ($universidades as $u): ?>
                                    <option value="<?= $u['id'] ?>" <?= ($registro && ($registro['universidad'] ?? '') == $u['id']) ? 'selected' : '' ?>>
                                        [<?= $u['id'] ?>] <?= $u['nombre'] ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <button class="btn btn-success me-2" type="submit">Guardar</button>
                    <a href="facultad.php" class="btn btn-secondary">Cancelar</a>
                </form>
            </div>
        </div>
    <?php endif; ?>

    <?php if (!empty($registros)): ?>
        <table class="table table-striped table-hover">
            <thead class="table-dark"><tr><th>ID</th><th>Nombre</th><th>Tipo</th><th>Fecha Fundación</th><th>Universidad</th><th>Acciones</th></tr></thead>
            <tbody>
                <?php foreach ($registros as $reg): ?>
                <tr>
                    <td><?= $reg['id'] ?></td>
                    <td><?= $reg['nombre'] ?></td>
                    <td><?= $reg['tipo'] ?></td>
                    <td><?= $reg['fecha_fun'] ?></td>
                    <td><?= $mapaUniversidades[$reg['universidad'] ?? ''] ?? $reg['universidad'] ?></td>
                    <td>
                        <a href="facultad.php?accion=editar&clave=<?= $reg['id'] ?>" class="btn btn-warning btn-sm me-1">Editar</a>
                        <form method="POST" action="facultad.php" style="display:inline" onsubmit="return confirm('¿Eliminar Facultad #<?= $reg['id'] ?>?')">
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
        <div class="alert alert-warning">No se encontraron registros en la tabla facultad.</div>
    <?php endif; ?>
</div>

<?php require __DIR__ . '/../includes/footer.php'; ?>
