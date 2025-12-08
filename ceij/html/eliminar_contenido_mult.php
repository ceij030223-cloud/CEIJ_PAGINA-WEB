<?php
header('Content-Type: application/json');
require 'db.php';
require 'seguridad.php';

// Desactivar warnings/notices para no romper el JSON
ini_set('display_errors', 0);

if (isset($_POST['eliminar_multiples']) && isset($_POST['seleccion'])) {

    $ids = array_map('intval', $_POST['seleccion']);
    if (empty($ids)) {
        echo json_encode(['success' => false, 'error' => 'No se enviaron IDs']);
        exit;
    }

    $lista_ids = implode(',', $ids);

    // Obtener rutas físicas antes de borrar
    $resultado = $conexion->query("SELECT ruta FROM imagenes_galeria WHERE id_imagen IN ($lista_ids)");
    if ($resultado) {
        while ($fila = $resultado->fetch_assoc()) {
            $ruta = __DIR__ . '/' . $fila['ruta'];
            if (file_exists($ruta)) unlink($ruta);
        }
    }

    // Eliminar registros de BD
    $conexion->query("DELETE FROM imagenes_galeria WHERE id_imagen IN ($lista_ids)");

    // Retornar JSON correcto
    echo json_encode([
        "success" => true,
        "id_seccion" => isset($_POST['id_seccion']) ? intval($_POST['id_seccion']) : 0
    ]);
    exit;
}

$ids = array_map('intval', $_POST['seleccion']);
file_put_contents('debug.txt', print_r($ids, true), FILE_APPEND);

$lista_ids = implode(',', $ids);
$resultado = $conexion->query("SELECT id_imagen, ruta FROM imagenes_galeria WHERE id_imagen IN ($lista_ids)");

if ($resultado) {
    while ($fila = $resultado->fetch_assoc()) {
        file_put_contents('debug.txt', print_r($fila, true), FILE_APPEND);
        $ruta = __DIR__ . '/' . $fila['ruta'];
        if (file_exists($ruta)) unlink($ruta);
    }
}


// Si se llama mal al script
echo json_encode(['success' => false, 'error' => 'Parámetros incorrectos']);
exit;