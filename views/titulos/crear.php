<?php
require_once __DIR__ . '/../../includes/init.php';
require_once __DIR__ . '/../../models/Titulo.php';

$db = (new Database())->getConnection();
$tituloModel = new Titulo($db);
$publicadores = $tituloModel->getPublicadores();

$errores = [];
$datos = [
    'id_titulo' => '', 'titulo' => '', 'tipo' => '',
    'id_pub' => '', 'precio' => '', 'notas' => '', 'fecha_pub' => '',
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $datos = array_merge($datos, $_POST);
    $errores = $tituloModel->validar($_POST, true);

    if (empty($errores)) {
        $tituloModel->create($_POST);
        setFlash('success', 'Titulo creado correctamente.');
        header('Location: index.php');
        exit;
    }
}

$tituloPagina = 'Nuevo titulo';
$paginaActiva = 'titulos';
require __DIR__ . '/../../includes/header.php';
?>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Datos del titulo</h6>
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
                    <label for="id_titulo">ID (hasta 6 caracteres)</label>
                    <input type="text" class="form-control" id="id_titulo" name="id_titulo"
                           maxlength="6" value="<?= e($datos['id_titulo']) ?>" required>
                </div>
                <div class="form-group col-md-9">
                    <label for="titulo">Titulo</label>
                    <input type="text" class="form-control" id="titulo" name="titulo"
                           maxlength="80" value="<?= e($datos['titulo']) ?>" required>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-4">
                    <label for="tipo">Tipo</label>
                    <input type="text" class="form-control" id="tipo" name="tipo"
                           maxlength="12" placeholder="ej. historia" value="<?= e($datos['tipo']) ?>">
                </div>
                <div class="form-group col-md-4">
                    <label for="id_pub">Editorial</label>
                    <select class="form-control" id="id_pub" name="id_pub">
                        <option value="">-- Sin editorial --</option>
                        <?php foreach ($publicadores as $p): ?>
                            <option value="<?= e($p['id_pub']) ?>"
                                <?= $datos['id_pub'] === $p['id_pub'] ? 'selected' : '' ?>>
                                <?= e($p['nombre_pub']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group col-md-2">
                    <label for="precio">Precio (RD$)</label>
                    <input type="number" step="0.01" min="0" class="form-control" id="precio" name="precio"
                           value="<?= e($datos['precio']) ?>">
                </div>
                <div class="form-group col-md-2">
                    <label for="fecha_pub">Publicacion</label>
                    <input type="date" class="form-control" id="fecha_pub" name="fecha_pub"
                           value="<?= e($datos['fecha_pub']) ?>">
                </div>
            </div>
            <div class="form-group">
                <label for="notas">Notas</label>
                <textarea class="form-control" id="notas" name="notas" rows="3"
                          maxlength="200"><?= e($datos['notas']) ?></textarea>
            </div>
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Guardar</button>
            <a href="index.php" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</div>

<?php require __DIR__ . '/../../includes/footer.php'; ?>