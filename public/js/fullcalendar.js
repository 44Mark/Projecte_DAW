document.addEventListener('DOMContentLoaded', function() {
  var fullcalendar = document.getElementById('calendar');

  var calendar = new FullCalendar.Calendar(fullcalendar, {
    themeSystem: 'bootstrap',
    initialView: 'dayGridMonth',
    dayMaxEventRows: 2, 
    moreLinkContent: 'veure mes', 
    headerToolbar: {
      left: 'prev,next today',
      center: 'title',
      right: 'dayGridMonth,timeGridWeek,timeGridDay'
    },
    events: {
      url: '../app/controlador/getHorarisFixes.php',
      method: 'GET',
      failure: function() {
        alert("No se pudieron cargar los eventos.");
      }
    },
    eventContent: function(arg) {
      // Extraer la hora del evento en formato 2-digit HH:MM
      const startTime = arg.event.start.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
      // Obtener el aula del evento
      const aula = arg.event.extendedProps.aula;
      // Obtener el color desde la propiedad extendida (color)
      const color = arg.event.extendedProps.color || '#000';
      
      // Creamos la "bolita" con el color correspondiente
      const dotHtml = `<span class="dot" style="display:inline-block;width:8px;height:8px;border-radius:50%;background-color:${color};margin-right:4px;"></span>`;
      
      // Retornar el contenido HTML del evento con un contenedor que respete el CSS
      return { html: `<div style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">${dotHtml}${startTime} - ${aula}</div>` };
    },
    eventClick: function(info) {
      // Acceder a las propiedades extendidas del evento
      const props = info.event.extendedProps;
      const start = info.event.start.toLocaleString();
      const end = info.event.end ? info.event.end.toLocaleString() : '';
      
      // Construir el contenido del modal con más información
      const modalContent = `
        <h5><strong>Professor: </strong>${props.profe} - <strong>Asignatura: </strong>${props.assignatura}</h5>
        <p><strong>Inicia:</strong> ${start}</p>
        <p><strong>Finalitza:</strong> ${end}</p>
        <p><strong>Grup:</strong> ${props.grup}</p>
        <p><strong>Aula:</strong> ${props.aula}</p>
      `;
      
      document.getElementById('modalBody').innerHTML = modalContent;
      var modal = new bootstrap.Modal(document.getElementById('eventoModal'));
      modal.show();
    }
  });

  calendar.render();

  // Exponer para que aules.js lo use
  window.myCalendar = calendar;
});
