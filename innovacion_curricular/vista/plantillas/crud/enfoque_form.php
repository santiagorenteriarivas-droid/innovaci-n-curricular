<?php
$titulo = ($accion === 'crear') ? 'Crear Enfoque' : 'Editar Enfoque';

$enfoque = $enfoque ?? null;
?>

<div class="row"><div class="col-md-6 mx-auto">
<div class="card">
    <div class="card-header"><?= $titulo ?></div>
    <div class="card-body">

        <form method="POST">

            <div class="mb-3">
                <label class="form-label">ID</label>
                <input type="text" class="form-control" name="id"
                       value="<?= $enfoque ? $enfoque->getId() : '' ?>"
                       <?= $accion === 'editar' ? 'readonly' : 'required' ?>>
            </div>

            <div class="mb-3">
                <label class="form-label">Nombre</label>
                <input type="text" class="form-control" name="nombre"
                       value="<?= $enfoque ? $enfoque->getNombre() : '' ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Descripción</label>
                <input type="text" class="form-control" name="descripcion"
                       value="<?= $enfoque ? $enfoque->getDescripcion() : '' ?>" required>
            </div>

            <button type="submit" class="btn btn-primary">Guardar</button>
            <a href="index.php?ruta=enfoque" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</div>
</div></div>