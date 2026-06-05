<?php
    require_once '../../validations/check.php';
    require_once '../../validations/conection.php';

    $tienda_destino = $_SESSION['tiendas_id'] ?? 0;
    $tienda_origen = $_POST['tienda_origen'] ?? 0;

    if (!$tienda_origen || !$tienda_destino) {
        echo json_encode(['ok' => false, 'msg' => 'Datos incompletos']);
        return;
    }

    // obtener productos de la tienda origen que no existan en la tienda destino
    $query = "SELECT codigo, nombre_producto, precio_compra, precio_venta, stock FROM productos WHERE tiendas_id = ? AND activo = 1 AND 
    codigo NOT IN(SELECT codigo FROM productos WHERE tiendas_id = ? )";

    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $tienda_origen, $tienda_destino);
    $stmt->execute();
    $resultado = $stmt->get_result();

    $productos = [];
    while ($row = $resultado->fetch_assoc()) {
        $productos[] = $row;
    }

    // si no hay productos nuevos para importar, muestra mensaje
    if (count($productos) === 0) {
        echo json_encode(['ok' => false, 'msg' => 'No hay productos nuevos para importar.']);
        return;
    }

    // insertar productos en la tienda destino
    $queryInsert = "INSERT INTO productos (codigo, nombre_producto, precio_compra, precio_venta, stock, tiendas_id) VALUES (?, ?, ?, ?, ?, ?)";
    $stmtInsert = $conn->prepare($queryInsert);

    $importados = 0;
    foreach ($productos as $p) {
        $stmtInsert->bind_param("ssddii", $p['codigo'], $p['nombre_producto'], $p['precio_compra'], $p['precio_venta'], $p['stock'], $tienda_destino);
        $stmtInsert->execute();
        $importados++;
    }

    echo json_encode(['ok' => true, 'msg' => "Se importaron $importados productos correctamente."], JSON_UNESCAPED_UNICODE);
?>