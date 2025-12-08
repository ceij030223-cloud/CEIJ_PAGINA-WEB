<?php
include 'db.php';
include 'seguridad.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />

  <!-- Bootstrap + íconos -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css"/>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

  <!-- Estilos propios -->  
  <link rel="icon" href="../icon/ceijicon.ico" />
  <link rel="stylesheet" href="../css/admin_practicas.css?v=2.9" />

  <!-- Fuente Google -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  
  <!-- Animación scroll AOS -->
  <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">

  <!-- SEO Meta Tags -->
  <meta name="description" content="CEIJ ofrece cursos certificados por SEP y STPS para que avances profesionalmente.">
  <meta name="author" content="CEIJ Juárez">
  <meta property="og:title" content="CEIJ | Centro de Educación Integral Juárez">
  <meta property="og:description" content="Transforma tu futuro con nuestros cursos certificados.">
  <meta property="og:image" content="https://tusitio.com/img/banner.jpg"> <!-- Cámbialo -->
  <meta property="og:url" content="https://tusitio.com">
  <meta name="twitter:card" content="summary_large_image">
  
  <title>Áreas de prácticas</title>
</head>
<body>
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
<main class="py-4">
    <div class="container">
      <div class="row row-cols-1 row-cols-md-3 g-4">
        <?php
        $query = "
        SELECT t.*, s.id_seccion, s.nombre AS nombre_seccion
        FROM tarjetas t
        LEFT JOIN secciones_galeria s ON t.id_seccion = s.id_seccion
        ORDER BY t.id_tarjeta ASC";
        $resultado = $conexion->query($query);
        while ($tarjeta = $resultado->fetch_assoc()):
        ?>
          <div class="col">
            <div class="card position-relative shadow-sm">
              <div class="card-scroll">
                <img src="<?= htmlspecialchars($tarjeta['imagen']) ?>" class="card-img-top" alt="<?= htmlspecialchars($tarjeta['titulo']) ?>">
                <h5 class="card-title mt-3"><strong><?= htmlspecialchars($tarjeta['titulo']) ?></strong></h5>
                <p class="card-text"><?= htmlspecialchars($tarjeta['descripcion']) ?></p>
              </div>

              <?php if (!empty($tarjeta['id_seccion'])): ?>
                <div class="card-footer-fixed">
                  <a href="galerias.php#seccion<?= $tarjeta['id_seccion'] ?>" class="btn btn-primary w-100">
                    <i class="bi bi-images me-2"></i> Ver galería de fotos
                  </a>
                </div>
              <?php endif; ?>

              <!-- Botones Editar / Eliminar -->
              <div class="card-actions">
                <button class="btn-edit" data-id="<?= $tarjeta['id_tarjeta'] ?>" data-titulo="<?= htmlspecialchars($tarjeta['titulo']) ?>" data-descripcion="<?= htmlspecialchars($tarjeta['descripcion']) ?>" data-imagen="<?= htmlspecialchars($tarjeta['imagen']) ?>" data-seccion="<?= htmlspecialchars($tarjeta['id_seccion']) ?>"><i class="bi bi-pencil"></i></button>
                <button class="btn-delete" data-id="<?= $tarjeta['id_tarjeta'] ?>"><i class="bi bi-trash"></i></button>
              </div>
            </div>
          </div>
        <?php endwhile; ?>
      </div>
    </div>
  </main>

  <!-- Botón flotante agregar -->
  <div class="btn-float" id="btnAgregar" title="Agregar área de práctica" data-bs-toggle="tooltip">
    <i class="bi bi-plus-lg"></i>
  </div>

  <!-- Modal agregar/editar área de práctica -->
  <div class="modal fade" id="modalTarjeta" tabindex="-1" aria-hidden="true">
   <div class="modal-dialog">
    <form class="modal-content" id="formTarjeta">
      <div class="modal-header" style="background-color:#e45e1b; color:white;">
        <h5 class="modal-title" id="tituloModal">Agregar área de práctica</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="id_tarjeta" name="id_tarjeta">

        <div class="mb-3">
          <input type="text" name="titulo" id="titulo" class="form-control" placeholder="" required>
          <label class="form-label">Título</label>
        </div>

        <div class="mb-3">
          <textarea name="descripcion" id="descripcion" class="form-control" rows="3" placeholder="" required></textarea>
          <label class="form-label">Descripción</label>
        </div>

        <!-- Área de carga de imagen -->
        <div class="mb-3">
          <!-- Imagen actual (solo visible al editar) -->
          <div id="currentImageContainer" class="text-center mb-3 d-none">
            <label class="form-label">Imagen actual</label>
            <br/>
            <img id="currentImage" class="img-fluid rounded" style="max-height: 150px; cursor: pointer;" alt="Imagen actual">
          </div>

          <!-- DropZone para subir nueva imagen -->
          <label class="form-label">Agregar / Cambiar imagen</label>
          <div id="dropZone" class="border border-2 border-dashed rounded p-4 text-center">
            <i class="bi bi-cloud-arrow-up" style="font-size: 2rem;"></i>
            <p class="mt-2 mb-0">Arrastra la imagen aquí o haz clic para seleccionar</p>
            <input type="file" id="imagenInput" name="imagen" accept="image/*" hidden>
            <img id="preview" class="mt-3 img-fluid rounded d-none" style="cursor: pointer;" alt="Vista previa" />
          </div>
        </div>

        <div class="mb-3">
          <select name="id_seccion" id="id_seccion" class="form-select">
            <option value="">Ninguna</option>
            <?php
              $galerias = $conexion->query("SELECT * FROM secciones_galeria ORDER BY nombre ASC");
              while ($g = $galerias->fetch_assoc()):
            ?>
              <option value="<?= $g['id_seccion'] ?>"><?= htmlspecialchars($g['nombre']) ?></option>
            <?php endwhile; ?>
          </select>
          <label placeholder ="" class="form-label">Vincular con galería</label>
        </div>
        <div id="errorImagen" class="text-danger small d-none mt-1"></div>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary">Guardar</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
      </div>
    </form>
  </div>
