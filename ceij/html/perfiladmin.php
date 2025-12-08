<?php
ob_start();
include 'db.php';
include 'seguridad.php';
$id = $_SESSION['usuario_id'];
$sql = "SELECT * FROM usuarios WHERE id = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$resultado = $stmt->get_result();
$usuario = $resultado->fetch_assoc();
if (!$usuario) { // Verificar si el usuario esta eliminado
    session_destroy(); // Cerrar sesión
    echo "<script>
        alert('Usuario no encontrado. Serás redirigido al inicio.');
        window.location.href='index.php';
    </script>";
    exit;
} 
// VERIFICACIÓN DEL ROL - SOLO USUARIOS CON ROL 'usuario' PUEDEN ACCEDER
if ($usuario['rol'] != 'administrador') {
    header("Location: perfil.php"); // O redirigir a su perfil de admin
    exit;
}
ob_end_flush(); 
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Perfil</title>

  <!-- Bootstrap + íconos -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css"/>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

  <!-- Estilos personalizados -->
  <link rel="stylesheet" href="../css/perfiladmin.css?v=2.7">
  <link rel="icon" href="../icon/ceijicon.ico" />

  <!-- Fuente Google -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@600;100&display=swap" rel="stylesheet">

  <!-- SEO Meta Tags -->
  <meta name="description" content="CEIJ ofrece cursos certificados por SEP y STPS para que avances profesionalmente.">
  <meta name="author" content="CEIJ Juárez">
  <meta property="og:title" content="CEIJ | Centro de Entrenamiento Industrial Juárez">
  <meta property="og:description" content="Transforma tu futuro con nuestros cursos certificados.">
  <meta property="og:image" content="https://ceij.site/img/ImagenEmpresa.jpg"> <!-- Cámbialo -->
  <meta property="og:url" content="https://ceij.site/">
  <meta name="twitter:title" content="CEIJ Juárez – Cursos Certificados">
  <meta name="twitter:description" content="Cursos con certificación SEP/STPS, aulas industriales equipadas y acompañamiento personalizado en CEIJ Juárez.">
  <meta name="twitter:image" content="https://ceij.site/img/ImagenEmpresa.jpg">
</head>

<body>
<!-- CONTENEDOR PRINCIPAL -->
<div class="perfil-container">
    <!-- BANNER AZUL -->
    <section class="banner">  

    <!--BOTON DE REGRESAR ATRAS EN EL INDEX -->
    <button id="bt-regresar" class="boton-flecha" onclick="window.location.href='index.php?t=' + new Date().getTime()">
        <i class="fa-solid fa-arrow-left"></i>
        <i class="fa-solid fa-house" style="display: none;"></i> <span class="texto-inicio">Ir al inicio</span> 
    </button> 
<!--BOTON DE CERRAR SESION-->
<button id="bt-cerrar" class="boton-cerrar" style="background-color: red;"  onclick="cerrarSesion()">
    <i class="fa-solid fa-right-from-bracket" style="color: white;"></i>
    <span class="texto-cerrar">Cerrar sesión</span>
</button>

<div class="foto-usuario">
            <i class="fa-solid fa-user"></i>
        </div>
        <div class="info-usuario">
            <h2><?php echo $usuario['nombre'] . " " . $usuario['apellido']; ?></h2>
            <p>Administrador</p> <!--Administrador como p solamente <p>?php echo $usuario['rol']; ?></p>-->
        </div>


    </section>

    <!-- CONTENIDO BLANCO -->
    <section class="contenido">
        <div class="tabs">
            <button class="tab-btn activo" data-tab="perfil">Perfil</button>
        </div>

        <!-- SECCION PERFIL--->
<div class="tab-content activo" id="perfil">
            <h3>Información del usuario</h3>
            <div class="info-grid" id="vista-info">
                <p><strong>Nombre(s):</strong> <span id="nombre-vista"><?php echo $usuario['nombre']; ?></span></p>
                <p><strong>Apellido(s):</strong><span id="apellido-vista"> <?php echo $usuario['apellido']; ?></span></p>
                <p><strong>Correo electrónico:</strong><span id="email-vista"> <?php echo $usuario['email']; ?></span></p>
                <p><strong>Teléfono:</strong> <span id="telefono-vista">
                <?php echo trim(preg_replace('/(\d{3})(\d{3})(\d{4})/', '$1 $2 $3', $usuario['telefono'])); ?></span></p>
            </div>
      

