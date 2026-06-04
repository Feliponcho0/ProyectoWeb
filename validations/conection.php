<?php
    $servername = "localhost";
    $username = "root";
    $password = "1644162176";
    $database = "mydb2_core";

    // $servername = "sql312.infinityfree.com";
    // $username = "if0_42064577";
    // $password = "1644162176";
    // $database = "if0_42064577_core";

    $conn = mysqli_connect($servername, $username, $password, $database);

    if (!$conn) {
        die("Error de conexión: " . mysqli_connect_error());
    }
?>