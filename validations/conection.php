<?php
    /*
    $servername = "bdzgbhzoece2npuq85jz-mysql.services.clever-cloud.com";
    $username = "u73962dkev2xk0n9";
    $password = "wUlj5spC069pdX720lpm";
    $database = "bdzgbhzoece2npuq85jz";


    // $servername = "sql312.infinityfree.com";
    // $username = "if0_42064577";
    // $password = "1644162176";
    // $database = "if0_42064577_core";

    $conn = mysqli_connect($servername, $username, $password, $database);

    if (!$conn) {
        die("Error de conexión: " . mysqli_connect_error());
    }

    */

    $servername = "localhost";
    $username = "root";
    $password = "1644162176";
    $database = "mydb2";

    $conn = mysqli_connect($servername, $username, $password, $database);

    if (!$conn) {
        die("Error de conexión: " . mysqli_connect_error());
    }
?>