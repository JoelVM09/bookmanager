<?php
require_once __DIR__ . '/../../includes/init.php';
require_once __DIR__ . '/../../models/Tienda.php';

$db = (new Database())->getConnection();
$tiendas = (new Tienda($db))->getAll();

$tituloPagina = 'Tiendas';
$paginaActiva = 'tiendas';
require __DIR__ . '/../../includes/header.php';
?>

<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold text-primary">Listado de tiendas</h6>
        <a href="crear.php" class="btn btn-primary btn-sm">
            <i class="fas fa-plus"></i> Nueva tienda
        </a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                <thead class="thead-light">
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Direccion</th>
                        <th>Ciudad</th>
                        <th>Provincia</th>
                        <th>Cod. postal</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($tiendas)): ?>
                        <tr><td colspan="7" class="text-center text-muted">No hay tiendas registradas.</td></tr>
                    <?php else: ?>
                        <?php foreach ($tiendas as $t): ?>
                            <tr>
                                <td><?= e($t['id_tienda']) ?></td>
                                <td><?= e($t['nombre_tienda']) ?></td>
                                <td><?= e($t['direccion']) ?></td>
                                <td><?= e($t['ciudad']) ?></td>
                                <td><?= e($t['provincia']) ?></td>
                                <td><?= e($t['codigo_postal']) ?></td>
                                <td class="text-center">
                                    <a href="editar.php?id=<?= urlencode($t['id_tienda']) ?>" class="btn btn-warning btn-sm" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="eliminar.php?id=<?= urlencode($t['id_tienda']) ?>" class="btn btn-danger btn-sm" title="Eliminar">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require __DIR__ . '/../../includes/footer.php'; ?>