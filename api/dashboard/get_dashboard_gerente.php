<?php 
    require_once '../../validations/check.php';
    require_once '../../validations/conection.php';

    $tiendas_id = $_SESSION['tiendas_id'] ?? 0;

    // obtiene el total de ventas del día
    $queryTotalDia = "SELECT SUM(total) as total_dia FROM ventas WHERE tiendas_id = ? AND DATE(fecha) = CURDATE()";
    $stmtTotalDia = $conn->prepare($queryTotalDia);
    $stmtTotalDia->bind_param("i", $tiendas_id);
    $stmtTotalDia->execute();
    $resultadoTotalDia = $stmtTotalDia->get_result();
    $totalDia = $resultadoTotalDia->fetch_assoc();
    $total_dia = $totalDia['total_dia'] ?? 0;

    // obtiene el número de ventas del día
    $queryNumVentas = "SELECT COUNT(*) as num_ventas FROM ventas WHERE tiendas_id = ? AND DATE(fecha) = CURDATE()";
    $stmtNumVentas = $conn->prepare($queryNumVentas);
    $stmtNumVentas->bind_param("i", $tiendas_id);
    $stmtNumVentas->execute();
    $resultadoNumVentas = $stmtNumVentas->get_result();
    $numVentas = $resultadoNumVentas->fetch_assoc();

    // obtiene el número de productos con bajo stock (5 o menos)
    $queryBajoStock = "SELECT COUNT(*) as bajo_stock FROM productos WHERE tiendas_id = ? AND stock <= 5 AND activo = 1";
    $stmtStock = $conn->prepare($queryBajoStock);
    $stmtStock->bind_param("i", $tiendas_id);
    $stmtStock->execute();
    $resultadoStock = $stmtStock->get_result();
    $stock = $resultadoStock->fetch_assoc();

    // obtiene los 5 productos más vendidos
    $queryMasVendidos = "SELECT p.nombre_producto, SUM(dv.cantidad) as cantidad_vendida FROM detalle_venta dv INNER JOIN 
    ventas v ON dv.ventas_id = v.ventas_id INNER JOIN productos p ON dv.producto_id = p.producto_id WHERE v.tiendas_id = ? AND 
    DATE(v.fecha) = CURDATE() GROUP BY p.producto_id ORDER BY cantidad_vendida DESC LIMIT 5";
    $stmtMasVendidos = $conn->prepare($queryMasVendidos);
    $stmtMasVendidos->bind_param("i", $tiendas_id);
    $stmtMasVendidos->execute();
    $resultadoMasVendidos = $stmtMasVendidos->get_result();

    $masVendidos = [];
    // recorre los resultados y los guarda
    while($row = $resultadoMasVendidos->fetch_assoc()){
        $masVendidos[] = $row;
    }

echo json_encode(['ok'=>true, 'data'=> ['total_dia'=> number_format($total_dia, 2), 'num_ventas' => $numVentas['num_ventas'], 'bajo_stock' => $stock['bajo_stock'], 'mas_vendidos' => $masVendidos]],  JSON_UNESCAPED_UNICODE);
?>