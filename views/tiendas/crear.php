<?php
require_once __DIR__ . '/../../includes/init.php';
require_once __DIR__ . '/../../models/Tienda.php';

$db = (new Database())->getConnection();
$tiendaModel = new Tienda($db);

$errores = [];
$datos = [
    'id_tienda' => '', 'nombre_tienda' => '', 'direcc_tienda' => '',
    'ciudad' => '', 'estado' => '', 'pais' => 'USA',
    'cod_postal' => '', 'terminos' => 'Net 30',
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $datos = array_merge($datos, $_POST);
    $errores = $tiendaModel->validar($_POST, true);

    if (empty($errores)) {
        $tiendaModel->create($_POST);
        setFlash('success', 'Tienda creada correctamente.');
        header('Location: index.php');
        exit;
    }
}

$tituloPagina = 'Nueva tienda';
$paginaActiva = 'tiendas';
require __DIR__ . '/../../includes/header.php';
?>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Datos de la tienda</h6>
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

        <form method="POST" action="crear.php">
            <div class="form-row">
                <div class="form-group col-md-3">
                    <label for="id_tienda">ID (4 caracteres)</label>
                    <input type="text" class="form-control" id="id_tienda" name="id_tienda"
                           maxlength="4" value="<?= e($datos['id_tienda']) ?>" required>
                </div>
                <div class="form-group col-md-9">
                    <label for="nombre_tienda">Nombre</label>
                    <input type="text" class="form-control" id="nombre_tienda" name="nombre_tienda"
                           maxlength="40" value="<?= e($datos['nombre_tienda']) ?>" required>
                </div>
            </div>
            <div class="form-group">
                <label for="direcc_tienda">Direccion</label>
                <input type="text" class="form-control" id="direcc_tienda" name="direcc_tienda"
                       maxlength="40" value="<?= e($datos['direcc_tienda']) ?>">
            </div>
            <div class="form-row">
                <div class="form-group col-md-4">
                    <label for="ciudad">Ciudad</label>
                    <input type="text" class="form-control" id="ciudad" name="ciudad"
                           maxlength="15" value="<?= e($datos['ciudad']) ?>">
                </div>
                <div class="form-group col-md-2">
                    <label for="estado">Estado (2 letras)</label>
                    <input type="text" class="form-control" id="estado" name="estado"
                           maxlength="2" value="<?= e($datos['estado']) ?>">
                </div>
                <div class="form-group col-md-2">
                    <label for="pais">Pais (3 letras)</label>
                    <input type="text" class="form-control" id="pais" name="pais"
                           maxlength="3" value="<?= e($datos['pais']) ?>">
                </div>
                <div class="form-group col-md-2">
                    <label for="cod_postal">Cod. postal</label>
                    <input type="text" class="form-control" id="cod_postal" name="cod_postal"
                           maxlength="5" value="<?= e($datos['cod_postal']) ?>">
                </div>
                <div class="form-group col-md-2">
                    <label for="terminos">Terminos</label>
                    <select class="form-control" id="terminos" name="terminos" required>
                        <?php foreach (Tienda::getTerminos() as $op): ?>
                            <option value="<?= e($op) ?>" <?= $datos['terminos'] === $op ? 'selected' : '' ?>>
                                <?= e($op) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Guardar</button>
            <a href="index.php" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</div>

<?php require __DIR__ . '/../../includes/footer.php'; ?>