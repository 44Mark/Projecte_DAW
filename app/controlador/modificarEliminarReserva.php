<?php
require_once __DIR__ . '/../model/aules.php';

header('Content-Type: application/json');

$input = json_decode(file_get_contents('php://input'), true);

if (!isset($input['id']) || empty($input['id'])) {
    echo json_encode(['success' => false, 'message' => 'ID no rebut.']);
    exit;
}

$id = intval($input['id']);

$resultat = eliminarReserva($connexio, $id);

if ($resultat) {
    echo json_encode(['success' => true, 'message' => 'Reserva eliminada correctament.']);
} else {
    echo json_encode(['success' => false, 'message' => 'No s\'ha pogut eliminar la reserva.']);
}
