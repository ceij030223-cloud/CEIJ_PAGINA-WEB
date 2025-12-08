<?php
include 'db.php';
include 'seguridad.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $id = $_POST['id_tarjeta'] ?? '';

  if (!$id) {
    echo json_encode(['status' => 'error', 'mensaje' => 'ID no recibido.']);
    exit;
  }

  // Obtener la ruta de la imagen
  $consulta = $conexion->prepare("SELECT imagen FROM tarjetas WHERE id_tarjeta = ?");
  $consulta->bind_param("i", $id);
  $consulta->execute();
  $resultado = $consulta->get_result();
  $tarjeta = $resultado->fetch_assoc();
  $rutaImagen = $tarjeta['imagen'] ?? '';
  $consulta->close();

  // Eliminar registro
  $stmt = $conexion->prepare("DELETE FROM tarjetas WHERE id_tarjeta = ?");
  $stmt->bind_param("i", $id);

  if ($stmt->execute()) {
    if ($rutaImagen && file_exists($rutaImagen)) {
      unlink($rutaImagen);
    }
    echo json_encode(['status' => 'ok', 'mensaje' => 'Tarjeta eliminada correctamente.']);
  } else {
    echo json_encode(['status' => 'error', 'mensaje' => 'Error al eliminar tarjeta.']);
  }


  $stmt->close();
  $conexion->close();
}
?>
