document.addEventListener('click', function(e) {
  if (e.target.closest('.modificar-btn')) {
    e.preventDefault(); // Evitar recarga de la página

    const id = e.target.closest('.modificar-btn').dataset.id;

    // Cerrar el modal de listado de reservas propias si está abierto
    const modalListado = bootstrap.Modal.getInstance(document.getElementById('veureReservaModal'));
    if (modalListado) {
      modalListado.hide();
    }

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