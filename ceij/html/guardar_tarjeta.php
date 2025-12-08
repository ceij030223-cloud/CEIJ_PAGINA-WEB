<?php
include 'db.php';
include 'seguridad.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $titulo = $_POST['titulo'] ?? '';
  $descripcion = $_POST['descripcion'] ?? '';
  $id_seccion = isset($_POST['id_seccion']) && $_POST['id_seccion'] !== '' ? $_POST['id_seccion'] : NULL;

  // Carpeta donde se guardarán las imágenes
  $carpetaBase = '../img/tarjetas/';
  if (!file_exists($carpetaBase)) {
    mkdir($carpetaBase, 0777, true);
  }

  $rutaImagen = '';

  // Verificar si hay imagen cargada
  if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
    $nombreOriginal = basename($_FILES['imagen']['name']);
    $extension = pathinfo($nombreOriginal, PATHINFO_EXTENSION);
    $nombreFinal = uniqid('tarjeta_') . '.' . $extension;
    $rutaImagen = $carpetaBase . $nombreFinal;

    if (!move_uploaded_file($_FILES['imagen']['tmp_name'], $rutaImagen)) {
      echo json_encode(['status' => 'error', 'mensaje' => 'Error al subir la imagen.']);
      exit;
    }
  }

  // Insertar en BD
  if ($id_seccion === NULL) {
      $stmt = $conexion->prepare("INSERT INTO tarjetas (titulo, descripcion, imagen, id_seccion) VALUES (?, ?, ?, NULL)");
      $stmt->bind_param("sss", $titulo, $descripcion, $rutaImagen);
  } else {
      $stmt = $conexion->prepare("INSERT INTO tarjetas (titulo, descripcion, imagen, id_seccion) VALUES (?, ?, ?, ?)");
      $stmt->bind_param("sssi", $titulo, $descripcion, $rutaImagen, $id_seccion);
  }

  if ($stmt->execute()) {
    echo json_encode(['status' => 'ok', 'mensaje' => 'Tarjeta guardada correctamente.']);
  } else {
    echo json_encode(['status' => 'error', 'mensaje' => 'Error al guardar en la base de datos.']);
  }

  $stmt->close();
  $conexion->close();
} else {
  echo json_encode(['status' => 'error', 'mensaje' => 'Método no permitido.']);
}
?>
