<?php
// Cargar el autoload de Composer para usar phpdotenv
require_once __DIR__ . '/../../../vendor/autoload.php';

// Cargar las variables de entorno desde el archivo .env
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../..');
$dotenv->load();

// Inicia la sesión para poder almacenar el 'state'
session_start();

// Credenciales de Google usando las variables del .env
$clientID    = $_ENV['GOOGLE_CLIENT_ID'];
$redirectURI = $_ENV['GOOGLE_REDIRECT_URI'];
$scope       = $_ENV['GOOGLE_SCOPE'] ?? 'email profile';

// Genera un valor aleatorio para el parámetro state (prevención de CSRF)
$state = bin2hex(random_bytes(8));
$_SESSION['oauth_state'] = $state;

// Construye la URL de autorización de Google
$params = [
    'client_id'     => $clientID,
    'redirect_uri'  => $redirectURI,
    'response_type' => 'code',
    'scope'         => $scope,
    'state'         => $state
];

$authUrl = 'https://accounts.google.com/o/oauth2/v2/auth?' . http_build_query($params);

// Redirige al usuario a la URL de autorización de Google
header("Location: $authUrl");
exit;
