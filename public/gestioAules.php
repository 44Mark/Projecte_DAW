<?php
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}
require_once __DIR__ . '../../app/controlador/llistatAules.php';

$maxAulasPorColumna = 12;
// Agafem un array i el dividim en trossos de $maxAulasPorColumna.
$files = array_chunk($aulas, $maxAulasPorColumna);
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Gestió i Reserves d'aules del centre</title>

  <!-- Importació de Bootstrap -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <!-- Biblioteca d'icones de Bootstrap -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
  <!-- Importació per adminsitrar les interaccions dels components de Bootstrap -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <!-- Importació de la llibreria de FullCalendar -->
  <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.7/index.global.min.js"></script>

  <!-- jQuery Library per poder utilitzar el Toast-->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

  <!-- Awesome per els logos -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">

  <!-- Toast CSS -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
  <!-- Toast JS -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

  <link rel="stylesheet" href="./css/estils.css">
  <link rel="stylesheet" href="./css/header_sidebar.css">
  <link rel="stylesheet" href="./css/fullcallendar.css">

  <script src="./js/fullcalendar.js"></script>
  <script src="./js/aules.js"></script>
  <script src="./js/toast.js"></script>
  <script src="./js/modalVeureReserves.js"></script>
  <script src="./js/modificarEliminarReserves.js"></script>
</head>
<body>

  <!-- Incluim el header de forma modular -->
  <?php include __DIR__ . '../../app/view/parts/header.php'; ?>

  <div class="container-fluid p-0">
    <div class="row g-0">      

      <!-- Incluim la barra lateral de forma modular-->
      <?php include __DIR__ . '../../app/view/parts/sidebar.php'; ?>

      <main class="col p-1">
        <div class="row">
          <!-- Div per el calendari -->
          <div class="col-12 col-lg-8 mb-3">
            <div id="calendar"></div>
          </div>
          <!-- Div per el llistat d'aules -->
          <div class="col-12 col-lg-4 mb-6 mt-5 custom-margin-top">
            <?php include __DIR__ . '../../app/view/parts/llistatAules.php'; ?>
          </div>
        </div>
      </main>
    </div>
  </div>
  
  <!-- Modal per a veure l'informació d'una reserva d'aula -->
  <?php include __DIR__ . '../../app/view/parts/modalInformacioReserva.php'; ?>
  <!-- Modal para crear una nueva reserva -->
  <?php include __DIR__ . '../../app/view/parts/modalCrearReserva.php'; ?>
  <!-- Modal per a veure les reserves propies d'un usuari -->
  <?php include __DIR__ . '../../app/view/parts/modalVeureReserva.php'; ?>

</body>
</html>