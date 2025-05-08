<?php
require_once __DIR__ . '/../model/aules.php';

// Indiquem que la resposta és en format JSON.
header('Content-Type: application/json');

$input = json_decode(file_get_contents('php://input'), true);

// Comprovem que s'ha rebut un ID vàlid.
if (!isset($input['id']) || empty($input['id'])) {
    echo json_encode(['success' => false, 'message' => 'ID no rebut.']);
    exit;
}

// Comprovem que l'ID és un número enter.
$id = intval($input['id']);

// Eliminem la reserva amb l'ID rebut.
$resultat = eliminarReserva($connexio, $id);

if ($resultat) {
    echo json_encode(['success' => true, 'message' => 'Reserva eliminada correctament.']);
} else {
    echo json_encode(['success' => false, 'message' => 'No s\'ha pogut eliminar la reserva.']);
}
