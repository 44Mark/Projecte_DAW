<?php
session_start();

// Verifiquem que s'han rebut els paràmetres correctes.
if (!isset($_GET['code']) || !isset($_GET['state'])) {
    die('Falten parametetres en la resposta de Google.');
}

// Verifiquem que el paràmetre state coincideix amb el de la sessió.
if ($_GET['state'] !== $_SESSION['oauth_state']) {
    die('El parámetro state no coincideix.');
}

$code = $_GET['code'];

require_once __DIR__ . '/../../../vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../..');
$dotenv->load();

$clientID     = $_ENV['GOOGLE_CLIENT_ID'];
$clientSecret = $_ENV['GOOGLE_CLIENT_SECRET'];
$redirectURI  = $_ENV['GOOGLE_REDIRECT_URI'];

$tokenURL = 'https://oauth2.googleapis.com/token';
$data = [
    'code'          => $code,
    'client_id'     => $clientID,
    'client_secret' => $clientSecret,
    'redirect_uri'  => $redirectURI,
    'grant_type'    => 'authorization_code'
];

// Creem una petició Curl per obtenir el token d'accés.
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $tokenURL);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
curl_close($ch);

$tokenData = json_decode($response, true);

// Verifiquem que s'ha rebut el token d'accés.
if (!isset($tokenData['access_token'])) {
    die('Error al obtenir el token d acces: ' . $response);
}

$accessToken = $tokenData['access_token'];

// Creem una petició Curl amb el token d'accés per obtenir les dades de l'usuari.
$userInfoURL = 'https://www.googleapis.com/oauth2/v2/userinfo';
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $userInfoURL);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Authorization: Bearer ' . $accessToken]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$userInfoResponse = curl_exec($ch);
curl_close($ch);

// Descodifiquem les dades de l'usuari.
$userData = json_decode($userInfoResponse, true);

// Verifiquem que el correu pertany al domini sapalomera.cat.
if (strpos($userData['email'], '@sapalomera.cat') === false) {
    die('Accés denegat: el correu no pertany al domini sapalomera.cat.');
}

// Incluim el controlador d'autenticació.
require_once __DIR__ . '../../../../app/controlador/autenticacio.php';

// Verifiquem que l'usuari és un professor actiu.
if (!ProfeActiu($userData['email'], $connexio)) {
    die('Accés denegat: L\'usuari no és un professor actiu.');
}

// Si tot esta correcte, guardem les dades de l'usuari a la sessió.
$_SESSION['user'] = $userData;
$_SESSION['profe'] = $userData['email'];


// Redirecció a la página principal.
header("Location: /DAW/public/calendari.php");
exit;
