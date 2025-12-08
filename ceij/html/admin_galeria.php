<?php
include 'db.php';
include 'seguridad.php';

// AGREGAR NUEVA SECCIÓN
if (isset($_POST['agregar_seccion'])) {
    $nombre = trim($_POST['nombre']);
    if (!empty($nombre)) {
        $conexion->query("INSERT INTO secciones_galeria (nombre) VALUES ('$nombre')");
        // Redirigir pasando el nombre de la sección como parámetro GET
        header("Location: admin_galeria.php?seccion_creada=" . urlencode($nombre));
        exit;
    }
}

// RENOMBRAR SECCIÓN
if (isset($_POST['renombrar_seccion'])) {
    $id = $_POST['id_seccion'];
    $nuevo_nombre = trim($_POST['nuevo_nombre']);
    if (!empty($nuevo_nombre)) {
        $conexion->query("UPDATE secciones_galeria SET nombre='$nuevo_nombre' WHERE id_seccion=$id");
        // En vez de redirigir, enviamos un indicador para JS
        echo "<script>window.sessionStorage.setItem('seccionRenombrada', '".addslashes($nuevo_nombre)."');</script>";
    }
}

// ELIMINAR SECCIÓN (Y SUS IMÁGENES/VIDEOS)
if (isset($_GET['eliminar_seccion'])) {
  $id = (int)$_GET['eliminar_seccion'];

  // Buscar todas las rutas de los archivos asociados
  $resultado = $conexion->query("SELECT ruta FROM imagenes_galeria WHERE id_seccion = $id");

  if ($resultado && $resultado->num_rows > 0) {
    while ($fila = $resultado->fetch_assoc()) {
      $ruta_archivo = $fila['ruta'];
      $ruta_completa = realpath(__DIR__ . '/' . $ruta_archivo);

      // Eliminar cada archivo físico
      if ($ruta_completa && file_exists($ruta_completa)) {
        unlink($ruta_completa);
      }
    }
  }

  // Eliminar los registros de imágenes asociados
  $conexion->query("DELETE FROM imagenes_galeria WHERE id_seccion = $id");

  // Eliminar la sección
  $conexion->query("DELETE FROM secciones_galeria WHERE id_seccion = $id");

  header("Location: admin_galeria.php");
  exit;
}


