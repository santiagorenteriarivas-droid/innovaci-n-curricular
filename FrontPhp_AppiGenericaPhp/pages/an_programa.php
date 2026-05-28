<?php

require_once __DIR__ . '/../services/ApiService.php';

$api = new ApiService();
$tabla = 'an_programa';
$clave = 'aspecto_normativo';
$claveSecundaria = 'programa';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    session_start();
    $accionPost = $_POST['accion_post'] ?? '';

    if ($accionPost === 'crear') {
        $datos = [
            'aspecto_normativo' => !empty($_POST['aspecto_normativo']) ? $_POST['aspecto_normativo'] : null,
            'programa'          => !empty($_POST['programa']) ? $_POST['programa'] : null
        ];
        $resultado = $api->crear($tabla, $datos);
        $_SESSION['mensaje'] = $resultado['mensaje'];
        $_SESSION['tipo'] = $resultado['exito'] ? 'success' : 'danger';
    }

    if ($accionPost === 'actualizar') {
        $valor = $_POST['aspecto_normativo'] ?? '';
        $valorSecundario = $_POST['programa_anterior'] ?? '';
        $datos = [
            'aspecto_normativo' => !empty($_POST['aspecto_normativo']) ? $_POST['aspecto_normativo'] : null,
            'programa'          => !empty($_POST['programa']) ? $_POST['programa'] : null
        ];
        $resultado = $api->actualizar($tabla, $clave, $valor, $datos);
        $_SESSION['mensaje'] = $resultado['mensaje'];
        $_SESSION['tipo'] = $resultado['exito'] ? 'success' : 'danger';
    }

    if ($accionPost === 'eliminar') {
        $valor = $_POST['aspecto_normativo'] ?? '';
        $valorSecundario = $_POST['programa'] ?? '';
        $resultado = $api->eliminar($tabla, $clave, $valor);
        $_SESSION['mensaje'] = $resultado['mensaje'];
        $_SESSION['tipo'] = $resultado['exito'] ? 'success' : 'danger';
    }

    header('Location: an_programa.php');
    exit;
}

$paginaActual = 'an_programa';
$tituloPagina = 'Aspectos Normativos - Programas';
require __DIR__ . '/../includes/header.php';

$accion = $_GET['accion'] ?? '';
$valorClave = $_GET['clave'] ?? '';
$valorClaveSecundaria = $_GET['clave_secundaria'] ?? '';

$registros = $api->listar($tabla);
$aspectosNormativos = $api->listar('aspecto_normativo');
$programas = $api->listar('programa');

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

$mapaAspectosNormativos = [];
foreach ($aspectosNormativos as $an) { $mapaAspectosNormativos[$an['id'] ?? ''] = $an['descripcion'] ?? 'Sin nombre'; }

$mapaProgramas = [];
foreach ($programas as $p) { $mapaProgramas[$p['id'] ?? ''] = $p['nombre'] ?? 'Sin nombre'; }
?>

<div class="container mt-4">
    <h3>Relación Aspectos Normativos - Programas</h3>

    <?php if (!$mostrarFormulario): ?>
        <a href="an_programa.php?accion=nuevo" class="btn btn-primary mb-3">Nueva Relación</a>
    <?php endif; ?>

    <?php if ($mostrarFormulario): ?>
        <div class="card mb-3">
            <div class="card-header"><?= $editando ? "Editar Relación" : "Nueva Relación" ?></div>
            <div class="card-body">
                <form method="POST" action="an_programa.php"
                      onsubmit="<?= $editando ? "return confirm('¿Está seguro de actualizar la Relación?')" : '' ?>">
                    <input type="hidden" name="accion_post" value="<?= $editando ? 'actualizar' : 'crear' ?>" />
                    <?php if ($editando): ?>
                        <input type="hidden" name="programa_anterior" value="<?= $registro['programa'] ?? '' ?>" />
                    <?php endif; ?>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Aspecto Normativo <span class="text-danger">*</span></label>
                            <select class="form-select" name="aspecto_normativo" required <?= $editando ? 'disabled' : '' ?>>
                                <option value="">-- Seleccione Aspecto Normativo --</option>
                                <?php foreach ($aspectosNormativos as $an): ?>
                                    <option value="<?= $an['id'] ?>" <?= ($registro && ($registro['aspecto_normativo'] ?? '') == $an['id']) ? 'selected' : '' ?>>
                                        [<?= $an['id'] ?>] <?= $an['descripcion'] ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <?php if ($editando): ?>
                                <input type="hidden" name="aspecto_normativo" value="<?= $registro['aspecto_normativo'] ?? '' ?>" />
                            <?php endif; ?>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Programa <span class="text-danger">*</span></label>
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
                    <a href="an_programa.php" class="btn btn-secondary">Cancelar</a>
                </form>
            </div>
        </div>
    <?php endif; ?>

    <?php if (!empty($registros)): ?>
        <table class="table table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th>Aspecto Normativo</th>
                    <th>Programa</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($registros as $reg): ?>
                <tr>
                    <td><?= $mapaAspectosNormativos[$reg['aspecto_normativo'] ?? ''] ?? $reg['aspecto_normativo'] ?></td>
                    <td><?= $mapaProgramas[$reg['programa'] ?? ''] ?? $reg['programa'] ?></td>
                    <td>
                        <a href="an_programa.php?accion=editar&clave=<?= $reg['aspecto_normativo'] ?>&clave_secundaria=<?= $reg['programa'] ?>" class="btn btn-warning btn-sm me-1">Editar</a>
                        <form method="POST" action="an_programa.php" style="display:inline" onsubmit="return confirm('¿Eliminar esta Relación?')">
                            <input type="hidden" name="accion_post" value="eliminar" />
                            <input type="hidden" name="aspecto_normativo" value="<?= $reg['aspecto_normativo'] ?>" />
                            <input type="hidden" name="programa" value="<?= $reg['programa'] ?>" />
                            <button class="btn btn-danger btn-sm" type="submit">Eliminar</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="alert alert-warning">No se encontraron relaciones en la tabla an_programa.</div>
    <?php endif; ?>
</div>

<?php require __DIR__ . '/../includes/footer.php'; ?>
