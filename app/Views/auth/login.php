<!doctype html>
<html lang="es">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <title><?= htmlspecialchars($titulo) ?></title>
</head>

<body class="d-flex flex-column min-vh-100">
  <?php require __DIR__ . '/../layout/header.php'; ?>
  <main class="flex-grow-1">

    <div class="container py-5">
      <div class="row justify-content-center">
        <div class="col-12 col-md-6 col-lg-4">
          <div class="card shadow-sm">
            <div class="card-body p-4 text-center">
              <h1 class="h4 mb-3">Iniciar sesión</h1>

              <?php if (!empty($errores)): ?>
                <div class="alert alert-danger text-start">
                  <ul class="mb-0">
                    <?php foreach ($errores as $e): ?>
                      <li><?= htmlspecialchars($e) ?></li>
                    <?php endforeach; ?>
                  </ul>
                </div>
              <?php endif; ?>

              <form method="post" class="text-start">
                <div class="mb-3">
                  <label class="form-label">Email</label>
                  <input type="email" name="email" class="form-control" required>
                </div>

                <div class="mb-3">
                  <label class="form-label">Contraseña</label>
                  <input type="password" name="password" class="form-control" required>
                </div>

                <button class="btn btn-primary w-100" type="submit">Entrar</button>
              </form>

              <hr class="my-4">

              <p class="small text-muted mb-0">
                Prueba: admin@blog.com / admin1234
              </p>
              <p class="small text-muted mb-0">
                Prueba: user@blog.com / user1234
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>
  <?php require __DIR__ . '/../layout/footer.php'; ?>
</body>

</html>