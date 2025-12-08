<?php
require 'db.php'; // Ajusta la ruta según tu estructura
require 'seguridad.php'; // Asegúrate de incluir seguridad.php para manejar sesiones
// Consultar el rol ACTUAL en la base de datos
$usuario_id = $_SESSION['usuario_id'];
$sql_verificar = "SELECT rol FROM usuarios WHERE id = ?";
$stmt = $conexion->prepare($sql_verificar);
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    // Usuario no existe en la BD
    session_destroy();
    header("Location: login.html");
    exit;
}

$usuario_actual = $result->fetch_assoc();

// Verificar si el rol en la BD es administrador
if ($usuario_actual['rol'] !== 'administrador') {
    // Actualizar la sesión con el rol correcto
    $_SESSION['rol'] = $usuario_actual['rol'];
    
    // Redirigir según el rol actual
    if ($usuario_actual['rol'] === 'usuario') {
        header("Location: perfil.php");
    } else {
        header("Location: login.html");
    }
    exit;
}

// Actualizar la sesión con el rol confirmado
$_SESSION['rol'] = 'administrador';



// Obtener TODOS los usuarios registrados
$sql = "SELECT u.id, u.nombre, u.apellido, u.email, u.telefono, u.rol
        FROM usuarios u
        LEFT JOIN (
            SELECT pc1.*
            FROM progreso_cursos pc1
            WHERE pc1.id = (
                SELECT MAX(pc2.id) 
                FROM progreso_cursos pc2 
                WHERE pc2.usuario_id = pc1.usuario_id
            )
        ) pc ON u.id = pc.usuario_id";

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
        'telefono' => '000-0000'
         ];
}


// Configuración de paginación
$resultados_por_pagina = 4;
$pagina_actual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$offset = ($pagina_actual - 1) * $resultados_por_pagina;



// Procesar búsqueda si existe
$search_term = "";
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $search_term = $conexion->real_escape_string($_GET['search']);
}

// Modificar la consulta SQL para incluir la búsqueda
$sql_where = "";
if (!empty($search_term)) {
    $sql_where = " WHERE (u.nombre LIKE '%$search_term%' OR u.apellido LIKE '%$search_term%' OR u.email LIKE '%$search_term%')";
}

// Actualizar la consulta de total de usuarios
$sql_total = "SELECT COUNT(*) as total FROM usuarios u" . $sql_where;

// Actualizar la consulta principal con paginación
$sql = "SELECT u.id, u.nombre, u.apellido, u.email, u.telefono, u.rol
        FROM usuarios u
        LEFT JOIN (
            SELECT pc1.*
            FROM progreso_cursos pc1
            WHERE pc1.id = (
                SELECT MAX(pc2.id) 
                FROM progreso_cursos pc2 
                WHERE pc2.usuario_id = pc1.usuario_id
            )
        ) pc ON u.id = pc.usuario_id" 
        . $sql_where . 
        " LIMIT $offset, $resultados_por_pagina";




// Obtener el total de usuarios (CON búsqueda)
$sql_total = "SELECT COUNT(*) as total FROM usuarios u" . $sql_where;
$result_total = $conexion->query($sql_total);
$total_usuarios = $result_total->fetch_assoc()['total'];
$total_paginas = ceil($total_usuarios / $resultados_por_pagina);

// Obtener cantidad de administradores (CON búsqueda)
if (!empty($search_term)) {
    $sql_admin = "SELECT COUNT(*) as total_admin FROM usuarios u" . $sql_where . " AND rol = 'administrador'";
} else {
    $sql_admin = "SELECT COUNT(*) as total_admin FROM usuarios u WHERE rol = 'administrador'";
}
$result_admin = $conexion->query($sql_admin);
$total_administradores = $result_admin->fetch_assoc()['total_admin'];

