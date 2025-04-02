<!-- Bucle per llistar totes les aules del centre que hi ha a la BD. -->

<div class="row">
  <?php foreach ($files as $chunk): ?>
    <div class="col-auto">
      <div class="list-group">
        <?php foreach ($chunk as $aula): ?>
          <div class="list-group-item d-flex align-items-center">
            <i class="bi bi-eye-slash me-2 toggle-icon" 
               data-state="closed" 
               data-aula="<?= htmlspecialchars(str_replace('Aula ', '', $aula['nom']), ENT_QUOTES, 'UTF-8'); ?>" 
               style="cursor: pointer;"></i>
            <span class="aula-text"><?= htmlspecialchars($aula['nom'], ENT_QUOTES, 'UTF-8'); ?></span>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  <?php endforeach; ?>
</div>
</div>