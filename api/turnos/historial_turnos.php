<?php
    require_once "../../validations/check.php";
    require_once "../../validations/conection.php";

    header('Content-Type: application/json');

    $tiendas_id = $_SESSION['tiendas_id'] ?? 0;
    $fecha = $_GET['fecha'] ?? date('Y-m-d');

    $query = "SELECT cc.*, u.nombre_usuario FROM corte_caja cc INNER JOIN usuarios u ON cc.usuarios_id = u.usuarios_id WHERE cc.tiendas_id = ? AND DATE(cc.fecha_inicio) = ? ORDER BY cc.fecha_inicio DESC";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("is", $tiendas_id, $fecha);
    $stmt->execute();
    $result = $stmt->get_result();

    $data = [];
    while ($row = $result->fetch_assoc()) {
        $row['corte_caja_id'] = (int)$row['corte_caja_id'];
        $row['usuarios_id'] = (int)$row['usuarios_id'];
        $row['saldo_inicial'] = (float)$row['saldo_inicial'];
        $row['ingresos_efectivo'] = (float)$row['ingresos_efectivo'];
        $row['total_sistema'] = (float)$row['total_sistema'];
        $row['total_real'] = (float)$row['total_real'];
        $row['diferencia'] = (float)$row['diferencia'];
        $data[] = $row;
    }

    echo json_encode(['ok' => true, 'data' => $data]);
?>