<?php
require 'db.php';
require 'seguridad.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario_id = $_POST['usuario_id'] ?? '';
    $nuevo_rol = $_POST['rol'] ?? '';
    
    // Validar datos
    if (empty($usuario_id) || empty($nuevo_rol)) {
        echo json_encode(['success' => false, 'message' => 'Datos incompletos']);
        exit;
    }
    
    // Validar que el rol sea válido
    $roles_permitidos = ['administrador', 'usuario'];
    if (!in_array($nuevo_rol, $roles_permitidos)) {
        echo json_encode(['success' => false, 'message' => 'Rol no válido']);
        exit;
    }
    
    // Actualizar el rol en la base de datos
    $query = "UPDATE usuarios SET rol = ? WHERE id = ?";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param("si", $nuevo_rol, $usuario_id);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Rol actualizado correctamente']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error en la base de datos: ' . $stmt->error]);
    }
    
    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
}
?>