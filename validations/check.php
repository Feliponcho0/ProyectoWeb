<?php
session_start();

function require_login(){
    if(!isset($_SESSION['id_usuario']) && !isset($_SESSION['nombre_usuario'])){
        header('Location: ../index.php?error=2');
        exit;
    }
}

function require_rol($rol){
    require_login();
    if($_SESSION['rol'] !== $rol){
        header('Location: ../index.php?error=3');
        exit;
    }
}

function getTiendaId(){
    if($_SESSION['rol'] === 'Administrador'){
        return null;
    }
    return $_SESSION['tiendas_id'] ?? 0;
}

function verificar_usuario_activo() {
    if(!isset($_SESSION['id_usuario'])) {
        return;
    }
    
    global $conn;
    
    $id_usuario = $_SESSION['id_usuario'];
    $stmt = $conn->prepare("SELECT activo FROM usuarios WHERE usuarios_id = ?");
    $stmt->bind_param("i", $id_usuario);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if($result->num_rows > 0) {
        $usuario = $result->fetch_assoc();
        if($usuario['activo'] == 0) {
            session_unset();
            session_destroy();
            header('Location: ../index.php?error=4');
            exit;
        }
    }
}
?>