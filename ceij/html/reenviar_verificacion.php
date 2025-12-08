<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require '../vendor/autoload.php';
include 'db.php';
include 'seguridad.php';
if (isset($_GET['token'])) {
    $token_antiguo = $_GET['token'];

    // Buscar usuario que tenga ese token y aún no esté activo
    $stmt = $conexion->prepare("SELECT id, email, nombre FROM usuarios WHERE token_verificacion=? AND activo=0");
    $stmt->bind_param("s", $token_antiguo);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        $user = $resultado->fetch_assoc();

        // Nuevo token y nueva expiración
        $nuevo_token = bin2hex(random_bytes(16));
        $nueva_expira = date("Y-m-d H:i:s", strtotime('+1 hour'));

        // Actualizar en la BD
        $update = $conexion->prepare("UPDATE usuarios SET token_verificacion=?, token_expira_verificacion=? WHERE id=?");
        $update->bind_param("ssi", $nuevo_token, $nueva_expira, $user['id']);
        $update->execute();

        // Enviar correo de verificación
        $url = "https://ceij.site/html/verificar.php?token=". $nuevo_token;

        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'ceij030223@gmail.com';
            $mail->Password   = 'tzwl faht kvsl ergz';
            $mail->SMTPSecure = 'tls';
            $mail->Port       = 587;

            $mail->setFrom('ceij030223@gmail.com', 'Soporte CEIJ');
            $mail->addAddress($user['email']);

            $mail->isHTML(true);
            $mail->CharSet = 'UTF-8';
            $mail->Subject = 'Nuevo enlace de verificación - CEIJ';
            $mail->Body = "Hola {$user['nombre']},<br><br>Tu anterior enlace expiró.<br>
                           Aquí tienes uno nuevo para activar tu cuenta:<br>
                           <a href='$url'>$url</a><br><br>Este enlace expira en 1 hora.";

            $mail->send();

            echo "<script>alert('Se ha enviado un nuevo enlace de verificación. Revisa tu correo.'); window.location.href='login.html';</script>";
        } catch (Exception $e) {
            echo "<script>alert('Error al reenviar el enlace. Intenta más tarde.'); window.location.href='login.html';</script>";
        }

    } else {
        echo "<script>alert('El token no es válido o la cuenta ya está activa.'); window.location.href='login.html';</script>";
    }
} else {
    echo "<script>alert('Acceso no autorizado.'); window.location.href='login.html';</script>";
}
?>