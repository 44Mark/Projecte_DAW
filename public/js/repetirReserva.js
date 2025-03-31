document.addEventListener('DOMContentLoaded', function () {
const repetirSelect = document.getElementById('repetir');
const repeticionContainer = document.getElementById('repeticion-container');
const repeticionLabel = document.getElementById('repeticion-label');
const repeticionText = document.getElementById('repeticion-text');

// Initialize Bootstrap tooltips
const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl);
});

repetirSelect.addEventListener('change', function () {
    const valor = this.value;

    if (valor === 'semanal') {
    repeticionLabel.textContent = "Numero de setmanes";
    repeticionContainer.style.display = 'block';
    } else if (valor === 'mensual') {
    repeticionLabel.textContent = "Numero de mesos";
    repeticionContainer.style.display = 'block';
    } else {
    repeticionContainer.style.display = 'none';
    }
});
});