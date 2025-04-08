<!-- Modal per poder fer una reserva nova. -->
<link rel="stylesheet" href="/DAW/public/css/modal.css">

<div class="modal fade" id="crearReservaModal" tabindex="-1" aria-labelledby="crearReservaModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
    <form id="crearReservaForm">
        <div class="modal-header">
          <h5 class="modal-title" id="crearReservaModalLabel">Crear Nova Reserva</h5>
          <div>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
          </div>
        </div>
        <div class="modal-body">
          <div class="container">
            <div class="row mb-3">
              <div class="col-md-6">
                <label for="motivo" class="form-label">Motiu de la Reserva</label>
                <input type="text" class="form-control" id="motivo" name="motivo" required>
              </div>
              <div class="col-md-6">
                <?php
                if (!isset($_SESSION)) {
                    session_start();
                }
                $profeNombre = isset($_SESSION['profe_nombre']) ? htmlspecialchars($_SESSION['profe_nombre'], ENT_QUOTES, 'UTF-8') : 'N/A';
                ?>
                <label for="profe" class="form-label">Professor</label>
                <input type="text" class="form-control" id="profe" name="profe" value="<?= $profeNombre; ?>" readonly>
              </div>
            </div>
            <div class="row mb-3">
              <div class="col-md-6">
                <label for="grup" class="form-label">Grup</label>
                <select class="form-select" id="grup" name="grup" required>
                  <option value="" disabled selected>---</option>
                  <?php foreach($grupCrearReserva as $grup): ?></div>
                    <option value="<?= htmlspecialchars($grup['grup'], ENT_QUOTES, 'UTF-8'); ?>">
                      <?= htmlspecialchars($grup['grup'], ENT_QUOTES, 'UTF-8'); ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div class="col-md-6">
                <label for="aula" class="form-label">Aula</label>
                <select class="form-select" id="aula" name="aula" required>
                  <option value="" disabled selected>---</option>
                  <?php foreach($aulasCrearReserva as $aula): ?>
                    <option value="<?= htmlspecialchars($aula['nom'], ENT_QUOTES, 'UTF-8'); ?>">
                      <?= htmlspecialchars($aula['nom'], ENT_QUOTES, 'UTF-8'); ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>

            <!-- Fila amb data, hora inici, hora fi, repetir i repetició -->
            <div class="row mb-2 align-items-end" id="fila-repeticio">
              <div class="col-md-3 data-container">
                <label for="data" class="form-label">Data</label>
                <input type="date" class="form-control" name="data" required>
              </div>
              <div class="col-md-2 ini-container">
                <label for="ini" class="form-label">Hora d'inici</label>
                <input type="time" class="form-control" name="ini" required>
              </div>
              <div class="col-md-2 fin-container">
                <label for="fin" class="form-label">Hora fi</label>
                <input type="time" class="form-control" name="fin" required>
              </div>
              <div class="col-md-3 repetir-container">
                <label for="repetir" class="form-label">Repetir
                  <span class="info-icon" data-bs-toggle="tooltip" data-bs-placement="top" title="Si vols repetir mes cops aquesta reserva, selecciona el número de setmanes o mesos.">ℹ️</span>
                </label>
                <select class="form-select" id="repetir" name="repetir">
                  <option value="" disabled selected>---</option>
                  <option value="semanal">Setmanalment</option>
                  <option value="mensual">Mensualment</option>
                </select>
              </div>
              <div class="col-md-2 repeticion-container" id="repeticion-container" style="display: none;">
                <label for="num_repeticions" class="form-label" id="repeticion-label">Número de repeticions</label>
                <input type="number" class="form-control" name="num_repeticions" id="num_repeticions" min="1">
              </div>
              <div class="col-md-1 d-flex align-items-end">
                <button type="button" class="btn btn-danger btn-sm eliminar-fila">X</button>
              </div>
            </div>
            <div class="row mb-1">
              <div class="col-md-3 d-flex align-items-end">
                <button type="button" class="btn btn-secondary mt-4">Més reserves</button>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer mt-4">
          <button type="submit" id="submitReserva" class="btn btn-primary">Guardar Reserva</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Script per la logica d'afegir repeticions i afegir fer mes reserves -->
<script src="./js/repetirReserva.js"></script>