$secciones = $conexion->query("SELECT * FROM secciones_galeria ORDER BY id_seccion DESC");
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Panel de Galería</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

  <!-- Bootstrap + íconos -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css"/>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
  
  <!-- Estilos propios -->
  <link rel="stylesheet" href="../css/admin_galeria.css?v=1.5"/>
  <link rel="icon" href="../icon/ceijicon.ico" />

  <!-- Fuente Google -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  
  <!-- Animación scroll AOS -->
  <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">

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
<main>
<div class="container">

  <h2 class="text-center mb-4 text-primary">Galería de los cursos</h2>

  <!-- LISTADO DE SECCIONES -->
  <div id="listadoSecciones">
    <div class="card mb-4">
      <div class="card-header bg-primary text-white">
        <strong>Secciones existentes</strong>
      </div>
      <div class="card-body">
        <?php if ($secciones->num_rows > 0): ?>
          <ul class="list-group">
            <?php
              $modales = ''; // acumulador de modales
              while($sec = $secciones->fetch_assoc()):
                $idSec = $sec['id_seccion'];
                $nombreSec = htmlspecialchars($sec['nombre'], ENT_QUOTES, 'UTF-8');
            ?>
              <li class="list-group-item d-flex justify-content-between align-items-center" data-id="<?= $idSec ?>">
                <span class="nombre-seccion"><i class="bi bi-folder"></i> <?= $nombreSec ?></span>
                <div>
                  <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#modalEditar<?= $idSec ?>">
                    <i class="bi bi-pencil"></i> Editar
                  </button>
                  <a href="#" class="btn btn-sm btn-danger btn-eliminar-seccion"
                    data-id="<?= $idSec ?>" data-nombre="<?= $nombreSec ?>">
                    <i class="bi bi-trash"></i>
                  </a>
                </div>
              </li>

            <?php
                // Construir modal en buffer y añadir a $modales (no lo imprimimos aquí para mantener el UL limpio)
                ob_start();
            ?>
              <!-- MODAL EDITAR -->
              <div class="modal fade" id="modalEditar<?= $idSec ?>" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title">Editar galería: <?= $nombreSec ?></h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">

                      <!-- Cambiar nombre -->
                      <form method="POST" class="renombrar-form" data-id="<?= $idSec ?>">
                        <input type="hidden" name="id_seccion" value="<?= $idSec ?>">
                        <label class="form-label">Nombre de la galería:</label>
                        <div class="input-group mb-3">
                          <input type="text" name="nuevo_nombre" class="form-control" value="<?= $nombreSec ?>" required>
                          <button type="submit" class="btn btn-warning">
                            <i class="bi bi-check-lg"></i> Confirmar
                          </button>
                        </div>
                      </form>

                      <!-- Imágenes existentes -->
                      <div class="mb-3">
                        <label class="form-label">Contenido multimedia existente:</label><br>
                        <div id="contenidoExistente<?= $idSec ?>"> <!-- aquí van los videos e imágenes existentes -->
                          <?php
                            $imagenes = $conexion->query("SELECT * FROM imagenes_galeria WHERE id_seccion={$idSec}");
                            if ($imagenes->num_rows > 0):
                          ?>

                        <form method="POST" id="formEliminar<?= $idSec ?>">
                          <input type="hidden" name="id_seccion" value="<?= $idSec ?>" id="idSeccion">

                          <div class="d-flex flex-wrap">
                              <?php while($img = $imagenes->fetch_assoc()): ?>
                                  <div class="img-container border p-1 text-center position-relative me-2 mb-2">
                                      <input type="checkbox" name="seleccion[]" value="<?= $img['id_imagen'] ?>" class="position-absolute m-1" data-ruta="<?= $img['ruta'] ?>">
                                      <?php if($img['tipo']==='video'): ?>
                                          <video src="<?= $img['ruta'] ?>" class="imagen-mini video-mini border" muted loop autoplay></video>
                                      <?php else: ?>
                                          <img src="<?= $img['ruta'] ?>" class="imagen-mini border">
                                      <?php endif; ?>
                                  </div>
                              <?php endwhile; ?>
                          </div>

                          <input type="hidden" name="eliminar_multiples" value="1">
                          <button type="submit" class="btn btn-danger mt-2 w-100 btnEliminar">
                              <i class="bi bi-trash"></i> Eliminar seleccionados
                          </button>
                        </form>

                        <?php else: ?>
                          <p class="text-muted">No hay contenido aún.</p>
                        <?php endif; ?>
                        </div> <!-- AHORA SÍ SE CIERRA CORRECTAMENTE -->
                      </div>

                      <!-- Subir nueva imagen -->
                      <form method="POST" enctype="multipart/form-data" id="formUpload<?= $idSec ?>" class="form-subida">
                        <input type="hidden" name="id_seccion" value="<?= $idSec ?>">

                        <div class="upload-area border border-2 border-primary rounded p-4 text-center mb-3"
                             id="uploadArea<?= $idSec ?>">
                          <i class="bi bi-cloud-arrow-up" style="font-size: 2rem;"></i>
                          <p class="mt-2 mb-0 text-muted">Arrastra tus imágenes o videos aquí<br>o haz clic para seleccionarlos</p>
                          <input type="file" name="imagenes[]" id="fileInput<?= $idSec ?>" accept="image/*,video/*" multiple hidden>
                        </div>

                        <div id="preview<?= $idSec ?>" class="d-flex flex-wrap gap-2 mb-3 preview-area"></div>

                        <!-- Botón Subir contenido -->
                        <button type="submit" class="btn btn-primary w-100" name="agregar_imagen">
                          <i class="bi bi-upload"></i> Subir contenido
                        </button>
                      </form>

                    </div> <!-- modal-body -->
                  </div> <!-- modal-content -->
                </div> <!-- modal-dialog -->
              </div> <!-- modal fade -->
            <?php
                $modales .= ob_get_clean();
              endwhile;
            ?>

          </ul>

          <!-- Imprimimos aquí fuera del <ul> todos los modales acumulados -->
          <?= $modales ?>

        <?php else: ?>
          <p class="text-muted">No hay secciones registradas aún.</p>
        <?php endif; ?>
      </div> <!-- card-body -->
    </div> <!-- card -->
  </div> <!-- listadoSecciones -->

  <!-- AGREGAR NUEVA SECCIÓN -->
  <div class="mt-4">
    <form method="POST" class="row g-2 justify-content-center">
      <div class="col-12 col-md-4">
        <input type="text" name="nombre" class="form-control" placeholder="Nombre de nueva galería" required>
      </div>
      <div class="col-12 col-md-2 text-center">
        <button class="btn btn-success w-100 btn-agregar-seccion" name="agregar_seccion">
          <i class="bi bi-plus-lg"></i> Agregar sección
        </button>
      </div>
    </form>
  </div>

