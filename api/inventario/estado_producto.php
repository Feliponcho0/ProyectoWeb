<?php
require_once "../../validations/check.php";
require_once "../../validations/conection.php";

$id = $_POST['id'] ?? null;

$query = "UPDATE productos SET activo = 1 - activo WHERE producto_id = ?";
$cons = $conn->prepare($query);
$cons->bind_param("i", $id);

if ($cons->execute()) {
    echo json_encode(["ok" => true]);
} else {
    echo json_encode(["ok" => false, "msg" => "Error al cambiar el estado"]);
}
?>