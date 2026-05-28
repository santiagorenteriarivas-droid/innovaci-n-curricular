<?php $titulo = 'Aliado';?>

<div class="card">

    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-people"></i> Listado de Aliado</span>
        <a href="index.php?ruta=aliado&accion=crear" class="btn btn-light btn-sm">
            <i class="bi bi-plus-circle"></i> Nueva
        </a>
    </div>

    <div class="card-body">

        <?php if (empty($aliados)): ?>
            <p class="text-muted text-center py-3">No hay aliados registrados.</p>
        <?php else: ?>

        <table class="table table-hover">
            <thead> 
                <tr><th>Nit</th><th>Razon_social</th><th>Nombre_contacto</th><th>Correo</th><th>Telefono</th><th>Ciudad</th><th>Acciones</th></tr>
            </thead>
            <tbody>

            <?php foreach ($aliados as $a): ?>
            <tr>
                <td><?= $a->getNit() ?></td>  
                <td><?= $a->getRazon_Social() ?></td>  
                <td><?= $a->getNombre_Contacto() ?></td>    
                <td><?= $a->getCorreo() ?></td>
                <td><?= $a->getTelefono() ?></td>    
                <td><?= $a->getCiudad() ?></td> 
                <td>
                    <a href="index.php?ruta=aliado&accion=editar&id=<?= $a->getNit() ?>" class="btn btn-sm btn-warning">Editar</a>

                    <a href="index.php?ruta=aliado&accion=eliminar&id=<?= $a->getNit() ?>" class="btn btn-sm btn-danger" onclick="return confirm('Eliminar?')">Eliminar</a>
                </td>
            </tr>
            <?php endforeach; ?>

            </tbody>
        </table>
        <?php endif; ?>
    </div>
</div>