<?php
    require_once '../../validations/check.php';
    require_once '../../validations/conection.php';

    header('Content-Type: application/json');

    // Verificar que el usuario está logueado
    require_login();

    $id_usuario = $_SESSION['id_usuario'];
    $password_actual = $_POST['password_actual'] ?? '';
    $password_nueva = $_POST['password_nueva'] ?? '';

    if (empty($password_actual) || empty($password_nueva)) {
        echo json_encode(['ok' => false, 'msg' => 'Todos los campos son obligatorios']);
        exit;
    }

    if (strlen($password_nueva) < 6) {
        echo json_encode(['ok' => false, 'msg' => 'La nueva contraseña debe tener al menos 6 caracteres']);
        exit;
    }

    $stmt = $conn->prepare("SELECT password FROM usuarios WHERE usuarios_id = ?");
    $stmt->bind_param("i", $id_usuario);
    $stmt->execute();
    $result = $stmt->get_result();
    $usuario = $result->fetch_assoc();

    if (!$usuario) {
        echo json_encode(['ok' => false, 'msg' => 'Usuario no encontrado']);
        exit;
    }

    if (!password_verify($password_actual, $usuario['password'])) {
        echo json_encode(['ok' => false, 'msg' => 'La contraseña actual es incorrecta']);
        exit;
    }

    $nueva_hashed = password_hash($password_nueva, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("UPDATE usuarios SET password = ? WHERE usuarios_id = ?");
    $stmt->bind_param("si", $nueva_hashed, $id_usuario);

    if ($stmt->execute()) {
        echo json_encode(['ok' => true, 'msg' => 'Contraseña actualizada correctamente']);
    } else {
        echo json_encode(['ok' => false, 'msg' => 'Error al actualizar la contraseña']);
    }
?>