// Obtener usuarios con paginación Y búsqueda
$sql = "SELECT u.id, u.nombre, u.apellido, u.email, u.telefono, u.rol
        FROM usuarios u
        LEFT JOIN (
            SELECT pc1.*
            FROM progreso_cursos pc1
            WHERE pc1.id = (
                SELECT MAX(pc2.id) 
                FROM progreso_cursos pc2 
                WHERE pc2.usuario_id = pc1.usuario_id
            )
        ) pc ON u.id = pc.usuario_id" 
        . $sql_where . 
        " LIMIT $offset, $resultados_por_pagina";

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
    <title>Administración de roles</title>
    <!-- Bootstrap + íconos -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    <!-- Estilos personalizados -->
    <link rel="stylesheet" href="../css/paneladmins.css?v=2.8" />
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="icon" href="../icon/ceijicon.ico" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

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
                <form id="search-form" method="GET">
                    <div class="row">
                        <div class="col-12">
                            <div class="row no-gutters">
        <div class="col-lg-9 col-md-7 col-sm-10 p-0">
        <input type="text" placeholder="Buscar..." class="form-control" id="search" name="search" 
        value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
        </div>
                                <div class="col-lg-1 col-md-1 col-sm-2 p-0">
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
                                            <div class="records">Total de usuarios registrados:
                                          <b><?php echo($total_usuarios); ?></b>
                                          <div class="records">
                                             Administradores: <b><?php echo($total_administradores); ?></b>
                                         </div>
                                        </div>
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
</td>

<!--Rol-->
 <td>
<div class="widget-26-job-info">
<p class="type m-0">Rol</p>
<p class="text-muted m-0">De 
    <span class="location">                                                       
  <div class="col-lg-3 col-md-3 col-sm-12 p-0">
 <select class="form-control rol-select" name="rol"
  style="background-color: #fff; border: 1px solid #ccc; color: #333; font-size: 0.9rem; padding: 5px; width:128px;" disabled>
<option value="administrador" <?php echo ($row['rol'] ?? '') == 'administrador' ? 'selected' : ''; ?>>Administrador</option>
<option value="usuario" <?php echo ($row['rol'] ?? '') == 'usuario' ? 'selected' : ''; ?>>Usuario</option>
</select>
 </div> 
</span></p>
</div>
 </td>

<!--BOTONES  <button class="btn-eliminar"style="margin-top: 3px !important;" -->
<td>
  <div class="sectmod" style=" margin-top: -50px;">

  <div class="botonesmod-eli" style="margin-top: 50px;" >
    <button class="btn-modificar" data-user-id="<?php echo $row['id']; ?>">
     <span>Modificar</span>
    </button>
    <br>
     <button class="btn-eliminar"style="margin-top: 5px !important;" data-user-id="<?php echo $row['id']; ?>">
     <span>Eliminar cuenta</span>
    </button>
</div>

<div class="boton-guardar" style="display: none; margin-top: -30px !important;"" >
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
<!-- Modal para eliminar cuenta -->
<div class="modal fade" id="modalEliminar" tabindex="-1" role="dialog" aria-labelledby="modalEliminarLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
    <div class="modal-content">
      <div class="modal-header modal-header-orange">
        <h5 class="modal-title w-100 text-center text-white" id="modalEliminarLabel">
          Enviar correo de eliminación
        </h5>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">
        <form id="formEliminar" enctype="multipart/form-data">
          <div class="form-group">
            <label for="correoDestino">Correo destinatario:</label>
            <input type="email" class="form-control" id="correoDestino" name="correoDestino" readonly>
          </div>

          <div class="form-group">
            <label for="asunto">Asunto:</label>
            <input type="text" class="form-control" id="asunto" name="asunto" value="Eliminación de cuenta" readonly required>
          </div>

          <div class="form-group">
            <label for="comentario">Comentario: <span style="color:red;">*</span></label>
            <textarea class="form-control" id="comentario" name="comentario" rows="4" required></textarea>
            <small class="form-text text-muted">Campo obligatorio</small>
          </div>

          <div class="form-group">
            <label for="imagen">Imagen adjunta</label>
            <input type="file" class="form-control-file" id="imagen" name="imagen" accept="image/*">
            <div id="vistaPrevia" class="mt-2"></div>
          </div>
        </form>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-primary" id="btnEnviarCorreo" style="background-color:orange; border-color:orange;">
          Enviar correo
        </button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal Éxito Rol Actualizado -->
