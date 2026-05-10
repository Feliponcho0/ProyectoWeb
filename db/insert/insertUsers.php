<?php
    require_once '../../validations/conection.php';

    $nombre_usuario = trim($_POST['nombre_usuario'] ?? '');
    $password = $_POST['password'] ?? '';
    $correo = trim($_POST['correo'] ?? '');
    $rol = $_POST['rol'] ?? '';

    if (empty($nombre_usuario) || empty($password) || empty($rol)) {
        echo json_encode(['ok' => false, 'msg' => 'Datos incompletos']);
        exit;
    }
    
    $check = $conn->prepare("SELECT usuarios_id FROM usuarios WHERE nombre_usuario = ?");
    $check->bind_param("s", $nombre_usuario);
    $check->execute();
    if ($check->get_result()->num_rows > 0) {
        echo json_encode(['ok' => false, 'msg' => 'El nombre de usuario ya existe']);
        exit;
    }

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO usuarios (nombre_usuario, password, correo, rol, activo, created_at, updated_at) VALUES (?, ?, ?, ?, 1, NOW(), NOW())");
    $stmt->bind_param("ssss", $nombre_usuario, $hashed_password, $correo, $rol);

    if ($stmt->execute()) {
        echo json_encode(['ok' => true]);
    } else {
        echo json_encode(['ok' => false, 'msg' => 'Error al insertar: ' . $conn->error]);
    }
?>