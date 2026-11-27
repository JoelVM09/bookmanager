<?php
require_once __DIR__ . '/../../includes/init.php';
require_once __DIR__ . '/../../models/Titulo.php';

$db = (new Database())->getConnection();
$titulos = (new Titulo($db))->getAll();

$tituloPagina = 'Titulos';
$paginaActiva = 'titulos';
require __DIR__ . '/../../includes/header.php';
?>

<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold text-primary">Listado de titulos</h6>
        <a href="crear.php" class="btn btn-primary btn-sm">
            <i class="fas fa-plus"></i> Nuevo titulo
        </a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                <thead class="thead-light">
                    <tr>
                        <th>ID</th>
                        <th>Titulo</th>
                        <th>Tipo</th>
                        <th>Editorial</th>
                        <th class="text-right">Precio</th>
                        <th class="text-right">Ventas</th>
                        <th>Publicacion</th>
                        <th class="text-center">Contrato</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($titulos)): ?>
                        <tr><td colspan="9" class="text-center text-muted">No hay titulos registrados.</td></tr>
                    <?php else: ?>
                        <?php foreach ($titulos as $t): ?>
                            <tr>
                                <td><?= e($t['id_titulo']) ?></td>
                                <td><?= e($t['titulo']) ?></td>
                                <td><?= e($t['tipo']) ?></td>
                                <td><?= e($t['nombre_pub'] ?? 'Sin editorial') ?></td>
                                <td class="text-right">
                                    <?= $t['precio'] !== null ? number_format((float) $t['precio'], 2) : '-' ?>
                                </td>
                                <td class="text-right">
                                    <?= $t['total_ventas'] !== null ? number_format((int) $t['total_ventas']) : '-' ?>
                                </td>
                                <td><?= e(substr((string) $t['fecha_pub'], 0, 10)) ?></td>
                                <td class="text-center">
                                    <?= (string) $t['contrato'] === '1'
                                        ? '<span class="badge badge-success">Si</span>'
                                        : '<span class="badge badge-secondary">No</span>' ?>
                                </td>
                                <td class="text-center">
                                    <a href="editar.php?id=<?= urlencode($t['id_titulo']) ?>" class="btn btn-warning btn-sm" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="eliminar.php?id=<?= urlencode($t['id_titulo']) ?>" class="btn btn-danger btn-sm" title="Eliminar">
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