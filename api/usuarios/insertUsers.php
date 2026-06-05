<?php
    require_once '../../validations/conection.php';

    header('Content-Type: application/json');

    $nombre_usuario = trim($_POST['nombre_usuario'] ?? '');
    $password = $_POST['password'] ?? '';
    $correo = trim($_POST['correo'] ?? '');
    $rol = $_POST['rol'] ?? '';
    $tiendas_id = intval($_POST['tiendas_id'] ?? 0);

    if (empty($nombre_usuario) || empty($password) || empty($rol) || $tiendas_id <= 0) {
        echo json_encode(['ok' => false, 'msg' => 'Datos incompletos. Debes asignar una tienda válida.']);
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

    $query = "INSERT INTO usuarios (nombre_usuario, password, correo, rol, activo, created_at, updated_at, tiendas_id) VALUES (?, ?, ?, ?, 1, NOW(), NOW(), ?)";

    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssssi", $nombre_usuario, $hashed_password, $correo, $rol, $tiendas_id);

    if ($stmt->execute()) {
        echo json_encode(['ok' => true, 'msg' => 'Usuario registrado exitosamente']);
    } else {
        echo json_encode(['ok' => false, 'msg' => 'Error al insertar en la base de datos: ' . $conn->error]);
    }
?>