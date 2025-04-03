<?php
require_once __DIR__ . '/../model/aules.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $errors = [];
    $conflicts = [];

    $motiu = $_POST['motivo'] ?? '';
    $profe = $_POST['profe'] ?? '';
    $grup = $_POST['grup'] ?? '';
    $aula = $_POST['aula'] ?? '';

    $dates = isset($_POST['data']) ? (array)$_POST['data'] : [];
    $inis = isset($_POST['ini']) ? (array)$_POST['ini'] : [];
    $fins = isset($_POST['fin']) ? (array)$_POST['fin'] : [];
    $repetirs = isset($_POST['repetir']) ? (array)$_POST['repetir'] : [];
    $num_repeticions = isset($_POST['num_repeticions']) ? (array)$_POST['num_repeticions'] : [];

    $reserves = [];

    for ($i = 0; $i < count($dates); $i++) {
        $data = $dates[$i] ?? '';
        $ini = $inis[$i] ?? '';
        $fin = $fins[$i] ?? '';
        $repetir = $repetirs[$i] ?? '';
        $reps = $num_repeticions[$i] ?? null;

        if (empty($data) || empty($ini) || empty($fin)) {
            $errors[] = "Falta omplir dades";
            continue;
        }

        $dayOfWeek = date('N', strtotime($data));
        if ($dayOfWeek >= 6) {
            $errors[] = "No es poden fer reserves els caps de setmana.";
            continue;
        }

        $iniParts = explode(':', $ini);
        $finParts = explode(':', $fin);
        $iniMins = (int)$iniParts[0] * 60 + (int)$iniParts[1];
        $finMins = (int)$finParts[0] * 60 + (int)$finParts[1];

        if ($iniMins < 480 || $finMins > 1290) {
            $errors[] = "Les hores han d'estar entre les 8:00 i les 21:30.";
            continue;
        }

        if (comprovarReserva($connexio, $aula, $data, $iniMins, $finMins)) {
            $conflicts[] = [
                'data' => $data,
                'aula' => $aula,
                'ini' => $ini,
                'fin' => $fin
            ];
        } else {
            $reserves[] = [
                'motiu' => $motiu,
                'profe' => $profe,
                'grup' => $grup,
                'aula' => $aula,
                'data' => $data,
                'hora_ini' => $iniMins,
                'hora_fi' => $finMins
            ];
        }

        // Repeticions
        if (is_numeric($reps) && $reps > 0 && in_array($repetir, ['semanal', 'mensual'])) {
            $interval = $repetir === 'semanal' ? '+7 days' : '+28 days';
            $currentDate = $data;

            for ($j = 0; $j < $reps; $j++) {
                $currentDate = date('Y-m-d', strtotime($interval, strtotime($currentDate)));

                if (date('N', strtotime($currentDate)) >= 6) {
                    continue;
                }

                if (comprovarReserva($connexio, $aula, $currentDate, $iniMins, $finMins)) {
                    $conflicts[] = [
                        'data' => $currentDate,
                        'aula' => $aula,
                        'ini' => $ini,
                        'fin' => $fin
                    ];
                } else {
                    $reserves[] = [
                        'motiu' => $motiu,
                        'profe' => $profe,
                        'grup' => $grup,
                        'aula' => $aula,
                        'data' => $currentDate,
                        'hora_ini' => $iniMins,
                        'hora_fi' => $finMins
                    ];
                }
            }
        }
    }

    if (!empty($conflicts)) {
        header('Content-Type: application/json');
        echo json_encode([
            'success' => false,
            'conflicts' => $conflicts,
            'message' => generarMissatgeConflictes($conflicts) // ← Ya la tienes en el modelo
        ]);
        exit;
    }

    if (!empty($errors)) {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'errors' => $errors]);
        exit;
    }

    foreach ($reserves as $reserva) {
        insertReserva($connexio, $reserva);
    }

    header('Content-Type: application/json');
    echo json_encode(['success' => true]);
    exit;
} else {
    echo "Accés no permès";
}
?>