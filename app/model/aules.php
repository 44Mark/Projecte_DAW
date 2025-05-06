<?php
require_once __DIR__ . '/../../config/connexio.php';

// Obtener únicamente las aulas actives.
function agafarAules() {
    global $connexio;
    $stmt = $connexio->prepare("SELECT nom FROM kw_aules WHERE mostrar = 1");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Funció per obtenir les reserves i transformar-les a JSON per a FullCalendar.
function obtenirHorarisClases($connexio) {
    try {
        $stmt = $connexio->prepare("SELECT id, motiu, color, profe, grup, aula, data, franja, ini, fin FROM kw_reserves");
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (!$result) {
            return false;
        }

        $events = array();

        foreach ($result as $row) {
            if (empty($row['grup']) || empty($row['aula'])) {
                continue;
            }

            $date = $row['data'];

            $startTotalMins = $row['ini'];
            $endTotalMins   = $row['fin'];

            $startHour = floor($startTotalMins / 60);
            $startMin  = $startTotalMins % 60;
            $startTime = sprintf('%02d:%02d', $startHour, $startMin);

            $endHour = floor($endTotalMins / 60);
            $endMin  = $endTotalMins % 60;
            $endTime = sprintf('%02d:%02d', $endHour, $endMin);

            $start = $date . "T" . $startTime;
            $end   = $date . "T" . $endTime;

            $profe = $row['profe'];
            $motiu = $row['motiu'];

            $title = $startTime . ' - ' . $row['aula'];

            $events[] = array(
                'title' => $title,
                'start' => $start,
                'end'   => $end,
                'color' => $row['color'],
                'extendedProps' => [
                    'profe' => $row['profe'],
                    'motiu' => $row['motiu'],
                    'grup'  => $row['grup'],
                    'aula'  => $row['aula']
                ]
            );

        }

        return json_encode($events);
    } catch (Exception $e) {
        error_log("Error en obtenirHorarisClases: " . $e->getMessage());
        return false;
    }
}

// Funció per obtenir nomes les aules de la taula kw_aules
function obtenirAules($connexio) {
    try {
        $stmt = $connexio->prepare("SELECT nom FROM kw_aules WHERE mostrar = 1");
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (!$result) {
            return false;
        }

        return json_encode($result);
    } catch (Exception $e) {
        error_log("Error en obtenirAules: " . $e->getMessage());
        return false;
    }
}

// Funció per obtenir els grups de la taula kw_solucio
function agafarGrups($connexio) {
    try {
        $stmt = $connexio->prepare("SELECT DISTINCT grup FROM kw_solucio WHERE grup IS NOT NULL ORDER BY grup ASC");
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (!$result) {
            return false;
        }

        return json_encode($result);
    } catch (Exception $e) {
        error_log("Error en obtenirGrups: " . $e->getMessage());
        return false;
    }
}

// Funció per obtenir les reserves de la taula reserves
function obtenirReserves($connexio) {
    $sql = "SELECT * FROM reserves";
    $resultat = $connexio->query($sql);

    if ($resultat->num_rows > 0) {
        return $resultat->fetch_all(MYSQLI_ASSOC);
    } else {
        return [];
    }
}

// // Funció per obtenir els professors de la taula kw_solucio
// function agafarProfessors($connexio) {
//     try {
//         $stmt = $connexio->prepare("SELECT DISTINCT profe FROM kw_solucio WHERE profe IS NOT NULL ORDER BY profe ASC");
//         $stmt->execute();
//         $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

//         if (!$result) {
//             return false;
//         }

//         return json_encode($result);
//     } catch (Exception $e) {
//         error_log("Error en obtenirProfessors: " . $e->getMessage());
//         return false;
//     }
// }

// Funció per inserir una reserva a la base de dades
function insertReserva($connexio, $reserva) {
    try {
        $sql = "INSERT INTO kw_reserves 
                (motiu, profe, grup, aula, data, ini, fin, color)
                VALUES 
                (:motiu, :profe, :grup, :aula, :data, :ini, :fin, :color)";
        
        $stmt = $connexio->prepare($sql);
        $stmt->execute([
            ':motiu' => $reserva['motiu'],
            ':profe' => $reserva['profe'],
            ':grup' => $reserva['grup'],
            ':aula' => $reserva['aula'],
            ':data' => $reserva['data'],
            ':ini' => $reserva['hora_ini'],
            ':fin' => $reserva['hora_fi'],
            ':color' => $reserva['color'] ?? '#000000'
        ]);

        return true;
    } catch (Exception $e) {
        error_log("Error en insertReserva: " . $e->getMessage());
        return false;
    }
}

// Funció per actualitzar una reserva a la base de dades
function actualitzarReserva($connexio, $reserva) {
    try {
        $sql = "UPDATE kw_reserves 
                SET motiu = :motiu, profe = :profe, grup = :grup, aula = :aula, data = :data, ini = :ini, fin = :fin, color = :color 
                WHERE id = :id";
        
        $stmt = $connexio->prepare($sql);
        $stmt->execute([
            ':motiu' => $reserva['motiu'],
            ':profe' => $reserva['profe'],
            ':grup' => $reserva['grup'],
            ':aula' => $reserva['aula'],
            ':data' => $reserva['data'],
            ':ini' => $reserva['hora_ini'],
            ':fin' => $reserva['hora_fi'],
            ':color' => $reserva['color'] ?? '#000000',
            ':id' => $reserva['id']
        ]);

        return true;
    } catch (Exception $e) {
        error_log("Error en actualitzarReserva: " . $e->getMessage());
        return false;
    }
}

// Funció per comprovar si ja existeix una reserva en una aula durant unes hores determinades a kw_reserves
function comprovarReserva($connexio, $aula, $data, $hora_ini, $hora_fi) {
    try {
        // Asegurar que el prefijo "Aula " se maneje de manera consistente en las verificaciones
        if (strpos($aula, 'Aula ') === false) {
            $aula = 'Aula ' . $aula;
        }

        $sql = "SELECT * FROM kw_reserves 
                WHERE aula = :aula AND data = :data 
                AND ((ini < :hora_fi AND fin > :hora_ini))
                LIMIT 1";

        $stmt = $connexio->prepare($sql);
        $stmt->execute([
            ':aula' => $aula,
            ':data' => $data,
            ':hora_ini' => $hora_ini,
            ':hora_fi' => $hora_fi
        ]);

        $conflict = $stmt->fetch(PDO::FETCH_ASSOC);

        return $conflict ? $conflict : false;
    } catch (Exception $e) {
        error_log("Error en comprovarReserva: " . $e->getMessage());
        return false;
    }
}

// Funció per comprovar si el professor jat e una reserva en aquelles hores en una altre aula.
function comprovarReservaProfessor($connexio, $profe, $data, $hora_ini, $hora_fi) {
    try {
        $sql = "SELECT * FROM kw_reserves 
                WHERE UPPER(profe) = UPPER(:profe)
                AND data = :data
                AND ((ini < :hora_fi) AND (fin > :hora_ini))
                LIMIT 1";
        $stmt = $connexio->prepare($sql);
        $stmt->execute([
            ':profe'     => $profe,
            ':data'      => $data,
            ':hora_ini'  => $hora_ini,
            ':hora_fi'   => $hora_fi
        ]);
        $conflict = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $conflict ? $conflict : false;
    } catch (Exception $e) {
        error_log("Error en comprovarReservaProfessor: " . $e->getMessage());
        return false;
    }
}

// Funció per agafar les reserves del professor que ha iniciat la sessió
function agafarReserves($connexio, $profe) {
    try {
        $stmt = $connexio->prepare("SELECT * FROM kw_reserves WHERE UPPER(profe) = UPPER(:profe) ORDER BY data ASC");
        $stmt->execute([':profe' => $profe]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        error_log("Error en agafarReserves: " . $e->getMessage());
        return false;
    }
}

// Funció per eliminar una reserva
function eliminarReserva($connexio, $id) {
    try {
        $stmt = $connexio->prepare("DELETE FROM kw_reserves WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return true;
    } catch (Exception $e) {
        error_log("Error en eliminarReserva: " . $e->getMessage());
        return false;
    }
}

function comprovarReservaSolucio($connexio, $aula, $data, $hora_ini, $hora_fi) {
    try {
        // Asegurar que el prefijo "Aula " se maneje de manera consistente en las verificaciones
        if (strpos($aula, 'Aula ') === false) {
            $aula = 'Aula ' . $aula;
        }

        $sql = "SELECT * FROM kw_solucio 
                WHERE aula = :aula AND dia = WEEKDAY(:data)
                AND ((ini < :hora_fi AND fin > :hora_ini))
                LIMIT 1";

        $stmt = $connexio->prepare($sql);
        $stmt->execute([
            ':aula' => $aula,
            ':data' => $data,
            ':hora_ini' => $hora_ini,
            ':hora_fi' => $hora_fi
        ]);

        $conflict = $stmt->fetch(PDO::FETCH_ASSOC);

        return $conflict ? $conflict : false;
    } catch (Exception $e) {
        error_log("Error en comprovarReservaSolucio: " . $e->getMessage());
        return false;
    }
}

function comprovarReservaProfessorSolucio($connexio, $profe, $data, $hora_ini, $hora_fi) {
    try {
        $sql = "SELECT * FROM kw_solucio 
                WHERE UPPER(profe) = UPPER(:profe)
                AND dia = WEEKDAY(:data)
                AND ((ini < :hora_fi AND fin > :hora_ini))
                LIMIT 1";

        $stmt = $connexio->prepare($sql);
        $stmt->execute([
            ':profe' => $profe,
            ':data' => $data,
            ':hora_ini' => $hora_ini,
            ':hora_fi' => $hora_fi
        ]);

        $conflict = $stmt->fetch(PDO::FETCH_ASSOC);

        return $conflict ? $conflict : false;
    } catch (Exception $e) {
        error_log("Error en comprovarReservaProfessorSolucio: " . $e->getMessage());
        return false;
    }
}
