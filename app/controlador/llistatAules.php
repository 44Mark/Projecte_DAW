<?php
require_once __DIR__ . '/../model/aules.php';

// Agafar les aules per llistar-les al filtre del calendari
$aulas = agafarAules();

// Agafar les aules per el modal de CrearReserva
$aulasCrearReserva = agafarAules();
// Agafar els grups per el modal de CrearReserva
$grupCrearReserva = json_decode(agafarGrups($connexio), true);
// Agafar les assignatures per el modal de CrearReserva
$assignaturesCrearReserva = json_decode(agafarAssignatures($connexio), true);
// Agafar els professors per el modal de CrearReserva
$professorsCrearReserva = json_decode(agafarProfessors($connexio), true);
?>
