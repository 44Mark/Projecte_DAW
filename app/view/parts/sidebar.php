<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<nav class="sidebar-lila col-auto p-0">
  <ul class="nav flex-column">
    <li class="nav-item mb-2">
      <a class="nav-link" href="../public/calendari.php">Calendari</a>
    </li>
    <li class="nav-item mb-2">
      <a class="nav-link" href="../public/horaris.php">Horaris</a>
    </li>
    <?php if (isset($_SESSION['user'])): ?>
      <li class="nav-item mb-2">
        <a class="nav-link" href="./gestioAules.php">Gestió Aules</a>
      </li>
    <?php endif; ?>
  </ul>
</nav>
