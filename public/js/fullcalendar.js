// Script per a inicialitzar el calendari de FullCalendar

document.addEventListener('DOMContentLoaded', function() {
  var fullcalendar = document.getElementById('calendar');

  // Inicialitzem el modal de Bootstrap per a la creació de reserves
  var calendar = new FullCalendar.Calendar(fullcalendar, {
    themeSystem: 'bootstrap',
    initialView: 'dayGridMonth',
    dayMaxEventRows: 2,
    moreLinkContent: 'veure mes',
    customButtons: {
      botoCrearReserva: {
        text: 'Crear reserva',
        click: function() {
          // Funció per a mostrar el modal de creació de reserva
          var crearModal = new bootstrap.Modal(document.getElementById('crearReservaModal'));
          document.getElementById('crearReservaForm').reset();
          crearModal.show();
        }
      }
    },
    // Capçalera del calendari
    headerToolbar: {
      left: 'prev,next today',
      center: 'title',
      right: 'dayGridMonth,timeGridWeek,timeGridDay botoCrearReserva'
    },
    // Events de FullCalendar
    events: {
      url: '../app/controlador/getHorarisFixes.php',
      method: 'GET',
      failure: function() {
        alert("No se pudieron cargar los eventos.");
      }
    },
    // Configuració de la vista del calendari
    eventContent: function(arg) {
      const startTime = arg.event.start.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
      const aula = arg.event.extendedProps.aula;
      const color = arg.event.extendedProps.color || '#000';
      const dotHtml = `<span class="dot" style="display:inline-block;width:8px;height:8px;border-radius:50%;background-color:${color};margin-right:4px;"></span>`;
      return { html: `<div style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">${dotHtml}${startTime} - ${aula}</div>` };
    },
    // 
    eventClick: function(info) {
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
    eventsSet: function() {
      setTimeout(function() {
        if (window.filterCalendarEvents) {
          window.filterCalendarEvents();
        }
      }, 0);
    }
  });

  calendar.render();
  window.myCalendar = calendar;
});