</div> <!-- container -->

</main>

<!-- Modal Éxito: Imágenes subidas correctamente -->
<div class="modal fade" id="modalExitoSubir" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered modal-sm modal-md modal-lg">
    <div class="modal-content border-success">

      <div class="modal-header text-white justify-content-center position-relative"
           style="background: linear-gradient(135deg, #28a745 0%, #1f7a36 100%);">
        <h5 class="modal-title">Éxito</h5>
        <button type="button" class="btn-close btn-close-white position-absolute end-0 me-3"
                style="filter: invert(1);" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body d-flex flex-column flex-md-row align-items-center gap-3 text-center text-md-start">

        <i class="bi bi-check-circle-fill" style="font-size: 3.2rem; color:#28a745;"></i>

        <div>
          <p class="mb-0 fs-5">Imágenes y videos subidos correctamente.</p>
        </div>

      </div>

    </div>
  </div>
</div>

<!-- Modal Error: No imágenes seleccionadas para eliminar -->
<div class="modal fade" id="modalErrorEliminar" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered modal-sm modal-md modal-lg">
    <div class="modal-content border-danger">

      <div class="modal-header text-white justify-content-center position-relative"
           style="background: linear-gradient(135deg, #dc3545 0%, #b81f2e 100%);">
        <h5 class="modal-title">Error</h5>
        <button type="button" class="btn-close btn-close-white position-absolute end-0 me-3"
                style="filter: invert(1);" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body d-flex flex-column flex-md-row align-items-center gap-3 text-center text-md-start">

        <i class="bi bi-x-circle-fill"
           style="font-size: 3.2rem; color:#dc3545;"></i>

        <div>
          <p class="mb-0 fs-5">No has seleccionado ninguna imagen o video para eliminar.</p>
          <small class="text-muted">Selecciona al menos una imagen o video.</small>
        </div>

      </div>

    </div>
  </div>
</div>

<!-- Modal Error: No imágenes seleccionadas para eliminar -->
<div class="modal fade" id="modalErrorEliminar" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered modal-sm">
    <div class="modal-content border-danger">

      <div class="modal-header text-white justify-content-center position-relative"
           style="background: linear-gradient(135deg, #dc3545 0%, #b81f2e 100%);">
        <h5 class="modal-title">Error</h5>
        <button type="button" class="btn-close btn-close-white position-absolute end-0 me-3"
                data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body d-flex align-items-center gap-3 text-center">
        <i class="bi bi-x-circle-fill" style="font-size: 3.2rem; color:#dc3545;"></i>
        <div>
          <p class="mb-0 fs-5">No has seleccionado ningún archivo.</p>
          <small class="text-muted">Selecciona al menos uno.</small>
        </div>
      </div>

    </div>
  </div>
</div>

<!-- Modal Error: No imágenes seleccionadas para subir -->
<div class="modal fade" id="modalErrorSubir" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered modal-sm modal-md modal-lg">
    <div class="modal-content border-danger">

      <div class="modal-header text-white justify-content-center position-relative"
           style="background: linear-gradient(135deg, #dc3545 0%, #b81f2e 100%);">
        <h5 class="modal-title">Error</h5>
        <button type="button" class="btn-close btn-close-white position-absolute end-0 me-3"
                style="filter: invert(1);" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body d-flex flex-column flex-md-row align-items-center gap-3 text-center text-md-start">
        
        <i class="bi bi-x-circle-fill" style="font-size: 3.2rem; color:#dc3545;"></i>

        <div>
          <p class="mb-0 fs-5">No has seleccionado ninguna imagen o video para subir.</p>
          <small class="text-muted">Debes elegir al menos una imagen o video.</small>
        </div>

      </div>

    </div>
  </div>
