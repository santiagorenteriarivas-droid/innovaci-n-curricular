<?php

require_once __DIR__ . '/../services/ApiService.php';

$api = new ApiService();
$tabla = 'alianza';
$clave = 'aliado';
$claveSecundaria = 'departamento';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    session_start();
    $accionPost = $_POST['accion_post'] ?? '';

    if ($accionPost === 'crear') {
        $datos = [
            'aliado'        => !empty($_POST['aliado']) ? $_POST['aliado'] : null,
            'departamento'  => !empty($_POST['departamento']) ? $_POST['departamento'] : null,
            'fecha_inicio'  => $_POST['fecha_inicio'] ?? '',
            'fecha_fin'     => !empty($_POST['fecha_fin']) ? $_POST['fecha_fin'] : null,
            'docente'       => !empty($_POST['docente']) ? $_POST['docente'] : null
        ];
        $resultado = $api->crear($tabla, $datos);
        $_SESSION['mensaje'] = $resultado['mensaje'];
        $_SESSION['tipo'] = $resultado['exito'] ? 'success' : 'danger';
    }

    if ($accionPost === 'actualizar') {
        $valor = $_POST['aliado'] ?? '';
        $valorSecundario = $_POST['departamento_anterior'] ?? '';
        $datos = [
            'aliado'        => !empty($_POST['aliado']) ? $_POST['aliado'] : null,
            'departamento'  => !empty($_POST['departamento']) ? $_POST['departamento'] : null,
            'fecha_inicio'  => $_POST['fecha_inicio'] ?? '',
            'fecha_fin'     => !empty($_POST['fecha_fin']) ? $_POST['fecha_fin'] : null,
            'docente'       => !empty($_POST['docente']) ? $_POST['docente'] : null
        ];
        $resultado = $api->actualizar($tabla, $clave, $valor, $datos);
        $_SESSION['mensaje'] = $resultado['mensaje'];
        $_SESSION['tipo'] = $resultado['exito'] ? 'success' : 'danger';
    }

    if ($accionPost === 'eliminar') {
        $valor = $_POST['aliado'] ?? '';
        $resultado = $api->eliminar($tabla, $clave, $valor);
        $_SESSION['mensaje'] = $resultado['mensaje'];
        $_SESSION['tipo'] = $resultado['exito'] ? 'success' : 'danger';
    }

    header('Location: alianza.php');
    exit;
}

$paginaActual = 'alianza';
$tituloPagina = 'Alianzas';
require __DIR__ . '/../includes/header.php';

$accion = $_GET['accion'] ?? '';
$valorClave = $_GET['clave'] ?? '';
$valorClaveSecundaria = $_GET['clave_secundaria'] ?? '';

$registros = $api->listar($tabla);
$aliados = $api->listar('aliado');
$departamentos = $api->listar('departamento');
$docentes = $api->listar('docente');

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

$mapaAliados = [];
foreach ($aliados as $a) { $mapaAliados[$a['id'] ?? ''] = $a['nombre'] ?? 'Sin nombre'; }

$mapaDepartamentos = [];
foreach ($departamentos as $dp) { $mapaDepartamentos[$dp['id'] ?? ''] = $dp['nombre'] ?? 'Sin nombre'; }

$mapaDocentes = [];
foreach ($docentes as $d) { $mapaDocentes[$d['id'] ?? ''] = $d['nombre'] ?? 'Sin nombre'; }
?>

