<?php
$host = "localhost";
$user = "root"; // o tu usuario de base de datos
$pass = "";     // o tu contraseña
$dbname = "ceij_db";

$conexion = new mysqli($host, $user, $pass, $dbname);

if ($conexion->connect_error) {
  die("Conexión fallida: " . $conexion->connect_error);
}
?>