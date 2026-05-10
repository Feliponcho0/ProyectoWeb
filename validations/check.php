<?php
    session_start();
    function require_login(){
        if(!isset($_SESSION['nombre_usuario'])){
            header('Location: index.html?error=2');
            exit;
        }
    }
    function require_rol($rol){
        require_login();
        if($_SESSION['rol'] !== $rol){
            header('Location: index.html?error=3');
            exit;
        }
    }
?>