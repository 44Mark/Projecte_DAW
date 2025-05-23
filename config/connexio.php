<?php
require_once __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

// Variables d'entorn per a la connexió a la base de dades.
$host   = $_ENV['DB_HOST'];
$dbname = $_ENV['DB_NAME'];
$user   = $_ENV['DB_USER'];
$pass   = $_ENV['DB_PASSW'];

try {
    $connexio = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass);
    $connexio->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) { 
    echo "Error en la conexión: " . $e->getMessage();
}
?>