</div>

<!-- Modal Éxito Sección -->
<div class="modal fade" id="modalSeccionExito" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered modal-sm modal-md modal-lg">
    <div class="modal-content border-success" 
         style="border-radius: 14px; animation: fadeIn .25s ease;">

      <!-- ENCABEZADO -->
      <div class="modal-header d-flex justify-content-center position-relative"
           style="background: linear-gradient(135deg, #28a745 0%, #1f7a36 100%);">

        <h5 class="modal-title text-white">Éxito</h5>

        <button type="button" class="btn-close btn-close-white position-absolute end-0 me-3"
                data-bs-dismiss="modal"></button>
      </div>

      <!-- CUERPO -->
      <div class="modal-body d-flex flex-column flex-md-row align-items-center gap-3 
                  text-center text-md-start justify-content-start">

        <i class="bi bi-check-circle-fill" style="font-size: 3.2rem; color:#28a745;"></i>

        <div>
          <p id="mensajeSeccionExito" class="mb-0 fs-5">Sección creada con éxito.</p>
        </div>

      </div>

    </div>
  </div>
</div>

<!-- Modal Mensaje Final al eliminar archivos correctamente -->
<div class="modal fade" id="modalMensaje" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered modal-sm modal-md modal-lg">
    <div class="modal-content border-success" 
         style="border-radius: 14px; animation: fadeIn .25s ease;">

      <div class="modal-header text-white justify-content-center position-relative"
           style="background: linear-gradient(135deg, #28a745 0%, #1f7a36 100%);">
        <h5 class="modal-title">Éxito</h5>

        <button type="button" class="btn-close btn-close-white position-absolute end-0 me-3"
                style="filter: invert(1);" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body d-flex flex-column flex-md-row align-items-center gap-3 
                  text-center text-md-start justify-content-start">

        <i class="bi bi-check-circle-fill" style="font-size: 3.2rem; color:#28a745;"></i>

        <div>
          <p id="mensajeTexto" class="mb-0 fs-5">Archivos eliminados correctamente.</p>
        </div>

      </div>
    </div>
  </div>
</div>

<!-- Modal Confirmación de Eliminación -->
<div class="modal fade" id="modalConfirmarEliminar" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content"
         style="border-radius: 12px; animation: fadeIn 0.25s ease; border: 2px solid #dc3545;">

      <!-- HEADER con degradado rojo -->
      <div class="modal-header text-white justify-content-center"
           style="background: linear-gradient(135deg, #dc3545, #ff6b6b); border-top-left-radius: 12px; border-top-right-radius: 12px;">
        <h5 class="modal-title w-100 text-center" style="font-weight: bold;">
          Confirmar eliminación
        </h5>
        <button type="button" class="btn-close btn-close-white position-absolute end-0 me-3"
                data-bs-dismiss="modal"></button>
      </div>

      <!-- CUERPO -->
      <div class="modal-body">
        <div class="d-flex align-items-center justify-content-start gap-3">
          <i class="bi bi-exclamation-octagon-fill" 
             style="font-size: 3.5rem; color:#dc3545;"></i>
          <div>
            <p id="mensajeEliminarSeccion" class="mb-1 fs-5 fw-semibold">¿Eliminar la sección y todo su contenido multimedia?</p>
            <small class="text-muted">Esta acción no se puede deshacer.</small>
          </div>
        </div>
      </div>

      <!-- FOOTER -->
      <div class="modal-footer" style="display: flex; justify-content: center; gap: 10px; border-top: none;">
        <button class="btn"
                data-bs-dismiss="modal"
                style="background: #ccc; color: #333; border: none; border-radius: 8px; padding: 8px 20px; font-weight: bold; cursor: pointer; transition: background 0.2s ease;"
                onmouseover="this.style.background='#b8b8b8'"
                onmouseout="this.style.background='#ccc'">
          Cancelar
        </button>

        <button id="btnEliminarConfirmado"
                style="background: linear-gradient(135deg, #dc3545, #ff6b6b); color: white; border: none; border-radius: 8px; padding: 8px 25px; font-weight: bold; cursor: pointer; box-shadow: 0 3px 8px rgba(0,0,0,0.2); transition: transform 0.2s ease, opacity 0.2s ease;"
                onmouseover="this.style.transform='scale(1.05)'"
                onmouseout="this.style.transform='scale(1)'">
          Eliminar
        </button>
      </div>

    </div>
  </div>
