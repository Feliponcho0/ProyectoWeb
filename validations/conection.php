<?php
    $servername = "localhost";
    $username = "u804959721_user";
    $password = "Smsl785#";
    $database = "u804959721_mydb";

    $conn = mysqli_connect($servername, $username, $password, $database);

    if (!$conn) {
        die("Error de conexión: " . mysqli_connect_error());
    }
?>