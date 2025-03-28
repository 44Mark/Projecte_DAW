<?php
// Views/partials/header.php

// Inicia la sesión si aún no está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<nav class="navbar navbar-dark bg-dark">
  <div class="container-fluid">
    <!-- Logo e instituto -->
    <a class="navbar-brand d-flex align-items-center">
      <img src="../public/images/logo-sapa.svg" alt="Logo Institut sa Palomera" height="68" class="me-2">
    </a>
    <!-- Si el usuario ha iniciado sesión, muestra su nombre y el botón de logout -->
    <?php if (isset($_SESSION['user'])): ?>
      <span class="navbar-text">
         Benvingut, <?php echo htmlspecialchars($_SESSION['user']['name']); ?>
      </span>
      <a href="/DAW/public/logout.php" class="btn btn-outline-light ms-2">
        Tancar sessió
      </a>
    <?php else: ?>
      <!-- Si no ha iniciado sesión, muestra el botón para iniciar sesión -->
      <a href="/DAW/public/auth/google" class="btn btn-outline-light">
        Iniciar sessió
      </a>
    <?php endif; ?>
  </div>
</nav>
