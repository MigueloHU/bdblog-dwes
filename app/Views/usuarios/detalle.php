<?php
function h(string $s): string
{
  return htmlspecialchars($s, ENT_QUOTES, 'UTF-8');
}
?>
<!doctype html>
<html lang="es">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <title><?= h($titulo) ?></title>
</head>

<body>
  <?php require __DIR__ . '/../layout/header.php'; ?>

  <div class="container py-4">
    <a class="btn btn-outline-secondary mb-3"
      href="index.php?controller=usuario&action=listar">
      ← Volver al listado
    </a>

    <div class="card shadow-sm">
      <div class="card-body">
        <h1 class="h4 mb-3"><?= h($usuario['nick']) ?></h1>

        <ul class="list-group">
          <li class="list-group-item"><strong>Nombre:</strong> <?= h($usuario['nombre']) ?></li>
          <li class="list-group-item"><strong>Apellidos:</strong> <?= h($usuario['apellidos']) ?></li>
          <li class="list-group-item"><strong>Email:</strong> <?= h($usuario['email']) ?></li>
          <li class="list-group-item"><strong>Perfil:</strong> <?= h($usuario['perfil']) ?></li>
        </ul>
      </div>
    </div>
  </div>
  <?php require __DIR__ . '/../layout/footer.php'; ?>

</body>

</html>