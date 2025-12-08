<?php
include 'db.php';
include 'seguridad.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_SESSION['usuario_id'];
    $nombre = trim($_POST['nombre']);
    $apellido = trim($_POST['apellido']);
    $email = trim($_POST['email']);
    $telefono = trim($_POST['telefono']);

    // Validar que el nombre y apellido solo contengan letras, espacios, tildes y ñ
    $patron = "/^[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+$/u";

    if (!preg_match($patron, $nombre)) {
        echo json_encode(['success' => false, 'message' => 'El nombre solo puede contener letras']);
        exit;
    }

    if (!preg_match($patron, $apellido)) {
        echo json_encode(['success' => false, 'message' => 'El apellido solo puede contener letras']);
        exit;
    }

    // Corregir formato: primera letra de cada palabra en mayúscula, resto en minúscula
    $nombre = mb_convert_case(mb_strtolower($nombre, 'UTF-8'), MB_CASE_TITLE, 'UTF-8');
    $apellido = mb_convert_case(mb_strtolower($apellido, 'UTF-8'), MB_CASE_TITLE, 'UTF-8');

    // Validar email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['success' => false, 'message' => 'Email no válido']);
        exit;
    }

    // Validar teléfono
    if (strlen($telefono) !== 10 || !ctype_digit($telefono)) {
        echo json_encode(['success' => false, 'message' => 'El teléfono debe tener exactamente 10 dígitos numéricos']);
        exit;
    }

    // Actualizar datos en la base de datos
    $sql = "UPDATE usuarios SET nombre = ?, apellido = ?, email = ?, telefono = ? WHERE id = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("ssssi", $nombre, $apellido, $email, $telefono, $id);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Perfil actualizado correctamente']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al actualizar los datos en la base de datos']);
    }
}
?>
