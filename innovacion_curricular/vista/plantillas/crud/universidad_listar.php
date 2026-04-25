<?php $titulo = 'Universidades';?>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-building"></i> Listado de Universidades</span>
        <a href="index.php?ruta=universidad&accion=crear" class="btn btn-light btn-sm">
            <i class="bi bi-plus-circle"></i> Nueva
        </a>
    </div>

    <div class="card-body">
        <?php if (empty($universidades)): ?>
            <p class="text-muted text-center py-3">No hay universidades registradas.</p>
        <?php else: ?>
        <table class="table table-hover">
            <thead>
                <tr><th>ID</th><th>Nombre</th><th>Tipo</th><th>Ciudad</th><th>Acciones</th></tr>
            </thead>
            <tbody>
            <?php foreach ($universidades as $u): ?>
            <tr>
                <td><?= $u->getId() ?></td>
                <td><?= $u->getNombre() ?></td>
                <td><?= $u->getTipo() ?></td>
                <td><?= $u->getCiudad() ?></td>
                <td>
                    <a href="index.php?ruta=universidad&accion=editar&id=<?= $u->getId() ?>" class="btn btn-sm btn-warning">Editar</a>
                    <a href="index.php?ruta=universidad&accion=eliminar&id=<?= $u->getId() ?>" class="btn btn-sm btn-danger" onclick="return confirm('Eliminar?')">Eliminar</a>
                </td>
            </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <?php endif; ?>
    </div>
</div>