<!-- SECCION DE MODIFICAR DATOS DE PERFIL -->
<div class="info-edit" id="editar-info" style="display: none;">
    <form id="form-editar">
        <p><strong>Nombre(s):</strong> 
            <input type="text" id="nombre-editar" name="nombre" value="<?php echo $usuario['nombre']; ?>" required>
            <small id="error-nombre" style="color:red; display:none;">El nombre solo puede contener letras</small>
        </p>
        <p><strong>Apellido(s):</strong> 
            <input type="text" id="apellido-editar" name="apellido" value="<?php echo $usuario['apellido']; ?>" required>
            <small id="error-apellido" style="color:red; display:none;">El apellido solo puede contener letras</small>
        </p>
        <p><strong>Correo electrónico:</strong> 
            <input type="email" id="email-editar" name="email" value="<?php echo $usuario['email']; ?>" required>
            <small id="error-email" style="color:red; display:none;">El correo debe incluir @</small>
        </p>
        <p><strong>Teléfono:</strong> 
            <input type="text" id="telefono-editar" name="telefono" value="<?php echo $usuario['telefono']; ?>" maxlength="10" required>
            <small id="error-telefono" style="color:red; display:none;">El teléfono debe tener 10 dígitos</small>
        </p>
    </form>
</div>

   
   <!--BOTONES DE MODIFICAR, GUARDAR Y CANCELAR-->
    <div class="sectmod">
    <button class="btn-modificar" id="btn-modificar">
     <span>Modificar</span>
    </button>
<div class="boton-guardar" style="display: none;" id="div-guardar">
<button class="btn-cambiar" id="btn-guardar">
     <span>Guardar</span>
    </button>
    <button class="btn-cancelar" id="btn-cancelar">
     <span>Cancelar</span>
    </button>
    </div>
  </div>
</div>


        
    


        


        <!-- SECCION HISTORIAL -->
        
        </div>
    </section>
