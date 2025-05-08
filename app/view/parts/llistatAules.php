<div class="row">
  <?php
    // Colors per a les aules.
    $colorsAules = [
      'Aula A0'      => '#e6194B',
      'Aula A1'      => '#f58231',
      'Aula A2'      => '#ffe119',
      'Aula A3'      => '#3cb44b',
      'Aula A4'      => '#4363d8',
      'Biblioteca'   => '#42d4f4',
      'Aula BTX1A'   => '#911eb4',
      'Aula BTX1B'   => '#f032e6',
      'Aula BTX2A'   => '#46f0f0',
      'Aula BTX2B'   => '#bfef45',
      'Aula DAW 1'   => '#fabed4',
      'Aula FPB 1'   => '#ffd700',
      'Aula FPB 2'   => '#dcbeff',
      'GIMNAS1'      => '#469990',
      'GIMNAS2'      => '#9A6324',
    ];
    
  ?>

  <?php foreach ($files as $chunk): ?>
    <div class="col-auto">
      <div class="list-group">
        <?php foreach ($chunk as $aula): ?>
          <?php
            $nomAula = $aula['nom'];
            $color = $colorsAules[$nomAula] ?? '#000'; // negro si no hay color definido
          ?>
          <div class="list-group-item d-flex align-items-center">
            <i class="bi bi-eye-slash me-2 toggle-icon" 
               data-state="closed" 
               data-aula="<?= htmlspecialchars(str_replace('Aula ', '', $nomAula), ENT_QUOTES, 'UTF-8'); ?>" 
               style="cursor: pointer;"></i>
            <!-- Bolita de color -->
            <span class="d-inline-block me-2 rounded-circle" 
                  style="width: 10px; height: 10px; background-color: <?= $color ?>;"></span>
            <!-- Nombre del aula -->
            <span class="aula-text"><?= htmlspecialchars($nomAula, ENT_QUOTES, 'UTF-8'); ?></span>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  <?php endforeach; ?>
</div>
