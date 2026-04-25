<?php

require_once __DIR__ . '/../services/ApiService.php';

$api = new ApiService();
$tabla = 'aa_rc';
$clave = 'activ_academicas_idcurso';
$claveSecundaria = 'registro_calificado_codigo';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    session_start();
    $accionPost = $_POST['accion_post'] ?? '';

    if ($accionPost === 'crear') {
        $datos = [
            'activ_academicas_idcurso'  => !empty($_POST['activ_academicas_idcurso']) ? $_POST['activ_academicas_idcurso'] : null,
            'registro_calificado_codigo' => !empty($_POST['registro_calificado_codigo']) ? $_POST['registro_calificado_codigo'] : null,
            'componente'                 => $_POST['componente'] ?? '',
            'semestre'                   => !empty($_POST['semestre']) ? $_POST['semestre'] : 0
        ];
        $resultado = $api->crear($tabla, $datos);
        $_SESSION['mensaje'] = $resultado['mensaje'];
        $_SESSION['tipo'] = $resultado['exito'] ? 'success' : 'danger';
    }

    if ($accionPost === 'actualizar') {
        $valor = $_POST['activ_academicas_idcurso'] ?? '';
        $valorSecundario = $_POST['registro_calificado_codigo_anterior'] ?? '';
        $datos = [
            'activ_academicas_idcurso'  => !empty($_POST['activ_academicas_idcurso']) ? $_POST['activ_academicas_idcurso'] : null,
            'registro_calificado_codigo' => !empty($_POST['registro_calificado_codigo']) ? $_POST['registro_calificado_codigo'] : null,
            'componente'                 => $_POST['componente'] ?? '',
            'semestre'                   => !empty($_POST['semestre']) ? $_POST['semestre'] : 0
        ];
        $resultado = $api->actualizar($tabla, $clave, $valor, $datos);
        $_SESSION['mensaje'] = $resultado['mensaje'];
        $_SESSION['tipo'] = $resultado['exito'] ? 'success' : 'danger';
    }

    if ($accionPost === 'eliminar') {
        $valor = $_POST['activ_academicas_idcurso'] ?? '';
        $resultado = $api->eliminar($tabla, $clave, $valor);
        $_SESSION['mensaje'] = $resultado['mensaje'];
        $_SESSION['tipo'] = $resultado['exito'] ? 'success' : 'danger';
    }

    header('Location: aa_rc.php');
    exit;
}

$paginaActual = 'aa_rc';
$tituloPagina = 'Actividades Académicas - Registros Calificados';
require __DIR__ . '/../includes/header.php';

$accion = $_GET['accion'] ?? '';
$valorClave = $_GET['clave'] ?? '';
$valorClaveSecundaria = $_GET['clave_secundaria'] ?? '';

$registros = $api->listar($tabla);
$actividadesAcademicas = $api->listar('activ_academica');
$registrosCalificados = $api->listar('registro_calificado');

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

$mapaActividadesAcademicas = [];
foreach ($actividadesAcademicas as $aa) { $mapaActividadesAcademicas[$aa['id'] ?? ''] = $aa['nombre'] ?? 'Sin nombre'; }

$mapaRegistrosCalificados = [];
foreach ($registrosCalificados as $rc) { $mapaRegistrosCalificados[$rc['codigo'] ?? ''] = $rc['codigo'] ?? 'Sin código'; }
?>

