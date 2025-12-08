<?php
include 'db.php';
include 'seguridad.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = $_POST['titulo'] ?? '';
    $descripcion = $_POST['descripcion'] ?? '';
    $duracion = $_POST['duracion'] ?? '';
    $alumnos = $_POST['alumnos'] ?? '';
    $fecha_inicio = $_POST['fecha_inicio'] ?? '';
    $fecha_fin = $_POST['fecha_fin'] ?? '';
    $horario = $_POST['horario'] ?? '';
    $dias = $_POST['dias'] ?? '';
    $modalidad = $_POST['modalidad'] ?? 'Presencial'; // NUEVO CAMPO
    $sucursal = $_POST['sucursal'] ?? '';
    $costo_total = $_POST['costo_total'] ?? '';
    $costo_inscripcion = $_POST['costo_inscripcion'] ?? '';
    $costo_sesion = $_POST['costo_sesion'] ?? '';

    // === Carpeta destino (desde /html hacia /img/cursos) ===
    $carpeta = '../img/cursos/';
    if (!file_exists($carpeta)) {
        mkdir($carpeta, 0777, true);
    }

    // === Verificar si se subió una imagen ===
    $ruta = '';
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
        $nombreOriginal = basename($_FILES['imagen']['name']);
        $extension = pathinfo($nombreOriginal, PATHINFO_EXTENSION);
        $nombreFinal = uniqid('curso_') . '.' . $extension;
        $ruta = $carpeta . $nombreFinal;

        if (!move_uploaded_file($_FILES['imagen']['tmp_name'], $ruta)) {
            echo "<script>alert('Error al subir la imagen.'); window.location.href='admin_cursos.php';</script>";
            exit;
        }
    } else {
        echo "<script>alert('Por favor selecciona una imagen.'); window.location.href='admin_cursos.php';</script>";
        exit;
    }

    // === Guardar datos en la BD ===
    $stmt = $conexion->prepare("INSERT INTO cursos 
        (titulo, descripcion, duracion, alumnos, imagen, fecha_inicio, fecha_fin, horario, dias, modalidad, sucursal, costo_total, costo_inscripcion, costo_sesion)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssisssssssddd", 
        $titulo, $descripcion, $duracion, $alumnos, $ruta, $fecha_inicio, $fecha_fin, $horario, $dias, $modalidad, $sucursal, $costo_total, $costo_inscripcion, $costo_sesion);

    if ($stmt->execute()) {
        header("Location: admin_cursos.php?msg=ok");
    } else {
        echo "<script>alert('Error al guardar en la base de datos.'); window.location.href='admin_cursos.php';</script>";
    }

    $stmt->close();
    $conexion->close();
}
?>