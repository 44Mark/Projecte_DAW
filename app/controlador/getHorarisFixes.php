<?php
// 1) Cabecera para indicar que devolveremos JSON
header('Content-Type: application/json');

// 2) Incluir la conexión y el modelo
require_once __DIR__ . '/../model/aules.php';
require_once __DIR__ . '/../../config/connexio.php';

// 3) Llamar a la función y hacer echo
$jsonEvents = obtenirHorarisClases($connexio);

// 4) Imprimir el JSON
echo $jsonEvents;
