// Script per a inicialitzar el calendari de FullCalendar

document.addEventListener('DOMContentLoaded', function () {
  var fullcalendar = document.getElementById('calendar');

  var calendar = new FullCalendar.Calendar(fullcalendar, {
    locale: 'ca',
    firstDay: 1,
    themeSystem: 'bootstrap',
    initialView: 'dayGridMonth',
    allDayText: 'Tot el dia',
    dayMaxEventRows: 2,
    moreLinkContent: 'veure més',
    
    slotMinTime: '08:00:00',
    allDaySlot: false,

    buttonText: {
      today: 'Avui',
      month: 'Mes',
      week: 'Setmana',
      day: 'Dia'
    },

    customButtons: {
      botoCrearReserva: {
        text: '',
        click: function () {
          var crearModal = new bootstrap.Modal(document.getElementById('crearReservaModal'));
          document.getElementById('crearReservaForm').reset();
          crearModal.show();
        }
      },
      botoVeureReserva: {
        text: '',
        click: function () {
          var veureModal = new bootstrap.Modal(document.getElementById('veureReservaModal'));
          veureModal.show();
        }
      }
    },

    headerToolbar: {
      left: 'prev next today',
      center: 'title',
      right: 'dayGridMonth,timeGridWeek,timeGridDay botoCrearReserva botoVeureReserva'
    },

    events: {
      url: '../app/controlador/getHorarisFixes.php',
      method: 'GET',
      failure: function () {
        alert("No s'han pogut carregar els esdeveniments.");
      }
    },

    // Color de fons segons el color associat a l'aula
    eventDidMount: function(info) {
      const color = info.event.extendedProps.color;

      if (color) {
        info.el.style.backgroundColor = color;
        info.el.style.color = '#fff';
        info.el.style.border = 'none';
        info.el.style.padding = '2px 4px';
        info.el.style.borderRadius = '4px';
      }
    },

    // Mostrar detalls al fer clic en un esdeveniment
    eventClick: function (info) {
      const props = info.event.extendedProps;
      const start = info.event.start.toLocaleString();
      const end = info.event.end ? info.event.end.toLocaleString() : '';

      const modalContent = `
        <h5><strong>Professor: </strong>${props.profe} - <strong>Motiu: </strong>${props.motiu}</h5>
        <p><strong>Inicia:</strong> ${start}</p>
        <p><strong>Finalitza:</strong> ${end}</p>
        <p><strong>Grup:</strong> ${props.grup}</p>
        <p><strong>Aula:</strong> ${props.aula}</p>
      `;

      document.getElementById('modalBody').innerHTML = modalContent;
      var modal = new bootstrap.Modal(document.getElementById('eventoModal'));
      modal.show();
    },

    // Filtratge de visibilitat per aula (preferides)
    eventsSet: function () {
      setTimeout(function () {
        if (window.filterCalendarEvents) {
          window.filterCalendarEvents();
        }
      }, 0);
    }
  });

  calendar.render();
  window.myCalendar = calendar;

  // Personalització dels botons després de renderitzar el calendari
  setTimeout(() => {
    const btnCrear = document.querySelector('.fc-botoCrearReserva-button');
    if (btnCrear) {
      btnCrear.innerHTML = `<i class="bi bi-calendar-plus" data-bs-toggle="tooltip" title="Crear nova reserva" style="font-size: 1rem;"></i>`;
      new bootstrap.Tooltip(btnCrear.querySelector('i'));
    }

    const btnVeure = document.querySelector('.fc-botoVeureReserva-button');
    if (btnVeure) {
      btnVeure.innerHTML = `<i class="bi bi-calendar-event" data-bs-toggle="tooltip" title="Veure les meves reserves" style="font-size: 1rem;"></i>`;
      new bootstrap.Tooltip(btnVeure.querySelector('i'));
    }
  }, 0);
});
