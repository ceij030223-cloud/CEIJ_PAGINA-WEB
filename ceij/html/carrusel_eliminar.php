<?php
include 'db.php';
include 'seguridad.php';

if (isset($_POST['seleccion']) && is_array($_POST['seleccion'])) {

    $ids = array_map('intval', $_POST['seleccion']);
    $ids_list = implode(',', $ids);

    // Buscar rutas
    $res = $conexion->query("SELECT imagen FROM carrusel WHERE id IN ($ids_list)");

    while($fila = $res->fetch_assoc()) {
        $ruta = "../img/carrusel/" . $fila['imagen'];
        if (file_exists($ruta)) {
            unlink($ruta);
        }
    }

    // Eliminar registros
    $conexion->query("DELETE FROM carrusel WHERE id IN ($ids_list)");

    echo "success";
    exit;
}

// Si algo salió mal
echo "error";
exit;
?>
