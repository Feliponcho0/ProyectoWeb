<?php
    require_once '../../validations/conection.php';

    $usuarios_id = (int)($_POST['usuarios_id'] ?? 0);
    $activo = (int)($_POST['activo'] ?? 0);

    $stmt = $conn->prepare("UPDATE usuarios SET activo = ?, updated_at = NOW() WHERE usuarios_id = ?");
    $stmt->bind_param("ii", $activo, $usuarios_id);

    if ($stmt->execute()) {
        echo json_encode(['ok' => true]);
    } else {
        echo json_encode(['ok' => false, 'msg' => 'Error al cambiar estado']);
    }
?>