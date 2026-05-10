<?php
    require_once 'check.php';
    require_once 'conection.php';

    $nombre_usuario = $_POST['nombre_usuario'] ?? '';
    $contra = $_POST['password'] ?? '';
    
    $query = "SELECT rol FROM usuarios WHERE nombre_usuario = ? AND password = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $nombre_usuario, $contra);
    $stmt->execute();
    $resultado = $stmt->get_result();
    //$fila = $resultado->fetch_assoc();
    // $rol = $fila['rol'];
    // echo $rol;

    if ($resultado->num_rows > 0) {

        $fila = $resultado->fetch_assoc();
        $rol = $fila['rol'];

        if ($rol === "Administrador") {
            header('Location: ../pages/dashboard.php');
        } else if ($rol === "Gerente") {
            
            header('Location: ../pages/dashboard.php');
        } else if ($rol === "Cajero") {
            header('Location: ../pages/dashboard.php');
        }
    } else {
        header('Location: ../index.php?error=1');
    }
?>