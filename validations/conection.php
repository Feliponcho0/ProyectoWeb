<?php
    $servername = "br9bm8i4ietmialfxmhz-mysql.services.clever-cloud.com";
    $username = "umdm2bhuj1unejhw";
    $password = "AEC1kYvZmNMyJMFPuh2a";
    $database = "br9bm8i4ietmialfxmhz";

    // $servername = "sql312.infinityfree.com";
    // $username = "if0_42064577";
    // $password = "1644162176";
    // $database = "if0_42064577_core";

    $conn = mysqli_connect($servername, $username, $password, $database);

    if (!$conn) {
        die("Error de conexión: " . mysqli_connect_error());
    }
?>