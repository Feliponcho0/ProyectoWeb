<?php
    require_once '../../validations/conection.php';

    $usuarios_id = (int)($_POST['usuarios_id'] ?? 0);
    $nombre_usuario = trim($_POST['nombre_usuario'] ?? '');
    $password = $_POST['password'] ?? '';
    $correo = trim($_POST['correo'] ?? '');
    $rol = $_POST['rol'] ?? '';

    if ($usuarios_id <= 0 || empty($nombre_usuario) || empty($rol)) {
        echo json_encode(['ok' => false, 'msg' => 'Datos incompletos']);
        exit;
    }

    if (!empty($password)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $sql = "UPDATE usuarios SET nombre_usuario = ?, password = ?, correo = ?, rol = ?, updated_at = NOW() WHERE usuarios_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssi", $nombre_usuario, $hashed_password, $correo, $rol, $usuarios_id);
    } else {
        $sql = "UPDATE usuarios SET nombre_usuario = ?, correo = ?, rol = ?, updated_at = NOW() WHERE usuarios_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssi", $nombre_usuario, $correo, $rol, $usuarios_id);
    }

    if ($stmt->execute()) {
        echo json_encode(['ok' => true]);
    } else {
        echo json_encode(['ok' => false, 'msg' => 'Error al actualizar']);
    }
?>