<div class="container mt-4">
    <h3>Relación Actividades Académicas - Registros Calificados</h3>

    <?php if (!$mostrarFormulario): ?>
        <a href="aa_rc.php?accion=nuevo" class="btn btn-primary mb-3">Nueva Relación</a>
    <?php endif; ?>

    <?php if ($mostrarFormulario): ?>
        <div class="card mb-3">
            <div class="card-header"><?= $editando ? "Editar Relación" : "Nueva Relación" ?></div>
            <div class="card-body">
                <form method="POST" action="aa_rc.php"
                      onsubmit="<?= $editando ? "return confirm('¿Está seguro de actualizar la Relación?')" : '' ?>">
                    <input type="hidden" name="accion_post" value="<?= $editando ? 'actualizar' : 'crear' ?>" />
                    <?php if ($editando): ?>
                        <input type="hidden" name="registro_calificado_codigo_anterior" value="<?= $registro['registro_calificado_codigo'] ?? '' ?>" />
                    <?php endif; ?>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Actividad Académica <span class="text-danger">*</span></label>
                            <select class="form-select" name="activ_academicas_idcurso" required <?= $editando ? 'disabled' : '' ?>>
                                <option value="">-- Seleccione Actividad Académica --</option>
                                <?php foreach ($actividadesAcademicas as $aa): ?>
                                    <option value="<?= $aa['id'] ?>" <?= ($registro && ($registro['activ_academicas_idcurso'] ?? '') == $aa['id']) ? 'selected' : '' ?>>
                                        [<?= $aa['id'] ?>] <?= $aa['nombre'] ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <?php if ($editando): ?>
                                <input type="hidden" name="activ_academicas_idcurso" value="<?= $registro['activ_academicas_idcurso'] ?? '' ?>" />
                            <?php endif; ?>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Registro Calificado <span class="text-danger">*</span></label>
                            <select class="form-select" name="registro_calificado_codigo" required>
                                <option value="">-- Seleccione Registro Calificado --</option>
                                <?php foreach ($registrosCalificados as $rc): ?>
                                    <option value="<?= $rc['codigo'] ?>" <?= ($registro && ($registro['registro_calificado_codigo'] ?? '') == $rc['codigo']) ? 'selected' : '' ?>>
                                        [<?= $rc['codigo'] ?>]
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Componente</label>
                            <input class="form-control" type="text" name="componente" value="<?= $registro['componente'] ?? '' ?>" required />
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Semestre</label>
                            <input class="form-control" type="number" name="semestre" value="<?= $registro['semestre'] ?? 0 ?>" min="0" required />
                        </div>
                    </div>
                    <button class="btn btn-success me-2" type="submit">Guardar</button>
                    <a href="aa_rc.php" class="btn btn-secondary">Cancelar</a>
                </form>
            </div>
        </div>
    <?php endif; ?>

    <?php if (!empty($registros)): ?>
        <table class="table table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th>Actividad Académica</th>
                    <th>Registro Calificado</th>
                    <th>Componente</th>
                    <th>Semestre</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($registros as $reg): ?>
                <tr>
                    <td><?= $mapaActividadesAcademicas[$reg['activ_academicas_idcurso'] ?? ''] ?? $reg['activ_academicas_idcurso'] ?></td>
                    <td><?= $mapaRegistrosCalificados[$reg['registro_calificado_codigo'] ?? ''] ?? $reg['registro_calificado_codigo'] ?></td>
                    <td><?= $reg['componente'] ?></td>
                    <td><?= $reg['semestre'] ?></td>
                    <td>
                        <a href="aa_rc.php?accion=editar&clave=<?= $reg['activ_academicas_idcurso'] ?>&clave_secundaria=<?= $reg['registro_calificado_codigo'] ?>" class="btn btn-warning btn-sm me-1">Editar</a>
                        <form method="POST" action="aa_rc.php" style="display:inline" onsubmit="return confirm('¿Eliminar esta Relación?')">
                            <input type="hidden" name="accion_post" value="eliminar" />
                            <input type="hidden" name="activ_academicas_idcurso" value="<?= $reg['activ_academicas_idcurso'] ?>" />
                            <input type="hidden" name="registro_calificado_codigo" value="<?= $reg['registro_calificado_codigo'] ?>" />
                            <button class="btn btn-danger btn-sm" type="submit">Eliminar</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="alert alert-warning">No se encontraron relaciones en la tabla aa_rc.</div>
    <?php endif; ?>
</div>

<?php require __DIR__ . '/../includes/footer.php'; ?>