<?php
    require_once 'check.php'; 
    require_once 'conection.php';

    session_start();
    
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

    // buscar el usuario que trata de loguearse
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

    // verificar que el usuario esté activo
    if ($fila['activo'] != 1) {
        header('Location: ../index.php?error=4');
        exit();
    }
    
    // verificar contraseña
    $password_valida = false;
    if ($contra === $fila['password'] || password_verify($contra, $fila['password'])) {
        $password_valida = true;
    }

    if (!$password_valida) {
        header('Location: ../index.php?error=1');
        exit();
    }

    // verificar tienda 
    if ($fila['rol'] !== 'Administrador') {
        // Verificar que tenga tienda asignada
        if ($fila['tiendas_id'] === null || $fila['tiendas_id'] <= 0) {
            header('Location: ../index.php?error=5');
            exit();
        }
        
        // verificar que la tienda esté activa
        $stmtTienda = $conn->prepare("SELECT activo FROM tiendas WHERE tiendas_id = ?");
        $stmtTienda->bind_param("i", $fila['tiendas_id']);
        $stmtTienda->execute();
        $resultTienda = $stmtTienda->get_result();
        $tienda = $resultTienda->fetch_assoc();
        
        // tienda inactiva verifica si exitse
        if (!$tienda || $tienda['activo'] != 1) {
            header('Location: ../index.php?error=5');
            exit();
        }
    }

    $_SESSION['id_usuario'] = $fila['usuarios_id'];
    $_SESSION['rol'] = $fila['rol'];
    $_SESSION['tiendas_id'] = $fila['tiendas_id'];
    $_SESSION['nombre_usuario'] = $nombre_usuario;
    $_SESSION['correo'] = $fila['correo'];
    $_SESSION['activo'] = $fila['activo'];

    header('Location: ../pages/dashboard.php');
    exit();
?>
