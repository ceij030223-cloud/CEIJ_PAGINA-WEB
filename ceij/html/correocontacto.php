<?php
include 'db.php'; 
include 'seguridad.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php'; // Ajusta ruta si es necesario

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Limpiar datos
    $nombre  = trim($_POST['nombre']);
    $email   = trim($_POST['email']);
    $asunto  = trim($_POST['asunto']);
    $mensaje = trim($_POST['mensaje']);

    // Validaciones básicas
    if (empty($nombre) || empty($email) || empty($asunto) || empty($mensaje)) {
        echo "<script>alert('Todos los campos son obligatorios'); window.history.back();</script>";
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>alert('Correo electrónico no válido'); window.history.back();</script>";
        exit;
    }

    // Configurar PHPMailer
    $mail = new PHPMailer(true);

    try {
        // Servidor SMTP (Gmail)
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;

        // AQUÍ PONES TU CORREO Y CONTRASEÑA DE APLICACIÓN DE GMAIL
        $mail->Username   = 'ceij030223@gmail.com';
        $mail->Password   = 'tzwl faht kvsl ergz';

        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        // Remitente → quien llena el formulario
        $mail->setFrom('ceij030223@gmail.com', 'CEIJ – Atención al Cliente');
        $mail->addReplyTo($email, $nombre);

        // Destinatario → CEIJ
        $mail->addAddress('ceij030223@gmail.com', 'CEIJ - Contacto');

        // Contenido
        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8';
        $mail->Subject = $asunto;

        $mail->Body = "
            <div style='font-family: Arial, sans-serif; padding: 20px; background: #f5f5f5;'>
                <div style='background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);'>
                    <h2 style='color: #333333; margin-bottom: 10px;'><strong>Nuevo mensaje desde el formulario web</strong></h2>
                    <p style='font-size: 15px;'><strong>Nombre:</strong> $nombre</p>
                    <p style='font-size: 15px;'><strong>Correo:</strong> $email</p>
                    <p style='font-size: 15px;'><strong>Mensaje:</strong><br><br> $mensaje</p>
                </div>
            </div>
        ";


        // Enviar correo
        $mail->send();

        header("Location: contacto.php?estado=exito");
        exit;


    } catch (Exception $e) {

        header("Location: contacto.php?estado=error");
        exit;
    }
}
?>
