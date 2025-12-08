<?php
session_start();
include 'db.php';
include 'seguridad.php';
// Inicializar contador de intentos si no existe
if (!isset($_SESSION['intentos_login'])) {
    $_SESSION['intentos_login'] = 0;
    $_SESSION['ultimo_intento'] = 0;
}

// Bloqueo temporal si excedió intentos
$tiempo_espera = 120; // 2 minutos
if ($_SESSION['intentos_login'] >= 3) {
    $segundos_pasados = time() - $_SESSION['ultimo_intento'];
    if ($segundos_pasados < $tiempo_espera) {
        $restante = $tiempo_espera - $segundos_pasados;
        $minutos = floor($restante / 60);
        $segundos = $restante % 60;

        $mensaje = "Vuelve a iniciar sesión dentro de ";
        if ($minutos > 0) {
            $mensaje .= $minutos . " minuto" . ($minutos > 1 ? "s" : "");
            if ($segundos > 0) $mensaje .= " con ";
        }
        if ($segundos > 0) {
            $mensaje .= $segundos . " segundo" . ($segundos > 1 ? "s" : "");
        }

        echo "<script>
            alert('Te quedaste sin intentos. $mensaje.');
            window.history.back();
        </script>";
        exit;
    } else {
        // Reiniciar contador después del tiempo de espera
        $_SESSION['intentos_login'] = 0;
    }
}

// Si ya hay sesión activa, redirige según rol
if (isset($_SESSION['usuario_id']) && $_SESSION['usuario_id'] > 0) {
    $rol_actual = $_SESSION['rol'] ?? '';
    $destino = 'index.php'; // default

    if ($rol_actual === 'administrador') {
        $destino = 'admin_index.php';
    } elseif ($rol_actual === 'usuario') {
        $destino = 'index.php';
    }

    echo "<script>
        alert('Ya tienes una sesión activa. Cierra sesión primero para iniciar con otra cuenta.');
        window.location.href = '$destino';
    </script>";
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email    = $_POST['email'];
    $password = $_POST['password'];

    // Buscar usuario
    $sql = "SELECT * FROM usuarios WHERE email = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $resultado = $stmt->get_result();

    $login_exitoso = false;

    if ($resultado->num_rows > 0) {
        $usuario = $resultado->fetch_assoc();

        // ✅ Validar si la cuenta está activa
        if ($usuario['activo'] == 0) {
            echo "<script>
                alert('Tu cuenta aún no ha sido verificada. Revisa tu correo electrónico para activarla.');
                window.history.back();
            </script>";
            exit;
        }

        // Verificar contraseña
        if (password_verify($password, $usuario['password'])) {
            $login_exitoso = true;
        }
    }

    if ($login_exitoso) {
        // Resetear intentos al iniciar sesión correctamente
        $_SESSION['intentos_login'] = 0;
        session_regenerate_id(true); // Evitar session fixation
        $_SESSION['usuario_id'] = $usuario['id'];
        $_SESSION['rol'] = $usuario['rol'];
        $_SESSION['nombre'] = $usuario['nombre'];

        // Redirigir según rol
        switch ($usuario['rol']) {
            case 'administrador':
                header("Location: perfiladmin.php");
                exit;
            case 'usuario':
                header("Location: perfil.php");
                exit;
            default:
                header("Location: login.php");
                exit;
        }
    } else {
        // Fallo de login: aumentar contador
        $_SESSION['intentos_login']++;
        $_SESSION['ultimo_intento'] = time();

        $intentos_restantes = 3 - $_SESSION['intentos_login'];

        if ($intentos_restantes > 0) {
            // ✅ Corregido: singular/plural de "intento"
            $texto_intento = $intentos_restantes === 1 ? 'intento' : 'intentos';
            echo "<script>
                alert('Correo o contraseña incorrectos. Te queda $intentos_restantes $texto_intento.');
                window.history.back();
            </script>";
        } else {
            echo "<script>
                alert('Te quedaste sin intentos. Vuelve a iniciar sesión dentro de 2 minutos.');
                window.history.back();
            </script>";
        }
        exit;
    }
}
?>