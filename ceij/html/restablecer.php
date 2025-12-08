<?php
include 'db.php';
include 'seguridad.php';
$token = $_POST['token'] ?? $_GET['token'];

if (!$token) {
    http_response_code(400);
    echo "token_invalido";
    exit;
}

$stmt = $conexion->prepare("SELECT id, email, token_expira FROM usuarios WHERE token_recuperacion=?");
$stmt->bind_param("s", $token);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows === 0) {
    echo "token_invalido";
    exit;
}

$usuario = $resultado->fetch_assoc();
$expira = strtotime($usuario['token_expira']);

if ($expira < time()) {
    echo "<script>
            if (confirm('El enlace de recuperación ha expirado. ¿Deseas que te enviemos uno nuevo?')) {
                window.location.href='reenviar_recuperacion.php?token=$token';
            } else {
                window.location.href='login.html';
            }
          </script>";
    exit;
}

// Si envían nueva contraseña
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $pass = $_POST['password'];
    $confirmar = $_POST['confirmar'];

    if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,15}$/', $pass)) {
        echo "formato_invalido";
        exit;
    }

    if ($pass !== $confirmar) {
        echo "no_coinciden";
        exit;
    }

    $passHash = password_hash($pass, PASSWORD_DEFAULT);
    $update = $conexion->prepare("UPDATE usuarios SET password=?, token_recuperacion=NULL, token_expira=NULL WHERE id=?");
    $update->bind_param("si", $passHash, $usuario['id']);

    if ($update->execute()) {
        if ($update->affected_rows > 0) {
            echo "success";
        } else {
            echo "no_update";
        }
    } else {
        echo "error";
    }

    $update->close();
    $conexion->close();
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restablecer contraseña</title>
    <link rel="stylesheet" href="../css/restablecer.css?v=1">
    <link rel="icon" href="../icon/ceijicon.ico" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

<div class="contenedor ">

    <div class="form-caja">
        <h1>Restablecer contraseña</h1>
        <p>Estás a punto de restablecer la contraseña de tu cuenta.</p>
		<form action="" method="POST" id="formRecuperacion" autocomplete="off" > <!--id="formrestablecer"-->
      <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
            <div class="inputss">
            <input type="password" name="password" minlength="8" maxlength="15" placeholder="Contraseña" required>
             </div>

             <div class="inputss">
             <input type="password" name="confirmar" minlength="8" maxlength="15" placeholder="Confirmar contraseña " required>
             </div>
            
             <div class="btn-modificar">
                <button type="button" id="btnModificar" style="color: white;">Modificar</button>
             </div>
			
		  </form>    
        </div>
    </div>
		


<div class="alertageneral">
<div class="alertcont">
<i class="fa-solid fa-triangle-exclamation icon" style="color: #FFD43B;"></i> 
<div class="alerta">
<h1 >¿Estás seguro de restablecer la contraseña?</h1>
 <button type="button" class="btn-confirmar" >Confirmar</button>
    <button type="button" class="btn-cancelar">Cancelar</button>
</div>
</div>

</div>
<script src="../js/restablecer.js?v=1.7"></script>
</body>
</html>