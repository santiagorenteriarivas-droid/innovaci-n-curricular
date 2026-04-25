<?php

ob_start();

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
$baseUrl = rtrim(str_replace('/pages', '', $scriptDir), '/');
$baseUrl = rtrim(str_replace('/vista', '', $baseUrl), '/');

if (!isset($paginaActual)) $paginaActual = '';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <title><?= $tituloPagina ?? 'Sistema de Innovación Curricular' ?></title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />

    <link href="<?= $baseUrl ?>/assets/css/app.css" rel="stylesheet" />
</head>
<body>
    <div class="page">

        <div class="sidebar">
            <div class="top-row ps-3 navbar navbar-dark">
                <div class="container-fluid">
                    <a class="navbar-brand" href="<?= $baseUrl ?>/pages/home.php">
                        <span class="bi bi-book"></span> Innovación Curricular
                    </a>
                </div>
            </div>

            <input type="checkbox" title="Menu de navegacion" class="navbar-toggler" />

            <div class="nav-scrollable">
                <nav class="nav flex-column">

                    <!-- INICIO -->
                    <div class="nav-item px-3">
                        <a class="nav-link <?= $paginaActual === 'home' ? 'active' : '' ?>"
                           href="<?= $baseUrl ?>/pages/home.php">
                            <span class="bi bi-house-door-fill-nav-menu"></span> Inicio
                        </a>
                    </div>

                    <!-- SEPARADOR - PROYECTO 1: TABLAS INDEPENDIENTES -->
                    <div class="nav-item px-3 pt-2">
                        <small class="text-muted">
                            <strong>PROYECTO 1 - TABLAS BASE</strong>
                        </small>
                    </div>

                    <!-- Área de Conocimiento -->
                    <div class="nav-item px-3">
                        <a class="nav-link <?= $paginaActual === 'area_conocimiento' ? 'active' : '' ?>"
                           href="<?= $baseUrl ?>/../innovacion_curricular/index.php?ruta=area_conocimiento">
                            <span class="bi bi-list-nested-nav-menu"></span> Área de Conocimiento
                        </a>
                    </div>

                    <!-- Universidad -->
                    <div class="nav-item px-3">
                        <a class="nav-link <?= $paginaActual === 'universidad' ? 'active' : '' ?>"
                           href="<?= $baseUrl ?>/../innovacion_curricular/index.php?ruta=universidad">
                            <span class="bi bi-list-nested-nav-menu"></span> Universidad
                        </a>
                    </div>

                    <!-- Aspecto Normativo -->
                    <div class="nav-item px-3">
                        <a class="nav-link <?= $paginaActual === 'aspecto_normativo' ? 'active' : '' ?>"
                           href="<?= $baseUrl ?>/../innovacion_curricular/index.php?ruta=aspecto_normativo">
                            <span class="bi bi-list-nested-nav-menu"></span> Aspecto Normativo
                        </a>
                    </div>

                    <!-- Práctica Estrategia -->
                    <div class="nav-item px-3">
                        <a class="nav-link <?= $paginaActual === 'practica_estrategia' ? 'active' : '' ?>"
                           href="<?= $baseUrl ?>/../innovacion_curricular/index.php?ruta=practica_estrategia">
                            <span class="bi bi-list-nested-nav-menu"></span> Práctica Estrategia
                        </a>
                    </div>

                    <!-- Enfoque -->
                    <div class="nav-item px-3">
                        <a class="nav-link <?= $paginaActual === 'enfoque' ? 'active' : '' ?>"
                           href="<?= $baseUrl ?>/../innovacion_curricular/index.php?ruta=enfoque">
                            <span class="bi bi-list-nested-nav-menu"></span> Enfoque
                        </a>
                    </div>

                    <!-- Característica de Innovación -->
                    <div class="nav-item px-3">
                        <a class="nav-link <?= $paginaActual === 'car_innovacion' ? 'active' : '' ?>"
                           href="<?= $baseUrl ?>/../innovacion_curricular/index.php?ruta=car_innovacion">
                            <span class="bi bi-list-nested-nav-menu"></span> Característica Innovación
                        </a>
                    </div>

                    <!-- Aliado -->
                    <div class="nav-item px-3">
                        <a class="nav-link <?= $paginaActual === 'aliado' ? 'active' : '' ?>"
                           href="<?= $baseUrl ?>/../innovacion_curricular/index.php?ruta=aliado">
                            <span class="bi bi-list-nested-nav-menu"></span> Aliado
                        </a>
                    </div>

                    <!-- SEPARADOR - PROYECTO 2: TABLAS CON RELACIONES COMPLEJAS -->
                    <div class="nav-item px-3 pt-2">
                        <small class="text-muted">
                            <strong>PROYECTO 2 - MÓDULOS INTEGRADOS</strong>
                        </small>
                    </div>

                    <!-- Facultad -->
                    <div class="nav-item px-3">
                        <a class="nav-link <?= $paginaActual === 'facultad' ? 'active' : '' ?>"
                           href="<?= $baseUrl ?>/pages/facultad.php">
                            <span class="bi bi-list-nested-nav-menu"></span> Facultad
                        </a>
                    </div>

                    <!-- Programa -->
                    <div class="nav-item px-3">
                        <a class="nav-link <?= $paginaActual === 'programa' ? 'active' : '' ?>"
                           href="<?= $baseUrl ?>/pages/programa.php">
                            <span class="bi bi-list-nested-nav-menu"></span> Programa
                        </a>
                    </div>

                    <!-- Acreditación -->
                    <div class="nav-item px-3">
                        <a class="nav-link <?= $paginaActual === 'acreditacion' ? 'active' : '' ?>"
                           href="<?= $baseUrl ?>/pages/acreditacion.php">
                            <span class="bi bi-list-nested-nav-menu"></span> Acreditación
                        </a>
                    </div>

                    <!-- Registro Calificado -->
                    <div class="nav-item px-3">
                        <a class="nav-link <?= $paginaActual === 'registro_calificado' ? 'active' : '' ?>"
                           href="<?= $baseUrl ?>/pages/registro_calificado.php">
                            <span class="bi bi-list-nested-nav-menu"></span> Registro Calificado
                        </a>
                    </div>

                    <!-- Actividad Académica -->
                    <div class="nav-item px-3">
                        <a class="nav-link <?= $paginaActual === 'activ_academica' ? 'active' : '' ?>"
                           href="<?= $baseUrl ?>/pages/activ_academica.php">
                            <span class="bi bi-list-nested-nav-menu"></span> Actividad Académica
                        </a>
                    </div>

                    <!-- Pasantía -->
                    <div class="nav-item px-3">
                        <a class="nav-link <?= $paginaActual === 'pasantia' ? 'active' : '' ?>"
                           href="<?= $baseUrl ?>/pages/pasantia.php">
                            <span class="bi bi-list-nested-nav-menu"></span> Pasantía
                        </a>
                    </div>

                    <!-- Premio -->
                    <div class="nav-item px-3">
                        <a class="nav-link <?= $paginaActual === 'premio' ? 'active' : '' ?>"
                           href="<?= $baseUrl ?>/pages/premio.php">
                            <span class="bi bi-list-nested-nav-menu"></span> Premio
                        </a>
                    </div>

                    <!-- Programa Actividad Complementaria -->
                    <div class="nav-item px-3">
                        <a class="nav-link <?= $paginaActual === 'programa_ac' ? 'active' : '' ?>"
                           href="<?= $baseUrl ?>/pages/programa_ac.php">
                            <span class="bi bi-list-nested-nav-menu"></span> Programa Actividad Complementaria
                        </a>
                    </div>

                    <!-- Programa Prácticas Experienciales -->
                    <div class="nav-item px-3">
                        <a class="nav-link <?= $paginaActual === 'programa_pe' ? 'active' : '' ?>"
                           href="<?= $baseUrl ?>/pages/programa_pe.php">
                            <span class="bi bi-list-nested-nav-menu"></span> Programa Prácticas Experienciales
                        </a>
                    </div>

                    <!-- Programa Innovación Curricular -->
                    <div class="nav-item px-3">
                        <a class="nav-link <?= $paginaActual === 'programa_ci' ? 'active' : '' ?>"
                           href="<?= $baseUrl ?>/pages/programa_ci.php">
                            <span class="bi bi-list-nested-nav-menu"></span> Programa Innovación Curricular
                        </a>
                    </div>

                    <!-- Análisis Programa -->
                    <div class="nav-item px-3">
                        <a class="nav-link <?= $paginaActual === 'an_programa' ? 'active' : '' ?>"
                           href="<?= $baseUrl ?>/pages/an_programa.php">
                            <span class="bi bi-list-nested-nav-menu"></span> Análisis Programa
                        </a>
                    </div>

                    <!-- Enfoque Registro Calificado -->
                    <div class="nav-item px-3">
                        <a class="nav-link <?= $paginaActual === 'enfoque_rc' ? 'active' : '' ?>"
                           href="<?= $baseUrl ?>/pages/enfoque_rc.php">
                            <span class="bi bi-list-nested-nav-menu"></span> Enfoque Registro Calificado
                        </a>
                    </div>

                    <!-- Actividad Académica Registro Calificado -->
                    <div class="nav-item px-3">
                        <a class="nav-link <?= $paginaActual === 'aa_rc' ? 'active' : '' ?>"
                           href="<?= $baseUrl ?>/pages/aa_rc.php">
                            <span class="bi bi-list-nested-nav-menu"></span> Actividad Académica Registro Calificado
                        </a>
                    </div>

                    <!-- Docente Departamento -->
                    <div class="nav-item px-3">
                        <a class="nav-link <?= $paginaActual === 'docente_departamento' ? 'active' : '' ?>"
                           href="<?= $baseUrl ?>/pages/docente_departamento.php">
                            <span class="bi bi-list-nested-nav-menu"></span> Docente Departamento
                        </a>
                    </div>

                    <!-- Alianza -->
                    <div class="nav-item px-3">
                        <a class="nav-link <?= $paginaActual === 'alianza' ? 'active' : '' ?>"
                           href="<?= $baseUrl ?>/pages/alianza.php">
                            <span class="bi bi-list-nested-nav-menu"></span> Alianza
                        </a>
                    </div>

                </nav>
            </div>
        </div>

        <main>
            <div class="top-row px-4">
                <span>Sistema de Innovación Curricular — Gestión Integrada de Módulos</span>
            </div>

            <article class="content px-4">

                <?php if (isset($_SESSION['mensaje'])): ?>
                    <div class="alert alert-<?= $_SESSION['tipo'] ?? 'info' ?> alert-dismissible fade show mt-3">
                        <?= $_SESSION['mensaje'] ?>
                        <button type="button" class="btn-close" onclick="this.parentElement.remove()"></button>
                    </div>
                    <?php unset($_SESSION['mensaje'], $_SESSION['tipo']); ?>
                <?php endif; ?>
