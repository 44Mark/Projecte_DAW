document.addEventListener('DOMContentLoaded', function () {
  const repetirSelect = document.getElementById('repetir');
  const repeticionContainer = document.getElementById('repeticion-container');
  const repeticionLabel = document.getElementById('repeticion-label');

  const dataContainer = document.querySelector('.data-container');
  const iniContainer = document.querySelector('.ini-container');
  const finContainer = document.querySelector('.fin-container');
  const repetirContainer = document.querySelector('.repetir-container');

  // Guardamos las clases de referencia
  let classesReferencia = {};

  repetirSelect.addEventListener('change', function () {
    const valor = this.value;

    if (valor === 'semanal' || valor === 'mensual') {
      repeticionLabel.textContent = valor === 'semanal' ? "Nº setmanes" : "Nº mesos";
      repeticionContainer.style.display = 'block';

      dataContainer.className = 'col-md-2 data-container';
      iniContainer.className = 'col-md-2 ini-container';
      finContainer.className = 'col-md-2 fin-container';
      repetirContainer.className = 'col-md-3 repetir-container';
      repeticionContainer.className = 'col-md-2 repeticion-container';

      classesReferencia = {
        data: dataContainer.className,
        ini: iniContainer.className,
        fin: finContainer.className,
        repetir: repetirContainer.className,
        repeticions: repeticionContainer.className
      };
    } else {
      repeticionContainer.style.display = 'none';
    }
  });

  const addReservaButton = document.querySelector('.btn-secondary');
  const filaRepeticio = document.getElementById('fila-repeticio');
  const container = filaRepeticio.parentElement;

  addReservaButton.addEventListener('click', function () {
    const nuevaFila = filaRepeticio.cloneNode(true);

    // Reset inputs y selects
    nuevaFila.querySelectorAll('input, select').forEach(input => {
      if (input.tagName === 'SELECT') {
        input.selectedIndex = 0;
      } else {
        input.value = '';
      }
    });

    const repeticionContainerNuevo = nuevaFila.querySelector('.repeticion-container');
    repeticionContainerNuevo.style.display = 'none';

    const dataContainerNuevo = nuevaFila.querySelector('.data-container');
    const iniContainerNuevo = nuevaFila.querySelector('.ini-container');
    const finContainerNuevo = nuevaFila.querySelector('.fin-container');
    const repetirContainerNuevo = nuevaFila.querySelector('.repetir-container');
    const repeticionLabelNuevo = nuevaFila.querySelector('#repeticion-label');
    const repetirSelectNuevo = nuevaFila.querySelector('#repetir');

    // Aplicar clases base iguales a la original
    dataContainerNuevo.className = dataContainer.className;
    iniContainerNuevo.className = iniContainer.className;
    finContainerNuevo.className = finContainer.className;
    repetirContainerNuevo.className = repetirContainer.className;
    repeticionContainerNuevo.className = repeticionContainer.className;

    // Asegurar el botón X funciona
    const deleteBtn = nuevaFila.querySelector('.eliminar-fila');
    if (deleteBtn) {
      deleteBtn.onclick = function () {
        this.closest('.row').remove();
      };
    }

    // Ajuste de columnas según selección
    function ajustarColumnas(valor) {
      if (valor === 'semanal' || valor === 'mensual') {
        repeticionLabelNuevo.textContent = valor === 'semanal' ? "Nº setmanes" : "Nº mesos";
        repeticionContainerNuevo.style.display = 'block';

        if (Object.keys(classesReferencia).length > 0) {
          dataContainerNuevo.className = classesReferencia.data;
          iniContainerNuevo.className = classesReferencia.ini;
          finContainerNuevo.className = classesReferencia.fin;
          repetirContainerNuevo.className = classesReferencia.repetir;
          repeticionContainerNuevo.className = classesReferencia.repeticions;
        }
      } else {
        repeticionContainerNuevo.style.display = 'none';

        // Restaurar a clases iniciales
        dataContainerNuevo.className = dataContainer.className;
        iniContainerNuevo.className = iniContainer.className;
        finContainerNuevo.className = finContainer.className;
        repetirContainerNuevo.className = repetirContainer.className;
        repeticionContainerNuevo.className = repeticionContainer.className;
      }
    }

    // Aplicar si hi ha
    ajustarColumnas(repetirSelectNuevo.value);

    // Veure els canvis en temps real
    repetirSelectNuevo.addEventListener('change', function () {
      ajustarColumnas(this.value);
    });

    // Afegir la nova fila abans del botó d'afegir reserva
    container.insertBefore(nuevaFila, addReservaButton.parentElement.parentElement);
  });

  // Activar el botón de eliminar de la primera fila (por si acaso)
  const deleteBtnInicial = filaRepeticio.querySelector('.eliminar-fila');
  if (deleteBtnInicial) {
    deleteBtnInicial.onclick = function () {
      this.closest('.row').remove();
    };
  }
});

