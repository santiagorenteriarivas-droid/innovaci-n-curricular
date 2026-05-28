<?php
$titulo = ($accion === 'crear') ? 'Crear Práctica de Estrategia' : 'Editar Práctica de Estrategia';

$practica_estrategia = $practica_estrategia ?? null;
?>

<div class="row"><div class="col-md-6 mx-auto">
<div class="card">
    <div class="card-header"><?= $titulo ?></div>
    <div class="card-body">

        <form method="POST">

            <div class="mb-3">
                <label class="form-label">ID</label>
                <input type="text" class="form-control" name="id"
                       value="<?= $practica_estrategia ? $practica_estrategia->getId() : '' ?>"
                       <?= $accion === 'editar' ? 'readonly' : 'required' ?>>
            </div>

            <div class="mb-3">
                <label class="form-label">Tipo</label>
                <input type="text" class="form-control" name="tipo"
                       value="<?= $practica_estrategia ? $practica_estrategia->getTipo() : '' ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Nombre</label>
                <input type="text" class="form-control" name="nombre"
                       value="<?= $practica_estrategia ? $practica_estrategia->getNombre() : '' ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Descripción</label>
                <input type="text" class="form-control" name="descripcion"
                       value="<?= $practica_estrategia ? $practica_estrategia->getDescripcion() : '' ?>" required>
            </div>

            <button type="submit" class="btn btn-primary">Guardar</button>
            <a href="index.php?ruta=practica_estrategia" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</div>
</div></div>