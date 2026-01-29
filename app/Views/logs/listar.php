<?php
function h(string $s): string { return htmlspecialchars($s, ENT_QUOTES, 'UTF-8'); }
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
  <div class="container py-4 text-center">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h1 class="h3 mb-0"><?= h($titulo) ?></h1>
      <div>
        <a class="btn btn-outline-secondary" href="index.php?controller=entrada&action=listar">Volver</a>
      </div>
    </div>

    <div class="mb-3 text-start">
      <a class="btn btn-outline-danger" target="_blank"
         href="index.php?controller=log&action=pdf">
        Imprimir PDF
      </a>
    </div>

    <div class="table-responsive text-start">
      <table class="table table-striped align-middle">
        <thead>
          <tr>
            <th>ID</th>
            <th>Fecha/Hora</th>
            <th>Usuario</th>
            <th>Operación</th>
            <th style="width:140px;">Operaciones</th>
          </tr>
        </thead>
        <tbody>
        <?php if (empty($logs)): ?>
          <tr><td colspan="5" class="text-center text-muted">No hay logs.</td></tr>
        <?php else: ?>
          <?php foreach ($logs as $l): ?>
            <tr>
              <td><?= (int)$l['id'] ?></td>
              <td><?= h($l['fecha_hora']) ?></td>
              <td><?= h($l['usuario']) ?></td>
              <td><?= h($l['operacion']) ?></td>
              <td>
                <button type="button"
                        class="btn btn-sm btn-danger btn-confirm"
                        data-bs-toggle="modal"
                        data-bs-target="#confirmModal"
                        data-action-url="index.php?controller=log&action=eliminar&id=<?= (int)$l['id'] ?>"
                        data-log-id="<?= (int)$l['id'] ?>">
                  Eliminar
                </button>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>

  <!-- Modal confirmación -->
  <div class="modal fade" id="confirmModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Confirmar eliminación</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        <div class="modal-body" id="confirmBody">
          ¿Deseas eliminar este log?
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
          <a href="#" class="btn btn-danger" id="confirmBtn">Sí, eliminar</a>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    (function () {
      const body = document.getElementById('confirmBody');
      const btn  = document.getElementById('confirmBtn');

      document.querySelectorAll('.btn-confirm').forEach(b => {
        b.addEventListener('click', () => {
          const url = b.getAttribute('data-action-url');
          const id  = b.getAttribute('data-log-id');
          btn.setAttribute('href', url);
          body.innerHTML = 'Vas a <strong>eliminar</strong> el log con ID <strong>' + id + '</strong>. ¿Continuar?';
        });
      });
    })();
  </script>
</body>
</html>