<div class="container mt-4">
    <h3>Alianzas</h3>

    <?php if (!$mostrarFormulario): ?>
        <a href="alianza.php?accion=nuevo" class="btn btn-primary mb-3">Nueva Alianza</a>
    <?php endif; ?>

    <?php if ($mostrarFormulario): ?>
        <div class="card mb-3">
            <div class="card-header"><?= $editando ? "Editar Alianza" : "Nueva Alianza" ?></div>
            <div class="card-body">
                <form method="POST" action="alianza.php"
                      onsubmit="<?= $editando ? "return confirm('¿Está seguro de actualizar la Alianza?')" : '' ?>">
                    <input type="hidden" name="accion_post" value="<?= $editando ? 'actualizar' : 'crear' ?>" />
                    <?php if ($editando): ?>
                        <input type="hidden" name="departamento_anterior" value="<?= $registro['departamento'] ?? '' ?>" />
                    <?php endif; ?>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Aliado <span class="text-danger">*</span></label>
                            <select class="form-select" name="aliado" required <?= $editando ? 'disabled' : '' ?>>
                                <option value="">-- Seleccione Aliado --</option>
                                <?php foreach ($aliados as $a): ?>
                                    <option value="<?= $a['id'] ?>" <?= ($registro && ($registro['aliado'] ?? '') == $a['id']) ? 'selected' : '' ?>>
                                        [<?= $a['id'] ?>] <?= $a['nombre'] ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <?php if ($editando): ?>
                                <input type="hidden" name="aliado" value="<?= $registro['aliado'] ?? '' ?>" />
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
                            <label class="form-label">Fecha de Inicio <span class="text-danger">*</span></label>
                            <input class="form-control" type="date" name="fecha_inicio" value="<?= $registro['fecha_inicio'] ?? '' ?>" required />
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Fecha de Fin</label>
                            <input class="form-control" type="date" name="fecha_fin" value="<?= $registro['fecha_fin'] ?? '' ?>" />
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Docente Responsable</label>
                            <select class="form-select" name="docente">
                                <option value="">-- Seleccione Docente --</option>
                                <?php foreach ($docentes as $d): ?>
                                    <option value="<?= $d['id'] ?>" <?= ($registro && ($registro['docente'] ?? '') == $d['id']) ? 'selected' : '' ?>>
                                        [<?= $d['id'] ?>] <?= $d['nombre'] ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <button class="btn btn-success me-2" type="submit">Guardar</button>
                    <a href="alianza.php" class="btn btn-secondary">Cancelar</a>
                </form>
            </div>
        </div>
    <?php endif; ?>

    <?php if (!empty($registros)): ?>
        <table class="table table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th>Aliado</th>
                    <th>Departamento</th>
                    <th>Fecha Inicio</th>
                    <th>Fecha Fin</th>
                    <th>Docente Responsable</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($registros as $reg): ?>
                <tr>
                    <td><?= $mapaAliados[$reg['aliado'] ?? ''] ?? $reg['aliado'] ?></td>
                    <td><?= $mapaDepartamentos[$reg['departamento'] ?? ''] ?? $reg['departamento'] ?></td>
                    <td><?= $reg['fecha_inicio'] ?></td>
                    <td>
                        <?php if (!empty($reg['fecha_fin'])): ?>
                            <?= $reg['fecha_fin'] ?>
                        <?php else: ?>
                            <span class="text-muted">-</span>
                        <?php endif; ?>
                    </td>
                    <td><?= !empty($reg['docente']) ? ($mapaDocentes[$reg['docente'] ?? ''] ?? $reg['docente']) : 'N/A' ?></td>
                    <td>
                        <?php 
                            $hoy = date('Y-m-d');
                            if (!empty($reg['fecha_fin']) && $reg['fecha_fin'] < $hoy): ?>
                                <span class="badge bg-secondary">Finalizada</span>
                            <?php elseif (empty($reg['fecha_fin']) || $reg['fecha_fin'] >= $hoy): ?>
                                <span class="badge bg-success">Activa</span>
                            <?php endif; ?>
                    </td>
                    <td>
                        <a href="alianza.php?accion=editar&clave=<?= $reg['aliado'] ?>&clave_secundaria=<?= $reg['departamento'] ?>" class="btn btn-warning btn-sm me-1">Editar</a>
                        <form method="POST" action="alianza.php" style="display:inline" onsubmit="return confirm('¿Eliminar esta Alianza?')">
                            <input type="hidden" name="accion_post" value="eliminar" />
                            <input type="hidden" name="aliado" value="<?= $reg['aliado'] ?>" />
                            <input type="hidden" name="departamento" value="<?= $reg['departamento'] ?>" />
                            <button class="btn btn-danger btn-sm" type="submit">Eliminar</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="alert alert-warning">No se encontraron registros en la tabla alianza.</div>
    <?php endif; ?>
</div>

<?php require __DIR__ . '/../includes/footer.php'; ?>