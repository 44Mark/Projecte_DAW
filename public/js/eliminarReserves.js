// Script per gestionar la modificació i eliminació de reserves

// Eliminar una reserva
document.addEventListener('click', function(e) {
    if (e.target.closest('.eliminar-btn')) {
      const id = e.target.closest('.eliminar-btn').dataset.id;

      fetch('/DAW/app/controlador/eliminarReserva.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({ id: id })
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          // Recargar las reservas
          document.querySelector('#veureReservaModal').dispatchEvent(new Event('show.bs.modal'));
        } else {
          alert('Error: ' + data.message);
        }
      })
      .catch(error => {
        console.error('Error en la petició:', error);
      });
    }
  });