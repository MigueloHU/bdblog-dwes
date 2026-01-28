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
    <div class="mb-3">
      <a class="btn btn-outline-secondary"
         href="index.php?controller=entrada&action=listar">
        ← Volver al listado
      </a>
    </div>

    <article class="card shadow-sm">
      <?php if (!empty($entrada['imagen'])): ?>
        <img src="uploads/<?= htmlspecialchars($entrada['imagen']) ?>"
             class="card-img-top"
             alt="Imagen de la entrada">
      <?php endif; ?>

      <div class="card-body">
        <h1 class="card-title h3 mb-3">
          <?= htmlspecialchars($entrada['titulo']) ?>
        </h1>

        <div class="mb-3 text-muted small">
          <strong>Categoría:</strong> <?= htmlspecialchars($entrada['categoria_nombre']) ?> |
          <strong>Autor:</strong> <?= htmlspecialchars($entrada['autor_nick']) ?> |
          <strong>Fecha:</strong> <?= htmlspecialchars($entrada['fecha']) ?>
        </div>

        <div class="card-text">
          <?= nl2br(htmlspecialchars($entrada['descripcion'])) ?>
        </div>
      </div>
    </article>
  </div>
</body>
</html>
