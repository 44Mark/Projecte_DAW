<?php
require_once __DIR__ . '/../model/aules.php';

// Colors per a les aules.
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
    $grup  = $_POST['grup']  ?? '';
    $aula  = $_POST['aula']  ?? '';

    // Asegurem que el prefixes 'Aula ' estigui present a l'aula.
    if (strpos($aula, 'Aula ') === false) {
        $aula = 'Aula ' . $aula;
    }

    // Es recullen arrays amb dates, hores, repeticions, etc.
    $dates = isset($_POST['data']) ? (array)$_POST['data'] : [];
    $inis = isset($_POST['ini']) ? (array)$_POST['ini'] : [];
    $fins = isset($_POST['fin']) ? (array)$_POST['fin'] : [];
    $repetirs = isset($_POST['repetir']) ? (array)$_POST['repetir'] : [];
    $num_repeticions = isset($_POST['num_repeticions']) ? (array)$_POST['num_repeticions'] : [];

    $reserves = [];

    $id = $_POST['id'] ?? null; // Verificar si es una actualizació.

    if ($id) {
        // Actualitzem la reserva existent
        $reserva = [
            'motiu' => $motiu,
            'profe' => $profe,
            'grup' => $grup,
            'aula' => $aula,
            'data' => $dates[0] ?? '',
            'hora_ini' => $inis[0] ? ((int)explode(':', $inis[0])[0]) * 60 + ((int)explode(':', $inis[0])[1]) : 0,
            'hora_fi' => $fins[0] ? ((int)explode(':', $fins[0])[0]) * 60 + ((int)explode(':', $fins[0])[1]) : 0,
            'color' => $colorsAules[$aula] ?? '#000000',
            'id' => $id
        ];

        if (actualitzarReserva($connexio, $reserva)) {
            echo json_encode(['success' => true, 'message' => 'Reserva actualitzada correctament.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error en actualitzar la reserva.']);
        }
        exit;
    }

    // Recorrer cada data de la reserva
    for ($i = 0; $i < count($dates); $i++) {
        $data = $dates[$i] ?? '';
        $ini = $inis[$i] ?? '';
        $fin = $fins[$i] ?? '';
        $repetir = $repetirs[$i] ?? '';
        $reps = $num_repeticions[$i] ?? null;

        if (empty($data) || empty($ini) || empty($fin)) {
            $errors[] = "Falta omplir dades en algun camp.";
            continue;
        }

        // Comprovem que la data sigui un dia laboral, entre dilluns (1) i divendres (5).
        $dayOfWeek = date('N', strtotime($data)); // 1 (dilluns) a 7 (diumenge).
        if ($dayOfWeek < 1 || $dayOfWeek > 5) {
            $errors[] = "Les reserves només es poden fer en dies laborables.";
            continue;
        }

        // Convertir les hores de format "HH:MM" a minuts.
        $iniParts = explode(':', $ini);
        $finParts = explode(':', $fin);
        $iniMins = ((int)$iniParts[0]) * 60 + ((int)$iniParts[1]);
        $finMins = ((int)$finParts[0]) * 60 + ((int)$finParts[1]);

        // Verificar límits d'horari (entre les 08:00 i 21:30).
        if ($iniMins < 480 || $finMins > 1290) {
            $errors[] = "Les reserves  s'han de fer entre les 8:00 i les 21:30.";
            continue;
        }

        // Comprovar si hi ha conflicte en la mateixa aula.
        $conflictAula = comprovarReserva($connexio, $aula, $data, $iniMins, $finMins);
        if ($conflictAula !== false) {
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
            continue;
        }

        // Comprovar si el professor ja té una reserva en aquest interval (en qualsevol aula).
        $conflictProfesor = comprovarReservaProfessor($connexio, $profe, $data, $iniMins, $finMins);
        if ($conflictProfesor !== false) {
            // Aquí recopilamos datos del conflicto
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
            continue;
        }
        
        // Comprovar si hi ha conflicte en la mateixa aula (també a kw_solucio).
        $conflictAulaSolucio = comprovarReservaSolucio($connexio, $aula, $data, $iniMins, $finMins);
        if ($conflictAulaSolucio !== false) {
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
            continue;
        }

        // Comprovar si el professor ja té reserva a la taula kw_solucio.
        $conflictProfeSolucio = comprovarReservaProfessorSolucio($connexio, $profe, $data, $iniMins, $finMins);
        if ($conflictProfeSolucio !== false) {
            $conflicts[] = [
                'data' => $data,
                'aula' => $aula,
                'ini'  => $ini,
                'fin'  => $fin,
                'motiu' => 'Tens classe aquest dia a l\'aula ' . $conflictProfeSolucio['aula'] .
                        ' (de ' . sprintf('%02d:%02d', floor($conflictProfeSolucio['ini'] / 60), $conflictProfeSolucio['ini'] % 60) .
                        ' a ' . sprintf('%02d:%02d', floor($conflictProfeSolucio['fin'] / 60), $conflictProfeSolucio['fin'] % 60) . ')'
            ];
            continue;
        }


        // No hi ha conflictes: afegir la reserva
        $reserves[] = [
            'motiu' => $motiu,
            'profe' => $profe,
            'grup'  => $grup,
            'aula'  => $aula,
            'data'  => $data,
            'hora_ini' => $iniMins,
            'hora_fi'  => $finMins
        ];

        // Gestió de repeticions, si s'aplica.
        if (is_numeric($reps) && $reps > 0 && in_array($repetir, ['semanal', 'mensual'])) {
            $interval = $repetir === 'semanal' ? '+7 days' : '+28 days';
            $currentDate = $data;
            for ($j = 0; $j < $reps; $j++) {
                $currentDate = date('Y-m-d', strtotime($interval, strtotime($currentDate)));
                // Només afegir les reserves en dies laborables
                if (date('N', strtotime($currentDate)) > 5) {
                    continue;
                }
                if (comprovarReserva($connexio, $aula, $currentDate, $iniMins, $finMins) ||
                    comprovarReservaProfessor($connexio, $profe, $currentDate, $iniMins, $finMins)) {
                    $conflicts[] = [
                        'data' => $currentDate,
                        'aula' => $aula,
                        'ini' => $ini,
                        'fin' => $fin,
                        'motiu' => 'Ja hi ha una reserva en una de les dates repetides.'
                    ];
                } else {
                    $reserves[] = [
                        'motiu' => $motiu,
                        'profe' => $profe,
                        'grup'  => $grup,
                        'aula'  => $aula,
                        'data'  => $currentDate,
                        'hora_ini' => $iniMins,
                        'hora_fi'  => $finMins
                    ];
                }
            }
        }
    }

    // Si hi ha conflictes, informar-los
    if (!empty($conflicts)) {
        header('Content-Type: application/json');
        echo json_encode([
            'success' => false,
            'conflicts' => $conflicts,
            // Cambiar 'message' para usar los mensajes personalizados de 'motiu'
            'message' => array_map(fn($conflict) => $conflict['motiu'], $conflicts)
        ]);
        exit;
    }

    // Si hi ha errors, retornar-los.
    if (!empty($errors)) {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'errors' => $errors]);
        exit;
    }

    // Inserir totes les reserves sense conflictes.
    foreach ($reserves as $reserva) {
        // Assignar color segons l'aula, per defecte '#000000'
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