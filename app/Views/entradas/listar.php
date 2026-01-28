<?php

use App\Config\Auth;

$user = Auth::user();

$ordenActual = $_GET['orden'] ?? 'fecha';
$dirActual   = $_GET['dir'] ?? 'desc';
$qActual     = trim($_GET['q'] ?? '');

function nextDir(string $orden, string $ordenActual, string $dirActual): string
{
    if ($orden !== $ordenActual) return 'asc';
    return ($dirActual === 'asc') ? 'desc' : 'asc';
}

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
    <div class="container py-4 text-center">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div class="text-start">
                <h1 class="h3 mb-0"><?= h($titulo) ?></h1>
                <div class="text-muted small">
                    Sesión: <?= h($user['nick']) ?> (<?= h($user['perfil']) ?>)
                </div>
            </div>
            <div>
                <a class="btn btn-outline-secondary" href="index.php?controller=auth&action=logout">Salir</a>
            </div>
        </div>

        <div class="mb-3 text-start">
            <a class="btn btn-primary" href="index.php?controller=entrada&action=crear">+ Nueva entrada</a>
        </div>
        <?php if (!empty($_GET['ok'])): ?>
            <div class="alert alert-success text-start">
                Entrada creada correctamente ✅
            </div>
        <?php endif; ?>


        <!-- BUSCADOR (importante: form bien cerrado) -->
        <form class="row g-2 align-items-center mb-3 text-start" method="get" action="index.php">
            <input type="hidden" name="controller" value="entrada">
            <input type="hidden" name="action" value="listar">
            <input type="hidden" name="orden" value="<?= h($ordenActual) ?>">
            <input type="hidden" name="dir" value="<?= h($dirActual) ?>">

            <div class="col-12 col-md-8">
                <input type="text" class="form-control" name="q"
                    placeholder="Buscar por título, descripción, categoría o autor..."
                    value="<?= h($qActual) ?>">
            </div>

            <div class="col-12 col-md-4 d-grid gap-2 d-md-flex">
                <button class="btn btn-success" type="submit">Buscar</button>
                <a class="btn btn-outline-secondary" href="index.php?controller=entrada&action=listar">Limpiar</a>
            </div>
        </form>

        <div class="table-responsive text-start">
            <table class="table table-striped align-middle">
                <thead>
                    <tr>
                        <th>
                            <a href="index.php?controller=entrada&action=listar&orden=titulo&dir=<?= nextDir('titulo', $ordenActual, $dirActual) ?>&q=<?= urlencode($qActual) ?>">
                                Título
                            </a>
                        </th>
                        <th>
                            <a href="index.php?controller=entrada&action=listar&orden=categoria&dir=<?= nextDir('categoria', $ordenActual, $dirActual) ?>&q=<?= urlencode($qActual) ?>">
                                Categoría
                            </a>
                        </th>
                        <th>
                            <a href="index.php?controller=entrada&action=listar&orden=autor&dir=<?= nextDir('autor', $ordenActual, $dirActual) ?>&q=<?= urlencode($qActual) ?>">
                                Autor
                            </a>
                        </th>
                        <th>
                            <a href="index.php?controller=entrada&action=listar&orden=fecha&dir=<?= nextDir('fecha', $ordenActual, $dirActual) ?>&q=<?= urlencode($qActual) ?>">
                                Fecha
                            </a>
                        </th>
                        <th>Imagen</th>
                        <th style="width: 260px;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($entradas)): ?>
                        <tr>
                            <td colspan="6" class="text-center text-muted">No hay entradas.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($entradas as $e): ?>
                            <tr>
                                <td><?= h($e['titulo']) ?></td>
                                <td><?= h($e['categoria_nombre']) ?></td>
                                <td><?= h($e['autor_nick']) ?></td>
                                <td><?= h($e['fecha']) ?></td>
                                <td>
                                    <?php if (!empty($e['imagen'])): ?>
                                        <img src="uploads/<?= h($e['imagen']) ?>" style="width:70px; height:auto;">
                                    <?php else: ?>
                                        <span class="text-muted">—</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a class="btn btn-sm btn-info"
                                        href="index.php?controller=entrada&action=detalle&id=<?= (int)$e['id'] ?>">
                                        Ver
                                    </a>

                                    <?php
                                    $puedeEditar = Auth::isAdmin() || ((int)$e['usuario_id'] === (int)$user['id']);
                                    ?>

                                    <?php if ($puedeEditar): ?>
                                        <a class="btn btn-sm btn-warning"
                                            href="index.php?controller=entrada&action=editar&id=<?= (int)$e['id'] ?>">
                                            Editar
                                        </a>
                                        <a class="btn btn-sm btn-danger"
                                            href="index.php?controller=entrada&action=eliminar&id=<?= (int)$e['id'] ?>"
                                            onclick="return confirm('¿Seguro que deseas eliminar esta entrada?');">
                                            Eliminar
                                        </a>
                                    <?php endif; ?>
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