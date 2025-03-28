<?php
session_start();
session_unset();
session_destroy();
header("Location: /DAW/public/calendari.php");
exit;
?>
