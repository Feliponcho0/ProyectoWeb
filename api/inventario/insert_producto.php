<?php 
require_once '../../validations/conection.php';
$codigo = $_POST['codigo'] ?? '';
$nombre_producto = $_POST['nombre_producto'] ?? '';
$precio_compra = $_POST['precio_compra'] ?? 0;  
$precio_venta = $_POST['precio_venta'] ?? 0;
$stock = $_POST['stock'] ?? 0;
$activo = $_POST['activo'] ?? 0;

if ($nombre_producto==="" || $precio_compra==="" || $precio_venta==="" || $stock==="" || $activo==="" || $codigo==="") {
    echo json_encode(['ok' => false, 'msg' => 'Todos los campos son obligatorios']);
    return;
}
$stmt = $conn->prepare("INSERT INTO productos (codigo, nombre_producto, precio_compra, precio_venta, stock, activo, tiendas_id) VALUES (?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("sddiiss", $codigo, $nombre_producto, $precio_compra, $precio_venta, $stock, $activo);
if ($stmt->execute()) {
    echo json_encode(['ok' => true]);
} else {
    echo json_encode(['ok' => false, 'msg' => $stmt->error]);
}

?>