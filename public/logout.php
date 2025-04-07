<?php
// Al fer Logout.php, esborram la sessió i redirigim a la pàgina inicial.

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
session_unset();
session_destroy();
header("Location: /DAW/public/calendari.php");
exit;
?>
