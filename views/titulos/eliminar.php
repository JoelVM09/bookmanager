<?php
require_once __DIR__ . '/../../includes/init.php';
require_once __DIR__ . '/../../models/Titulo.php';

$db = (new Database())->getConnection();
$tituloModel = new Titulo($db);

$id = $_GET['id'] ?? $_POST['id_titulo'] ?? '';
$titulo = $tituloModel->getById($id);

if (!$titulo) {
    setFlash('danger', 'El titulo solicitado no existe.');
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tituloModel->delete($id);
    setFlash('success', 'Titulo eliminado correctamente.');
    header('Location: index.php');
    exit;
}

$tituloPagina = 'Eliminar titulo';
$paginaActiva = 'titulos';
require __DIR__ . '/../../includes/header.php';
?>

<div class="card shadow mb-4 border-left-danger">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-danger">Confirmar eliminacion</h6>
    </div>
    <div class="card-body">
        <p>Estas seguro de que deseas eliminar este titulo? Esta accion no se puede deshacer.</p>
        <ul>
            <li><strong>ID:</strong> <?= e($titulo['id_titulo']) ?></li>
            <li><strong>Titulo:</strong> <?= e($titulo['titulo']) ?></li>
            <li><strong>Tipo:</strong> <?= e($titulo['tipo']) ?></li>
        </ul>

        <form method="POST" action="eliminar.php?id=<?= urlencode($id) ?>">
            <input type="hidden" name="id_titulo" value="<?= e($id) ?>">
            <button type="submit" class="btn btn-danger"><i class="fas fa-trash"></i> Si, eliminar</button>
            <a href="index.php" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</div>

<?php require __DIR__ . '/../../includes/footer.php'; ?>