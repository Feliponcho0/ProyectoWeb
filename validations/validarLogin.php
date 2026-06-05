<?php
    require_once 'check.php'; 
    require_once 'conection.php';

    session_start();
    // Limpiar sesión anterior si existe
    if (isset($_SESSION['id_usuario'])) {
        session_unset();
        session_destroy();
        session_start();
    }

    $nombre_usuario = trim($_POST['nombre_usuario'] ?? '');
    $contra = $_POST['password'] ?? '';

    if (empty($nombre_usuario) || empty($contra)) {
        header('Location: ../index.php?error=1');
        exit();
    }

    // Buscar el usuario q trata de logiar
    $query = "SELECT usuarios_id, rol, tiendas_id, password, correo, activo FROM usuarios WHERE nombre_usuario = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $nombre_usuario);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows === 0) {
        header('Location: ../index.php?error=1');
        exit();
    }

    $fila = $resultado->fetch_assoc();

    // Verificar que esté activo
    if ($fila['activo'] != 1) {
        header('Location: ../index.php?error=4');
        exit();
    }
    
    $password_valida = false;
    if ($contra === $fila['password'] || password_verify($contra, $fila['password'])) {
        $password_valida = true;
    }

    if (!$password_valida) {
        header('Location: ../index.php?error=1');
        exit();
    }

    // Verificar tienda para no administradores
    if ($fila['rol'] !== 'Administrador' && ($fila['tiendas_id'] === null || $fila['tiendas_id'] <= 0)) {
        header('Location: ../index.php?error=5');
        exit();
    }

    // Crear sesión
    $_SESSION['id_usuario'] = $fila['usuarios_id'];
    $_SESSION['rol'] = $fila['rol'];
    $_SESSION['tiendas_id'] = $fila['tiendas_id'];
    $_SESSION['nombre_usuario'] = $nombre_usuario;
    $_SESSION['correo'] = $fila['correo'];
    $_SESSION['activo'] = $fila['activo'];

    header('Location: ../pages/dashboard.php');
    exit();
?>
