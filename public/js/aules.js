document.addEventListener('DOMContentLoaded', function() {
  // 1. Cargar el array de aulas preferidas desde localStorage
  let aulasPreferides = JSON.parse(localStorage.getItem('aulasPreferides')) || [];

  // 2. Definir la función de filtrado en window para que la llame fullcalendar.js
  window.filterCalendarEvents = function() {
    const calendar = window.myCalendar;
    if (!calendar) return;

    const events = calendar.getEvents();

    events.forEach(evt => {
      const aulaEvento = evt.extendedProps.aula || '';
      const aulaNormalizada = aulaEvento.replace(/^Aula\s+/i, '');

      // Determinar el display deseado
      let desiredDisplay = 'none';
      if (aulasPreferides.length > 0 && aulasPreferides.includes(aulaNormalizada)) {
        desiredDisplay = 'auto';
      }

      // Opción B: solo cambiar si difiere del actual
      if (evt.display !== desiredDisplay) {
        evt.setProp('display', desiredDisplay);
      }
    });
  };

  // 3. Inicializar los iconos de ojo en la lista de aulas
  document.querySelectorAll('.toggle-icon').forEach(function(icon) {
    const aulaId = icon.getAttribute('data-aula');
    const aulaNormalizada = aulaId.replace(/^Aula\s+/i, '');

    // Ajustar el icono según si está en aulasPreferides
    if (aulasPreferides.includes(aulaNormalizada)) {
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

    // Listener para alternar el estado
    icon.addEventListener('click', function() {
      if (icon.dataset.state === 'closed') {
        icon.classList.remove('bi-eye-slash');
        icon.classList.add('bi-eye');
        icon.dataset.state = 'open';
        icon.parentElement.classList.add('visible');
        if (!aulasPreferides.includes(aulaNormalizada)) {
          aulasPreferides.push(aulaNormalizada);
        }
      } else {
        icon.classList.remove('bi-eye');
        icon.classList.add('bi-eye-slash');
        icon.dataset.state = 'closed';
        icon.parentElement.classList.remove('visible');
        aulasPreferides = aulasPreferides.filter(id => id !== aulaNormalizada);
      }
      localStorage.setItem('aulasPreferides', JSON.stringify(aulasPreferides));

      // Llamar a la función de filtrado
      window.filterCalendarEvents();
    });
  });

  // 4. Al cargar la página, aplicar el filtro inicial
  window.filterCalendarEvents();
});
