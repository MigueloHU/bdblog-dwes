<?php
use App\Config\Auth;
$user = Auth::user();
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <title><?= htmlspecialchars($titulo) ?></title>
</head>
<body>
  <div class="container py-4 text-center">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <div class="text-start">
        <h1 class="h3 mb-0"><?= htmlspecialchars($titulo) ?></h1>
        <div class="text-muted small">
          Sesión: <?= htmlspecialchars($user['nick']) ?> (<?= htmlspecialchars($user['perfil']) ?>)
        </div>
      </div>
      <div>
        <a class="btn btn-outline-secondary" href="index.php?controller=auth&action=logout">Salir</a>
      </div>
    </div>

    <div class="mb-3 text-start">
      <a class="btn btn-primary" href="index.php?controller=entrada&action=crear">+ Nueva entrada</a>
    </div>

    <div class="table-responsive text-start">
      <table class="table table-striped align-middle">
        <thead>
          <tr>
            <th>Título</th>
            <th>Categoría</th>
            <th>Autor</th>
            <th>Fecha</th>
            <th>Imagen</th>
            <th style="width: 180px;">Acciones</th>
          </tr>
        </thead>
        <tbody>
        <?php if (empty($entradas)): ?>
          <tr><td colspan="6" class="text-center text-muted">No hay entradas.</td></tr>
        <?php else: ?>
          <?php foreach ($entradas as $e): ?>
            <tr>
              <td><?= htmlspecialchars($e['titulo']) ?></td>
              <td><?= htmlspecialchars($e['categoria_nombre']) ?></td>
              <td><?= htmlspecialchars($e['autor_nick']) ?></td>
              <td><?= htmlspecialchars($e['fecha']) ?></td>
              <td>
                <?php if (!empty($e['imagen'])): ?>
                  <img src="uploads/<?= htmlspecialchars($e['imagen']) ?>" style="width:70px; height:auto;">
                <?php else: ?>
                  <span class="text-muted">—</span>
                <?php endif; ?>
              </td>
              <td>
                <a class="btn btn-sm btn-warning" href="index.php?controller=entrada&action=editar&id=<?= (int)$e['id'] ?>">Editar</a>
                <a class="btn btn-sm btn-danger"
                   href="index.php?controller=entrada&action=eliminar&id=<?= (int)$e['id'] ?>"
                   onclick="return confirm('¿Seguro que deseas eliminar esta entrada?');">
                  Eliminar
                </a>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</body>
</html>
