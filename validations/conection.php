<?php
    $servername = "localhost";
    $username = "root";
    $password = "1644162176";
    $database = "mydb2";

    $conn = mysqli_connect($servername, $username, $password, $database);

    if (!$conn) {
        die("Error de conexión: " . mysqli_connect_error());
    }
?>