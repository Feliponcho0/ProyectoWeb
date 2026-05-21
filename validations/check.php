<?php
    session_start();
    
    function require_login(){
        if(!isset($_SESSION['nombre_usuario']) && !isset($_SESSION['id_usuario'])){
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
    
    function require_tienda(){
        require_login();
        if($_SESSION['rol'] !== 'Administrador' && ($_SESSION['tiendas_id'] === null || $_SESSION['tiendas_id'] <= 0)){
            header('Location: ../index.php?error=5');
            exit;
        }
    }
    
    function getTiendaId(){
        if($_SESSION['rol'] === 'Administrador'){
            return null; // Admin no tiene tienda fija
        }
        return $_SESSION['tiendas_id'] ?? 0;
    }
?>