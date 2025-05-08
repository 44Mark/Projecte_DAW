<?php
require_once __DIR__ . '/../../config/connexio.php';

// Funció per controlar si es professor i esta en actiu al iniciar sessió.
function isProfessorActive($email, $connexio) {
    $stmt = $connexio->prepare("SELECT * FROM usuaris WHERE mail = :email AND tipus = 'Professor' AND actiu = 1");
    $stmt->execute(['email' => $email]);
    return $stmt->fetch(PDO::FETCH_ASSOC) !== false;
}

// Funció per verificar si el usuari té la sessió iniciada i si es administrador.
function esAdmin($connexio, $email) {
    try {
        // Consultar en la base de datos si el usuario es administrador
        $stmt = $connexio->prepare("SELECT admin FROM usuaris WHERE mail = :email LIMIT 1");
        $stmt->execute([':email' => $email]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verificar si el campo admin es igual a 1
        return $result && $result['admin'] == 1;
    } catch (Exception $e) {
        error_log("Error en esAdmin: " . $e->getMessage());
        return false;
    }
}
?>