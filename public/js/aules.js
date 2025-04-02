// Script per gestionar la viicbilitat de les aules en el calendari

document.addEventListener('DOMContentLoaded', function() {
  // Carreguem les aules preferides de localStorage o inicialitzem un array buit.
  let aulasPreferides = JSON.parse(localStorage.getItem('aulasPreferides')) || [];

  // Definim la funció per filtrar els esdeveniments del calendari i mostrar només aquells que corresponen a les aules preferides.
  window.filterCalendarEvents = function() {
    const calendar = window.myCalendar;
    if (!calendar) return;

    const events = calendar.getEvents();

    events.forEach(evt => {
      const aulaEvento = evt.extendedProps.aula || '';
      const aulaNormalizada = aulaEvento.replace(/^Aula\s+/i, '');

      // Determinem el valor de display segons si l'aula està a la llista de preferides
      let desiredDisplay = 'none';
      if (aulasPreferides.length > 0 && aulasPreferides.includes(aulaNormalizada)) {
        desiredDisplay = 'auto';
      }

      // Ajustem la visibilitat de l'esdeveniment
      if (evt.display !== desiredDisplay) {
        evt.setProp('display', desiredDisplay);
      }
    });
  };

  // Inicialitzem els icons de toggle per a cada aula
  document.querySelectorAll('.toggle-icon').forEach(function(icon) {
    const aulaId = icon.getAttribute('data-aula');
    const aulaNormalizada = aulaId.replace(/^Aula\s+/i, '');

    // Assignem l'estat inicial del toggle segons si l'aula està a la llista de preferides
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

    // Creem l'esdeveniment de clic per al toggle
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

      // Cridem a la funció de filtratge per actualitzar la visibilitat dels esdeveniments
      window.filterCalendarEvents();
    });
  });

  // Carreguem els esdeveniments del calendari i apliquem el filtratge inicial
  window.filterCalendarEvents();
});