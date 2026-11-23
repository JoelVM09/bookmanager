<?php
require_once __DIR__ . '/includes/init.php';
require_once __DIR__ . '/models/Tienda.php';
require_once __DIR__ . '/models/Titulo.php';

$db = (new Database())->getConnection();
$totalTiendas = count((new Tienda($db))->getAll());
$totalTitulos = (new Titulo($db))->count();

$tituloPagina = 'Inicio';
$paginaActiva = 'inicio';
require __DIR__ . '/includes/header.php';
?>

<div class="row">
    <div class="col-xl-4 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Tiendas registradas</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $totalTiendas ?></div>
                    </div>
                    <div class="col-auto"><i class="fas fa-store fa-2x text-gray-300"></i></div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-4 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Titulos registrados</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $totalTitulos ?></div>
                    </div>
                    <div class="col-auto"><i class="fas fa-book fa-2x text-gray-300"></i></div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card shadow mb-4">
    <div class="card-body">
        <h5 class="text-gray-800">Bienvenido a BookManager</h5>
        <p class="mb-0">Usa el menu lateral para gestionar las tiendas y el inventario de titulos.</p>
    </div>
</div>

<?php require __DIR__ . '/includes/footer.php'; ?>