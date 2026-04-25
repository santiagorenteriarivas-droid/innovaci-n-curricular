<?php
$titulo = ($accion === 'crear') ? 'Crear Área de Conocimiento' : 'Editar Área de Conocimiento';

$area_conocimiento = $area_conocimiento ?? null;
?>

<div class="row"><div class="col-md-6 mx-auto">
<div class="card">
    <div class="card-header"><?= $titulo ?></div>
    <div class="card-body">

        <form method="POST">

            <div class="mb-3">
                <label class="form-label">ID</label>
                <input type="text" class="form-control" name="id"
                       value="<?= $area_conocimiento ? $area_conocimiento->getId() : '' ?>"
                       <?= $accion === 'editar' ? 'readonly' : 'required' ?>>
            </div>

            <div class="mb-3">
                <label class="form-label">Gran Área</label>
                <input type="text" class="form-control" name="gran_area"
                       value="<?= $area_conocimiento ? $area_conocimiento->getGran_Area() : '' ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Área</label>
                <input type="text" class="form-control" name="area"
                       value="<?= $area_conocimiento ? $area_conocimiento->getArea() : '' ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Disciplina</label>
                <input type="text" class="form-control" name="disciplina"
                       value="<?= $area_conocimiento ? $area_conocimiento->getDisciplina() : '' ?>" required>
            </div>

            <button type="submit" class="btn btn-primary">Guardar</button>
            <a href="index.php?ruta=area_conocimiento" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</div>
</div></div>