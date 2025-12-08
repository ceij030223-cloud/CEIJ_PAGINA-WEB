<?php 
include 'db.php'; 
include 'seguridad.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require '../vendor/autoload.php';
if ($_SERVER["REQUEST_METHOD"]=="POST") {

    // Recibir y limpiar datos
    $nombre    = trim($_POST['nombre']);
    $apellido  = trim($_POST['apellido']);
    $email     = trim($_POST['email']);
    $telefono  = trim($_POST['telefono']);
    $password  = $_POST['password'];
    $confirmar = $_POST['confirmar'];

    // Validaciones (igual que antes)
    if (empty($nombre) || empty($apellido) || empty($email) || empty($telefono) || empty($password) || empty($confirmar)) {
        echo "<script>alert('Todos los campos son obligatorios'); window.history.back();</script>";
        exit;
    }
    if (!preg_match("/^[a-zA-ZáéíóúÁÉÍÓÚñÑ ]+$/u", $nombre)) {
        echo "<script>alert('El nombre solo puede contener letras'); window.history.back();</script>";
        exit;
    }
    if (!preg_match("/^[a-zA-ZáéíóúÁÉÍÓÚñÑ ]+$/u", $apellido)) {
        echo "<script>alert('El apellido solo puede contener letras'); window.history.back();</script>";
        exit;
    }

    $nombre   = mb_convert_case($nombre, MB_CASE_TITLE, "UTF-8");
    $apellido = mb_convert_case($apellido, MB_CASE_TITLE, "UTF-8");

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>alert('Correo electrónico no válido'); window.history.back();</script>";
        exit;
    }
    if (!preg_match("/^[0-9]{10}$/", $telefono)) {
        echo "<script>alert('Teléfono no válido. Debe contener 10 números'); window.history.back();</script>";
        exit;
    }

    // Verificar si email ya existe
    $checkEmail = $conexion->prepare("SELECT id FROM usuarios WHERE email=?");
    $checkEmail->bind_param("s", $email);
    $checkEmail->execute();
    $checkEmail->store_result();
    if ($checkEmail->num_rows > 0) {
        echo "<script>alert('Este correo ya está registrado'); window.history.back();</script>";
        exit;
    }

    // Verificar si teléfono ya existe
    $checkTel = $conexion->prepare("SELECT id FROM usuarios WHERE telefono=?");
    $checkTel->bind_param("s", $telefono);
    $checkTel->execute();
    $checkTel->store_result();

    if (!preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W]).{8,15}$/", $password)) {
        echo "<script>alert('La contraseña debe incluir al menos una mayúscula, una minúscula, un número y un carácter especial'); window.history.back();</script>";
        exit;
    }

    if($password !== $confirmar){
        echo "<script>alert('Las contraseñas no coinciden'); window.history.back();</script>";
        exit;
    }

    $hash = password_hash($password, PASSWORD_DEFAULT);

    // Generar token de verificación
    $token = bin2hex(random_bytes(16));
    $expira = date("Y-m-d H:i:s", strtotime('+1 hour'));

    // Insertar usuario como inactivo
    $sql ="INSERT INTO usuarios (nombre, apellido, email, telefono, password, rol, token_verificacion, token_expira_verificacion, activo)
           VALUES (?,?,?,?,?,'usuario',?,?,0)";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("sssssss", $nombre, $apellido, $email, $telefono, $hash, $token, $expira);

    if($stmt->execute()){
        // Enviar correo de verificación
        $url = "https://ceij.site/html/verificar.php?token=".$token;

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
            $mail->Subject = 'Confirma tu cuenta CEIJ';
            $mail->Body = "Hola,<br><br>Haz clic en el siguiente enlace para activar tu cuenta:<br>
                           <a href='$url'>$url</a><br><br>Este enlace expira en 1 hora.";

            $mail->send();

            echo "<script>alert('Registro exitoso. Revisa tu correo para activar tu cuenta.'); window.location.href='login.html';</script>";
            exit;

        } catch (Exception $e) {
            echo "<script>alert('Error al enviar correo de verificación.'); window.history.back();</script>";
            exit;
        }

    } else {
        echo "Error: " .$stmt->error;
    }

    $stmt->close();
    $checkEmail->close();
    $checkTel->close();
}
?>
