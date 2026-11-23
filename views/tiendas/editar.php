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

$errores = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $errores = $tiendaModel->validar($_POST, false);

    if (empty($errores)) {
        $tiendaModel->update($id, $_POST);
        setFlash('success', 'Tienda actualizada correctamente.');
        header('Location: index.php');
        exit;
    }

    // Si hay errores, se conservan los valores escritos (el ID no cambia)
    $tienda = array_merge($tienda, $_POST, ['id_tienda' => $id]);
}

$tituloPagina = 'Editar tienda';
$paginaActiva = 'tiendas';
require __DIR__ . '/../../includes/header.php';
?>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Editar tienda <?= e($tienda['id_tienda']) ?></h6>
    </div>
    <div class="card-body">

        <?php if (!empty($errores)): ?>
            <div class="alert alert-danger">
                <ul class="mb-0">
                    <?php foreach ($errores as $err): ?>
                        <li><?= e($err) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form method="POST" action="editar.php?id=<?= urlencode($id) ?>">
            <input type="hidden" name="id_tienda" value="<?= e($id) ?>">

            <div class="form-row">
                <div class="form-group col-md-3">
                    <label>ID</label>
                    <input type="text" class="form-control" value="<?= e($tienda['id_tienda']) ?>" disabled>
                </div>
                <div class="form-group col-md-9">
                    <label for="nombre_tienda">Nombre</label>
                    <input type="text" class="form-control" id="nombre_tienda" name="nombre_tienda"
                           maxlength="40" value="<?= e($tienda['nombre_tienda']) ?>" required>
                </div>
            </div>
            <div class="form-group">
                <label for="direccion">Direccion</label>
                <input type="text" class="form-control" id="direccion" name="direccion"
                       maxlength="60" value="<?= e($tienda['direccion']) ?>">
            </div>
            <div class="form-row">
                <div class="form-group col-md-4">
                    <label for="ciudad">Ciudad</label>
                    <input type="text" class="form-control" id="ciudad" name="ciudad"
                           maxlength="30" value="<?= e($tienda['ciudad']) ?>">
                </div>
                <div class="form-group col-md-5">
                    <label for="provincia">Provincia</label>
                    <input type="text" class="form-control" id="provincia" name="provincia"
                           maxlength="30" value="<?= e($tienda['provincia']) ?>">
                </div>
                <div class="form-group col-md-3">
                    <label for="codigo_postal">Codigo postal</label>
                    <input type="text" class="form-control" id="codigo_postal" name="codigo_postal"
                           maxlength="5" value="<?= e($tienda['codigo_postal']) ?>">
                </div>
            </div>
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Actualizar</button>
            <a href="index.php" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</div>

<?php require __DIR__ . '/../../includes/footer.php'; ?>