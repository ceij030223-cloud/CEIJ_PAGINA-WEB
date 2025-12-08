<?php
session_start();
$_SESSION = []; // Limpiar todas las variables de sesión
session_destroy(); // Destruir la sesión
header("Location: login.html");
exit;
?>
