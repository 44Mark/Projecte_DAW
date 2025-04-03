// Script per gestionar la duplicació i configuració de files de reserva (amb repeticions)

document.addEventListener('DOMContentLoaded', function () {
  // 🔄 Elements de control de repeticions
  const repetirSelect = document.getElementById('repetir');
  const repeticionContainer = document.getElementById('repeticion-container');
  const repeticionLabel = document.getElementById('repeticion-label');

  // Contenidors de les columnes de la primera fila
  const dataContainer = document.querySelector('.data-container');
  const iniContainer = document.querySelector('.ini-container');
  const finContainer = document.querySelector('.fin-container');
  const repetirContainer = document.querySelector('.repetir-container');

  // 📦 Guardem la classe original per restaurar-la després
  let classesReferencia = {};

  // 🎯 Quan es canvia el valor de "Repetir", mostrem o ocultem el camp "nº repeticions"
  repetirSelect.addEventListener('change', function () {
    const valor = this.value;

    if (valor === 'semanal' || valor === 'mensual') {
      repeticionLabel.textContent = valor === 'semanal' ? "Nº setmanes" : "Nº mesos";
      repeticionContainer.style.display = 'block';

      // Ajustem columnes perquè encaixi bé visualment
      dataContainer.className = 'col-md-2 data-container';
      iniContainer.className = 'col-md-2 ini-container';
      finContainer.className = 'col-md-2 fin-container';
      repetirContainer.className = 'col-md-3 repetir-container';
      repeticionContainer.className = 'col-md-2 repeticion-container';

      // Guardem les classes base
      classesReferencia = {
        data: dataContainer.className,
        ini: iniContainer.className,
        fin: finContainer.className,
        repetir: repetirContainer.className,
        repeticions: repeticionContainer.className
      };
    } else {
      // Amaguem el camp si no s'ha seleccionat opció
      repeticionContainer.style.display = 'none';
    }
  });

  // ➕ Afegeix nova fila de reserva
  const addReservaButton = document.querySelector('.btn-secondary');
  const filaRepeticio = document.getElementById('fila-repeticio');
  const container = filaRepeticio.parentElement;

  addReservaButton.addEventListener('click', function () {
    // Clonem la fila original
    const nuevaFila = filaRepeticio.cloneNode(true);

    // Netejar inputs i selects de la nova fila
    nuevaFila.querySelectorAll('input, select').forEach(input => {
      if (input.tagName === 'SELECT') {
        input.selectedIndex = 0;
      } else {
        input.value = '';
      }
    });

    // Inicialitzar valors per la nova fila
    const repeticionContainerNuevo = nuevaFila.querySelector('.repeticion-container');
    repeticionContainerNuevo.style.display = 'none';

    const dataContainerNuevo = nuevaFila.querySelector('.data-container');
    const iniContainerNuevo = nuevaFila.querySelector('.ini-container');
    const finContainerNuevo = nuevaFila.querySelector('.fin-container');
    const repetirContainerNuevo = nuevaFila.querySelector('.repetir-container');
    const repeticionLabelNuevo = nuevaFila.querySelector('#repeticion-label');
    const repetirSelectNuevo = nuevaFila.querySelector('#repetir');

    // Aplicar classes originals a la nova fila
    dataContainerNuevo.className = dataContainer.className;
    iniContainerNuevo.className = iniContainer.className;
    finContainerNuevo.className = finContainer.className;
    repetirContainerNuevo.className = repetirContainer.className;
    repeticionContainerNuevo.className = repeticionContainer.className;

    // 🗑️ Configurar el botó d'eliminar fila
    const deleteBtn = nuevaFila.querySelector('.eliminar-fila');
    if (deleteBtn) {
      deleteBtn.onclick = function () {
        this.closest('.row').remove();
      };
    }

    // 🔁 Funció per ajustar columnes segons valor de repetició
    function ajustarColumnas(valor) {
      if (valor === 'semanal' || valor === 'mensual') {
        repeticionLabelNuevo.textContent = valor === 'semanal' ? "Nº setmanes" : "Nº mesos";
        repeticionContainerNuevo.style.display = 'block';

        // Aplicar les classes base si existeixen
        if (Object.keys(classesReferencia).length > 0) {
          dataContainerNuevo.className = classesReferencia.data;
          iniContainerNuevo.className = classesReferencia.ini;
          finContainerNuevo.className = classesReferencia.fin;
          repetirContainerNuevo.className = classesReferencia.repetir;
          repeticionContainerNuevo.className = classesReferencia.repeticions;
        }
      } else {
        // Si no es selecciona repetició, ocultem i restaurem classes
        repeticionContainerNuevo.style.display = 'none';
        dataContainerNuevo.className = dataContainer.className;
        iniContainerNuevo.className = iniContainer.className;
        finContainerNuevo.className = finContainer.className;
        repetirContainerNuevo.className = repetirContainer.className;
        repeticionContainerNuevo.className = repeticionContainer.className;
      }
    }

    // Aplicar l'ajust inicial segons el valor actual del select
    ajustarColumnas(repetirSelectNuevo.value);

    // Reaccionar als canvis de valor en la nova fila
    repetirSelectNuevo.addEventListener('change', function () {
      ajustarColumnas(this.value);
    });

    // Inserir la nova fila abans del botó "Afegir altra reserva"
    container.insertBefore(nuevaFila, addReservaButton.parentElement.parentElement);
  });

  // 🧹 Permetre eliminar la primera fila també
  const deleteBtnInicial = filaRepeticio.querySelector('.eliminar-fila');
  if (deleteBtnInicial) {
    deleteBtnInicial.onclick = function () {
      this.closest('.row').remove();
    };
  }
});

