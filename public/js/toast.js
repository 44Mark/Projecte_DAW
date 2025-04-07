// Script per agafar els misatges de la reserva i mostrar-los amb toast.

document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('crearReservaForm');
  
    form.addEventListener('submit', function (e) {
      e.preventDefault();
  
      const formData = new FormData(form);
  
      fetch('/DAW/app/controlador/crearReserva.php', {
        method: 'POST',
        body: formData
      })
      .then(res => res.json())
      .then(data => {
        if (data.success) {
          toastr.success('Reserva creada correctament!');
  
          // Tanquem el modal.
          const modalEl = document.getElementById('crearReservaModal');
          const modal = bootstrap.Modal.getInstance(modalEl);
          modal.hide();
  
          // Resetejem el formulari.
          form.reset();
        } else {
          if (data.conflicts && data.message) {
            toastr.warning(data.message);
          } else if (data.errors && Array.isArray(data.errors)) {
            data.errors.forEach(err => {
              if (typeof err === 'string') {
                toastr.error(err);
              } else {
                console.error('Error no válido:', err);
              }
            });
          } else {
            toastr.error('Error desconegut en fer la reserva.');
          }
        }
      })
      .catch(err => {
        console.error('Error:', err);
        toastr.error('Hi ha hagut un problema amb el servidor.');
      });
    });
  });
  
  // Configuració del toast.
  toastr.options = {
    "closeButton": true,
    "debug": false,
    "newestOnTop": false,
    "progressBar": true,
    "positionClass": "toast-bottom-center",
    "preventDuplicates": false,
    "onclick": null,
    "showDuration": "600",
    "hideDuration": "1000",
    "timeOut": "5000",
    "extendedTimeOut": "1000",
    "showEasing": "swing",
    "hideEasing": "linear",
    "showMethod": "fadeIn",
    "hideMethod": "fadeOut"
  };