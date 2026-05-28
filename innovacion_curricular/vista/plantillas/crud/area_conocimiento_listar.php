<?php $titulo = 'Área de Conocimiento';?>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-book"></i> Listado de Área de Conocimiento</span>
        <a href="index.php?ruta=area_conocimiento&accion=crear" class="btn btn-light btn-sm">
            <i class="bi bi-plus-circle"></i> Nueva
        </a>
    </div>

    <div class="card-body">
        <?php if (empty($areas_conocimiento)): ?>
            <p class="text-muted text-center py-3">No hay áreas de conocimiento registradas.</p>
        <?php else: ?>
        <table class="table table-hover">
            <thead>
                <tr><th>ID</th><th>Gran_area</th><th>area</th><th>Disciplina</th><th>Acciones</th></tr>
            </thead>
            <tbody>
            <?php foreach ($areas_conocimiento as $a): ?>
            <tr>
                <td><?= $a->getId() ?></td>
                <td><?= $a->getGran_Area() ?></td>
                <td><?= $a->getArea() ?></td>
                <td><?= $a->getDisciplina() ?></td>
                <td>
                    <a href="index.php?ruta=area_conocimiento&accion=editar&id=<?= $a->getId() ?>" class="btn btn-sm btn-warning">Editar</a>
                    <a href="index.php?ruta=area_conocimiento&accion=eliminar&id=<?= $a->getId() ?>" class="btn btn-sm btn-danger" onclick="return confirm('Eliminar?')">Eliminar</a>
                </td>
            </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <?php endif; ?>
    </div>
</div>