<?php
require 'db.php';
require 'seguridad.php';
// En tu consulta principal, haz un LEFT JOIN con progreso_cursos
$query = "SELECT u.*, 
                 pc.curso, 
                 pc.fecha_inicio, 
                 pc.fecha_finalizacion, 
                 pc.estado, 
                 pc.sucursal,
                 pc.certificado
          FROM usuarios u 
          LEFT JOIN progreso_cursos pc ON u.id = pc.usuario_id"; // u alias de usuario, pc alias de progreso_curso

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario_id = $_POST['usuario_id'] ?? '';
    $curso = $_POST['curso'] ?? '';
    $fecha_inicio = $_POST['fecha_inicio'] ?? '';
    $fecha_finalizacion = $_POST['fecha_finalizacion'] ?? '';
    $estado = $_POST['estado'] ?? '';
    $sucursal = $_POST['sucursal'] ?? '';
    
    // Validar datos
    if (empty($usuario_id) || empty($curso) || empty($fecha_inicio) || empty($estado)) {
        echo json_encode(['success' => false, 'message' => 'Datos incompletos']);
        exit;
    }
    
    // Manejar archivo de certificado
    $certificado_nombre = null;
    if (isset($_FILES['certificado']) && $_FILES['certificado']['error'] === UPLOAD_ERR_OK) {
        $certificado = $_FILES['certificado'];
        
        $certificado_nombre = $_FILES['certificado']['name'];
        $upload_dir = 'certificados/';
        
        if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }
    
    move_uploaded_file($certificado['tmp_name'], $upload_dir . $certificado_nombre);
    }








    
    
    // Insertar o actualizar en la tabla progreso_cursos
    $query = "INSERT INTO progreso_cursos (usuario_id, curso, fecha_inicio, fecha_finalizacion, estado, certificado, sucursal) 
              VALUES (?, ?, ?, ?, ?, ?, ?) 
              ON DUPLICATE KEY UPDATE 
              curso = VALUES(curso), 
              fecha_inicio = VALUES(fecha_inicio), 
              fecha_finalizacion = VALUES(fecha_finalizacion), 
              estado = VALUES(estado), 
              certificado = VALUES(certificado),
              sucursal = VALUES(sucursal)";
    
    $stmt = $conexion->prepare($query);
    $stmt->bind_param("issssss", $usuario_id, $curso, $fecha_inicio, $fecha_finalizacion, $estado, $certificado_nombre, $sucursal);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Datos guardados correctamente']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error en la base de datos: ' . $stmt->error]);
    }
    
    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
}

?>
