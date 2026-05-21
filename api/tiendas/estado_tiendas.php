<?php
require_once "../../validations/check.php";
require_once "../../validations/conection.php";

$id = $_POST['tiendas_id'] ?? null;

$query = "UPDATE tiendas SET activo = 1 - activo WHERE tiendas_id = ?";
$cons = $conn->prepare($query);
$cons->bind_param("i", $id);

if ($cons->execute()) {
    echo json_encode(["ok" => true]);
} else {
    echo json_encode(["ok" => false, "msg" => "Error al cambiar el estado"]);
}

?>