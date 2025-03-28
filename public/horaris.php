<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Horaris</title>
  <!-- Enlace a Bootstrap 5 (CDN) -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <!-- Tu hoja de estilos adicional -->
  <link rel="stylesheet" href="./css/estils.css">
  <link rel="stylesheet" href="./css/header_sidebar.css">

</head>
<body>

  <!-- Incluye el navbar (header) -->
  <?php include __DIR__ . '../../app/view/parts/header.php'; ?>

  <div class="container-fluid p-0">
    <div class="row g-0">      
      <!-- Incluye la barra lateral -->
      <?php include __DIR__ . '../../app/view/parts/sidebar.php'; ?>

      <main class="col p-2">
        <h1 class="mb-2">Horari DAW 2</h1>

        <div class="text-center mb-4">
        <img src="./Images/horari.jpg" alt="Horari DAW 2" class="img-fluid imagen-pequena">
      </div>
      </main>
    </div>
  </div>

  <!-- JS de Bootstrap (opcional) -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
