<?php
require_once '../../validations/check.php';
require_login();
require_once '../../validations/conection.php';

$productos = json_decode($_POST['productos'], true);
$total = $_POST['total'] ?? 0;

if (!$productos){
    echo json_encode(['ok' => false, 'msg' => 'No se recibieron productos']);
    return;
}
$tiendas_id = $_SESSION['tiendas_id'];
$usuarios_id = $_SESSION['id_usuario'];

$conn->begin_transaction();

try{
    //$numero_ticket = str_pad($ventas_id, 4, "0", STR_PAD_LEFT);
    $query = "INSERT INTO ventas (numero_ticket, total, descuento, fecha, tiendas_id, usuarios_id) VALUES ('', ?, 0, now(), ?, ?)";
    $rows = $conn->prepare($query);
    $rows->bind_param("dii", $total, $tiendas_id, $usuarios_id);
    $rows->execute();

    //obtener id de venta
    $ventas_id = $conn->insert_id;

    $numero_ticket = str_pad($ventas_id, 4, "0", STR_PAD_LEFT);

    //actualizar numero de ticket
    $query = "UPDATE ventas SET numero_ticket = ? WHERE ventas_id = ?";
    $rows = $conn->prepare($query);
    $rows->bind_param("si", $numero_ticket, $ventas_id);
    $rows->execute();


    //guardar detalle de venta
    $query_detalle =$conn->prepare( "INSERT INTO detalle_venta (cantidad, precio_unitario, subtotal, ventas_id, producto_id) VALUES (?, ?, ?, ?, ?)");

    foreach($productos as $p){
        $query_producto = "SELECT producto_id, stock FROM productos WHERE codigo = ? and tiendas_id = ?";
        $buscarProducto= $conn->prepare($query_producto);

        $buscarProducto->bind_param("si", $p['codigo'], $tiendas_id);
        $buscarProducto->execute();
        $resultado = $buscarProducto->get_result();
        $prod= $resultado->fetch_assoc();

        //validar que el producto exista
        if (!$prod){
            throw new Exception("Producto no encontrado");
        }

        $producto_id = $prod['producto_id'];
        $stock_actual = $prod['stock'];

        //validar stock
        if ($stock_actual < $p['cantidad']){
            throw new Exception("Stock insuficiente");
        }
        $cantidad = $p['cantidad'];
        $precio = $p['precio'];
        $subtotal = $cantidad * $precio;

        $query_detalle ->bind_param("iddii", $cantidad, $precio, $subtotal, $ventas_id, $producto_id);
        $query_detalle ->execute();

        //actualizar stock
        $query_stock = "UPDATE productos SET stock = stock - ? WHERE producto_id = ?";
        $stock = $conn->prepare($query_stock);
        $stock->bind_param("ii", $cantidad, $producto_id);
        $stock->execute();
    }

    $conn->commit();
    echo json_encode(['ok' => true, 'msg' => 'Venta generada correctamente']);
}catch(Exception $e){
    $conn->rollback();
    echo json_encode(['ok' => false, 'msg' => 'Error al generar la venta']);
    return;
}


?>