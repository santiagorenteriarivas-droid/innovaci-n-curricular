<?php
$titulo = ($accion === 'crear') ? 'Crear Característica de Innovación' : 'Editar Característica de Innovación';

$car_innovacion = $car_innovacion ?? null;
?>

<div class="row"><div class="col-md-6 mx-auto">
<div class="card">
    <div class="card-header"><?= $titulo ?></div>
    <div class="card-body">

        <form method="POST">

            <div class="mb-3">
                <label class="form-label">ID</label>
                <input type="text" class="form-control" name="id"
                       value="<?= $car_innovacion ? $car_innovacion->getId() : '' ?>"
                       <?= $accion === 'editar' ? 'readonly' : 'required' ?>>
            </div>

            <div class="mb-3">
                <label class="form-label">Nombre</label>
                <input type="text" class="form-control" name="nombre"
                       value="<?= $car_innovacion ? $car_innovacion->getNombre() : '' ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Descripción</label>
                <input type="text" class="form-control" name="descripcion"
                       value="<?= $car_innovacion ? $car_innovacion->getDescripcion() : '' ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Tipo</label>
                <input type="text" class="form-control" name="tipo"
                       value="<?= $car_innovacion ? $car_innovacion->getTipo() : '' ?>" required>
            </div>

            <button type="submit" class="btn btn-primary">Guardar</button>
            <a href="index.php?ruta=car_innovacion" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</div>
</div></div>