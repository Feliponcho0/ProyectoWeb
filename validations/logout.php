<?php
    session_start(); #inicializamos
    session_unset(); #vaciamos
    session_destroy(); #destruimos
    header('Location: ../../index.php');
    exit;
?>