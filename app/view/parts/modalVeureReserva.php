<div class="modal fade" id="veureReservaModal" tabindex="-1" aria-labelledby="veureReservaModalLabel" aria-hidden="true">
  <div class="modal-dialog">
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
                <th>Hora Inici</th>
                <th>Hora Fi</th>
                <th>Aula</th>
                <th>Grup</th>
              </tr>
            </thead>
            <tbody id="reservesTableBody">
              
            </tbody>
          </table>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    const veureReservaModal = document.getElementById('veureReservaModal');

    veureReservaModal.addEventListener('show.bs.modal', function() {
      fetch('/DAW/app/controlador/getReservesPropies.php')
        .then(response => {
          if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
          }
          return response.json();
        })
        .then(data => {
          const reservesTableBody = document.getElementById('reservesTableBody');
          reservesTableBody.innerHTML = '';

          if (data.error) {
            reservesTableBody.innerHTML = `<tr><td colspan="6">${data.error}</td></tr>`;
          } else {
            data.forEach(reserva => {
              const row = document.createElement('tr');
              row.innerHTML = `
                <td>${reserva.motiu}</td>
                <td>${reserva.data}</td>
                <td>${reserva.ini}</td>
                <td>${reserva.fin}</td>
                <td>${reserva.aula}</td>
                <td>${reserva.grup}</td>
              `;
              reservesTableBody.appendChild(row);
            });
          }
        })
        .catch(error => {
          console.error('Error al carregar les reserves:', error);
        });
    });
  });
</script>
