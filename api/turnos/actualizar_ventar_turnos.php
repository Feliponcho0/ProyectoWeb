<?php
    require_once "../../validations/check.php";
    require_once "../../validations/conection.php";

    header('Content-Type: application/json');

    $cajero_id = $_SESSION['id_usuario'] ?? 0;

    // Obtener turno activo del cajero
    $query = "SELECT corte_caja_id, total_sistema, ingresos_efectivo FROM corte_caja WHERE usuarios_id = ? AND fecha_fin IS NULL";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $cajero_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $turno = $result->fetch_assoc();

    if (!$turno) {
        echo json_encode(['ok' => false, 'msg' => 'No hay turno activo']);
        exit;
    }

    // Calcular ventas del turno desde detalle_venta
    $query_ventas = "SELECT SUM(dv.subtotal) as total_ventas, SUM(CASE WHEN v.tipo_pago = 'efectivo' THEN dv.subtotal ELSE 0 END) as efectivo, SUM(CASE WHEN v.tipo_pago = 'tarjeta' THEN dv.subtotal ELSE 0 END) as tarjeta FROM ventas v INNER JOIN detalle_venta dv ON v.ventas_id = dv.ventas_id WHERE v.usuarios_id = ? AND DATE(v.fecha) = CURDATE()";
    $stmt_v = $conn->prepare($query_ventas);
    $stmt_v->bind_param("i", $cajero_id);
    $stmt_v->execute();
    $result_v = $stmt_v->get_result();
    $ventas = $result_v->fetch_assoc();

    $total_sistema = ($ventas['total_ventas'] ?? 0) + $turno['saldo_inicial'];

    // Actualizar turno
    $update = "UPDATE corte_caja SET total_sistema = ?, ingresos_efectivo = ? WHERE corte_caja_id = ?";
    $stmt_u = $conn->prepare($update);
    $stmt_u->bind_param("ddi", $total_sistema, $ventas['efectivo'], $turno['corte_caja_id']);
    $stmt_u->execute();

    echo json_encode(['ok' => true, 'data' => $ventas]);
?>