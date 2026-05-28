<?php $titulo = 'Característica Innovación';?>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-lightbulb"></i> Listado de Característica Innovación</span>
        <a href="index.php?ruta=car_innovacion&accion=crear" class="btn btn-light btn-sm">
            <i class="bi bi-plus-circle"></i> Nueva
        </a>
    </div>

    <div class="card-body">
        <?php if (empty($car_innovaciones)): ?>
            <p class="text-muted text-center py-3">No hay características de innovación registradas.</p>
        <?php else: ?>
        <table class="table table-hover">
            <thead>
                <tr><th>ID</th><th>Nombre</th><th>Descripción</th><th>Tipo</th><th>Acciones</th></tr>
            </thead>
            <tbody>
            <?php foreach ($car_innovaciones as $c): ?>
            <tr>
                <td><?= $c->getId() ?></td>
                <td><?= $c->getNombre() ?></td>
                <td><?= $c->getDescripcion() ?></td>
                <td><?= $c->getTipo() ?></td>
                <td>
                    <a href="index.php?ruta=car_innovacion&accion=editar&id=<?= $c->getId() ?>" class="btn btn-sm btn-warning">Editar</a>
                    <a href="index.php?ruta=car_innovacion&accion=eliminar&id=<?= $c->getId() ?>" class="btn btn-sm btn-danger" onclick="return confirm('Eliminar?')">Eliminar</a>
                </td>
            </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <?php endif; ?>
    </div>
</div>