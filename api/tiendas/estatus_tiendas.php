<?php
require_once "../../validations/conection.php";

// Cambiamos 'tiendas_id' por 'id' para que coincida con tu $.post de JS
$id = $_POST['tiendas_id'] ?? null;

if (!$id) {
    echo json_encode(["ok" => false, "msg" => "ID no recibido"]);
    exit;
}

// 1 - activo: Si es 1, (1-1) = 0. Si es 0, (1-0) = 1. ¡Un interruptor perfecto!
$query = "UPDATE tiendas SET activo = 1 - activo WHERE tiendas_id = ?";
$cons = $conn->prepare($query);
$cons->bind_param("i", $id);

if ($cons->execute()) {
    echo json_encode(["ok" => true]);
} else {
    echo json_encode(["ok" => false, "msg" => "Error al cambiar el estatus"]);
}
?>