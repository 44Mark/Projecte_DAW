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

    $id = $_POST['id'] ?? null;
    $motiu = $_POST['motivo'] ?? '';
    $profe = $_POST['profe'] ?? '';
    $grup  = $_POST['grup']  ?? '';
    $aula  = $_POST['aula']  ?? '';
    $data  = $_POST['data'] ?? '';
    $ini   = $_POST['ini'] ?? '';
    $fin   = $_POST['fin'] ?? '';

    // Asegurar que el prefijo "Aula " se maneje de manera consistente
    if (strpos($aula, 'Aula ') === false) {
        $aula = 'Aula ' . $aula;
    }

    if (!$id || empty($data) || empty($ini) || empty($fin)) {
        $errors[] = "Falta omplir dades en algun camp.";
    } else {
        $dayOfWeek = date('N', strtotime($data));
        if ($dayOfWeek < 1 || $dayOfWeek > 5) {
            $errors[] = "Les reserves només es poden fer en dies laborables.";
        }

        $iniParts = explode(':', $ini);
        $finParts = explode(':', $fin);
        $iniMins = ((int)$iniParts[0]) * 60 + ((int)$iniParts[1]);
        $finMins = ((int)$finParts[0]) * 60 + ((int)$finParts[1]);

        if ($iniMins < 480 || $finMins > 1290) {
            $errors[] = "Les reserves s'han de fer entre les 8:00 i les 21:30.";
        }

        $conflictAula = comprovarReserva($connexio, $aula, $data, $iniMins, $finMins);
        if ($conflictAula !== false && $conflictAula['id'] != $id) {
            $conflicts[] = [
                'data' => $data,
                'aula' => $aula,
                'ini'  => $ini,
                'fin'  => $fin,
                'motiu' => 'Ja hi ha una reserva en aquesta aula durant aquest període: ' .
                            'Profe: ' . $conflictAula['profe'] .', ' .  $conflictAula['aula'] . 
                           ', Hora: ' . sprintf('%02d:%02d', floor($conflictAula['ini'] / 60), $conflictAula['ini'] % 60) . 
                           ' - ' . sprintf('%02d:%02d', floor($conflictAula['fin'] / 60), $conflictAula['fin'] % 60)
            ];
        }

        $conflictProfesor = comprovarReservaProfessor($connexio, $profe, $data, $iniMins, $finMins);
        if ($conflictProfesor !== false && $conflictProfesor['id'] != $id) {
            $conflicts[] = [
                'data' => $data,
                'aula' => $aula,
                'ini'  => $ini,
                'fin'  => $fin,
                'motiu' => 'Ja tens una reserva aquest dia i hores a l' . $conflictProfesor['aula'] .
                           ' (el ' . date('d/m/Y', strtotime($conflictProfesor['data'])) . 
                           ' de ' . sprintf('%02d:%02d', floor($conflictProfesor['ini'] / 60), $conflictProfesor['ini'] % 60) . 
                           ' a ' . sprintf('%02d:%02d', floor($conflictProfesor['fin'] / 60), $conflictProfesor['fin'] % 60) . ')'
            ];
        }

        $conflictAulaSolucio = comprovarReservaSolucio($connexio, $aula, $data, $iniMins, $finMins);
        if ($conflictAulaSolucio !== false && $conflictAulaSolucio['id'] != $id) {
            $conflicts[] = [
                'data' => $data,
                'aula' => $aula,
                'ini'  => $ini,
                'fin'  => $fin,
                'motiu' => 'Hi ha classe en aquesta aula!!! ' .
                            'Profe: ' . $conflictAulaSolucio['profe'] . ', Aula: ' . $conflictAulaSolucio['aula'] .
                            ', Hora: ' . sprintf('%02d:%02d', floor($conflictAulaSolucio['ini'] / 60), $conflictAulaSolucio['ini'] % 60) .
                            ' - ' . sprintf('%02d:%02d', floor($conflictAulaSolucio['fin'] / 60), $conflictAulaSolucio['fin'] % 60)
            ];
        }

        $conflictProfeSolucio = comprovarReservaProfessorSolucio($connexio, $profe, $data, $iniMins, $finMins);
        if ($conflictProfeSolucio !== false && $conflictProfeSolucio['id'] != $id) {
            $conflicts[] = [
                'data' => $data,
                'aula' => $aula,
                'ini'  => $ini,
                'fin'  => $fin,
                'motiu' => 'Ja tens una reserva a kw_solucio aquest dia a l\'aula ' . $conflictProfeSolucio['aula'] .
                        ' (de ' . sprintf('%02d:%02d', floor($conflictProfeSolucio['ini'] / 60), $conflictProfeSolucio['ini'] % 60) .
                        ' a ' . sprintf('%02d:%02d', floor($conflictProfeSolucio['fin'] / 60), $conflictProfeSolucio['fin'] % 60) . ')'
            ];
        }
    }

    if (!empty($conflicts)) {
        header('Content-Type: application/json');
        echo json_encode([
            'success' => false,
            'conflicts' => $conflicts,
            'message' => array_map(fn($conflict) => $conflict['motiu'], $conflicts)
        ]);
        exit;
    }

    if (!empty($errors)) {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'errors' => $errors]);
        exit;
    }

    $reserva = [
        'id' => $id,
        'motiu' => $motiu,
        'profe' => $profe,
        'grup' => $grup,
        'aula' => $aula,
        'data' => $data,
        'hora_ini' => $iniMins,
        'hora_fi' => $finMins,
        'color' => $colorsAules[$aula] ?? '#000000'
    ];

    if (actualitzarReserva($connexio, $reserva)) {
        echo json_encode(['success' => true, 'message' => 'Reserva actualitzada correctament.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error en actualitzar la reserva.']);
    }
    exit;
} else {
    echo "Accés no permès";
}
?>