<?php
require_once __DIR__ . '/../model/aules.php';

// Indiquem que la resposta és en format JSON.
header('Content-Type: application/json');

// Agafem els horaris fixes de les classes.
$horaris = obtenirHorarisClases($connexio);
echo $horaris;