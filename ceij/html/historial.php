<!-- Modal Historial -->
<div id="historialModal" class="modal fade">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-body" id="contenidoHistorial">
                <!-- Contenido cargado desde historial.php -->
            </div>
        </div>
    </div>
</div>
<?php
// Conexión a la base de datos
require 'db.php';
require 'seguridad.php';
// Obtener datos del usuario (ejemplo con ID fijo, debes adaptar según tu lógica)
$usuario_id = isset($_GET['usuario_id']) ? intval($_GET['usuario_id']) : 0;
$sql_usuario = "SELECT nombre, apellido FROM usuarios WHERE id = $usuario_id";
$result_usuario = $conexion->query($sql_usuario);
$usuario = $result_usuario->fetch_assoc();

// Obtener cursos del usuario
$sql_cursos = "SELECT curso, sucursal, fecha_inicio, fecha_finalizacion, estado 
               FROM progreso_cursos 
               WHERE usuario_id = $usuario_id";
$result_cursos = $conexion->query($sql_cursos);
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historial de Cursos</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
   <link rel="stylesheet" href="../css/historial.css?v=3.1">
</head>
<body>
<div class="contenedor">
    <div class="fila">

                      
<div class="alumnoheader">
<h2 class="nombrealumno" style="font-size: 20px; text-align: center; color: black;"><?php echo $usuario['nombre'] . " " . $usuario['apellido']; ?></h2>
</div>
                
                <div class="tarjeta-body">
                    <div class="tabla-responsiva">
                        <?php while($row = $result_cursos->fetch_assoc()): ?>
                        <!-- Fila -->
                        <div class="curso-fila">
<div class="cursonom"><?php echo $row['curso']; ?></div>            
<div class="fechas">
<strong>Inicio:</strong> <?php echo date('d/m/Y', strtotime($row['fecha_inicio'])); ?>- 
<strong>Finalización:</strong> <?php echo date('d/m/Y', strtotime($row['fecha_finalizacion'])); ?>
</div>
<div class="sucursal"> <strong>Sucursal:</strong> <?php echo $row['sucursal']; ?></div>
<p></p> <span class="estado  <?php echo strtolower(str_replace(' ', '', $row['estado'])); ?>">
            <?php echo $row['estado']; ?></span>
                         
                        </div>
                        <?php endwhile; ?>
                    </div>
                </div>
            
        
    </div>
</div>
</body>
</html>
<?php $conexion->close(); ?>