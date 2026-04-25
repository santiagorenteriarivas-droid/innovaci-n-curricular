<?php $titulo = 'Enfoques';?>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-diagram-3"></i> Listado de Enfoques</span>
        <a href="index.php?ruta=enfoque&accion=crear" class="btn btn-light btn-sm">
            <i class="bi bi-plus-circle"></i> Nuevo
        </a>
    </div>

    <div class="card-body">
        <?php if (empty($enfoques)): ?>
            <p class="text-muted text-center py-3">No hay enfoques registrados.</p>
        <?php else: ?>
        <table class="table table-hover">
            <thead>
                <tr><th>ID</th><th>Nombre</th><th>Descripción</th><th>Acciones</th></tr>
            </thead>
            <tbody>
            <?php foreach ($enfoques as $e): ?>
            <tr>
                <td><?= $e->getId() ?></td>
                <td><?= $e->getNombre() ?></td>
                <td><?= $e->getDescripcion() ?></td>
                <td>
                    <a href="index.php?ruta=enfoque&accion=editar&id=<?= $e->getId() ?>" class="btn btn-sm btn-warning">Editar</a>
                    <a href="index.php?ruta=enfoque&accion=eliminar&id=<?= $e->getId() ?>" class="btn btn-sm btn-danger" onclick="return confirm('Eliminar?')">Eliminar</a>
                </td>
            </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <?php endif; ?>
    </div>
</div>