// aules.js
document.addEventListener('DOMContentLoaded', function() {
  let aulasPreferidas = JSON.parse(localStorage.getItem('aulasPreferidas')) || [];

  // Definimos la función en window
  window.filterCalendarEvents = function() {
    const calendar = window.myCalendar;
    if (!calendar) return;

    const events = calendar.getEvents();

    events.forEach(evt => {
      const aulaEvento = evt.extendedProps.aula || '';
      const aulaNormalizada = aulaEvento.replace(/^Aula\s+/i, '');

      if (aulasPreferidas.length === 0) {
        // Ninguna aula seleccionada => ocultar todos los eventos
        evt.setProp('display', 'none');
      } else {
        // Mostrar solo si está en aulasPreferidas
        if (aulasPreferidas.includes(aulaNormalizada)) {
          evt.setProp('display', 'auto');
        } else {
          evt.setProp('display', 'none');
        }
      }
    });
  };

  // Inicializar iconos de ojo
  document.querySelectorAll('.toggle-icon').forEach(function(icon) {
    const aulaId = icon.getAttribute('data-aula');
    const aulaNormalizada = aulaId.replace(/^Aula\s+/i, '');

    if (aulasPreferidas.includes(aulaNormalizada)) {
      icon.classList.remove('bi-eye-slash');
      icon.classList.add('bi-eye');
      icon.dataset.state = 'open';
      icon.parentElement.classList.add('visible');
    } else {
      icon.classList.remove('bi-eye');
      icon.classList.add('bi-eye-slash');
      icon.dataset.state = 'closed';
      icon.parentElement.classList.remove('visible');
    }

    icon.addEventListener('click', function() {
      if (icon.dataset.state === 'closed') {
        icon.classList.remove('bi-eye-slash');
        icon.classList.add('bi-eye');
        icon.dataset.state = 'open';
        icon.parentElement.classList.add('visible');

        if (!aulasPreferidas.includes(aulaNormalizada)) {
          aulasPreferidas.push(aulaNormalizada);
        }
      } else {
        icon.classList.remove('bi-eye');
        icon.classList.add('bi-eye-slash');
        icon.dataset.state = 'closed';
        icon.parentElement.classList.remove('visible');

        aulasPreferidas = aulasPreferidas.filter(function(id) {
          return id !== aulaNormalizada;
        });
      }

      localStorage.setItem('aulasPreferidas', JSON.stringify(aulasPreferidas));

      // Llamar a filterCalendarEvents (ahora en window)
      if (window.filterCalendarEvents) {
        window.filterCalendarEvents();
      }
    });
  });

  // Si quieres que, al cargar la página, se filtre si ya había aulas guardadas
  // (lo ideal es esperar a que FullCalendar haya cargado, 
  //  pero si quieres forzar ya mismo, lo puedes llamar):
  if (window.filterCalendarEvents) {
    window.filterCalendarEvents();
  }
});
