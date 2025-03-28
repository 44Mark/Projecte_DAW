<?php
require_once __DIR__ . '/../../config/connexio.php';

// Obtener únicamente las aulas donde 'mostrar' es 1
function agafarAules() {
    global $connexio;
    $stmt = $connexio->prepare("SELECT nom FROM kw_aules WHERE mostrar = 1");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Función para obtener las reservas y transformarlas a JSON para FullCalendar
function obtenirHorarisClases($connexio) {
    try {
        // Ahora se selecciona el campo `data` en lugar de `dia`
        $stmt = $connexio->prepare("SELECT id, assignatura, color, profe, grup, aula, data, franja, ini, fin FROM kw_reserves");
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (!$result) {
            return false;
        }

        $events = array();

        foreach ($result as $row) {
            // Verificar que 'grup' y 'aula' no estén vacíos
            if (empty($row['grup']) || empty($row['aula'])) {
                continue; // Saltar el registro si 'grup' o 'aula' están vacíos
            }

            // Se usa el campo 'data' directamente, ya viene en formato YYYY-MM-DD
            $date = $row['data'];

            // Convertir 'ini' y 'fin' (en minutos desde medianoche) a formato "HH:MM"
            $startTotalMins = $row['ini'];
            $endTotalMins   = $row['fin'];

            $startHour = floor($startTotalMins / 60);
            $startMin  = $startTotalMins % 60;
            $startTime = sprintf('%02d:%02d', $startHour, $startMin);

            $endHour = floor($endTotalMins / 60);
            $endMin  = $endTotalMins % 60;
            $endTime = sprintf('%02d:%02d', $endHour, $endMin);

            // Construir el formato ISO8601 para FullCalendar: "YYYY-MM-DDTHH:MM"
            $start = $date . "T" . $startTime;
            $end   = $date . "T" . $endTime;

            // Puedes personalizar el título; aquí se combinan asignatura y profesor
            $profe = $row['profe'];
            $assignatura = $row['assignatura'];

            $events[] = array(
                'id'    => $row['id'],
                'profe' => $profe,
                'assignatura' => $assignatura,
                'grup'  => $row['grup'],
                'aula'  => $row['aula'],
                'start' => $start,
                'end'   => $end,
                'color' => $row['color'] // Asegurarse de incluir el color
            );
        }

        return json_encode($events);
    } catch (Exception $e) {
        error_log("Error en obtenirHorarisClases: " . $e->getMessage());
        return false; // Retornar false en caso de error
    }
}
