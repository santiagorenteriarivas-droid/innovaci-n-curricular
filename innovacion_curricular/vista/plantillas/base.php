<!DOCTYPE html> 
<html lang="es"> 
<head>
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?= $titulo ?? 'Innovacion curricular' ?></title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <style>
        :root { --sidebar-width: 240px; }

        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }

        .sidebar {
            position: fixed; top: 0; left: 0; height: 100vh; 
            width: var(--sidebar-width); 
            background: linear-gradient(180deg, #0f2027 0%, #203a43 50%, #2c5364 100%); 
            padding-top: 20px; color: white; overflow-y: auto; 
        }

        .sidebar .nav-link {
            color: rgba(255,255,255,0.75); padding: 10px 20px;
            transition: all 0.3s; 
            border-left: 3px solid transparent;
        }

        .sidebar .nav-link:hover, .sidebar .nav-link.active {
            color: #fff; background: rgba(255,255,255,0.1);
            border-left-color: #17a2b8;
        }

        .sidebar .nav-link i { width: 24px; margin-right: 8px; }

        .main-content {
            margin-left: var(--sidebar-width); padding: 20px;
            min-height: 100vh; background: #f0f2f5;
        }

        .navbar-top {
            background: #fff; box-shadow: 0 1px 4px rgba(0,0,0,0.08);
            padding: 14px 24px; margin-bottom: 24px; border-radius: 8px;
        }

        .card { border: none; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); }
        .card-header {
            background: linear-gradient(135deg, #0f2027, #2c5364);
            color: #fff; font-weight: 600; border-radius: 10px 10px 0 0 !important;
        }
        .btn-primary {
            background: linear-gradient(135deg, #203a43, #2c5364); border: none;
        }
        .btn-primary:hover { background: linear-gradient(135deg, #2c5364, #203a43); }
    </style>
</head>
<body>

    <div class="sidebar">
        <div class="text-center mb-4">
            <h5 class="fw-bold"><i class="bi bi-receipt"></i> Innovación curricular</h5>
        </div>

        <nav class="nav flex-column">
            <a class="nav-link <?= ($ruta ?? '') === 'home' ? 'active' : '' ?>" href="index.php?ruta=home">
                <i class="bi bi-house-door"></i> Inicio
            </a>

            <div class="px-3 mt-3 mb-1"><small class="text-uppercase opacity-50">Tablas</small></div>

            <a class="nav-link <?= ($ruta ?? '') === 'aliado' ? 'active' : '' ?>" href="index.php?ruta=aliado">
                <i class="bi bi-people"></i> Aliados
            </a>
            <a class="nav-link <?= ($ruta ?? '') === 'area_conocimiento' ? 'active' : '' ?>" href="index.php?ruta=area_conocimiento">
                <i class="bi bi-book"></i> Áreas de conocimiento
            </a>
            <a class="nav-link <?= ($ruta ?? '') === 'aspecto_normativo' ? 'active' : '' ?>" href="index.php?ruta=aspecto_normativo">
                <i class="bi bi-journal-check"></i> Aspectos normativos
            </a>
            <a class="nav-link <?= ($ruta ?? '') === 'car_innovacion' ? 'active' : '' ?>" href="index.php?ruta=car_innovacion">
                <i class="bi bi-lightbulb"></i> Características de innovación
            </a>
            <a class="nav-link <?= ($ruta ?? '') === 'enfoque' ? 'active' : '' ?>" href="index.php?ruta=enfoque">
                <i class="bi bi-eye"></i> Enfoque
            </a>
            <a class="nav-link <?= ($ruta ?? '') === 'practica_estrategia' ? 'active' : '' ?>" href="index.php?ruta=practica_estrategia">
                <i class="bi bi-layers"></i> Prácticas de estrategias
            </a>
            <a class="nav-link <?= ($ruta ?? '') === 'universidad' ? 'active' : '' ?>" href="index.php?ruta=universidad">
                <i class="bi bi-building"></i> Universidades
            </a>
        </nav>
    </div>

    <div class="main-content">
        <div class="navbar-top d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><?= $titulo ?? 'Panel' ?></h5>
            <span class="text-muted small">Innovación curricular</span>
        </div>
        <?php if (isset($_SESSION['mensaje'])): ?>
        <div class="alert alert-<?= $_SESSION['mensaje']['tipo'] ?? 'info' ?> alert-dismissible fade show">
            <?= $_SESSION['mensaje']['texto'] ?? '' ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['mensaje']); endif; ?>

        <?= $contenido ?? '' ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>