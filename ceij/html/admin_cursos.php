<?php
  include 'db.php';
  include 'seguridad.php';
  function esc($str){ return htmlspecialchars($str,ENT_QUOTES); }
  $resultadoCursos = $conexion->query("SELECT * FROM cursos ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Administrar Cursos</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-..." crossorigin="anonymous" referrerpolicy="no-referrer" />
  <link rel="stylesheet" href="../css/admin_cursos.css?v=1.3"/>
  <link rel="icon" href="../icon/ceijicon.ico"/>
</head>
<body>
  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg" style="background-color: #e45e1b;">
    <div class="container-fluid">
      <!-- Botón offcanvas para móvil -->
      <button class="btn d-lg-none text-white" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileNavbar" aria-controls="mobileNavbar">
        <i class="bi bi-list" style="font-size: 1.5rem;"></i>
      </button>

      <!-- Logo -->
      <a class="navbar-brand ms-2" href="index.php">
        <img src="../img/sin fondo.png" alt="Logo" style="height:37px;">
      </a>

      <!-- Navbar desktop -->
      <div class="collapse navbar-collapse d-none d-lg-flex">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">

          <!-- Menú general -->
          <?php if($admin): ?>
            <li class="nav-item"><a class="nav-link text-white" href="admin_index.php">Inicio</a></li>
            <li class="nav-item"><a class="nav-link text-white" href="nosotros.php">Nosotros</a></li>

            <!-- Cursos sin dropdown -->
            <li class="nav-item">
              <a class="nav-link text-white" href="admin_practicas.php">Cursos</a>
            </li>

          <?php else: ?>
            <li class="nav-item"><a class="nav-link text-white" href="index.php">Inicio</a></li>
            <li class="nav-item"><a class="nav-link text-white" href="nosotros.php">Nosotros</a></li>

            <!-- Cursos sin dropdown -->
            <li class="nav-item">
              <a class="nav-link text-white" href="areadepracticas.php">Cursos</a>
            </li>

          <?php endif; ?>

          <!-- Galería -->
          <li class="nav-item dropdown">
            <a class="nav-link text-white" href="#" role="button" data-bs-toggle="dropdown">Galería</a>
            <ul class="dropdown-menu">
              <?php
              $resultado = $conexion->query("SELECT * FROM secciones_galeria ORDER BY id_seccion ASC");
              while($seccion = $resultado->fetch_assoc()):
                $id = $seccion['id_seccion'];
                $nombre = htmlspecialchars($seccion['nombre'], ENT_QUOTES, 'UTF-8');
              ?>
                <li><a class="dropdown-item" href="galerias.php#seccion<?= $id ?>"><?= $nombre ?></a></li>
              <?php endwhile; ?>
            </ul>
          </li>

          <li class="nav-item"><a class="nav-link text-white" href="ubicanos.php">Ubícanos</a></li>

          <!-- Contacto -->
          <li class="nav-item"><a class="nav-link text-white" href="contacto.php">Contáctanos</a></li>

          <!-- Inscríbete -->
          <li class="nav-item">
            <a class="nav-link text-white" href="https://forms.gle/LA5RFfpnKS5UWYXv8" target="_blank">
              Inscríbete
            </a>
          </li>

        </ul>

        <!-- Menú usuario / admin -->
        <ul class="navbar-nav ms-auto mb-2 mb-lg-0">

          <?php if(!$logueado): ?>

            <li class="nav-item"><a class="nav-link text-white" href="login.html">Iniciar sesión</a></li>

          <?php elseif($admin): ?>

            <!-- Admin -->
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle text-white d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown">
                <i class="bi bi-person-circle me-1" style="font-size:1.2rem;"></i> <?= $usuario_nombre ?>
              </a>
              <ul class="dropdown-menu" style="right: 0; left: auto;">
                <li><a class="dropdown-item" href="paneladmins.php"><i class="bi bi-people-fill me-1"></i>Gestión de Usuarios</a></li>
                <li><a class="dropdown-item" href="panelcursos.php"><i class="bi bi-journal-check me-1"></i>Administrar Cursos</a></li>
                <li><a class="dropdown-item" href="admin_galeria.php"><i class="bi bi-images me-1"></i>Panel de Galería</a></li>
                <li><a class="dropdown-item" href="perfiladmin.php"><i class="bi bi-person-gear me-1"></i>Mi Cuenta</a></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item text-danger" onclick="confirmarLogout()"><i class="bi bi-box-arrow-right me-1"></i>Cerrar Sesión</a></li>
              </ul>
            </li>

          <?php else: ?>

            <!-- Usuario normal -->
            <li class="nav-item">
              <a class="nav-link text-white d-flex align-items-center" href="perfil.php">
                <i class="bi bi-person-circle me-1" style="font-size:1.2rem;"></i> <?= $usuario_nombre ?>
              </a>
            </li>

          <?php endif; ?>

        </ul>
      </div>
      <!-- Offcanvas para móvil -->
      <div class="offcanvas offcanvas-start custom-offcanvas d-lg-none" tabindex="-1" id="mobileNavbar" aria-labelledby="mobileNavbarLabel">
        <div class="offcanvas-header text-white" style="background-color: #e45e1b;">
          <h5 class="offcanvas-title" id="mobileNavbarLabel">Menú</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Cerrar"></button>
        </div>

        <div class="offcanvas-body px-3">
          <ul class="navbar-nav w-100">

            <!-- Sección usuario/admin -->
            <?php if($logueado): ?>
              <div class="position-relative text-center mb-2 p-3 rounded-3" style="background-color: #e45e1b; color: white;">
                <a href="#" onclick="confirmarLogout()"
                  class="position-absolute top-0 end-0 mt-2 me-3 text-white"
                  title="Cerrar sesión" style="font-size: 1.3rem;">
                  <i class="bi bi-box-arrow-right"></i>
                </a>

                <i class="bi bi-person-circle mb-1" style="font-size: 3.5rem;"></i>
                <h5 class="fw-bold mb-0" style="text-transform: capitalize;"><?= $usuario_nombre ?></h5>

                <?php if($admin): ?>
                  <small class="fst-italic">Administrador</small>
                <?php else: ?>
                  <small class="fst-italic">Usuario</small>
                <?php endif; ?>
              </div>

              <?php if($admin): ?>
                <li class="nav-item dropdown text-center mb-1 mt-1">
                  <a class="nav-link dropdown-toggle fw-semibold text-dark" href="#" role="button" data-bs-toggle="dropdown">
                    <i class="bi bi-gear-fill me-2"></i> Opciones de administración
                  </a>
                  <ul class="dropdown-menu shadow-sm text-start">
                    <li><a class="dropdown-item" href="paneladmins.php"><i class="bi bi-people-fill me-2"></i>Gestión de Usuarios</a></li>
                    <li><a class="dropdown-item" href="panelcursos.php"><i class="bi bi-journal-check me-2"></i>Administrar Cursos</a></li>
                    <li><a class="dropdown-item" href="admin_galeria.php"><i class="bi bi-images me-2"></i>Panel de Galería</a></li>
                    <li><a class="dropdown-item" href="perfiladmin.php"><i class="bi bi-person-gear me-2"></i>Mi Cuenta</a></li>
                  </ul>
                </li>
              <?php else: ?>
                <li class="nav-item text-center mb-1 mt-1">
                  <a class="btn w-75 fw-semibold shadow-sm" href="perfil.php"
                    style="background-color: #e45e1b; color: white; border: 2px solid #c84f14; border-radius: 10px;">
                    <i class="bi bi-person-lines-fill me-1 text-white"></i> Ver mi perfil
                  </a>
                </li>
              <?php endif; ?>

              <hr class="mt-2 mb-2">

            <?php else: ?>
              <!-- Usuario no logueado -->
              <div class="text-center mb-3 p-4 rounded-3 shadow-sm" style="background-color: #e45e1b; color: white;">
                <h5 class="fw-semibold mb-3">Bienvenido</h5>
                <a href="login.html" class="btn w-75 fw-semibold shadow-sm"
                  style="background-color: #e45e1b; color: white; border: 2px solid white; border-radius: 10px;">
                  <i class="bi bi-box-arrow-in-right me-1"></i> Iniciar sesión
                </a>
              </div>
            <?php endif; ?>

            <!-- Menú general (ORDEN NUEVO) -->

            <?php if($admin): ?>
              <!-- INICIO -->
              <li class="nav-item mt-1">
                <a class="nav-link text-dark" href="admin_index.php">
                  <i class="bi bi-house-door me-2"></i>Inicio
                </a>
              </li>
            <?php else: ?>
              <li class="nav-item mt-1">
                <a class="nav-link text-dark" href="index.php">
                  <i class="bi bi-house-door me-2"></i>Inicio
                </a>
              </li>
            <?php endif; ?>

            <!-- NOSOTROS -->
            <li class="nav-item mt-1">
              <a class="nav-link text-dark" href="nosotros.php">
                <i class="bi bi-people me-2"></i>Nosotros
              </a>
            </li>

            <!-- CURSOS (SIN DROPDOWN) -->
            <li class="nav-item mt-1">
              <a class="nav-link text-dark"
                href="<?= $admin ? 'admin_practicas.php' : 'areadepracticas.php' ?>">
                <i class="bi bi-journal-text me-2"></i>Cursos
              </a>
            </li>

            <!-- GALERÍA -->
            <li class="nav-item dropdown mt-1">
              <a class="nav-link dropdown-toggle text-dark" href="#" data-bs-toggle="dropdown">
                <i class="bi bi-images me-2"></i>Galería
              </a>
              <ul class="dropdown-menu">
                <?php
                $resultadoGaleria = $conexion->query("SELECT * FROM secciones_galeria ORDER BY id_seccion ASC");
                while($seccion = $resultadoGaleria->fetch_assoc()):
                    $id = $seccion['id_seccion'];
                    $nombre = htmlspecialchars($seccion['nombre'], ENT_QUOTES, 'UTF-8');
                ?>
                    <li><a class="dropdown-item" href="galerias.php#seccion<?= $id ?>"><?= $nombre ?></a></li>
                <?php endwhile; ?>
              </ul>
            </li>

            <!-- UBÍCANOS -->
            <li class="nav-item mt-1">
              <a class="nav-link text-dark" href="ubicanos.php">
                <i class="bi bi-geo-alt me-2"></i>Ubícanos
              </a>
            </li>

            <!-- CONTACTO -->
            <li class="nav-item mt-1">
              <a class="nav-link text-dark" href="contacto.php">
                <i class="bi bi-envelope me-2"></i>Contáctanos
              </a>
            </li>

            <!-- INSCRÍBETE -->
            <li class="nav-item mt-1 mb-2">
              <a class="nav-link text-dark" href="https://forms.gle/LA5RFfpnKS5UWYXv8" target="_blank">
                <i class="bi bi-pencil-square me-2"></i>Inscríbete
              </a>
            </li>

          </ul>
        </div>
      </div>
    </div>
  </nav>

  <!-- Mensaje de éxito -->
  <?php if (isset($_GET['msg']) && $_GET['msg'] === 'ok'): ?>
    <div class="alert alert-success text-center mt-3">
      ¡Curso agregado exitosamente!
    </div>
  <?php endif; ?>

  <!-- Listado de cursos -->
  <div class="container mt-4 mb-5">
    <div class="row g-4 justify-content-center">
      <?php while ($curso = $resultadoCursos->fetch_assoc()): ?>
        <div class="col-12 col-md-6 col-lg-4">
          <div class="card h-100 shadow-sm">
            <img src="<?= htmlspecialchars($curso['imagen'], ENT_QUOTES, 'UTF-8') ?>" 
            class="card-img-top" 
            alt="<?= htmlspecialchars($curso['titulo']) ?>" 
            style="height:200px; object-fit:cover;">
            <div class="card-body">
              <h5 class="card-title"><?= htmlspecialchars($curso['titulo']) ?></h5>
              <p class="card-text text-muted" style="font-size:14px;">
                <?= nl2br(htmlspecialchars($curso['descripcion'])) ?>
              </p>
            </div>
            <div class="card-footer text-center">
              <button 
                  class="btn btn-warning btn-sm me-2"
                  data-bs-toggle="modal" 
                  data-bs-target="#modalEditarCurso"
                  data-id="<?= $curso['id'] ?>"
                  data-titulo="<?= esc($curso['titulo']) ?>"
                  data-descripcion="<?= esc($curso['descripcion']) ?>"
                  data-duracion="<?= esc($curso['duracion']) ?>"
                  data-alumnos="<?= $curso['alumnos'] ?>"
                  data-fechainicio="<?= date('Y-m-d', strtotime($curso['fecha_inicio'])) ?>"
                  data-fechafin="<?= date('Y-m-d', strtotime($curso['fecha_fin'])) ?>"
                  data-horario="<?= esc($curso['horario']) ?>"
                  data-dias="<?= esc($curso['dias']) ?>"
                  data-modalidad="<?= esc($curso['modalidad']) ?>"
                  data-sucursal="<?= esc($curso['sucursal']) ?>"
                  data-costototal="<?= $curso['costo_total'] ?>"
                  data-inscripcion="<?= $curso['costo_inscripcion'] ?>"
                  data-sesion="<?= $curso['costo_sesion'] ?>"
                  data-imagen="<?= $curso['imagen'] ?>"
              >
                  <i class="bi bi-pencil-square"></i> Editar
              </button>

              <a href="eliminar_curso.php?id=<?= $curso['id']?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Seguro que deseas eliminar este curso?');">
                  <i class="bi bi-trash"></i> Eliminar
              </a>
            </div>
          </div>
        </div>
      <?php endwhile; ?>

      <!-- Card especial para agregar curso -->
      <div class="col-12 col-md-6 col-lg-4">
        <div class="card h-100 border-2 border-dashed d-flex align-items-center justify-content-center" 
             id="modalAgregarCursoCard"
             style="border:2px dashed #e45e1b; background-color:#fff; cursor:pointer;"
             data-bs-toggle="modal" data-bs-target="#modalAgregarCurso"
             data-bs-toggle="tooltip" data-bs-placement="top" title="Agregar Curso">
          <div class="text-center">
            <i class="bi bi-plus-circle-fill" style="font-size:4rem; color:#e45e1b;"></i>
            <p class="mt-2 fw-bold" style="color:#e45e1b;">Agregar Curso</p>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal de Agregar Curso -->
  <div class="modal fade" id="modalAgregarCurso" tabindex="-1" aria-labelledby="modalAgregarCursoLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-content">

        <div class="modal-header" style="background-color:#e45e1b; color:white;">
          <h5 class="modal-title" id="modalAgregarCursoLabel">Agregar Curso</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>

        <div class="modal-body">
          <form action="agregar_curso.php" method="POST" enctype="multipart/form-data" onsubmit="unirHorario()">
            
            <div class="input-container">
              <input type="text" name="titulo" id="titulo" required placeholder=" ">
              <label for="titulo">Título</label>
            </div>

            <div class="input-container">
              <textarea name="descripcion" id="descripcion" rows="4" required placeholder=" "></textarea>
              <label for="descripcion">Descripción</label>
            </div>

            <div class="input-container">
              <input type="text" name="duracion" id="duracion" required placeholder=" ">
              <label for="duracion">Duración</label>
            </div>

            <div class="input-container">
              <input type="number" name="alumnos" id="alumnos" required placeholder=" ">
              <label for="alumnos">Número de alumnos</label>
            </div>

            <div class="input-container">
              <div class="upload-area border border-2 border-primary rounded p-4 text-center mb-3" id="drop-agregar">
                <i class="bi bi-cloud-arrow-up text-primary" style="font-size:2rem;"></i>
                <p class="mt-2 mb-0 text-muted">Arrastra la imagen aquí<br>o haz clic para seleccionarla</p>
                <input type="file" id="imagen" name="imagen" accept="image/*" hidden>
              </div>

              <img id="preview-agregar" class="mt-2 mb-3" style="display:none; max-width:150px; max-height:150px; cursor:pointer;">
              <label for="imagen">Imagen</label>
            </div>

            <div class="input-container-static">
              <label for="fecha_inicio">Fechas</label>
              <div class="d-flex align-items-center gap-2 mt-2">
                <input type="date" name="fecha_inicio" id="fecha_inicio" class="hora-input" required>
                <span class="fw-bold">-</span>
                <input type="date" name="fecha_fin" id="fecha_fin" class="hora-input" required>
              </div>
            </div>
            <br/>
            <div class="input-container-static">
              <label for="hora_inicio">Horario</label>
              <div class="d-flex align-items-center gap-2 mt-2">
                <input type="time" id="hora_inicio" class="hora-input" value="00:00" required>
                <span class="fw-bold">-</span>
                <input type="time" id="hora_fin" class="hora-input" value="00:00" required>
              </div>
              <input type="hidden" name="horario" id="horario">
            </div>
            <br/>
            <div class="input-container"> 
              <input type="text" name="dias" id="dias" required placeholder=" "> 
              <label for="dias">Días</label> 
            </div>

            <div class="input-container-static sucursal-container">
              <label>Modalidad</label>
              <div class="d-flex gap-3 mt-2 justify-content-center">
                <label class="radio-box">
                  <input type="radio" name="modalidad" value="Presencial" required checked>
                  <span>Presencial</span>
                </label>
                <label class="radio-box">
                  <input type="radio" name="modalidad" value="En línea">
                  <span>En línea</span>
                </label>
                <label class="radio-box">
                  <input type="radio" name="modalidad" value="Semipresencial">
                  <span>Semipresencial</span>
                </label>
              </div>
            </div>

            <div class="input-container-static sucursal-container">
              <label>Sucursal</label>
              <div class="d-flex gap-3 mt-2 justify-content-center">
                <label class="radio-box">
                  <input type="radio" name="sucursal" value="Piña" required checked>
                  <span>Piña</span>
                </label>
                <label class="radio-box">
                  <input type="radio" name="sucursal" value="Mezquital">
                  <span>Mezquital</span>
                </label>
              </div>
            </div>

            <div class="input-container">
              <input type="number" step="0.01" name="costo_total" id="costo_total" required placeholder=" ">
              <label for="costo_total">Costo total</label>
            </div>

            <div class="input-container">
              <input type="number" step="0.01" name="costo_inscripcion" id="costo_inscripcion" required placeholder=" ">
              <label for="costo_inscripcion">Costo de inscripción</label>
            </div>

            <div class="input-container">
              <input type="number" step="0.01" name="costo_sesion" id="costo_sesion" required placeholder=" ">
              <label for="costo_sesion">Costo por sesión</label>
            </div>

            <div class="text-end mt-3">
              <button type="submit" class="btn btn-success">Guardar</button>
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal Editar Curso -->
  <div class="modal fade" id="modalEditarCurso" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header" style="background-color:#e45e1b; color:white;">
          <h5 class="modal-title">Editar Curso</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <form action="actualizar_curso.php" method="POST" enctype="multipart/form-data" onsubmit="unirHorarioEditar()">
          <div class="modal-body">
            <input type="hidden" name="id" id="edit-id">

            <div class="input-container">
              <input type="text" name="titulo" id="edit-titulo" required placeholder=" ">
              <label for="edit-titulo">Título</label>
            </div>

            <div class="input-container">
              <textarea name="descripcion" id="edit-descripcion" rows="4" required placeholder=" "></textarea>
              <label for="edit-descripcion">Descripción</label>
            </div>

            <div class="input-container">
              <input type="text" name="duracion" id="edit-duracion" required placeholder=" ">
              <label for="edit-duracion">Duración</label>
            </div>

            <div class="input-container">
              <input type="number" name="alumnos" id="edit-alumnos" required placeholder=" ">
              <label for="edit-alumnos">Número de alumnos</label>
            </div>

            <div class="input-container d-flex flex-column align-items-center">
              <label>Imagen actual</label><br>
              <img id="preview-imagen" src="" alt="Imagen actual" 
                  style="max-height:150px; max-width:150px; border-radius:8px; cursor:pointer; margin-bottom:10px;">
            </div>

            <div class="input-container">
              <div class="upload-area border border-2 border-primary rounded p-4 text-center mb-3" id="drop-editar">
                <i class="bi bi-cloud-arrow-up text-primary" style="font-size:2rem;"></i>
                <p class="mt-2 mb-0 text-muted">Arrastra la imagen aquí<br>o haz clic para seleccionarla</p>
                <input type="file" id="edit-imagen" name="imagen" accept="image/*" hidden>
              </div>
              <img id="preview-nueva" style="display:none; max-width:150px; max-height:150px; cursor:pointer; margin:10px auto;">
              <label for="edit-imagen">Nueva imagen</label>
            </div>

            <div class="input-container-static">
              <label>Fechas</label>
              <div class="d-flex align-items-center gap-2 mt-2">
                <input type="date" name="fecha_inicio" id="edit-fechainicio" class="hora-input" required>
                <span class="fw-bold">-</span>
                <input type="date" name="fecha_fin" id="edit-fechafin" class="hora-input" required>
              </div>
            </div>
            <br/>
            <div class="input-container-static">
              <label>Horario</label>
              <div class="d-flex align-items-center gap-2 mt-2">
                <input type="time" id="edit-hora_inicio" class="hora-input" value="00:00" required>
                <span class="fw-bold">-</span>
                <input type="time" id="edit-hora_fin" class="hora-input" value="00:00" required>
              </div>
              <input type="hidden" name="horario" id="edit-horario">
            </div>
            <br/>
            <div class="input-container">
              <input type="text" name="dias" id="edit-dias" required placeholder=" ">
              <label for="edit-dias">Días</label>
            </div>

            <div class="input-container-static sucursal-container">
              <label>Modalidad</label>
              <div class="d-flex gap-3 mt-2 justify-content-center">
                <label class="radio-box">
                  <input type="radio" name="modalidad" value="Presencial" required>
                  <span>Presencial</span>
                </label>
                <label class="radio-box">
                  <input type="radio" name="modalidad" value="En línea">
                  <span>En línea</span>
                </label>
                <label class="radio-box">
                  <input type="radio" name="modalidad" value="Semipresencial">
                  <span>Semipresencial</span>
                </label>
              </div>
            </div>

            <div class="input-container-static sucursal-container">
              <label>Sucursal</label>
              <div class="d-flex gap-3 mt-2 justify-content-center">
                <label class="radio-box">
                  <input type="radio" name="sucursal" value="Piña" required>
                  <span>Piña</span>
                </label>
                <label class="radio-box">
                  <input type="radio" name="sucursal" value="Mezquital">
                  <span>Mezquital</span>
                </label>
              </div>
            </div>

            <div class="input-container">
              <input type="number" step="0.01" name="costo_total" id="edit-costototal" required placeholder=" ">
              <label for="edit-costototal">Costo total</label>
            </div>

            <div class="input-container">
              <input type="number" step="0.01" name="costo_inscripcion" id="edit-inscripcion" required placeholder=" ">
              <label for="edit-inscripcion">Costo de inscripción</label>
            </div>

            <div class="input-container">
              <input type="number" step="0.01" name="costo_sesion" id="edit-sesion" required placeholder=" ">
              <label for="edit-sesion">Costo por sesión</label>
            </div>

          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-success">Guardar cambios</button>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          </div>
        </form>
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

      <div class="modal-body d-flex align-items-center gap-3">
        <i class="bi bi-exclamation-triangle-fill" 
           style="font-size: 3rem; color:#FF7D29;"></i>

        <div>
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
  <!-- Footer -->
  <div class="main">
    <footer>
      <div class="waves">
        <div class="wave" id="wave1"></div>
      </div>

      <div class="container position-relative">

        <!-- Redes sociales (CENTRO) -->
        <div class="position-absolute top-50 start-50 translate-middle d-flex justify-content-center">
          <div class="zoom">
            <ul class="social_icon d-flex justify-content-center mb-0">
              <li><a href="https://facebook.com/CeijJuarez"><ion-icon name="logo-facebook"></ion-icon></a></li>
              <li><a href="https://www.instagram.com/ceij_juarez"><ion-icon name="logo-instagram"></ion-icon></a></li>
              <li><a href="https://www.tiktok.com/@ceijjuarez"><ion-icon name="logo-tiktok"></ion-icon></a></li>
              <li><a href="https://www.youtube.com/@CEIJJUAREZ"><ion-icon name="logo-youtube"></ion-icon></a></li>
              <li><a href="https://wa.me/5216566367848"><ion-icon name="logo-whatsapp"></ion-icon></a></li>
              <li><a href="mailto:ceij030223@gmail.com"><ion-icon name="mail-outline"></ion-icon></a></li>
            </ul>
          </div>
        </div>

        <!-- Descarga App (DERECHA en desktop, centrado abajo en móvil) -->
        <div class="d-flex flex-wrap justify-content-center justify-content-md-end gap-3">
          
          <div class="d-flex flex-wrap justify-content-center justify-content-md-end gap-3">

            <!-- APK -->
            <a href="https://github.com/ceij030223-cloud/CEIJ_APP/releases/download/%3CCEIJ/app_ceij.1.apk"
              class="btn btn-dark d-flex align-items-center gap-2 px-3 py-2"
              target="_blank"
              style="height:50px;">
              <i class="fab fa-github"></i> Descargar APK
            </a>
            <!-- Google Play -->
            <a href="https://play.google.com/store/apps/details?id=app.ceij"
              target="_blank"
              class="btn btn-dark d-flex align-items-center px-2 py-0"
              style="height:50px; background-color:#000; border:none;">
              <img alt="Disponible en Google Play"
                  src="https://play.google.com/intl/en_us/badges/static/images/badges/es_badge_web_generic.png"
                  style="height:100%;"/>
            </a>
          </div>
        </div>
      </div>
      <p class="mt-3 text-center">© 2025 Todos los derechos reservados</p>
    </footer>
  </div>
  <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
  <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
  <!-- Scripts -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
  <script>AOS.init();</script>
  <script src="../js/admin_cursos.js?v=1.1"></script>
</body>
</html>