<?php
require_once "../../validations/check.php";
require_once "../../validations/conection.php";

$id = $_POST['id_edit'] ?? 0;
$nombre = trim($_POST['nombre_tienda']) ?? '';
$rfc = trim($_POST['rfc']) ?? '';
$direccion = trim($_POST['direccion']) ?? '';
$telefono = trim($_POST['telefono']) ?? '';

if($id <= 0 || empty($nombre)){
    echo json_encode(['ok' => false, 'msg' => 'Datos insuficientes']);
    exit;
}

$query = "UPDATE tiendas SET nombre_tienda = ?, rfc = ?, direccion = ?, telefono = ? WHERE tiendas_id = ?";
$update_tienda = $conn->prepare($query);
$update_tienda->bind_param("ssssi", $nombre, $rfc, $direccion, $telefono, $id);

if($update_tienda->execute()){
    echo json_encode(['ok' => true, 'msg' => 'Tienda actualizada']);
} else {
    echo json_encode(['ok' => false, 'msg' => 'Error al actualizar']);
}
?>