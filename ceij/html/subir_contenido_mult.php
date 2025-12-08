<?php
header('Content-Type: application/json');
error_reporting(0);
include 'db.php';
include 'seguridad.php';

if (isset($_FILES['imagenes'])) {
    $id_seccion = $_POST['id_seccion'] ?? 0;
    $subido = false;

    foreach ($_FILES['imagenes']['tmp_name'] as $index => $tmp) {
        if ($_FILES['imagenes']['error'][$index] === UPLOAD_ERR_OK) {
            $nombre_original = $_FILES['imagenes']['name'][$index];
            $ext = strtolower(pathinfo($nombre_original, PATHINFO_EXTENSION));
            $es_video = in_array($ext, ['mp4','mov','avi','webm','mkv','flv','mpeg','ogg']);
            $carpeta = $es_video ? __DIR__ . "/../videos/galeria/" : __DIR__ . "/../img/galeria/";
            $ruta_relativa = $es_video ? "../videos/galeria/" : "../img/galeria/";
            if (!file_exists($carpeta)) mkdir($carpeta, 0777, true);
            $nombre_archivo = time() . "_" . basename($nombre_original);
            $ruta_completa = $carpeta . $nombre_archivo;
            if (move_uploaded_file($tmp, $ruta_completa)) {
                $tipo = $es_video ? 'video' : 'imagen';
                $conexion->query("INSERT INTO imagenes_galeria (id_seccion, ruta, tipo) VALUES ('$id_seccion', '$ruta_relativa$nombre_archivo', '$tipo')");
                $subido = true;
            }
        }
    }

    // Generar HTML actualizado de imágenes/videos con controles completos
    $imagenes = $conexion->query("SELECT * FROM imagenes_galeria WHERE id_seccion={$id_seccion}");
    $html_imagenes = '';

    while($img = $imagenes->fetch_assoc()){
        $html_imagenes .= '<div class="img-container border p-1 text-center position-relative me-2 mb-2">';
        $html_imagenes .= '<input type="checkbox" name="seleccion[]" value="'.$img['id_imagen'].'" class="position-absolute m-1">';

        if($img['tipo']==='video'){
            $html_imagenes .= '<video src="'.$img['ruta'].'" class="imagen-mini video-mini border" muted loop autoplay></video>';
        } else {
            $html_imagenes .= '<img src="'.$img['ruta'].'" class="imagen-mini border">';
        }

        $html_imagenes .= '</div>';
    }

    echo json_encode([
        'success' => $subido,
        'id_seccion' => $id_seccion,
        'html_imagenes' => $html_imagenes
    ]);
    exit;
}
