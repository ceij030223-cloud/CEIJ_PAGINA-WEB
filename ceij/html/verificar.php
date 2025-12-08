<?php
include 'db.php';
include 'seguridad.php';
if(isset($_GET['token'])){
    $token = $_GET['token'];

    $stmt = $conexion->prepare("SELECT id, token_expira_verificacion FROM usuarios WHERE token_verificacion=? AND activo=0");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if($resultado->num_rows > 0){
        $user = $resultado->fetch_assoc();

        // Depuración de fechas
        // echo "Token expira: " . $user['token_expira_verificacion'] . "<br>";
        // echo "Hora actual: " . date("Y-m-d H:i:s") . "<br>";
        // echo "strtotime token: " . strtotime($user['token_expira_verificacion']) . "<br>";
        // echo "time(): " . time() . "<br>";

        if (strtotime($user['token_expira_verificacion']) >= time()) {
            // Activar cuenta
            $update = $conexion->prepare(
                "UPDATE usuarios 
                SET activo=1, token_verificacion=NULL, token_expira_verificacion=NULL 
                WHERE id=?"
            );
            $update->bind_param("i", $user['id']);

            if ($update->execute()) {
                echo "<script>alert('Cuenta activada correctamente. Ahora puedes iniciar sesión.'); window.location.href='login.html';</script>";
            } else {
                echo "Error al activar la cuenta: " . $update->error;
            }

        } else {
            // Enlace expirado → Mostrar opción para reenviar
            echo "
            <script>
                if (confirm('El enlace de verificación ha expirado. ¿Deseas que te enviemos uno nuevo?')) {
                    window.location.href='reenviar_verificacion.php?token=" . $token . "';
                } else {
                    window.location.href='login.html';
                }
            </script>";
        }
    } else {
        echo "<script>alert('Token inválido.'); window.location.href='login.html';</script>";
    }

} else {
    echo "<script>alert('Acceso inválido.'); window.location.href='login.html';</script>";
}
?>