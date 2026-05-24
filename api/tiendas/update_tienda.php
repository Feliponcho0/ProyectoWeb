<?php
require_once "../../validations/check.php";
require_once "../../validations/conection.php";

$id = $_POST['id_edit'] ?? 0;
$nombre_tienda = trim($_POST['nombre_tienda'] ?? '');
$rfc = trim($_POST['rfc'] ?? '');
$codigo_postal = trim($_POST['codigo_postal'] ?? '');
$calle = trim($_POST['calle'] ?? '');
$colonia = trim($_POST['colonia'] ?? '');
$estado = trim($_POST['estado'] ?? '');
$telefono = trim($_POST['telefono'] ?? '');

if($id <= 0 || empty($nombre_tienda) || empty($rfc) || empty($codigo_postal) || empty($calle) || empty($colonia) || empty($estado) || empty($telefono)){
    echo json_encode(['ok' => false, 'msg' => 'Datos insuficientes']);
    exit;
}

$query = "UPDATE tiendas SET nombre_tienda = ?, rfc = ?, codigo_postal = ?, calle = ?, colonia = ?, estado = ?, telefono = ? WHERE tiendas_id = ?";
$update_tienda = $conn->prepare($query);
$update_tienda->bind_param("sssssssi", $nombre_tienda, $rfc, $codigo_postal, $calle, $colonia, $estado, $telefono, $id);
if($update_tienda->execute()){
    echo json_encode(['ok' => true, 'msg' => 'Tienda actualizada']);
} else {
    echo json_encode(['ok' => false, 'msg' => 'Error al actualizar']);
}
?>