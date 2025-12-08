<?php
include 'db.php';
include 'seguridad.php';

// Función para escapar valores
function esc($str){ return htmlspecialchars($str, ENT_QUOTES); }

// Recibir datos del formulario
$id = intval($_POST['id']);
$titulo = $_POST['titulo'];
$descripcion = $_POST['descripcion'];
$duracion = $_POST['duracion'];
$alumnos = intval($_POST['alumnos']);
$fecha_inicio = $_POST['fecha_inicio'];
$fecha_fin = $_POST['fecha_fin'];
$horario = $_POST['horario'];
$dias = $_POST['dias'];
$modalidad = $_POST['modalidad'];
$sucursal = $_POST['sucursal'];
$costo_total = floatval($_POST['costo_total']);
$costo_inscripcion = floatval($_POST['costo_inscripcion']);
$costo_sesion = floatval($_POST['costo_sesion']);

// Obtener la ruta de la imagen actual
$query = $conexion->query("SELECT imagen FROM cursos WHERE id=$id");
$curso = $query->fetch_assoc();
$imagen_actual = $curso['imagen'];

// Carpeta donde se guardan las imágenes
$carpeta = '../img/cursos/';

// Revisar si se subió una nueva imagen
if(isset($_FILES['imagen']) && $_FILES['imagen']['tmp_name'] != "") {

    // Borrar la imagen anterior si existe
    if(file_exists($imagen_actual)){
        unlink($imagen_actual);
    }

    $nombreArchivo = time().'_'.basename($_FILES['imagen']['name']);
    $destino = $carpeta.$nombreArchivo;

    if(move_uploaded_file($_FILES['imagen']['tmp_name'], $destino)){
        $imagen_final = $destino;
    } else {
        // Si falla subir, mantener la antigua
        $imagen_final = $imagen_actual;
    }

} else {
    // Si no se sube nueva imagen, mantener la anterior
    $imagen_final = $imagen_actual;
}

// === ACTUALIZAR EN LA BASE DE DATOS ===
$stmt = $conexion->prepare("UPDATE cursos SET 
    titulo=?, 
    descripcion=?, 
    duracion=?, 
    alumnos=?, 
    fecha_inicio=?, 
    fecha_fin=?, 
    horario=?, 
    dias=?, 
    modalidad=?, 
    sucursal=?, 
    costo_total=?, 
    costo_inscripcion=?, 
    costo_sesion=?, 
    imagen=? 
WHERE id=?");

// Tipos de datos:
// s = string, i = integer, d = double
$stmt->bind_param(
    "sssissssssdddsi",
    $titulo,
    $descripcion,
    $duracion,
    $alumnos,
    $fecha_inicio,
    $fecha_fin,
    $horario,
    $dias,
    $modalidad,
    $sucursal,
    $costo_total,
    $costo_inscripcion,
    $costo_sesion,
    $imagen_final,
    $id
);

if($stmt->execute()){
    header("Location: admin_cursos.php");
} else {
    echo "Error al actualizar el curso: ".$stmt->error;
}

$stmt->close();
$conexion->close();
?>