<?php
session_start();

ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/control/ControlAliado.php';
require_once __DIR__ . '/control/ControlArea_conocimiento.php';
require_once __DIR__ . '/control/ControlAspecto_normativo.php';
require_once __DIR__ . '/control/ControlCar_innovacion.php';
require_once __DIR__ . '/control/ControlEnfoque.php';
require_once __DIR__ . '/control/ControlPractica_estrategia.php';
require_once __DIR__ . '/control/ControlUniversidad.php';

$ruta   = $_GET['ruta'] ?? 'home';      
$accion = $_GET['accion'] ?? 'listar';  
$id     = $_GET['id'] ?? null;          

function renderizar($vista, $datos = []) {
    global $ruta;       
    extract($datos);    
    ob_start();         
    require __DIR__ . "/vista/plantillas/{$vista}.php";  
    $contenido = ob_get_clean(); 
    require __DIR__ . '/vista/plantillas/base.php';  
}

function redireccionar($ruta, $mensaje = null) {
    if ($mensaje) {
        $_SESSION['mensaje'] = $mensaje; 
    }
    header("Location: index.php?ruta={$ruta}");
    exit();  
}

switch ($ruta) {

    case 'home':
        $stats = [
            'aliado'  => (new ControlAliado())->contar(),
            'area_conocimiento'  => (new ControlArea_conocimiento())->contar(),
            'aspecto_normativo' => (new ControlAspecto_normativo())->contar(),
            'car_innovacion'  => (new ControlCar_innovacion())->contar(),
            'enfoque'  => (new ControlEnfoque())->contar(),
            'practica_estrategia' => (new ControlPractica_estrategia())->contar(),
            'universidad' => (new ControlUniversidad())->contar(),
        ];
        renderizar('dashboard', ['stats' => $stats]);
        break;

    case 'aliado':
        $ctrl = new ControlAliado();

        switch ($accion) {

            case 'listar':
                $aliados = $ctrl->listar();
                renderizar('crud/aliado_listar', ['aliados' => $aliados]);
                break;

            case 'crear':
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $a = new Aliado($_POST['nit'], $_POST['razon_social'], $_POST['nombre_contacto'], $_POST['correo'], $_POST['telefono'], $_POST['ciudad']);
                    $ctrl->guardar($a); 
                    redireccionar('aliado', ['tipo' => 'success', 'texto' => 'Aliado creado']);
                }
                renderizar('crud/aliado_form', ['accion' => 'crear']);
                break;

            case 'editar':
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $a = new Aliado($id,  $_POST['razon_social'], $_POST['nombre_contacto'], $_POST['correo'], $_POST['telefono'], $_POST['ciudad']);
                    $ctrl->modificar($id, $a); 
                    redireccionar('aliado', ['tipo' => 'success', 'texto' => 'Aliado actualizado']);
                }
                $aliado = $ctrl->buscarPorId($id);
                renderizar('crud/aliado_form', ['aliado' => $aliado, 'accion' => 'editar']);
                break;

            case 'eliminar':
                $ctrl->borrar($id);
                redireccionar('aliado', ['tipo' => 'success', 'texto' => 'Aliado eliminado']);
                break;
        }
        break;

    case 'area_conocimiento':
        $ctrl = new ControlArea_conocimiento();

        switch ($accion) {
            case 'listar':
                $areas_conocimiento = $ctrl->listar();
                renderizar('crud/area_conocimiento_listar', ['areas_conocimiento' => $areas_conocimiento]);
                break;

            case 'crear':
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $a = new Area_conocimiento($_POST['id'], $_POST['gran_area'], $_POST['area'], $_POST['disciplina']);
                    $ctrl->guardar($a);
                    redireccionar('area_conocimiento', ['tipo' => 'success', 'texto' => 'Area de conocimiento creada']);
                }
                renderizar('crud/area_conocimiento_form', ['accion' => 'crear']);
                break;

            case 'editar':
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $a = new Area_conocimiento($id, $_POST['gran_area'], $_POST['area'], $_POST['disciplina']);
                    $ctrl->modificar($id, $a);
                    redireccionar('area_conocimiento', ['tipo' => 'success', 'texto' => 'Area de concimiento actualizada']);
                }
                $area_conocimiento = $ctrl->buscarPorId($id);
                renderizar('crud/area_conocimiento_form', ['area_conocimiento' => $area_conocimiento, 'accion' => 'editar']);
                break;

            case 'eliminar':
                $ctrl->borrar($id);
                redireccionar('area_conocimiento', ['tipo' => 'success', 'texto' => 'area de concominiento eliminada']);
                break;
        }
        break;

    case 'aspecto_normativo':
        $ctrl = new ControlAspecto_normativo();

        switch ($accion) {
            case 'listar':
                $aspectos_normativos = $ctrl->listar();
                renderizar('crud/aspecto_normativo_listar', ['aspectos_normativos' => $aspectos_normativos]);
                break;

            case 'crear':
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $a = new Aspecto_normativo($_POST['id'], $_POST['tipo'], $_POST['descripcion'], $_POST['fuente']);
                    $ctrl->guardar($a);
                    redireccionar('aspecto_normativo', ['tipo' => 'success', 'texto' => 'Aspecto normativo creado']);
                }
                renderizar('crud/aspecto_normativo_form', ['accion' => 'crear']);
                break;

            case 'editar':
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $a = new Aspecto_normativo($id, $_POST['tipo'], $_POST['descripcion'], $_POST['fuente']);
                    $ctrl->modificar($id, $a);
                    redireccionar('aspecto_normativo', ['tipo' => 'success', 'texto' => 'Aspecto normativo actualizado']);
                }
                $aspecto_normativo = $ctrl->buscarPorId($id);
                renderizar('crud/aspecto_normativo_form', ['aspecto_normativo' => $aspecto_normativo, 'accion' => 'editar']);
                break;

            case 'eliminar':
                $ctrl->borrar($id);
                redireccionar('aspecto_normativo', ['tipo' => 'success', 'texto' => 'Aspecto normativo eliminado']);
                break;
        }
        break;

    case 'car_innovacion':
    $ctrl = new ControlCar_innovacion();
    switch ($accion) {
        case 'listar':
            $car_innovaciones = $ctrl->listar();
            renderizar('crud/car_innovacion_listar', ['car_innovaciones' => $car_innovaciones]);
            break;
        case 'crear':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $c = new Car_innovacion($_POST['id'], $_POST['nombre'], $_POST['descripcion'], $_POST['tipo']);
                $ctrl->guardar($c);
                redireccionar('car_innovacion', ['tipo' => 'success', 'texto' => 'Car_innovacion creado']);
            }
            renderizar('crud/car_innovacion_form', ['accion' => 'crear']);
            break;
        case 'editar':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $c = new Car_innovacion($id, $_POST['nombre'], $_POST['descripcion'], $_POST['tipo']);
                $ctrl->modificar($id, $c);
                redireccionar('car_innovacion', ['tipo' => 'success', 'texto' => 'Car_innovacion actualizado']);
            }
            $car_innovacion = $ctrl->buscarPorId($id);
            renderizar('crud/car_innovacion_form', ['car_innovacion' => $car_innovacion, 'accion' => 'editar']);
            break;
        case 'eliminar':
            $ctrl->borrar($id);
            redireccionar('car_innovacion', ['tipo' => 'success', 'texto' => 'Car_innovacion eliminado']);
            break;
    }
    break;

    case 'enfoque':
    $ctrl = new ControlEnfoque();
    switch ($accion) {
        case 'listar':
            $enfoques = $ctrl->listar();
            renderizar('crud/enfoque_listar', ['enfoques' => $enfoques]);
            break;
        case 'crear':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $e = new Enfoque($_POST['id'], $_POST['nombre'], $_POST['descripcion']);
                $ctrl->guardar($e);
                redireccionar('enfoque', ['tipo' => 'success', 'texto' => 'Enfoque creado']);
            }
            renderizar('crud/enfoque_form', ['accion' => 'crear']);
            break;
        case 'editar':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $e = new Enfoque($id, $_POST['nombre'], $_POST['descripcion']);
                $ctrl->modificar($id, $e);
                redireccionar('enfoque', ['tipo' => 'success', 'texto' => 'Enfoque actualizado']);
            }
            $enfoque = $ctrl->buscarPorId($id);
            renderizar('crud/enfoque_form', ['enfoque' => $enfoque, 'accion' => 'editar']);
            break;
        case 'eliminar':
            $ctrl->borrar($id);
            redireccionar('enfoque', ['tipo' => 'success', 'texto' => 'Enfoque eliminado']);
            break;
    }
    break;

    case 'practica_estrategia':
    $ctrl = new ControlPractica_estrategia();
    switch ($accion) {
        case 'listar':
            $practicas = $ctrl->listar();
            renderizar('crud/practica_estrategia_listar', ['practicas_estrategia' => $practicas]);
            break;
        case 'crear':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $p = new Practica_estrategia($_POST['id'], $_POST['tipo'], $_POST['nombre'], $_POST['descripcion']);
                $ctrl->guardar($p);
                redireccionar('practica_estrategia', ['tipo' => 'success', 'texto' => 'Practica_estrategia creada']);
            }
            renderizar('crud/practica_estrategia_form', ['accion' => 'crear']);
            break;
        case 'editar':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $p = new Practica_estrategia($id, $_POST['tipo'], $_POST['nombre'], $_POST['descripcion']);
                $ctrl->modificar($id, $p);
                redireccionar('practica_estrategia', ['tipo' => 'success', 'texto' => 'Practica_estrategia actualizada']);
            }
            $practica_estrategia = $ctrl->buscarPorId($id);
            renderizar('crud/practica_estrategia_form', ['practica_estrategia' => $practica_estrategia, 'accion' => 'editar']);
            break;
        case 'eliminar':
            $ctrl->borrar($id);
            redireccionar('practica_estrategia', ['tipo' => 'success', 'texto' => 'Practica_estrategia eliminada']);
            break;
    }
    break;

    case 'universidad':
    $ctrl = new ControlUniversidad();
    switch ($accion) {
        case 'listar':
            $universidades = $ctrl->listar();
            renderizar('crud/universidad_listar', ['universidades' => $universidades]);
            break;
        case 'crear':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $u = new Universidad($_POST['id'], $_POST['nombre'], $_POST['tipo'], $_POST['ciudad']);
                $ctrl->guardar($u);
                redireccionar('universidad', ['tipo' => 'success', 'texto' => 'Universidad creada']);
            }
            renderizar('crud/universidad_form', ['accion' => 'crear']);
            break;
        case 'editar':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $u = new Universidad($id, $_POST['nombre'], $_POST['tipo'], $_POST['ciudad']);
                $ctrl->modificar($id, $u);
                redireccionar('universidad', ['tipo' => 'success', 'texto' => 'Universidad actualizada']);
            }
            $universidad = $ctrl->buscarPorId($id);
            renderizar('crud/universidad_form', ['universidad' => $universidad, 'accion' => 'editar']);
            break;
        case 'eliminar':
            $ctrl->borrar($id);
            redireccionar('universidad', ['tipo' => 'success', 'texto' => 'Universidad eliminada']);
            break;
    }
    break;

    default:
        renderizar('dashboard', ['stats' => ['personas' => 0, 'usuarios' => 0, 'productos' => 0]]);
        break;
}
?>