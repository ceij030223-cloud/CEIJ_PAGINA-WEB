<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';
include 'db.php';
include 'seguridad.php';
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Validar campo 'email'
    if (!isset($_POST['email']) || empty(trim($_POST['email']))) {
        echo "<script>
                alert('Por favor ingresa un correo electrónico.');
                window.history.back();
              </script>";
        exit;
    }

    $email = trim($_POST['email']);

    // Validar formato de correo
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>
                alert('Por favor ingresa un correo válido.');
                window.history.back();
              </script>";
        exit;
    }

    // Buscar usuario
    $stmt = $conexion->prepare("SELECT id, token_expira FROM usuarios WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        $usuario = $resultado->fetch_assoc();

        // Si ya tiene un token vigente (no expirado), no generar otro
        if (!empty($usuario['token_expira']) && strtotime($usuario['token_expira']) > time()) {
            echo "<script>
                    alert('Ya se envió un enlace de recuperación. Revisa tu correo o espera que expire para solicitar uno nuevo.');
                    window.history.back();
                  </script>";
            exit;
        }

        // Generar nuevo token y fecha de expiración
        $token = bin2hex(random_bytes(16));
        $expira = date("Y-m-d H:i:s", strtotime('+1 hour'));

        $update = $conexion->prepare("UPDATE usuarios SET token_recuperacion=?, token_expira=? WHERE email=?");
        $update->bind_param("sss", $token, $expira, $email);
        $update->execute();

        // URL de recuperación
        $url = "https://ceij.site/html/restablecer.php?token=" . $token;

        // Configurar PHPMailer
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
            $mail->addAddress($email);

            $mail->isHTML(true);
            $mail->CharSet = 'UTF-8';
            $mail->Subject = 'Restablece tu contraseña - CEIJ';
            $mail->Body = "
                <p>Hola,</p>
                <p>Has solicitado restablecer tu contraseña. Haz clic en el siguiente enlace para continuar:</p>
                <p><a href='$url' style='color:blue; font-weight:bold;'>Restablecer contraseña</a></p>
                <p>Este enlace expirará en 1 hora.</p>
                <p>Si no solicitaste esto, ignora este mensaje.</p>
                <br>
                <p><b>Soporte CEIJ</b></p>
            ";

            $mail->send();

            echo "<script>
                    alert('Se ha enviado un correo de recuperación. Revisa tu bandeja de entrada o spam.');
                    window.history.back();
                  </script>";
        } catch (Exception $e) {
            echo "<script>
                    alert('Error al enviar el correo: {$mail->ErrorInfo}');
                    window.history.back();
                  </script>";
        }
    } else {
        echo "<script>
                alert('El correo no está registrado en el sistema.');
                window.history.back();
              </script>";
    }
}
?>
