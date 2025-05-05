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

document.addEventListener('click', function(e) {
  if (e.target.closest('.modificar-btn')) {
    const id = e.target.closest('.modificar-btn').dataset.id;

    // Obtener datos de la reserva
    fetch(`/DAW/app/controlador/getReserva.php?id=${id}`)
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          // Cargar datos en el modal
          const form = document.getElementById('crearReservaForm');
          form.motivo.value = data.reserva.motiu;
          form.profe.value = data.reserva.profe;
          form.grup.value = data.reserva.grup;
          form.aula.value = data.reserva.aula;
          form.data.value = data.reserva.data;
          form.ini.value = data.reserva.ini;
          form.fin.value = data.reserva.fin;

          // Añadir el ID de la reserva al formulario
          let idInput = form.querySelector('input[name="id"]');
          if (!idInput) {
            idInput = document.createElement('input');
            idInput.type = 'hidden';
            idInput.name = 'id';
            form.appendChild(idInput);
          }
          idInput.value = data.reserva.id;

          // Mostrar el modal
          const modal = new bootstrap.Modal(document.getElementById('crearReservaModal'));
          modal.show();
        } else {
          toastr.error('No s\'han pogut carregar les dades de la reserva.');
        }
      })
      .catch(error => {
        console.error('Error al carregar la reserva:', error);
        toastr.error('Error al carregar la reserva.');
      });
  }
});