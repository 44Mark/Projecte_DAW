<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Indiquem que la resposta és en format JSON.
require_once __DIR__ . '/../model/aules.php';

// Obtenim el correu de l'usuari autenticat.
$email = $_SESSION['user']['email'] ?? null;

// Verificació de que hagi iniciar sessió.
if (!$email) {
    echo json_encode(['error' => 'No s\'ha iniciat sessió']);
    exit;
}

// Extreiem la part abans de l'@ i la convertim en majúscules.
$profe = strtoupper(explode('@', $email)[0]);

// Agafem les reserves de l'usuari.
$reserves = agafarReserves($connexio, $profe);

if ($reserves) {
    // Convertir las horas de segundos a formato legible.
    foreach ($reserves as &$reserva) {
        $reserva['ini'] = sprintf('%02d:%02d', floor($reserva['ini'] / 60), $reserva['ini'] % 60);
        $reserva['fin'] = sprintf('%02d:%02d', floor($reserva['fin'] / 60), $reserva['fin'] % 60);
    }
    echo json_encode($reserves);
} else {
    echo json_encode(['error' => 'No s\'han trobat reserves']);
}