</div>

<!-- Modal Confirmación de Eliminación contenido -->
<div class="modal fade" id="modalConfirmarEliminarContenido" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content" style="border-radius: 12px; border:2px solid #dc3545;">
      
      <div class="modal-header text-white justify-content-center"
           style="background:linear-gradient(135deg,#dc3545,#ff6b6b);border-radius:12px 12px 0 0;">
        <h5 class="modal-title w-100 text-center">Confirmar eliminación</h5>
        <button type="button" class="btn-close btn-close-white position-absolute end-0 me-3"
                data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">

        <div class="d-flex align-items-center gap-3">
          <i class="bi bi-exclamation-octagon-fill" style="font-size:3.5rem;color:#dc3545;"></i>
          <div>
            <p id="mensajeEliminarContenido" class="mb-1 fs-5 fw-semibold">
              ¿Estás seguro de eliminar los archivos seleccionados?
            </p>
            <small class="text-muted">Esta acción no se puede deshacer.</small>
          </div>
        </div>

        <!-- ⚠️ AQUÍ DEBE ESTAR EL PREVIEW -->
        <div id="previewEliminar"
             class="d-flex flex-wrap justify-content-center gap-2 mt-3"></div>

      </div>

      <div class="modal-footer" style="justify-content:center;gap:10px;border-top:none;">
        <button class="btn" data-bs-dismiss="modal"
                style="background:#ccc;color:#333;border-radius:8px;padding:8px 20px;">
          Cancelar
        </button>

        <button id="btnEliminarContenido"
                style="background:linear-gradient(135deg,#dc3545,#ff6b6b);color:white;border-radius:8px;padding:8px 25px;">
          Eliminar
        </button>
      </div>

    </div>
  </div>
</div>

<!-- Modal Éxito Eliminación -->
<div class="modal fade" id="modalSeccionEliminada" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered modal-sm modal-md modal-lg">
    <div class="modal-content border-success" 
         style="border-radius: 14px; animation: fadeIn .25s ease;">

      <!-- HEADER -->
      <div class="modal-header text-white justify-content-center position-relative"
           style="background: linear-gradient(135deg, #28a745 0%, #1f7a36 100%);">
        <h5 class="modal-title">Éxito</h5>

        <button type="button" class="btn-close btn-close-white position-absolute end-0 me-3"
                style="filter: invert(1);" data-bs-dismiss="modal"></button>
      </div>

      <!-- BODY -->
      <div class="modal-body d-flex flex-column flex-md-row align-items-center gap-3 
                  text-center text-md-start justify-content-start">

        <i class="bi bi-check-circle-fill" style="font-size: 3.2rem; color:#28a745;"></i>

        <div>
          <p id="mensajeSeccionEliminadaTexto" class="mb-0 fs-5">Sección eliminada correctamente.</p>
        </div>

      </div>

    </div>
  </div>
</div>

<!-- Modal Éxito Renombrar Sección -->
<div class="modal fade" id="modalSeccionRenombrada" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered modal-sm modal-md modal-lg">
    <div class="modal-content border-success" style="border-radius: 14px; animation: fadeIn .25s ease;">

      <div class="modal-header text-white justify-content-center position-relative"
           style="background: linear-gradient(135deg, #28a745 0%, #1f7a36 100%);">
        <h5 class="modal-title">Éxito</h5>

        <button type="button" class="btn-close btn-close-white position-absolute end-0 me-3"
                style="filter: invert(1);" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body d-flex flex-column flex-md-row align-items-center gap-3 
                  text-center text-md-start justify-content-start">
        <i class="bi bi-check-circle-fill" style="font-size: 3.2rem; color:#28a745;"></i>

        <div>
          <p id="mensajeSeccionRenombradaTexto" class="mb-0 fs-5">Sección renombrada correctamente.</p>
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
  <!-- Scripts -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
  <script>AOS.init();</script>
  <script src="../js/admin_galeria.js?v=2"></script>
</body>
</html>