<?php
    require_once 'check.php'; 
    require_once 'conection.php';

    $nombre_usuario = $_POST['nombre_usuario'] ?? '';
    $contra = $_POST['password'] ?? '';
    
    $query = "SELECT usuarios_id, rol, tiendas_id FROM usuarios WHERE nombre_usuario = ? AND password = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $nombre_usuario, $contra);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        $fila = $resultado->fetch_assoc();
        
        $_SESSION['id_usuario'] = $fila['usuarios_id'];
        $_SESSION['rol'] = $fila['rol']; // guardamos aqui el rol
        $_SESSION['tiendas_id'] = $fila['tiendas_id']; 

        header('Location: ../pages/dashboard.php');
        exit();

    } else {
        header('Location: ../index.php?error=1');
        exit();
    }
?>



