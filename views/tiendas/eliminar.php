<?php
require_once __DIR__ . '/../../includes/init.php';
require_once __DIR__ . '/../../models/Tienda.php';

$db = (new Database())->getConnection();
$tiendaModel = new Tienda($db);

$id = $_GET['id'] ?? $_POST['id_tienda'] ?? '';
$tienda = $tiendaModel->getById($id);

if (!$tienda) {
    setFlash('danger', 'La tienda solicitada no existe.');
    header('Location: index.php');
    exit;
}

$tieneVentas = $tiendaModel->tieneVentas($id);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($tieneVentas) {
        setFlash('danger', 'No se puede eliminar: la tienda tiene ventas registradas.');
    } else {
        $tiendaModel->delete($id);
        setFlash('success', 'Tienda eliminada correctamente.');
    }
    header('Location: index.php');
    exit;
}

$tituloPagina = 'Eliminar tienda';
$paginaActiva = 'tiendas';
require __DIR__ . '/../../includes/header.php';
?>

<div class="card shadow mb-4 border-left-danger">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-danger">Confirmar eliminacion</h6>
    </div>
    <div class="card-body">
        <?php if ($tieneVentas): ?>
            <div class="alert alert-warning">
                Esta tienda tiene ventas registradas y no puede eliminarse.
            </div>
        <?php else: ?>
            <p>Estas seguro de que deseas eliminar esta tienda? Esta accion no se puede deshacer.</p>
        <?php endif; ?>

        <ul>
            <li><strong>ID:</strong> <?= e($tienda['id_tienda']) ?></li>
            <li><strong>Nombre:</strong> <?= e($tienda['nombre_tienda']) ?></li>
            <li><strong>Ciudad:</strong> <?= e($tienda['ciudad']) ?></li>
        </ul>

        <form method="POST" action="eliminar.php?id=<?= urlencode($id) ?>">
            <input type="hidden" name="id_tienda" value="<?= e($id) ?>">
            <?php if (!$tieneVentas): ?>
                <button type="submit" class="btn btn-danger"><i class="fas fa-trash"></i> Si, eliminar</button>
            <?php endif; ?>
            <a href="index.php" class="btn btn-secondary">Volver</a>
        </form>
    </div>
</div>

<?php require __DIR__ . '/../../includes/footer.php'; ?>