<?php 
require_once "../../validations/check.php";
require_once '../../validations/conection.php';

$codigo= $_POST['codigo'] ?? '';
$nombre_producto = $_POST['nombre'] ?? '';
$costo = $_POST['costo'] ?? 0;
$precio_venta = $_POST['precio'] ?? 0;
$stock = $_POST['stock'] ?? 0;
$tiendas_id = $_SESSION['tiendas_id'] ?? 1;

//validar que los campos no estén vacíos
if($codigo === '' || $nombre_producto === '' || $tiendas_id <= 0){
    echo json_encode(['ok' => false, 'msg' => 'Datos incompletos']);
    exit;
}

// consulta de código único
$stmt = $conn->prepare("SELECT producto_id FROM productos WHERE codigo = ?");
$stmt->bind_param("s", $codigo);
$stmt->execute();
$result = $stmt->get_result();

//validar que el producto no exista
if ($result->num_rows > 0) {
    echo json_encode(['ok' => false, 'msg' => 'El producto ya existe']);
    exit;
}

//validar que el precio de venta no sea menor que el costo
if ($precio_venta < $costo) {
    echo json_encode(['ok' => false, 'msg' => 'El precio de venta no puede ser menor que el costo del producto']);
    exit;
}

//validar los que no tengan valores negativos
if ($costo < 0 || $precio_venta < 0 || $stock < 0) {
    echo json_encode(['ok' => false, 'msg' => 'Los campos no pueden ser negativos']);
    exit;
}
$query = "INSERT INTO productos (codigo, nombre_producto, precio_compra, precio_venta, stock, tiendas_id) VALUES (?, ?, ?, ?, ?, ?)";
$insert_producto = $conn->prepare($query);
$insert_producto->bind_param("ssddii", $codigo, $nombre_producto, $costo, $precio_venta, $stock, $tiendas_id);
$res = [];
if($insert_producto->execute()){
    $res["ok"] = true;
    $res["msg"] = "Producto insertado correctamente";
} else {
    $res["ok"] = false;
    $res["msg"] = "Error al insertar: " . $insert_producto->error;
}
echo json_encode($res);
?>