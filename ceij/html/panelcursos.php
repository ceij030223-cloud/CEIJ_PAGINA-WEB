<?php
require 'db.php'; // Ajusta la ruta según tu estructura
require 'seguridad.php'; // Asegúrate de incluir seguridad.php para manejar sesiones
// Obtener TODOS los usuarios registrados
$sql = "SELECT u.id, u.nombre, u.apellido, u.email, u.telefono,
               pc.curso, pc.sucursal, pc.fecha_inicio, pc.fecha_finalizacion, 
               pc.estado, pc.certificado
        FROM usuarios u
        LEFT JOIN (
            SELECT pc1.*
            FROM progreso_cursos pc1
            WHERE pc1.id = (
                SELECT MAX(pc2.id) 
                FROM progreso_cursos pc2 
                WHERE pc2.usuario_id = pc1.usuario_id
            )
        ) pc ON u.id = pc.usuario_id
         WHERE u.rol = 'usuario'";

$result = $conexion->query($sql);
$usuarios = [];
if ($result && $result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $usuarios[] = $row;
    }
} else {
    
    $usuarios[] = [
        'id' => 0,
        'nombre' => 'No hay usuarios',
        'apellido' => 'registrados',
        'email' => 'ejemplo@correo.com', 
        'telefono' => '000-0000',
        'curso' => '',
        'fecha_inicio' => '',
        'fecha_finalizacion' => '',
        'estado' => '',
        'certificado' => '',
        'sucursal' => ''
         ];
}


// Configuración de paginación
$resultados_por_pagina = 4;
$pagina_actual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$offset = ($pagina_actual - 1) * $resultados_por_pagina;

// Obtener el total de usuarios
$sql_total = "SELECT COUNT(*) as total FROM usuarios WHERE rol = 'usuario'";
$result_total = $conexion->query($sql_total);
$total_usuarios = $result_total->fetch_assoc()['total'];
$total_paginas = ceil($total_usuarios / $resultados_por_pagina);

// Obtener usuarios con paginación
$sql = "SELECT u.id, u.nombre, u.apellido, u.email, u.telefono,
               pc.curso, pc.sucursal, pc.fecha_inicio, pc.fecha_finalizacion, 
               pc.estado, pc.certificado
        FROM usuarios u
        LEFT JOIN (
            SELECT pc1.*
            FROM progreso_cursos pc1
            WHERE pc1.id = (
                SELECT MAX(pc2.id) 
                FROM progreso_cursos pc2 
                WHERE pc2.usuario_id = pc1.usuario_id
            )
        ) pc ON u.id = pc.usuario_id
        WHERE u.rol = 'usuario'
        LIMIT $offset, $resultados_por_pagina";

