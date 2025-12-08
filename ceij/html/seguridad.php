<?php
session_start();

// Variables globales
$logueado = false;
$admin = false;
$usuario_nombre = "";

// Si hay sesión activa
if(isset($_SESSION['usuario_id']) && $_SESSION['usuario_id'] > 0){
    $logueado = true;
    $usuario_nombre = $_SESSION['nombre'] ?? "";
    $admin = ($_SESSION['rol'] ?? "") === "administrador";
}

// Obtiene el nombre del archivo actual
$pagina_actual = basename($_SERVER['PHP_SELF']);

// Listas de páginas por tipo de usuario
$solo_admin = [
    'admin_index.php','admin_cursos.php','admin_practicas.php',
    'admin_galeria.php','perfiladmin.php','panelcursos.php','paneladmins.php'
];
$solo_registrado = ['perfil.php'];

// 🔒 Si NO está logueado y quiere entrar a zonas protegidas
if(!$logueado && (in_array($pagina_actual, $solo_admin) || in_array($pagina_actual, $solo_registrado))){
    echo "<script>
        alert('Debes iniciar sesión para ver esta página.');
        window.location.href='login.html';
    </script>";
    exit;
}

// 🚫 Si un usuario normal intenta acceder a zonas de admin
if($logueado && !$admin && in_array($pagina_actual, $solo_admin)){
    echo "<script>
        alert('Acceso no autorizado. Esta sección es solo para administradores.');
        window.location.href='index.php';
    </script>";
    exit;
}

// Si un ADMIN entra a páginas de usuario, se rediríge a sus equivalentes
$redirecciones_admin = [
    'index.php' => 'admin_index.php',
    'cursos.php' => 'admin_cursos.php',
    'areadepracticas.php' => 'admin_practicas.php',
    'perfil.php' => 'perfiladmin.php'
];

if($logueado && $admin && array_key_exists($pagina_actual, $redirecciones_admin)){
    header("Location: " . $redirecciones_admin[$pagina_actual]);
    exit;
}
?>
