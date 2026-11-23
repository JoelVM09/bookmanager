<?php
$tituloPagina = $tituloPagina ?? 'BookManager';
$paginaActiva = $paginaActiva ?? 'inicio';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title><?= e($tituloPagina) ?> | BookManager</title>

    <link href="<?= BASE_URL ?>/assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,300,400,600,700,800,900" rel="stylesheet">
    <link href="<?= BASE_URL ?>/assets/css/sb-admin-2.min.css" rel="stylesheet">
</head>
<body id="page-top">
<div id="wrapper">

    <!-- Sidebar -->
    <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

        <a class="sidebar-brand d-flex align-items-center justify-content-center" href="<?= BASE_URL ?>/index.php">
            <div class="sidebar-brand-icon"><i class="fas fa-book-open"></i></div>
            <div class="sidebar-brand-text mx-3">BookManager</div>
        </a>

        <hr class="sidebar-divider my-0">

        <li class="nav-item <?= $paginaActiva === 'inicio' ? 'active' : '' ?>">
            <a class="nav-link" href="<?= BASE_URL ?>/index.php">
                <i class="fas fa-fw fa-tachometer-alt"></i><span>Inicio</span>
            </a>
        </li>

        <hr class="sidebar-divider">
        <div class="sidebar-heading">Gestion</div>

        <li class="nav-item <?= $paginaActiva === 'tiendas' ? 'active' : '' ?>">
            <a class="nav-link" href="<?= BASE_URL ?>/views/tiendas/index.php">
                <i class="fas fa-fw fa-store"></i><span>Tiendas</span>
            </a>
        </li>

        <li class="nav-item <?= $paginaActiva === 'titulos' ? 'active' : '' ?>">
            <a class="nav-link" href="<?= BASE_URL ?>/views/titulos/index.php">
                <i class="fas fa-fw fa-book"></i><span>Titulos</span>
            </a>
        </li>

        <hr class="sidebar-divider d-none d-md-block">
        <div class="text-center d-none d-md-inline">
            <button class="rounded-circle border-0" id="sidebarToggle"></button>
        </div>
    </ul>

    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">

            <!-- Topbar -->
            <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
                <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                    <i class="fa fa-bars"></i>
                </button>
                <span class="h5 mb-0 text-gray-800"><?= e($tituloPagina) ?></span>
            </nav>

            <div class="container-fluid">
                <?php mostrarFlash(); ?>