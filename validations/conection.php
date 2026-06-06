<?php
    /*
    $servername = "localhost";
    $username = "u804959721_user";
    $password = "Smsl785#";
    $database = "u804959721_mydb";

    $conn = mysqli_connect($servername, $username, $password, $database);

    if (!$conn) {
        die("Error de conexión: " . mysqli_connect_error());
    }
        */
    date_default_timezone_set('America/Mazatlan');

    $servername = "localhost";
    $username = "root";
    $password = "1644162176";
    $database = "mydb2";

    $conn = mysqli_connect($servername, $username, $password, $database);

    if (!$conn) {
        die("Error de conexión: " . mysqli_connect_error());
    }
    $conn -> set_charset("utf8mb4");
    
    mysqli_query($conn, "SET time_zone = '-07:00'");



?>