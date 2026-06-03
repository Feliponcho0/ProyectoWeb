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
$rol = $_SESSION['rol'];

// Verificar turno activo para cajeros
if ($rol === 'Cajero') {
    $check_turno = $conn->prepare("SELECT corte_caja_id FROM corte_caja WHERE usuarios_id = ? AND fecha_fin IS NULL");
    $check_turno->bind_param("i", $usuarios_id);
    $check_turno->execute();
    $result_turno = $check_turno->get_result();
    
    if ($result_turno->num_rows === 0) {
        echo json_encode(['ok' => false, 'msg' => 'No tienes un turno activo. Solicita al gerente que inicie tu turno.']);
        return;
    }
}

$conn->begin_transaction();

try{
    $query = "INSERT INTO ventas (numero_ticket, total, descuento, fecha, tiendas_id, usuarios_id) VALUES ('', ?, 0, now(), ?, ?)";
    $rows = $conn->prepare($query);
    $rows->bind_param("dii", $total, $tiendas_id, $usuarios_id);
    $rows->execute();

    $ventas_id = $conn->insert_id;
    $numero_ticket = str_pad($ventas_id, 4, "0", STR_PAD_LEFT);

    $query = "UPDATE ventas SET numero_ticket = ? WHERE ventas_id = ?";
    $rows = $conn->prepare($query);
    $rows->bind_param("si", $numero_ticket, $ventas_id);
    $rows->execute();

    $query_detalle = $conn->prepare("INSERT INTO detalle_venta (cantidad, precio_unitario, subtotal, ventas_id, producto_id) VALUES (?, ?, ?, ?, ?)");

    foreach($productos as $p){
        $query_producto = "SELECT producto_id, stock FROM productos WHERE codigo = ? and tiendas_id = ?";
        $buscarProducto = $conn->prepare($query_producto);
        $buscarProducto->bind_param("si", $p['codigo'], $tiendas_id);
        $buscarProducto->execute();
        $resultado = $buscarProducto->get_result();
        $prod = $resultado->fetch_assoc();

        if (!$prod){
            throw new Exception("Producto no encontrado: " . $p['codigo']);
        }

        $producto_id = $prod['producto_id'];
        $stock_actual = $prod['stock'];

        if ($stock_actual < $p['cantidad']){
            throw new Exception("Stock insuficiente para el producto");
        }
        
        $cantidad = $p['cantidad'];
        $precio = $p['precio'];
        $subtotal = $cantidad * $precio;

        $query_detalle->bind_param("iddii", $cantidad, $precio, $subtotal, $ventas_id, $producto_id);
        $query_detalle->execute();

        $query_stock = "UPDATE productos SET stock = stock - ? WHERE producto_id = ?";
        $stock = $conn->prepare($query_stock);
        $stock->bind_param("ii", $cantidad, $producto_id);
        $stock->execute();
    }
    
    // Actualizar total_sistema en corte_caja si el cajero tiene turno activo
    if ($rol === 'Cajero') {
        $update_turno = $conn->prepare("UPDATE corte_caja SET total_sistema = (SELECT COALESCE(SUM(v.total), 0) + saldo_inicial FROM ventas v WHERE v.usuarios_id = ? AND DATE(v.fecha) = CURDATE()) WHERE usuarios_id = ? AND fecha_fin IS NULL");
        $update_turno->bind_param("ii", $usuarios_id, $usuarios_id);
        $update_turno->execute();
    }

    $conn->commit();
    echo json_encode(['ok' => true, 'msg' => 'Venta generada correctamente', 'data' => $ventas_id]);
    
} catch(Exception $e){
    $conn->rollback();
    echo json_encode(['ok' => false, 'msg' => 'Error al generar la venta: ' . $e->getMessage()]);
    return;
}



?>