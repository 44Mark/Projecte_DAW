<?php
require_once __DIR__ . '/../model/aules.php';

$colorsAules = [
    'Aula A0' => '#e6194B',
    'Aula A1' => '#f3722c',
    'Aula A2' => '#ffe119',
    'Aula A3' => '#3cb44b',
    'Aula A4' => '#4363d8',
    'Biblioteca' => '#42d4f4',
    'Aula BTX1A' => '#911eb4',
    'Aula BTX1B' => '#f032e6',
    'Aula BTX2A' => '#46f0f0',
    'Aula BTX2B' => '#bfef45',
    'Aula DAW 1' => '#fabed4',
    'Aula FPB 1' => '#ffd700',
    'Aula FPB 2' => '#dcbeff',
    'GIMNAS1' => '#469990',
    'GIMNAS2' => '#9A6324',
  ];
  


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

        $dayOfWeek = date('w', strtotime($data)); // Cambiado de 'N' a 'w'
        if ($dayOfWeek < 0 || $dayOfWeek > 4) { // Cambiado para reflejar lunes = 0 y viernes = 4
            $errors[] = "No es poden fer reserves fora dels dies laborables.";
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
                'fin' => $fin,
                'motiu' => 'Ja hi ha una reserva un dels dies que vols.'
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

                if (date('w', strtotime($currentDate)) < 0 || date('w', strtotime($currentDate)) > 4) {
                    continue;
                }

                if (comprovarReserva($connexio, $aula, $currentDate, $iniMins, $finMins)) {
                    $conflicts[] = [
                        'data' => $currentDate,
                        'aula' => $aula,
                        'ini' => $ini,
                        'fin' => $fin,
                        'motiu' => 'Ja hi ha una reserva un dels dies que vols.'
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

    foreach ($reserves as &$reserva) {
        $reserva['color'] = $colorsAules[$reserva['aula']] ?? '#000000';
        insertReserva($connexio, $reserva);
    }    

    header('Content-Type: application/json');
    echo json_encode(['success' => true]);
    exit;
} else {
    echo "Accés no permès";
}
?>