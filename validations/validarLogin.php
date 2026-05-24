<?php
    require_once 'check.php'; 
    require_once 'conection.php';

    $nombre_usuario = $_POST['nombre_usuario'] ?? '';
    $contra = $_POST['password'] ?? '';
    
    $query = "SELECT usuarios_id, rol, tiendas_id, password, correo, activo FROM usuarios WHERE nombre_usuario = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $nombre_usuario);
    $stmt->execute();
    $resultado = $stmt->get_result();
    if ($resultado->num_rows > 0) {
        $fila = $resultado->fetch_assoc();
        
        // Verificar que el usuario esté activo
        if ($fila['activo'] != 1) {
            header('Location: ../index.php?error=4'); // Usuario inactivo
            exit();
        }
        
        if ($contra === $fila['password'] || password_verify($contra, $fila['password'])) {
            $_SESSION['id_usuario'] = $fila['usuarios_id'];
            $_SESSION['rol'] = $fila['rol'];
            $_SESSION['tiendas_id'] = $fila['tiendas_id']; // Puede ser NULL para admin
            $_SESSION['nombre_usuario'] = $nombre_usuario;
            $_SESSION['correo'] = $fila['correo'];
            
            // Redirigir según el rol
            if ($fila['rol'] === 'Administrador') {
                header('Location: ../pages/dashboard.php');
            } else {
                // Para Gerente y Cajero, verificar que tengan tienda asignada
                if ($fila['tiendas_id'] === null) {
                    header('Location: ../index.php?error=5'); // Error: sin tienda asignada
                    exit();
                }
                header('Location: ../pages/dashboard.php');
            }
            exit();
        } else {
            header('Location: ../index.php?error=1');
            exit();
        }
    } else {
        header('Location: ../index.php?error=1');
        exit();
    }
?>
