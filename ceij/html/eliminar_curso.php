<?php
include 'db.php';
include 'seguridad.php';
// Validar que se haya recibido un id
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: admin_cursos.php?msg=error");
    exit;
}

$id = intval($_GET['id']);

// Obtener la ruta de la imagen del curso antes de eliminar
$stmt = $conexion->prepare("SELECT imagen FROM cursos WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->bind_result($imagen);
$stmt->fetch();
$stmt->close();

// Eliminar el registro de la base de datos
$stmt = $conexion->prepare("DELETE FROM cursos WHERE id = ?");
$stmt->bind_param("i", $id);
if ($stmt->execute()) {
    // Si existe imagen, eliminar archivo físico
    if (!empty($imagen) && file_exists($imagen)) {
        unlink($imagen);
    }
    $stmt->close();
    header("Location: admin_cursos.php?msg=deleted");
    exit;
} else {
    $stmt->close();
    header("Location: admin_cursos.php?msg=error");
    exit;
}
?>
