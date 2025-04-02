<?php
require_once __DIR__ . '/../model/usuari.php';

// Funció per controlar si es professor i esta en actiu.
function ProfeActiu($email, $connexio) {
    return isProfessorActive($email, $connexio);
}
?>