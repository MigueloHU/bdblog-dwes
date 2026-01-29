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

<body class="d-flex flex-column min-vh-100">
  <?php require __DIR__ . '/../layout/header.php'; ?>
  <main class="flex-grow-1">

    <div class="container py-4 text-center">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0"><?= h($titulo) ?></h1>
        <a class="btn btn-outline-secondary" href="index.php?controller=entrada&action=listar">Volver</a>
      </div>

      <div class="table-responsive text-start">
        <table class="table table-striped align-middle">
          <thead>
            <tr>
              <th>Nick</th>
              <th>Nombre</th>
              <th>Email</th>
              <th>Perfil</th>
              <th style="width:120px;">Operaciones</th>
            </tr>
          </thead>
          <tbody>
            <?php if (empty($usuarios)): ?>
              <tr>
                <td colspan="5" class="text-center text-muted">No hay usuarios.</td>
              </tr>
            <?php else: ?>
              <?php foreach ($usuarios as $u): ?>
                <tr>
                  <td><?= h($u['nick']) ?></td>
                  <td><?= h($u['nombre'] . ' ' . $u['apellidos']) ?></td>
                  <td><?= h($u['email']) ?></td>
                  <td><?= h($u['perfil']) ?></td>
                  <td>
                    <a class="btn btn-sm btn-info"
                      href="index.php?controller=usuario&action=detalle&id=<?= (int)$u['id'] ?>">
                      Ver
                    </a>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </main>
  <?php require __DIR__ . '/../layout/footer.php'; ?>

</body>

</html>