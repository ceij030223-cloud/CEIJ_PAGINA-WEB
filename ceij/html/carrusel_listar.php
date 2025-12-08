<?php
include 'db.php';
$resultado = $conexion->query("SELECT * FROM carrusel ORDER BY fecha_subida DESC");

$imagenes = [];

while ($fila = $resultado->fetch_assoc()) {
    $imagenes[] = [
        "id" => $fila["id"],
        "imagen" => $fila["imagen"]
    ];
}

header("Content-Type: application/json");
echo json_encode($imagenes);
?>
