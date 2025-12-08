<?php
include 'db.php';
include 'seguridad.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $id = $_POST['id_tarjeta'] ?? '';
  $titulo = $_POST['titulo'] ?? '';
  $descripcion = $_POST['descripcion'] ?? '';
  $id_seccion = isset($_POST['id_seccion']) && $_POST['id_seccion'] !== '' ? $_POST['id_seccion'] : null;

  if (!$id) {
    echo json_encode(['status' => 'error', 'mensaje' => 'ID no proporcionado.']);
    exit;
  }

  $carpetaBase = '../img/tarjetas/';
  $nuevaRuta = '';

  // Buscar imagen actual
  $consulta = $conexion->prepare("SELECT imagen FROM tarjetas WHERE id_tarjeta = ?");
  $consulta->bind_param("i", $id);
  $consulta->execute();
  $resultado = $consulta->get_result();
  $tarjeta = $resultado->fetch_assoc();
  $imagenActual = $tarjeta['imagen'] ?? '';
  $consulta->close();

  // Si sube una nueva imagen
  if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
    $nombreOriginal = basename($_FILES['imagen']['name']);
    $extension = pathinfo($nombreOriginal, PATHINFO_EXTENSION);
    $nombreFinal = uniqid('tarjeta_') . '.' . $extension;
    $nuevaRuta = $carpetaBase . $nombreFinal;

    if (!file_exists($carpetaBase)) {
      mkdir($carpetaBase, 0777, true);
    }

    if (move_uploaded_file($_FILES['imagen']['tmp_name'], $nuevaRuta)) {
      if (file_exists($imagenActual)) unlink($imagenActual);
    }
  } else {
    $nuevaRuta = $imagenActual;
  }

  // Actualizar según si tiene o no sección
  if ($id_seccion === null) {
    $stmt = $conexion->prepare("UPDATE tarjetas SET titulo=?, descripcion=?, imagen=?, id_seccion=NULL WHERE id_tarjeta=?");
    $stmt->bind_param("sssi", $titulo, $descripcion, $nuevaRuta, $id);
  } else {
    $stmt = $conexion->prepare("UPDATE tarjetas SET titulo=?, descripcion=?, imagen=?, id_seccion=? WHERE id_tarjeta=?");
    $stmt->bind_param("sssii", $titulo, $descripcion, $nuevaRuta, $id_seccion, $id);
  }

  echo $stmt->execute()
    ? json_encode(['status' => 'ok', 'mensaje' => 'Tarjeta actualizada correctamente.'])
    : json_encode(['status' => 'error', 'mensaje' => 'Error al actualizar en la base de datos.']);

  $stmt->close();
  $conexion->close();
}
?>