<?php
require_once __DIR__ . '/../../config/connexio.php';

// Funció per controlar si es professor i esta en actiu al iniciar sessió.
function isProfessorActive($email, $connexio) {
    $stmt = $connexio->prepare("SELECT * FROM usuaris WHERE mail = :email AND tipus = 'Professor' AND actiu = 1");
    $stmt->execute(['email' => $email]);
    return $stmt->fetch(PDO::FETCH_ASSOC) !== false;
}
?>