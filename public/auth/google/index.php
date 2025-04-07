<?php
// Aquest script redirigira a l'usuari a la página d'autorització de Google per obtenir el token d'accés amb OAuth 2.0.

// Carreguem l'autoload del Composer per utilitzar phpdotenv.
require_once __DIR__ . '/../../../vendor/autoload.php';

// Carreguem les variables guardades al .env.
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../..');
$dotenv->load();

// Iniciem la sessió per emmagatzemar el 'state'.
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Credencials de Google guardades al .env.
$clientID    = $_ENV['GOOGLE_CLIENT_ID'];
$redirectURI = $_ENV['GOOGLE_REDIRECT_URI'];
$scope       = $_ENV['GOOGLE_SCOPE'] ?? 'email profile';

// Generem un valor aleatori per al 'state' per evitar CSRF (Cross-Site Request Forgery).
$state = bin2hex(random_bytes(8));
$_SESSION['oauth_state'] = $state;

// Construïm la URL d'autorització de Google amb els paràmetres necessaris.
// La URL d'autorització és la que Google utilitza per demanar permís a l'usuari per accedir a les seves dades.
$params = [
    'client_id'     => $clientID,
    'redirect_uri'  => $redirectURI,
    'response_type' => 'code',
    'scope'         => $scope,
    'state'         => $state
];

// La URL d'autorització de Google és la que utilitzarem per redirigir l'usuari.
$authUrl = 'https://accounts.google.com/o/oauth2/v2/auth?' . http_build_query($params);

// Redirigim a l'usuari a la URL d'autorització de Google.
// Això obrirà una finestra emergent o una nova pestanya al navegador de l'usuari per demanar permís per accedir a les seves dades.
header("Location: $authUrl");
exit;