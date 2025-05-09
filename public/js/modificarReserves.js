// Script encarregat de modificar les resreves pròpies.

document.addEventListener('click', function(e) {
  if (e.target.closest('.modificar-btn')) {
    e.preventDefault();

    const id = e.target.closest('.modificar-btn').dataset.id;

    // Tanquem el modal de llistat de reserves si està obert.
    const modalListado = bootstrap.Modal.getInstance(document.getElementById('veureReservaModal'));
    if (modalListado) {
      modalListado.hide();
    }

    // Obtenim les dades de la reserva a modificar.
    fetch(`/DAW/app/controlador/getReserva.php?id=${id}`)
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          // Carreguem les dades de la reserva al formulari del modal de creació de reserves.
          const form = document.getElementById('crearReservaForm');
          form.motivo.value = data.reserva.motiu;
          form.profe.value = data.reserva.profe;
          form.grup.value = data.reserva.grup;
          form.aula.value = data.reserva.aula;
          form.data.value = data.reserva.data;
          form.ini.value = data.reserva.ini;
          form.fin.value = data.reserva.fin;

          // Afegim el valor de l'ID al formulari per identificar la reserva a modificar.
          let idInput = form.querySelector('input[name="id"]');
          if (!idInput) {
            idInput = document.createElement('input');
            idInput.type = 'hidden';
            idInput.name = 'id';
            form.appendChild(idInput);
          }
          idInput.value = data.reserva.id;

          // Mostrem el modal de creació de reserves amb les dades carregades.
          const modal = new bootstrap.Modal(document.getElementById('crearReservaModal'));
          modal.show();

          // Assegurem que el backdrop desaparegui quan es tanqui el modal.
          const modalElement = document.getElementById('crearReservaModal');
          modalElement.addEventListener('hidden.bs.modal', () => {
            const backdrop = document.querySelector('.modal-backdrop');
            if (backdrop) {
              backdrop.remove();
            }
          });
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