<div class="modal fade" id="modalRolActualizado" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered modal-sm modal-md modal-lg">
    <div class="modal-content border-success" 
         style="border-radius: 14px; animation: fadeIn .25s ease;">

      <!-- ENCABEZADO -->
      <div class="modal-header d-flex justify-content-center position-relative"
           style="background: linear-gradient(135deg, #28a745 0%, #1f7a36 100%);">

        <h5 class="modal-title text-white">Éxito</h5>

        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Cerrar" style="position: absolute; right: 12px;">
            <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <!-- CUERPO -->
      <div class="modal-body d-flex flex-column flex-md-row align-items-center gap-3 
                  text-center text-md-start justify-content-start">

        <i class="bi bi-check-circle-fill" style="font-size: 3.2rem; color:#28a745;"></i>

        <div>
          <p id="mensajeRolActualizado" class="mb-0 fs-5">Rol actualizado correctamente.</p>
        </div>

      </div>

    </div>
  </div>
</div>

<!-- Modal Éxito Envío Correo -->
<div class="modal fade" id="modalCorreoExito" tabindex="-1">
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
          <p id="mensajeCorreoExito" class="mb-0 fs-5">Correo enviado correctamente.</p>
        </div>

      </div>

    </div>
  </div>
</div>

<!-- Modal Advertencia Comentario -->
<div class="modal fade" id="modalComentarioVacio" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content"
         style="border-radius: 12px; animation: fadeIn 0.25s ease; border: 2px solid #FF7D29;">

      <div class="modal-header text-white justify-content-center"
           style="background: linear-gradient(135deg, #FF7D29, #F3C623);">

        <h5 class="modal-title w-100 text-center" style="font-weight: bold;">
          Advertencia
        </h5>

        <!-- botón cerrar -->
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Cerrar" style="position: absolute; right: 12px;">
            <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body d-flex align-items-center justify-center gap-3">
        <i class="bi bi-exclamation-circle-fill"
           style="font-size: 3rem; color:#FF7D29;"></i>

        <div>
          <p class="mb-0 fs-5 fw-semibold">Es obligatorio escribir el comentario.</p>
          <small class="text-muted">Por favor ingresa un comentario para continuar.</small>
        </div>
      </div>

      <div class="modal-footer"
           style="display: flex; justify-content: center; border-top: none;">

        <button data-bs-dismiss="modal"
          style="
            background: #ccc;
            color: #333;
            border: none;
            border-radius: 8px;
            padding: 8px 18px;
            cursor: pointer;
            font-weight: bold;
            transition: background 0.2s ease;
          ">Aceptar</button>

      </div>

    </div>
  </div>
</div>

<!-- Modal Error Rol -->
<div class="modal fade" id="modalErrorRol" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered modal-sm modal-md modal-lg">
    <div class="modal-content border-danger" style="border-radius: 14px; animation: fadeIn .25s ease;">

      <!-- HEADER -->
      <div class="modal-header text-white justify-content-center position-relative"
           style="background: linear-gradient(135deg, #dc3545 0%, #b81f2e 100%);">
        <h5 class="modal-title">Error</h5>

        <!-- X personalizada -->
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Cerrar" style="position: absolute; right: 12px;">
            <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <!-- BODY -->
      <div class="modal-body d-flex flex-column flex-md-row align-items-center gap-3 text-center text-md-start">

        <i class="bi bi-x-circle-fill" style="font-size: 3.2rem; color:#dc3545;"></i>

        <div>
          <p id="mensajeErrorRol" class="mb-0 fs-5">
            Error al actualizar el rol.
          </p>
        </div>

      </div>

    </div>
  </div>
</div>

<!-- Modal Error Correo -->
<div class="modal fade" id="modalErrorCorreo" tabindex="-1">
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
          <p id="mensajeErrorCorreo" class="mb-0 fs-5">
            Error al procesar la acción.
          </p>
        </div>
      </div>

    </div>
  </div>
</div>

