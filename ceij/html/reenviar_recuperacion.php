<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';
include 'db.php';
include 'seguridad.php';

if (isset($_GET['token'])) {

    $token_antiguo = $_GET['token'];

    // Buscar al usuario con ese token
    $stmt = $conexion->prepare("SELECT id, email FROM usuarios WHERE token_recuperacion=?");
    $stmt->bind_param("s", $token_antiguo);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {

        $user = $resultado->fetch_assoc();

        // Nuevo token
        $nuevo_token = bin2hex(random_bytes(16));
        $nueva_expira = date("Y-m-d H:i:s", strtotime('+1 hour'));

        // Actualizar valores
        $update = $conexion->prepare("UPDATE usuarios SET token_recuperacion=?, token_expira=? WHERE id=?");
        $update->bind_param("ssi", $nuevo_token, $nueva_expira, $user['id']);
        $update->execute();

        // Nueva URL
        $url = "https://ceij.site/html/restablecer.php?token=". $nuevo_token;

        // Enviar correo
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
            $mail->Subject = 'Nuevo enlace para restablecer tu contraseña - CEIJ';
            $mail->Body = "
                <p>Hola,</p>
                <p>Tu enlace anterior para restablecer la contraseña ha expirado.</p>
                <p>Aquí tienes un nuevo enlace válido por 1 hora:</p>
                <p><a href='$url' style='color:blue; font-weight:bold;'>Restablecer contraseña</a></p>
                <br>
                <p>Soporte CEIJ</p>
            ";

            $mail->send();

            echo "<script>
                    alert('Te enviamos un nuevo enlace de recuperación. Revisa tu correo.');
                    window.location.href='login.html';
                  </script>";

        } catch (Exception $e) {
            echo "<script>
                    alert('Error al enviar el nuevo enlace. Intenta más tarde.');
                    window.location.href='login.html';
                  </script>";
        }

    } else {
        echo "<script>
                alert('El token no es válido.');
                window.location.href='login.html';
              </script>";
    }

} else {
    echo "<script>
            alert('Acceso no autorizado.');
            window.location.href='login.html';
          </script>";
}
?>
