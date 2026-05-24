<?php 
    require_once "../validations/check.php";
    
    if ($_SESSION['rol'] === 'Administrador') {
        $pagina = "../views/dashboard_view.php";
    } else if ($_SESSION['rol'] === 'Gerente') {
        $pagina = "../views/dashboard_gerente_view.php";
    } else {
        $pagina = "../views/dashboard_cajero_view.php";
    }



    include "menu.php"; 
?>
