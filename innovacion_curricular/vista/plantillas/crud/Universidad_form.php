<?php
$titulo = ($accion === 'crear') ? 'Crear Universidad' : 'Editar Universidad';

$universidad = $universidad ?? null;
?>

<div class="row"><div class="col-md-6 mx-auto">
<div class="card">
    <div class="card-header"><?= $titulo ?></div>
    <div class="card-body">

        <form method="POST">

            <div class="mb-3">
                <label class="form-label">ID</label>
                <input type="text" class="form-control" name="id"
                       value="<?= $universidad ? $universidad->getId() : '' ?>"
                       <?= $accion === 'editar' ? 'readonly' : 'required' ?>>
            </div>

            <div class="mb-3">
                <label class="form-label">Nombre</label>
                <input type="text" class="form-control" name="nombre"
                       value="<?= $universidad ? $universidad->getNombre() : '' ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Tipo</label>
                <input type="text" class="form-control" name="tipo"
                       value="<?= $universidad ? $universidad->getTipo() : '' ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Ciudad</label>
                <input type="text" class="form-control" name="ciudad"
                       value="<?= $universidad ? $universidad->getCiudad() : '' ?>" required>
            </div>

            <button type="submit" class="btn btn-primary">Guardar</button>
            <a href="index.php?ruta=universidad" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</div>
</div></div>