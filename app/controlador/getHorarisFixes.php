<?php
header('Content-Type: application/json');

require_once __DIR__ . '/../model/aules.php';

$jsonEvents = obtenirHorarisClases($connexio);

echo $jsonEvents;