</div>
<!-- Modal Confirmar Eliminar Tarjeta -->
<div class="modal fade" id="modalEliminarTarjeta" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content"
         style="border-radius: 12px; animation: fadeIn 0.25s ease; border: 2px solid #dc3545;">

      <!-- HEADER -->
      <div class="modal-header text-white justify-content-center"
           style="
             background: linear-gradient(135deg, #dc3545, #ff6b6b);
             border-top-left-radius: 12px;
             border-top-right-radius: 12px;
           ">

        <h5 class="modal-title w-100 text-center" style="font-weight: bold;">
          Confirmar eliminación
        </h5>

        <button type="button" class="btn-close btn-close-white position-absolute end-0 me-3"
                data-bs-dismiss="modal"></button>
      </div>

      <!-- BODY -->
      <div class="modal-body">

        <div class="d-flex align-items-center gap-3">

          <i class="bi bi-exclamation-octagon-fill" 
             style="font-size: 3.5rem; color:#dc3545;"></i>

          <div class="text-center w-100">
            <p class="mb-1 fs-5 fw-semibold">¿Deseas eliminar esta tarjeta?</p>
            <small class="text-muted">Esta acción no se puede deshacer.</small>
          </div>

        </div>

      </div>

      <!-- FOOTER -->
      <div class="modal-footer" 
           style="display: flex; justify-content: center; gap: 10px; border-top: none;">

        <!-- Botón cancelar -->
        <button class="btn"
                data-bs-dismiss="modal"
                style="
                  background: #ccc;
                  color: #333;
                  border: none;
                  border-radius: 8px;
                  padding: 8px 20px;
                  font-weight: bold;
                  cursor: pointer;
                  transition: background 0.2s ease;
                "
                onmouseover="this.style.background='#b8b8b8'"
                onmouseout="this.style.background='#ccc'">
          Cancelar
        </button>

        <!-- Botón eliminar -->
        <button id="btnEliminarTarjetaConfirmado"
                style="
                  background: linear-gradient(135deg, #dc3545, #ff6b6b);
                  color: white;
                  border: none;
                  border-radius: 8px;
                  padding: 8px 25px;
                  font-weight: bold;
                  cursor: pointer;
                  box-shadow: 0 3px 8px rgba(0,0,0,0.2);
                  transition: transform 0.2s ease;
                "
                onmouseover="this.style.transform='scale(1.05)'"
                onmouseout="this.style.transform='scale(1)'">
          Eliminar
        </button>

      </div>

    </div>
  </div>
</div>

<!-- Modal Error al Eliminar Tarjeta -->
<div class="modal fade" id="modalErrorEliminar" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered modal-sm modal-md modal-lg">
    <div class="modal-content border-danger" style="border-radius: 14px; animation: fadeIn .25s ease;">

      <div class="modal-header text-white justify-content-center position-relative"
           style="background: linear-gradient(135deg, #dc3545 0%, #b81f2e 100%);">
        <h5 class="modal-title">Error</h5>

        <button type="button" class="btn-close btn-close-white position-absolute end-0 me-3"
                style="filter: invert(1);" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body d-flex flex-column flex-md-row align-items-center gap-3 
                  text-center text-md-start justify-content-start">

        <!-- Ícono -->
        <i class="bi bi-x-circle-fill" style="font-size: 3.2rem; color:#dc3545;"></i>

        <!-- Texto -->
        <div>
          <p id="mensajeErrorEliminar" class="mb-0 fs-5">Error al eliminar tarjeta.</p>
          <small class="text-muted">Inténtalo nuevamente.</small>
        </div>

      </div>

    </div>
  </div>
</div>

<!-- Modal Error de Conexión -->
<div class="modal fade" id="modalErrorConexion" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered modal-sm modal-md modal-lg">
    <div class="modal-content border-danger" style="border-radius: 14px; animation: fadeIn .25s ease;">

      <div class="modal-header text-white justify-content-center position-relative"
           style="background: linear-gradient(135deg, #dc3545 0%, #b81f2e 100%);">
        <h5 class="modal-title">Error de Conexión</h5>

        <button type="button" class="btn-close btn-close-white position-absolute end-0 me-3"
                style="filter: invert(1);" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body d-flex flex-column flex-md-row align-items-center gap-3 
                  text-center text-md-start justify-content-start">

        <!-- Ícono -->
        <i class="bi bi-wifi-off" style="font-size: 3.2rem; color:#dc3545;"></i>

        <!-- Texto -->
        <div>
          <p class="mb-0 fs-5">Error de conexión con el servidor.</p>
          <small class="text-muted">Por favor, revisa tu conexión o inténtalo más tarde.</small>
        </div>

      </div>

    </div>
  </div>
</div>

<!-- Modal Error al Guardar en Base de Datos -->
<div class="modal fade" id="modalErrorGuardarDB" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered modal-sm modal-md modal-lg">
    <div class="modal-content border-danger" style="border-radius: 14px; animation: fadeIn .25s ease;">

      <div class="modal-header text-white justify-content-center position-relative"
           style="background: linear-gradient(135deg, #dc3545 0%, #b81f2e 100%);">
        <h5 class="modal-title">Error al Guardar</h5>

        <button type="button" class="btn-close btn-close-white position-absolute end-0 me-3"
                style="filter: invert(1);" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body d-flex flex-column flex-md-row align-items-center gap-3 
                  text-center text-md-start justify-content-start">

        <!-- Ícono -->
        <i class="bi bi-x-circle-fill" style="font-size: 3.2rem; color:#dc3545;"></i>

        <!-- Texto -->
        <div>
          <p id="mensajeErrorGuardarDB" class="mb-0 fs-5">
            Error al guardar en la base de datos.
          </p>
          <small class="text-muted">Inténtalo nuevamente.</small>
        </div>

      </div>

    </div>
  </div>
</div>

<!-- Modal Éxito Eliminación de Tarjeta -->
<div class="modal fade" id="modalExitoTarjeta" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered modal-sm modal-md modal-lg">
    <div class="modal-content border-success" 
         style="border-radius: 14px; animation: fadeIn .25s ease;">

      <!-- Header con degradado verde -->
      <div class="modal-header text-white justify-content-center position-relative"
           style="background: linear-gradient(135deg, #28a745 0%, #1f7a36 100%);">
        <h5 class="modal-title">Éxito</h5>

        <button type="button" class="btn-close btn-close-white position-absolute end-0 me-3"
                style="filter: invert(1);" data-bs-dismiss="modal"></button>
      </div>

      <!-- Body -->
      <div class="modal-body d-flex flex-column flex-md-row align-items-center gap-3 
                  text-center text-md-start justify-content-start">

        <!-- Ícono -->
        <i class="bi bi-check-circle-fill" style="font-size: 3.2rem; color:#28a745;"></i>

        <!-- Texto dinámico -->
        <div>
          <p class="mb-0 fs-5">Tarjeta eliminada correctamente.</p>
        </div>

      </div>

    </div>
  </div>
</div>

<!-- Modal Éxito Guardar Tarjeta -->
<div class="modal fade" id="modalExitoGuardarTarjeta" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered modal-sm modal-md modal-lg">
    <div class="modal-content border-success"
         style="border-radius: 14px; animation: fadeIn .25s ease;">

      <!-- Header con degradado verde -->
      <div class="modal-header text-white justify-content-center position-relative"
           style="background: linear-gradient(135deg, #28a745 0%, #1f7a36 100%);">
        <h5 class="modal-title">Éxito</h5>
        <button type="button" class="btn-close btn-close-white position-absolute end-0 me-3"
                style="filter: invert(1);" data-bs-dismiss="modal"></button>
      </div>

      <!-- Body -->
      <div class="modal-body d-flex flex-column flex-md-row align-items-center gap-3 
                  text-center text-md-start justify-content-start">

        <!-- Ícono -->
        <i class="bi bi-check-circle-fill" style="font-size: 3.2rem; color:#28a745;"></i>

        <!-- Texto dinámico -->
        <div>
          <p class="mb-0 fs-5" id="mensajeExitoGuardarTarjeta">Tarjeta guardada correctamente.</p>
        </div>

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
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
  <script>AOS.init();</script>
  <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
  <script src="../js/admin_practicas.js?v=1.8"></script>
</body>
</html>