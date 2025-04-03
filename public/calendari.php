<?php
require_once __DIR__ . '/../config/connexio.php';
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Calendari de festes i activitats</title>

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">

  <link rel="stylesheet" href="./css/estils.css">
  <link rel="stylesheet" href="./css/header_sidebar.css">
</head>
<body>


  <?php include __DIR__ . '../../app/view/parts/header.php'; ?>

  <div class="container-fluid p-0">
    <div class="row g-0">      

      <?php include __DIR__ . '../../app/view/parts/sidebar.php'; ?>

      <main class="col p-2">
        <h1 class="mb-2">Calendari de festes i activitats</h1>

        <div class="text-center mb-4">
        <img src="./Images/vacances.jpg" alt="Imatge de vacançes" class="img-fluid imagen-pequena">
      </div>
      </main>
    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
