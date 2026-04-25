<?php
$titulo = ($accion === 'crear') ? 'Crear Aspecto Normativo' : 'Editar Aspecto Normativo';

$aspecto_normativo = $aspecto_normativo ?? null;
?>

<div class="row"><div class="col-md-6 mx-auto">
<div class="card">
    <div class="card-header"><?= $titulo ?></div>
    <div class="card-body">

        <form method="POST">

            <div class="mb-3">
                <label class="form-label">ID</label>
                <input type="text" class="form-control" name="id"
                       value="<?= $aspecto_normativo ? $aspecto_normativo->getId() : '' ?>"
                       <?= $accion === 'editar' ? 'readonly' : 'required' ?>>
            </div>

            <div class="mb-3">
                <label class="form-label">Tipo</label>
                <input type="text" class="form-control" name="tipo"
                       value="<?= $aspecto_normativo ? $aspecto_normativo->getTipo() : '' ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Descripción</label>
                <textarea class="form-control" name="descripcion" rows="3" required><?= $aspecto_normativo ? $aspecto_normativo->getDescripcion() : '' ?></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Fuente</label>
                <input type="text" class="form-control" name="fuente"
                       value="<?= $aspecto_normativo ? $aspecto_normativo->getFuente() : '' ?>" required>
            </div>

            <button type="submit" class="btn btn-primary">Guardar</button>
            <a href="index.php?ruta=aspecto_normativo" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</div>
</div></div>