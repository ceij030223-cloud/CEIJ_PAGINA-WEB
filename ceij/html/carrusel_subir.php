<?php
include 'db.php';
include 'seguridad.php';
// Verificar si se subieron archivos correctamente
if (!isset($_FILES['imagenes']) || !is_array($_FILES['imagenes']['name']) || count($_FILES['imagenes']['name']) === 0 || empty($_FILES['imagenes']['name'][0])) {
    header("Location: admin_index.php?error=no_files");
    exit;
}

$total = count($_FILES['imagenes']['name']);
$carpetaDestino = '../img/carrusel/';

// Crear carpeta si no existe
if (!is_dir($carpetaDestino)) {
    mkdir($carpetaDestino, 0777, true);
}

for ($i = 0; $i < $total; $i++) {
    $nombreArchivo = $_FILES['imagenes']['name'][$i] ?? '';
    $tmp = $_FILES['imagenes']['tmp_name'][$i] ?? '';

    if (empty($tmp) || !file_exists($tmp)) {
        continue;
    }

    $tipo = @mime_content_type($tmp);
    if ($tipo === false || strpos($tipo, 'image/') === false) {
        continue;
    }

    // Evitar duplicados
    $nombreLimpio = preg_replace('/[^A-Za-z0-9_\.-]/', '_', $nombreArchivo);
    $nombreFinal = time() . '_' . uniqid() . '_' . $nombreLimpio;
    $destino = $carpetaDestino . $nombreFinal;

    if (move_uploaded_file($tmp, $destino)) {
        $conexion->query("INSERT INTO carrusel (imagen) VALUES ('$nombreFinal')");
    }
}

// RESPUESTA FINAL PARA AJAX
echo "success";
exit;
?>