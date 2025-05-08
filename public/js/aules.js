// Script per gestionar la visibilitat de les aules en el calendari

document.addEventListener('DOMContentLoaded', function() {
  // Carreguem les aules preferides des del localStorage o inicialitzem un array buit si no n’hi ha.
  let aulasPreferides = JSON.parse(localStorage.getItem('aulasPreferides')) || [];

  // Funció per filtrar els esdeveniments del calendari i mostrar només els de les aules preferides.
  window.filterCalendarEvents = function() {
    const calendar = window.myCalendar;
    if (!calendar) return;

    const events = calendar.getEvents();

    events.forEach(evt => {
      const aulaEvento = evt.extendedProps.aula || '';
      const aulaNormalizada = aulaEvento.replace(/^Aula\s+/i, '');

      // Establim el valor de display en funció de si l'aula és preferida o no.
      let desiredDisplay = 'none';
      if (aulasPreferides.length > 0 && aulasPreferides.includes(aulaNormalizada)) {
        desiredDisplay = 'auto';
      }

      // Ajustem la visibilitat de l'esdeveniment.
      if (evt.display !== desiredDisplay) {
        evt.setProp('display', desiredDisplay);
      }
    });
  }

  // Inicialitzem les icones de visibilitat (toggle) per a cada aula.
  document.querySelectorAll('.toggle-icon').forEach(function(icon) {
    const aulaId = icon.getAttribute('data-aula');
    const aulaNormalizada = aulaId.replace(/^Aula\s+/i, '');

    // Assignem l'estat inicial del toggle segons si l'aula està a la llista de preferides.
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

    // Afegim l’esdeveniment de clic per canviar la visibilitat de l’aula.
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

      // Actualitzem la visibilitat dels esdeveniments aplicant el filtre actualitzat.
      window.filterCalendarEvents();
    });
  });
});