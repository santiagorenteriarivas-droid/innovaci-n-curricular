<?php
$titulo = ($accion === 'crear') ? 'Crear Aliado' : 'Editar Aliado';

$aliado = $aliado ?? null;
?>

<div class="row"><div class="col-md-6 mx-auto">
<div class="card">
    <div class="card-header"><?= $titulo ?></div>
    <div class="card-body">

        <form method="POST">

            <div class="mb-3">
                <label class="form-label">Nit</label>
                <input type="text" class="form-control" name="nit"
                       value="<?= $aliado ? $aliado->getNit() : '' ?>"
                       <?= $accion === 'editar' ? 'readonly' : 'required' ?>>
            </div>

            <div class="mb-3">
                <label class="form-label">Razón Social</label>
                <input type="text" class="form-control" name="razon_social"
                       value="<?= $aliado ? $aliado->getRazon_Social() : '' ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Nombre de Contacto</label>
                <input type="text" class="form-control" name="nombre_contacto"
                       value="<?= $aliado ? $aliado->getNombre_Contacto() : '' ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Correo</label>
                <input type="email" class="form-control" name="correo"
                       value="<?= $aliado ? $aliado->getCorreo() : '' ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Teléfono</label>
                <input type="tel" class="form-control" name="telefono"
                       value="<?= $aliado ? $aliado->getTelefono() : '' ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Ciudad</label>
                <input type="text" class="form-control" name="ciudad"
                       value="<?= $aliado ? $aliado->getCiudad() : '' ?>" required>
            </div>

            <button type="submit" class="btn btn-primary">Guardar</button>
            <a href="index.php?ruta=aliado" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</div>
</div></div>