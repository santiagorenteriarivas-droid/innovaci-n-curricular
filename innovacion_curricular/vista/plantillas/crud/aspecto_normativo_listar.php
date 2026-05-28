<?php $titulo = 'Aspecto Normativo';?>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-file-text"></i> Listado de Aspecto Normativo</span>
        <a href="index.php?ruta=aspecto_normativo&accion=crear" class="btn btn-light btn-sm">
            <i class="bi bi-plus-circle"></i> Nuevo
        </a>
    </div>

    <div class="card-body">
        <?php if (empty($aspectos_normativos)): ?>
            <p class="text-muted text-center py-3">No hay aspectos normativos registrados.</p>
        <?php else: ?>
        <table class="table table-hover">
            <thead>
                <tr><th>ID</th><th>Tipo</th><th>Descripción</th><th>Fuente</th><th>Acciones</th></tr>
            </thead>
            <tbody>
            <?php foreach ($aspectos_normativos as $a): ?>
            <tr>
                <td><?= $a->getId() ?></td>
                <td><?= $a->getTipo() ?></td>
                <td><?= $a->getDescripcion() ?></td>
                <td><?= $a->getFuente() ?></td>
                <td>
                    <a href="index.php?ruta=aspecto_normativo&accion=editar&id=<?= $a->getId() ?>" class="btn btn-sm btn-warning">Editar</a>
                    <a href="index.php?ruta=aspecto_normativo&accion=eliminar&id=<?= $a->getId() ?>" class="btn btn-sm btn-danger" onclick="return confirm('Eliminar?')">Eliminar</a>
                </td>
            </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <?php endif; ?>
    </div>
</div>