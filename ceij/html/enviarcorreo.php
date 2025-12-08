<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require '../vendor/autoload.php';
include 'db.php';
include 'seguridad.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $correoDestino = $_POST['correoDestino'];
    $asunto = $_POST['asunto'];
    $comentario = $_POST['comentario'];
    
    $mail = new PHPMailer(true);
    
    try {
        // Configuración SMTP única (Gmail) que envía a TODOS los proveedores
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'ceij030223@gmail.com'; // correo Gmail
        $mail->Password = 'tzwl faht kvsl ergz'; // Contraseña de aplicación Gmail
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;
        
        // Configuración SSL para compatibilidad
        $mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );
        
        // Remitente y destinatario
        $mail->setFrom('ceij030223@gmail.com', 'Soporte CEIJ');
        $mail->addAddress($correoDestino); // Puede ser cualquier dominio
        
        // Adjuntar imagen si existe
        if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
            $mail->addAttachment($_FILES['imagen']['tmp_name'], $_FILES['imagen']['name']);
        }
        
        // Contenido del correo
        $mail->isHTML(true);
        $mail->Subject = $asunto;
        $mail->Body = nl2br(htmlspecialchars($comentario));
        $mail->AltBody = strip_tags($comentario);
        $mail->CharSet = 'UTF-8';
          if ($mail->send()) {
            // Verificar si tenemos un ID de usuario para eliminar
             if (isset($_POST['usuario_id']) && !empty($_POST['usuario_id'])) {
        $usuario_id_eliminar = $_POST['usuario_id'];
                // Eliminar el usuario de la base de datos
                $sql_eliminar = "DELETE FROM usuarios WHERE id = ?";
                $stmt = $conexion->prepare($sql_eliminar);
                $stmt->bind_param("i", $usuario_id_eliminar);
                
                if ($stmt->execute()) {
                    echo json_encode(['success' => true, 'message' => 'Correo enviado y usuario eliminado correctamente']);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Correo enviado pero error al eliminar usuario']);
                }
            } else {
                echo json_encode(['success' => true, 'message' => 'Correo enviado correctamente (sin eliminación)']);
            }
        }
        
        
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Error al enviar: ' . $mail->ErrorInfo]);
    }
}
?>