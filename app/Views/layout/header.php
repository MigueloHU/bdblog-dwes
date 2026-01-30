<?php
// Detectar si estamos en la página de login
$esLogin = isset($_GET['controller']) && $_GET['controller'] === 'auth';
?>

<header class="bg-dark text-white py-3 mb-4">
  <div class="container-fluid text-center">
    <?php if ($esLogin): ?>
      <h1 class="h4 mb-0">Mi pequeño Blog</h1>
    <?php else: ?>
      <h1 class="h4 mb-0">
        <a href="index.php?controller=entrada&action=listar"
           class="text-white text-decoration-none">
          Mi pequeño Blog
        </a>
      </h1>
    <?php endif; ?>
  </div>
</header>
