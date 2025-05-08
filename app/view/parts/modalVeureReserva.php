<div class="modal fade" id="veureReservaModal" tabindex="-1" aria-labelledby="veureReservaModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <form id="veureReservaForm">
        <div class="modal-header">
          <h5 class="modal-title" id="veureReservaModalLabel">Les meves reserves</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tancar"></button>
        </div>
        <div class="modal-body">
          <table class="table">
            <thead>
              <tr>
                <th>Motiu</th>
                <th>Data</th>
                <th>Hores</th>
                <th>Aula</th>
                <th>Grup</th>
                <th>Accions</th>
              </tr>
            </thead>
            <tbody id="reservesTableBody">
              <?php if (!empty($reserves)): ?>
                <?php foreach ($reserves as $reserva): ?>
                  <tr>
                    <td><?= htmlspecialchars($reserva['motiu'], ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?= htmlspecialchars($reserva['data'], ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?= htmlspecialchars($reserva['hores'], ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?= htmlspecialchars($reserva['aula'], ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?= htmlspecialchars($reserva['grup'], ENT_QUOTES, 'UTF-8'); ?></td>
                    <td>  
                      <button type="button" class="btn btn-primary modificar-btn" data-id="<?= $reserva['id']; ?>">Modificar</button> 
                    </td>
                  </tr>
                <?php endforeach; ?>
              <?php else: ?>
                <tr>
                  <td colspan="6" class="text-center">Encara no hi han reserves.</td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
          <!-- Contenedor para la paginación -->
          <div id="pagination" class="d-flex justify-content-center mt-3"></div>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Script para paginación -->
<script>
  document.getElementById('veureReservaModal').addEventListener('shown.bs.modal', function () {
    const rowsPerPage = 9;
    const tableBody = document.getElementById("reservesTableBody");
    const pagination = document.getElementById("pagination");

    if (!pagination || !tableBody) return;

    const allRows = Array.from(tableBody.querySelectorAll("tr"));
    const totalPages = Math.ceil(allRows.length / rowsPerPage);
    let currentPage = 1;

    function showPage(page) {
      currentPage = page;
      tableBody.innerHTML = "";

      const start = (page - 1) * rowsPerPage;
      const end = start + rowsPerPage;

      const visibleRows = allRows.slice(start, end);
      visibleRows.forEach(row => tableBody.appendChild(row));

      renderPagination();
    }

    function renderPagination() {
      pagination.innerHTML = "";

      for (let i = 1; i <= totalPages; i++) {
        const btn = document.createElement("button");
        btn.classList.add("btn", "btn-sm", "btn-outline-primary", "mx-1");
        btn.textContent = i;
        if (i === currentPage) btn.classList.add("active");

        btn.addEventListener("click", () => showPage(i));
        pagination.appendChild(btn);
      }
    }

    if (allRows.length > 0) {
      showPage(1);
    }
  });
</script>
