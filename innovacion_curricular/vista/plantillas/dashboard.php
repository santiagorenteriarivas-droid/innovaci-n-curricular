<?php
$titulo = 'Inicio';
?>

<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="card text-center p-4">
            <div class="display-4 text-primary mb-2"><i class="bi bi-people"></i></div>
            <h3><?= $stats['aliado'] ?></h3>
            <p class="text-muted mb-2">Aliados</p>
            <a href="index.php?ruta=aliado" class="btn btn-outline-primary btn-sm">Ver listado</a>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card text-center p-4">
            <div class="display-4 text-success mb-2"><i class="bi bi-book"></i></div>
            <h3><?= $stats['area_conocimiento'] ?></h3>
            <p class="text-muted mb-2">Áreas de conocimiento</p>
            <a href="index.php?ruta=area_conocimiento" class="btn btn-outline-info btn-sm">Ver listado</a>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card text-center p-4">
            <div class="display-4 text-info mb-2"><i class="bi bi-journal-check"></i></div>
            <h3><?= $stats['aspecto_normativo'] ?></h3>
            <p class="text-muted mb-2">Aspectos normativos</p>
            <a href="index.php?ruta=aspecto_normativo" class="btn btn-outline-warning btn-sm">Ver listado</a>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card text-center p-4">
            <div class="display-4 text-info mb-2"><i class="bi bi-lightbulb"></i></div>
            <h3><?= $stats['car_innovacion'] ?></h3>
            <p class="text-muted mb-2">Características de innovación</p>
            <a href="index.php?ruta=car_innovacion" class="btn btn-outline-dark btn-sm">Ver listado</a>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card text-center p-4">
            <div class="display-4 text-info mb-2"><i class="bi bi-eye"></i></div>
            <h3><?= $stats['enfoque'] ?></h3>
            <p class="text-muted mb-2">Enfoque</p>
            <a href="index.php?ruta=enfoque" class="btn btn-outline-secondary btn-sm">Ver listado</a>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card text-center p-4">
            <div class="display-4 text-info mb-2"><i class="bi bi-layers"></i></div>
            <h3><?= $stats['practica_estrategia'] ?></h3>
            <p class="text-muted mb-2">Prácticas estrategias</p>
            <a href="index.php?ruta=practica_estrategia" class="btn btn-outline-danger btn-sm">Ver listado</a>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card text-center p-4">
            <div class="display-4 text-info mb-2"><i class="bi bi-building"></i></div>
            <h3><?= $stats['universidad'] ?></h3>
            <p class="text-muted mb-2">Universidades</p>
            <a href="index.php?ruta=universidad" class="btn btn-outline-success btn-sm">Ver listado</a>
        </div>
    </div>
</div>