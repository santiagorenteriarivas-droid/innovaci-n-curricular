<?php $titulo = 'Prácticas de Estrategia';?>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-gear"></i> Listado de Prácticas de Estrategia</span>
        <a href="index.php?ruta=practica_estrategia&accion=crear" class="btn btn-light btn-sm">
            <i class="bi bi-plus-circle"></i> Nueva
        </a>
    </div>

    <div class="card-body">
        <?php if (empty($practicas_estrategia)): ?>
            <p class="text-muted text-center py-3">No hay prácticas de estrategia registradas.</p>
        <?php else: ?>
        <table class="table table-hover">
            <thead>
                <tr><th>ID</th><th>Tipo</th><th>Nombre</th><th>Descripción</th><th>Acciones</th></tr>
            </thead>
            <tbody>
            <?php foreach ($practicas_estrategia as $p): ?>
            <tr>
                <td><?= $p->getId() ?></td>
                <td><?= $p->getTipo() ?></td>
                <td><?= $p->getNombre() ?></td>
                <td><?= $p->getDescripcion() ?></td>
                <td>
                    <a href="index.php?ruta=practica_estrategia&accion=editar&id=<?= $p->getId() ?>" class="btn btn-sm btn-warning">Editar</a>
                    <a href="index.php?ruta=practica_estrategia&accion=eliminar&id=<?= $p->getId() ?>" class="btn btn-sm btn-danger" onclick="return confirm('Eliminar?')">Eliminar</a>
                </td>
            </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <?php endif; ?>
    </div>
</div>