$result = $conexion->query($sql);
$usuarios = [];
if ($result && $result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $usuarios[] = $row;
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrap + íconos -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <title>Administración de alumnos</title>

    <!-- Estilos personalizados -->
    <link rel="stylesheet" href="../css/panelcursos.css?v=1.4"/>
    <link rel="icon" href="../icon/ceijicon.ico" />
    


  <!-- Font Awesome (iconos) -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.2/css/bootstrap.min.css">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.2/js/bootstrap.min.js"></script>

  <!-- Animación scroll AOS -->
  <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">
</head>
<body>
    
<div class="container">
<!--BOTON DE REGRESAR ATRAS EN EL INDEX-->
 <button id="bt-regresar" class="boton-flecha" onclick="window.location.href='index.php'">
        <i class="fa-solid fa-arrow-left"></i> Ir al inicio 
    </button> 

<button id="bt-irperfil" class="boton-irperfil"   onclick="window.location.href='perfiladmin.php'">
<i class="fa-solid fa-arrow-right" style="color: white"></i></i>
Ir al perfil
</button>


<div class="row">
    <div class="col-lg-12 card-margin">
        <div class="card search-form">
            <div class="card-body p-0">
                <form id="search-form">
                    <div class="row">
                        <div class="col-12">
                            <div class="row no-gutters">
                                <div class="col-lg-3 col-md-3 col-sm-12 p-0">
                                    <select class="form-control" id="exampleFormControlSelect1">
                                          <!--Sucursal-->
                                        <option>Mezquital</option>
                                        <option>Piña</option>
                                        
                                    </select>
                                </div>
                                <div class="col-lg-8 col-md-6 col-sm-12 p-0">
                                    <input type="text" placeholder="Buscar..." class="form-control" id="search" name="search">
                                </div>
                                <div class="col-lg-1 col-md-3 col-sm-12 p-0">
                                    <button type="submit" class="btn btn-base">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="row">
        <div class="col-12">
            <div class="card card-margin">
                <div class="card-body">
                    <div class="row search-body">
                        <div class="col-lg-12">
                            <div class="search-result">
                                <div class="result-header">
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="records">Usuarios:
                                          <b><?php echo($total_usuarios); ?></b></div>
<!--<div class="records">Mostrar: <b> < ?php echo count($usuarios); ?></b> de<b> < ?php echo($total_usuarios); ?></b> resultados</div>-->




                                          </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="result-actions">
                                                <div class="result-sorting">
                                                   
                                                </div>
                                               
                                            </div>
                                        </div>
                                    </div>
                                </div>
<div class="result-body">
<div class="table-responsive">
<table class="table widget-26">
<tbody>
<?php foreach($usuarios as $row): ?>
<tr data-user-id="<?php echo $row['id']; ?>">
<!--Perfil--  <a href="#">Luis Pablo &amp; Contreras Salomon</a>-->
<td>
 <div class="widget-26-job-title">
 <a href="#"><?php echo htmlspecialchars($row['nombre'] . ' ' . $row['apellido']); ?></a>
<p class="m-0"><a href="#" class="employer-name"> <?php echo htmlspecialchars($row['email']); ?></a><br>
 <span class="text-muted time"><?php echo htmlspecialchars($row['telefono']); ?></span></p>
</div>
<!--Se abre una ventana flotante del historialcursos.html-->
<div class="historialc">
<a href="#" class="ver-historial" data-user-id="<?php echo $row['id']; ?>" 
style="color: blue; font-size:12px; text-decoration:underline;">Historial de cursos</a>
</div>
</td>

<!--Sucursal-->
 <td>
<div class="widget-26-job-info">
<p class="type m-0">Sucursal</p>
<p class="text-muted m-0">En 
    <span class="location">                                                       
  <div class="col-lg-3 col-md-3 col-sm-12 p-0">
 <select class="form-control sucursal-select" name="sucursal"
  style="background-color: #fff; border: 1px solid #ccc; color: #333; font-size: 0.9rem; padding: 5px; width:100px;" disabled>
<option value="">--</option>
<option value="Mezquital" <?php echo ($row['sucursal'] ?? '') == 'Mezquital' ? 'selected' : ''; ?>>Mezquital</option>
<option value="Piña" <?php echo ($row['sucursal'] ?? '') == 'Piña' ? 'selected' : ''; ?>>Piña</option>
</select>
 </div> 
</span></p>
</div>
 </td>

 <!--Curso-->
<td>
<div class="widget-26-job-info">
<p class="type m-0">Curso</p>
<p class="text-muted m-0"> A
<span class="location">                                                       
<div class="col-lg-3 col-md-3 col-sm-12 p-0">
 <select class="curso-select" name="curso" 
  style="background-color: #fff; border: 1px solid #ccc; color: #333; font-size: 0.9rem; padding: 5px; width:280px;" disabled>
<option value="">Seleccionar curso</option>
<option value="SolidWorks" <?php echo ($row['curso'] ?? '') == 'SolidWorks' ? 'selected' : ''; ?>>SolidWorks Básico</option>
<option value="Instalación y Mantenimiento Preventivo de Minisplit" <?php echo ($row['curso'] ?? '') == 'Instalación y Mantenimiento Preventivo de Minisplit' ? 'selected' : ''; ?>>Instalación y Mantenimiento Preventivo de Minisplit</option>
<option value="Control Eléctrico Industrial" <?php echo ($row['curso'] ?? '') == 'Control Eléctrico Industrial' ? 'selected' : ''; ?>>Control Eléctrico Industrial</option>
<option value="Sublimación" <?php echo ($row['curso'] ?? '') == 'Sublimación' ? 'selected' : ''; ?>>Sublimación</option>
<option value="Programacion de CNC" <?php echo ($row['curso'] ?? '') == 'Programacion de CNC' ? 'selected' : ''; ?>>Programacion de CNC</option>
<option value="Excel" <?php echo ($row['curso'] ?? '') == 'Excel' ? 'selected' : ''; ?>>Excel</option>
<option value="Programacion y grabado laser CNC" <?php echo ($row['curso'] ?? '') == 'Programacion y grabado laser cnc' ? 'selected' : ''; ?>>Programacion y grabado laser CNC</option>
<option value="Instalaciones Eléctricas Residenciales" <?php echo ($row['curso'] ?? '') == 'Instalaciones Eléctricas Residenciales' ? 'selected' : ''; ?>>Instalaciones Eléctricas Residenciales</option>
<option value="Marketing Digital con IA" <?php echo ($row['curso'] ?? '') == 'Marketing Digital con IA' ? 'selected' : ''; ?>>Marketing Digital con IA</option>
<option value="Respuesta Inmediata contra Incendios" <?php echo ($row['curso'] ?? '') == 'Respuesta Inmediata contra Incendios' ? 'selected' : ''; ?>>Respuesta Inmediata contra Incendios</option>
<option value="Programacion PLC" <?php echo ($row['curso'] ?? '') == 'Programacion PLC' ? 'selected' : ''; ?>>Programacion PLC</option>
<option value="Montacargas" <?php echo ($row['curso'] ?? '') == 'Montacargas' ? 'selected' : ''; ?>>Montacargas</option>
<option value="Condiciones de Seguridad" <?php echo ($row['curso'] ?? '') == 'Condiciones de Seguridad' ? 'selected' : ''; ?>>Condiciones de Seguridad</option>
 </div>
</span></p>
</div>
</td>


<!--Inicio del curso-->
<td>
<div class="widget-26-job-info">
<p class="type m-0">Inicio del curso</p>
<p class="text-muted m-0">
<div class="col-lg-3 col-md-3 col-sm-12 p-0" style="display:inline-block;">
<input type="date" name="fecha_inicio" class="form-control fecha-inicio"
value="<?php echo $row['fecha_inicio'] ?? ''; ?>" style="width: 130px; background-color: #fff; border: 1px solid #ccc; 
color: #333; font-size: 0.9rem; padding:5px 10px; border-radius:5px;" disabled>
</div>
</p>
</div>
</td>


<!--Finalizacion del curso-->
 <td>
<div class="widget-26-job-info">
<p class="type m-0">Finalizacion del curso</p>
<p class="text-muted m-0">
<div class="col-lg-3 col-md-3 col-sm-12 p-0" style="display:inline-block;">
<input type="date" name="fecha_finalizacion" class="form-control fecha-fin" 
value="<?php echo $row['fecha_finalizacion'] ?? ''; ?>"style="width: 130px; background-color: #fff; border: 1px solid #ccc; 
color: #333; font-size: 0.9rem; padding:5px 10px; border-radius:5px;" disabled>
</div>
</p>
</div>
</td>

<!--Estado del curso-->
<td>
<div class="widget-26-job-info">
<p class="type m-0">Estado</p>
<p class="text-muted m-0">En
<div class="col-lg-3 col-md-3 col-sm-12 p-0" style="display:inline-block; ">
<select class="form-control estado-select" name="estado" style="width: 130px; 
background-color: #fff; border: 1px solid #ccc; color: #333; font-size: 0.9rem; padding:5px 10px; border-radius:5px;"  disabled>
<option value="Pendiente" <?php echo ($row['estado'] ?? '') == 'Pendiente' ? 'selected' : ''; ?>>Pendiente</option>
<option value="En Progreso" <?php echo ($row['estado'] ?? '') == 'En Progreso' ? 'selected' : ''; ?>>En Progreso</option>
<option value="Completado" <?php echo ($row['estado'] ?? '') == 'Completado' ? 'selected' : ''; ?>>Completado</option>
</select>   
</div>
</p>
</div> 
</td> 


<!--Certificado-->
<td>
<div class="widget-26-job-info">
<p class="type m-0">Certificado</p>
<p class="text-muted m-0">
<div class="col-lg-12 col-md-12 col-sm-12 p-0" style="display:inline-block;">
<input type="file"  id="certificadoInput_<?php echo $row['id']; ?>" class="certificado-input" accept=".pdf"  style="width: 220px; background-color: #fff; border: 1px solid #ccc; 
color: #333; font-size: 0.9rem; padding:5px 10px; border-radius:5px;" disabled>
<!--Contenedor para mostrar el nombre del PDF -->
<div id="pdfPreview_<?php echo $row['id']; ?>" class="pdf-preview"
style="margin-top:2px; text-align: center; text-decoration:none; cursor:pointer; color:blue !important;">
<?php 
// Mostrar el nombre del PDF y el estado NO es Completado      text-decoration:underline; font-weight: bold;
if (!empty($row['certificado']) && $row['estado'] !== 'Completado') {
 echo '<span style="">' . htmlspecialchars($row['certificado']) . '</span>';

}
// Mostrar mensaje cuando el estado es Completado y hay certificado
elseif (!empty($row['certificado']) && $row['estado'] === 'Completado') {
    echo '<p style="color: red; cursor:default; font-size:13px;">*Certificado subido con éxito*</p>';
}
?>
</div>
</div>
</p>
</div>
</td>

<!--BOTONES-->

<td>
  <div class="sectmod">
    <button class="btn-modificar" data-user-id="<?php echo $row['id']; ?>">
     <span>Modificar</span>
    </button>
<div class="boton-guardar" style="display: none;">
<button class="btn-cambiar" data-user-id="<?php echo $row['id']; ?>" >
     <span>Guardar</span>
    </button>
   <br><br>
    <button class="btn-cancelar" data-user-id="<?php echo $row['id']; ?>">
     <span>Cancelar</span>
    </button>
    </div>
  </div>
</td>
</tr>
                                                  <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

 <!--Nav de numeracion de paginas-->
<nav class="d-flex justify-content-center" style="margin-top: 0.1rem; margin-bottom: 2rem;">
    <ul class="pagination pagination-base pagination-boxed pagination-square mb-0">
        <?php if ($pagina_actual > 1): ?>
            <li class="page-item">
                <a class="page-link text-dark border-warning" href="?pagina=<?php echo $pagina_actual - 1; ?>">
                    <span aria-hidden="true">«</span>
                    <span class="sr-only">Anterior</span>
                </a>
            </li>
        <?php endif; ?>
        
        <?php for ($i = 1; $i <= $total_paginas; $i++): ?>
            <li class="page-item <?php echo $i == $pagina_actual ? 'active' : ''; ?>">
                <a class="page-link <?php echo $i == $pagina_actual ? 'bg-warning text-white no-border' : 'no-border bg-warning text-white'; ?>" 
                   href="?pagina=<?php echo $i; ?>">
                    <?php echo $i; ?>
                </a>
            </li>
        <?php endfor; ?>
        
        <?php if ($pagina_actual < $total_paginas): ?>
            <li class="page-item">
                <a class="page-link text-dark border-warning" href="?pagina=<?php echo $pagina_actual + 1; ?>">
                    <span aria-hidden="true">»</span>
                    <span class="sr-only">Siguiente</span>
                </a>
            </li>
        <?php endif; ?>
    </ul>
</nav>




                </div>
            </div>
        </div>
    </div>
</div>






<!-- Modal flotante -->
<div id="pdfModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; 
 background: rgba(0,0,0,0.6); justify-content:center; align-items:center; z-index: 9999;">
  <span id="closeModal" style="position:absolute; top:20px; right:20px; cursor:pointer; font-size:30px; color: white; z-index: 10001; background: rgba(0,0,0,0.5); border-radius: 50%; width: 40px; height: 40px; text-align: center; line-height: 40px;">&times;</span>
  <div style="background:#fff; padding:5px; border-radius:5px; width:90%; height:90%; position:relative; z-index: 10000;">
    <embed id="pdfFrame" src="" type="application/pdf" style="width:100%; height:100%; border:none;">
  </div>
</div>




<!-- Modal Historial -->
<div id="historialModal" class="modal fade">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Historial de cursos</h5>
                <button type="button" class="btn-close btn-close position-absolute end-0 me-3" 
                data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="contenidoHistorial">
                <!-- Contenido cargado desde historial.php -->
            </div>
        </div>
    </div>
</div>
<!-- Modal Campos Requeridos -->
<div class="modal fade" id="modalCamposRequeridos" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content"
         style="border-radius: 12px; animation: fadeIn 0.25s ease; border: 2px solid #FF7D29;">

      <div class="modal-header text-white justify-content-center"
           style="background: linear-gradient(135deg, #FF7D29, #F3C623);">
        <h5 class="modal-title w-100 text-center" style="font-weight: bold;">
          Advertencia
        </h5>

        <button type="button" class="close text-white" data-bs-dismiss="modal" aria-label="Cerrar" style="position: absolute; right: 12px;">
            <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body d-flex align-items-center justify-center gap-3">
        <i class="bi bi-exclamation-circle-fill" style="font-size: 3rem; color:#FF7D29;"></i>

        <div class="text-center">
          <p class="mb-0 fs-5 fw-semibold">Por favor complete todos los campos requeridos.</p>
        </div>
      </div>

      <div class="modal-footer" style="display: flex; justify-content: center; border-top: none;">
        <button data-bs-dismiss="modal"
          style="background: #ccc; color: #333; border: none; border-radius: 8px; padding: 8px 18px; cursor: pointer; font-weight: bold; transition: background 0.2s ease;">
          Aceptar
        </button>
      </div>

    </div>
  </div>
</div>
<!-- Modal Fecha Final Incorrecta -->
<div class="modal fade" id="modalFechaIncorrecta" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content"
         style="border-radius: 12px; animation: fadeIn 0.25s ease; border: 2px solid #FF7D29;">

      <div class="modal-header text-white justify-content-center"
           style="background: linear-gradient(135deg, #FF7D29, #F3C623);">
        <h5 class="modal-title w-100 text-center" style="font-weight: bold;">
          Advertencia
        </h5>

        <button type="button" class="close text-white" data-bs-dismiss="modal" aria-label="Cerrar" style="position: absolute; right: 12px;">
            <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body d-flex align-items-center justify-center gap-3">
        <i class="bi bi-exclamation-circle-fill" style="font-size: 3rem; color:#FF7D29;"></i>

        <div class="text-center">
          <p class="mb-0 fs-5 fw-semibold">La fecha de finalización no puede ser anterior a la fecha de inicio.</p>
        </div>
      </div>

      <div class="modal-footer" style="display: flex; justify-content: center; border-top: none;">
        <button data-bs-dismiss="modal"
          style="background: #ccc; color: #333; border: none; border-radius: 8px; padding: 8px 18px; cursor: pointer; font-weight: bold; transition: background 0.2s ease;">
          Aceptar
        </button>
      </div>

    </div>
  </div>
</div>

<!-- Modal Éxito Guardado -->
<div class="modal fade" id="modalDatosGuardados" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered modal-sm modal-md modal-lg">
    <div class="modal-content border-success" 
         style="border-radius: 14px; animation: fadeIn .25s ease;">

      <!-- ENCABEZADO -->
      <div class="modal-header d-flex justify-content-center position-relative"
           style="background: linear-gradient(135deg, #28a745 0%, #1f7a36 100%);">

        <h5 class="modal-title text-white">Éxito</h5>

        <button type="button" class="close text-white" data-bs-dismiss="modal" aria-label="Cerrar" style="position: absolute; right: 12px;">
            <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <!-- CUERPO -->
      <div class="modal-body d-flex flex-column flex-md-row align-items-center gap-3 
                  text-center text-md-start justify-content-start">

        <i class="bi bi-check-circle-fill" style="font-size: 3.2rem; color:#28a745;"></i>

        <div>
          <p id="mensajeDatosGuardados" class="mb-0 fs-5">Datos guardados correctamente.</p>
        </div>

      </div>

    </div>
  </div>
</div>

<!-- Modal Error Guardado -->
<div class="modal fade" id="modalErrorGuardado" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered modal-sm modal-md modal-lg">
    <div class="modal-content border-danger" style="border-radius: 14px; animation: fadeIn .25s ease;">

      <!-- HEADER -->
      <div class="modal-header text-white justify-content-center position-relative"
           style="background: linear-gradient(135deg, #dc3545 0%, #b81f2e 100%);">
        <h5 class="modal-title">Error</h5>

        <!-- X personalizada -->
        <button type="button" class="close text-white" data-bs-dismiss="modal" aria-label="Cerrar" style="position: absolute; right: 12px;">
            <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <!-- BODY -->
      <div class="modal-body d-flex flex-column flex-md-row align-items-center gap-3 text-center text-md-start">
        <i class="bi bi-x-circle-fill" style="font-size: 3.2rem; color:#dc3545;"></i>

        <div>
          <p id="mensajeErrorGuardado" class="mb-0 fs-5">
            Error al guardar los datos.
          </p>
        </div>
      </div>

    </div>
  </div>
</div>

<style>
@keyframes fadeIn {
  from { opacity: 0; transform: scale(0.95); }
  to { opacity: 1; transform: scale(1); }
}
</style>

    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
    <script>AOS.init();</script>
    <script src="../js/panelcursos.js?v=1.4"></script>
</body>

</html>
