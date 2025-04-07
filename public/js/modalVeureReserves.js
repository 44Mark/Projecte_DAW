
// Agafar les reserves propies i mostrar-les al modal
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
                <td>${reserva.ini}-${reserva.fin}</td>
                <td>${reserva.aula}</td>
                <td>${reserva.grup}</td>
                <td class="d-flex justify-content-between align-items-center gap-2">
                  <button class="btn btn-primary btn-sm modificar-btn" data-id="${reserva.id}">
                    <i class="fas fa-edit"></i>
                  </button>
                  <button class="btn btn-danger btn-sm eliminar-btn" data-id="${reserva.id}">
                    <i class="fas fa-trash"></i>
                  </button>
                </td>
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

