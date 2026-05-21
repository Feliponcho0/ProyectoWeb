<?php
require_once "../../validations/check.php"; 
require_once '../../validations/conection.php';

$id = $_POST['id'] ?? 0;
$nombre = trim($_POST['nombre']) ?? '';
$costo = trim($_POST['costo']) ?? '';
$precio = trim($_POST['precio']) ?? ''; 
$cantidad = trim($_POST['stock']) ?? '';

if($id <= 0 || empty($nombre)){
    echo json_encode(['ok' => false, 'msg' => 'Datos insuficientes']);
    exit;
}

$query = "UPDATE productos SET nombre_producto = ?, precio_compra = ?, precio_venta = ?, stock = ? WHERE producto_id = ?";
$update_producto = $conn->prepare($query);
$update_producto->bind_param("sddii", $nombre, $costo, $precio, $cantidad, $id);

if($update_producto->execute()){
    echo json_encode(['ok' => true, 'msg' => 'Producto actualizado']);
} else {
    echo json_encode(['ok' => false, 'msg' => 'Error al actualizar']);
} 
?>