<!-- Modal Error Conexión -->
<div class="modal fade" id="modalErrorConexion" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered modal-sm modal-md modal-lg">
    <div class="modal-content border-danger" style="border-radius: 14px; animation: fadeIn .25s ease;">

      <div class="modal-header text-white justify-content-center position-relative"
           style="background: linear-gradient(135deg, #dc3545 0%, #b81f2e 100%);">
        <h5 class="modal-title">Error de conexión</h5>

        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Cerrar" style="position: absolute; right: 12px;">
            <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body d-flex flex-column flex-md-row align-items-center gap-3 text-center text-md-start">

        <i class="bi bi-wifi-off" style="font-size: 3.2rem; color:#dc3545;"></i>

        <div>
          <p class="mb-0 fs-5">Error de conexión con el servidor.</p>
          <small class="text-muted">Verifica tu red e inténtalo nuevamente.</small>
        </div>

      </div>

    </div>
  </div>
</div>

<!-- Modal de confirmación inicial -->
<div class="modal fade" id="modalConfirmacion" tabindex="-1" role="dialog" aria-labelledby="modalConfirmacionLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content" style="
        border-radius: 16px;
        overflow: hidden;
        font-family: 'Poppins', sans-serif;
        animation: fadeIn 0.3s ease-out;
        box-shadow: 0 4px 15px rgba(0,0,0,0.3);
      ">
      
      <!-- HEADER -->
      <div class="modal-header" style="
          background: linear-gradient(135deg, #FF7D29, #F3C623);
          color: white;
          text-align: center;
          position: relative;
          display: flex;
          justify-content: center;
          align-items: center;
          padding: 15px;
      ">
        <h5 class="modal-title" id="modalConfirmacionLabel" style="margin: 0; font-weight: 600;">Confirmar acción</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="
            position: absolute;
            right: 15px;
            top: 10px;
            color: white;
            opacity: 1;
            font-size: 1.4rem;
            font-weight: bold;
            text-shadow: none;
        ">&times;</button>
      </div>

      <!-- CUERPO -->
      <div class="modal-body" style="text-align:center; padding: 0px 20px; color:#333;">
        <p style="font-size: 0.95rem;">
          ¿Estás seguro de que quieres realizar esta acción? Tome en cuenta que será definitiva.
        </p>
      </div>

      <!-- FOOTER -->
      <div class="modal-footer" style="
          display:flex;
          justify-content:center;
          gap:10px;
          border-top:none;
          padding-bottom:10px;
      ">
        <button type="button" class="btn btn-secondary" data-dismiss="modal" style="
            background:#ccc;
            color:#333;
            border:none;
            border-radius:8px;
            padding:8px 15px;
            font-weight:bold;
            transition: background 0.2s ease;
        ">Cancelar</button>

        <button type="button" class="btn" id="btnContinuar" style="
            background: linear-gradient(135deg, #FF7D29, #F3C623);
            color:white;
            border:none;
            border-radius:8px;
            padding:8px 15px;
            font-weight:bold;
            transition: transform 0.2s ease, opacity 0.2s ease;
        ">Continuar</button>
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


 <!--Nav de numeracion de paginas-->
<nav class="d-flex justify-content-center" style="margin-top: 0.1rem; margin-bottom: 2rem;">
    <ul class="pagination pagination-base pagination-boxed pagination-square mb-0">
        <?php if ($pagina_actual > 1): ?>
            <li class="page-item">
<a class="page-link text-dark border-warning" 
href="?pagina=<?php echo $pagina_actual - 1; ?>&search=<?php echo urlencode($search_term); ?>">
                    <span aria-hidden="true">«</span>
                    <span class="sr-only">Anterior</span>
                </a>
            </li>
        <?php endif; ?>
        
        <?php for ($i = 1; $i <= $total_paginas; $i++): ?>
            <li class="page-item <?php echo $i == $pagina_actual ? 'active' : ''; ?>">
<a class="page-link <?php echo $i == $pagina_actual ? 'bg-warning text-white no-border' : 'no-border bg-warning text-white'; ?>" 
href="?pagina=<?php echo $i; ?>&search=<?php echo urlencode($search_term); ?>">
                    <?php echo $i; ?>
</a>
            </li>
        <?php endfor; ?>
        
        <?php if ($pagina_actual < $total_paginas): ?>
            <li class="page-item">
<a class="page-link text-dark border-warning" 
href="?pagina=<?php echo $pagina_actual + 1; ?>&search=<?php echo urlencode($search_term); ?>">
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
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
    <script>AOS.init();</script>
    <script src="../js/paneladmins.js?v=1.5"></script>
</body>
</html>