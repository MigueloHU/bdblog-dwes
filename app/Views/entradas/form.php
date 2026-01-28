<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <title><?= htmlspecialchars($titulo) ?></title>
</head>
<body>
  <div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h1 class="h3 mb-0"><?= htmlspecialchars($titulo) ?></h1>
      <a class="btn btn-outline-secondary" href="index.php?controller=entrada&action=listar">Volver</a>
    </div>

    <?php if (!empty($errores)): ?>
      <div class="alert alert-danger">
        <ul class="mb-0">
          <?php foreach ($errores as $e): ?>
            <li><?= htmlspecialchars($e) ?></li>
          <?php endforeach; ?>
        </ul>
      </div>
    <?php endif; ?>

    <?php
      $action = ($modo === 'editar') ? 'actualizar' : 'guardar';
    ?>

    <form class="card shadow-sm p-4"
          method="post"
          enctype="multipart/form-data"
          action="index.php?controller=entrada&action=<?= $action ?>">

      <?php if ($modo === 'editar'): ?>
        <input type="hidden" name="id" value="<?= (int)$entrada['id'] ?>">
      <?php endif; ?>

      <div class="mb-3">
        <label class="form-label">Categoría</label>
        <select class="form-select" name="categoria_id" required>
          <option value="">-- Selecciona --</option>
          <?php foreach ($categorias as $c): ?>
            <option value="<?= (int)$c['id'] ?>"
              <?= ((int)$entrada['categoria_id'] === (int)$c['id']) ? 'selected' : '' ?>>
              <?= htmlspecialchars($c['nombre']) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="mb-3">
        <label class="form-label">Título</label>
        <input class="form-control" type="text" name="titulo" value="<?= htmlspecialchars($entrada['titulo']) ?>" required>
      </div>

      <div class="mb-3">
        <label class="form-label">Descripción</label>
        <textarea class="form-control" name="descripcion" rows="6" required><?= htmlspecialchars($entrada['descripcion']) ?></textarea>
      </div>

      <div class="mb-3">
        <label class="form-label">Imagen (opcional)</label>
        <input class="form-control" type="file" name="imagen" accept=".jpg,.jpeg,.png,.gif,.webp">

        <?php if (!empty($entrada['imagen'])): ?>
          <div class="mt-3">
            <div class="text-muted small mb-1">Imagen actual:</div>
            <img src="uploads/<?= htmlspecialchars($entrada['imagen']) ?>" style="max-width:220px; height:auto;">
          </div>
        <?php endif; ?>
      </div>

      <button class="btn btn-primary" type="submit">
        <?= ($modo === 'editar') ? 'Guardar cambios' : 'Crear entrada' ?>
      </button>
    </form>
  </div>
</body>
</html>
