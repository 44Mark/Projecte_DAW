<?php
require_once __DIR__ . '/../model/aules.php';

header('Content-Type: application/json');

$id = $_GET['id'] ?? null;
if (!$id) {
    echo json_encode(['success' => false, 'message' => 'ID no proporcionat.']);
    exit;
}

try {
    $stmt = $connexio->prepare("SELECT * FROM kw_reserves WHERE id = :id");
    $stmt->execute([':id' => $id]);
    $reserva = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($reserva) {
        // Convertir las horas de minutos a formato legible
        $reserva['ini'] = sprintf('%02d:%02d', floor($reserva['ini'] / 60), $reserva['ini'] % 60);
        $reserva['fin'] = sprintf('%02d:%02d', floor($reserva['fin'] / 60), $reserva['fin'] % 60);

        echo json_encode(['success' => true, 'reserva' => $reserva]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Reserva no trobada.']);
    }
} catch (Exception $e) {
    error_log("Error al obtenir la reserva: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Error al obtenir la reserva.']);
}