</div>
<!-- Modal Confirmar Modificación -->
<div class="modal fade" id="modalEditar" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content" 
         style="border-radius: 12px; animation: fadeIn 0.25s ease; border: 2px solid #FF7D29;">

      <div class="modal-header text-white justify-content-center"
           style="background: linear-gradient(135deg, #FF7D29, #F3C623);">

        <h5 class="modal-title w-100 text-center" style="font-weight: bold;">
          Confirmar acción
        </h5>

        <button type="button" class="btn-close btn-close-white position-absolute end-0 me-3" 
                data-bs-dismiss="modal"></button>

      </div>

      <div class="modal-body d-flex align-items-center gap-3">
        <i class="bi bi-exclamation-triangle-fill" 
            style="font-size: 3rem; color:#FF7D29;"></i>

        <div class="d-flex flex-column justify-content-center text-center">
            <p class="mb-0 fs-5 fw-semibold">¿Estás seguro de modificar tus datos personales?</p>
            <small class="text-muted">Se actualizarán los datos en tu perfil.</small>
        </div>
      </div>

      <div class="modal-footer" 
           style="display: flex; justify-content: center; gap: 10px; border-top: none;">

        <button id="btnConfirmarModificar"
          style="
            background: linear-gradient(135deg, #FF7D29, #F3C623);
            color: white;
            border: none;
            border-radius: 8px;
            padding: 8px 15px;
            cursor: pointer;
            font-weight: bold;
            transition: transform 0.2s ease, opacity 0.2s ease;
          ">Modificar</button>

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
          ">Cancelar</button>

      </div>

    </div>
  </div>
</div>
<!-- Modal Éxito: Datos actualizados correctamente -->
<div class="modal fade" id="modalExitoActualizar" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content" 
         style="border-radius: 12px; animation: fadeIn 0.25s ease; border: 2px solid #28a745;">

      <!-- HEADER con degradado -->
      <div class="modal-header text-white justify-content-center"
           style="
             background: linear-gradient(135deg, #28a745, #6fdc6f);
             border-top-left-radius: 12px;
             border-top-right-radius: 12px;
           ">
        <h5 class="modal-title w-100 text-center" style="font-weight: bold;">
          ¡Éxito!
        </h5>

        <!-- botón de cerrar -->
        <button type="button" class="btn-close btn-close-white position-absolute end-0 me-3" 
                data-bs-dismiss="modal"></button>
      </div>

      <!-- CUERPO -->
      <div class="modal-body d-flex align-items-center justify-content-center gap-3">
        <i class="bi bi-check-circle-fill" 
           style="font-size: 3rem; color:#28a745;"></i>

        <div class="d-flex flex-column justify-content-center text-center">
            <p class="mb-0 fs-5 fw-semibold">Datos actualizados correctamente.</p>
        </div>
      </div>

      <!-- FOOTER con botón bonito -->
      <div class="modal-footer" 
           style="display: flex; justify-content: center; border-top: none;">

        <button data-bs-dismiss="modal"
          style="
            background: linear-gradient(135deg, #28a745, #6fdc6f);
            color: white;
            border: none;
            border-radius: 8px;
            padding: 8px 18px;
            cursor: pointer;
            font-weight: bold;
            box-shadow: 0 3px 8px rgba(0,0,0,0.2);
            transition: transform 0.2s ease, opacity 0.2s ease;
          "
          onmouseover="this.style.transform='scale(1.05)'"
          onmouseout="this.style.transform='scale(1)'"
        >
          Aceptar
        </button>

      </div>

    </div>
  </div>
</div>
<!-- Modal Error: Acción fallida -->
<div class="modal fade" id="modalErrorSubir" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content"
         style="border-radius: 12px; animation: fadeIn 0.25s ease; border: 2px solid #dc3545;">

      <!-- HEADER rojo con degradado -->
      <div class="modal-header text-white justify-content-center"
           style="
             background: linear-gradient(135deg, #dc3545, #ff6b6b);
             border-top-left-radius: 12px;
             border-top-right-radius: 12px;
           ">

        <h5 class="modal-title w-100 text-center" style="font-weight: bold;">
          Error
        </h5>

        <button type="button" class="btn-close btn-close-white position-absolute end-0 me-3"
                data-bs-dismiss="modal"></button>
      </div>

      <!-- CUERPO -->
      <div class="modal-body d-flex align-items-center gap-3 justify-content-center">
        <i class="bi bi-x-circle-fill" style="font-size: 3.2rem; color:#dc3545;"></i>

        <div class="text-center">
          <p class="mb-0 fs-5 fw-semibold">Ha ocurrido un error.</p>
          <small class="text-muted">Por favor, inténtalo nuevamente.</small>
        </div>
      </div>

      <!-- BOTÓN -->
      <div class="modal-footer" 
           style="display: flex; justify-content: center; border-top: none;">
        
        <button data-bs-dismiss="modal"
          style="
            background: linear-gradient(135deg, #dc3545, #ff6b6b);
            color: white;
            border: none;
            border-radius: 8px;
            padding: 8px 18px;
            cursor: pointer;
            font-weight: bold;
            box-shadow: 0 3px 8px rgba(0,0,0,0.2);
            transition: transform 0.2s ease, opacity 0.2s ease;
          "
          onmouseover="this.style.transform='scale(1.05)'"
          onmouseout="this.style.transform='scale(1)'"
        >
          Entendido
        </button>

      </div>

    </div>
  </div>
</div>

<!-- Modal Confirmar Cerrar Sesión -->
<div class="modal fade" id="modalLogout" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content" 
         style="border-radius: 12px; animation: fadeIn 0.25s ease; border: 2px solid #FF7D29;">

      <div class="modal-header text-white justify-content-center"
           style="background: linear-gradient(135deg, #FF7D29, #F3C623);">

        <h5 class="modal-title w-100 text-center" style="font-weight: bold;">
          Confirmar acción
        </h5>

        <!-- Botón de cerrar -->
        <button type="button" class="btn-close btn-close-white position-absolute end-0 me-3" 
                data-bs-dismiss="modal"></button>

      </div>

      <div class="modal-body d-flex align-items-center justify-content-center gap-3">
        <i class="bi bi-exclamation-triangle-fill" 
            style="font-size: 3rem; color:#FF7D29;"></i>

        <div class="d-flex flex-column justify-content-center text-center">
            <p class="mb-0 fs-5 fw-semibold">¿Estás seguro de cerrar sesión?</p>
            <small class="text-muted">Perderás el acceso hasta volver a iniciar sesión.</small>
        </div>
      </div>

      <div class="modal-footer" 
           style="display: flex; justify-content: center; gap: 10px; border-top: none;">

        <button onclick="window.location.href='logout.php'" 
          style="
            background: linear-gradient(135deg, #FF7D29, #F3C623);
            color: white;
            border: none;
            border-radius: 8px;
            padding: 8px 15px;
            cursor: pointer;
            font-weight: bold;
            transition: transform 0.2s ease, opacity 0.2s ease;
          ">Cerrar sesión</button>

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
          ">Cancelar</button>

      </div>

    </div>
  </div>
</div>

<style>
  @keyframes fadeIn {
    from { opacity: 0; transform: scale(0.9); }
    to   { opacity: 1; transform: scale(1); }
  }
</style>
  <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
  <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script> AOS.init(); </script>
  <script src="../js/perfiladmin.js?v=1.8"></script>
</body>
</html>