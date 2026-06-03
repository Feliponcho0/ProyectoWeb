<?php
require_once "../../validations/check.php";
require_once "../../validations/conection.php";

header('Content-Type: application/json');

require_login();

if ($_SESSION['rol'] !== 'Gerente') {
    echo json_encode(['ok' => false, 'msg' => 'No tienes permiso para realizar esta acción']);
    exit;
}

$tiendas_id    = intval($_SESSION['tiendas_id'] ?? 0);
$corte_id      = intval($_POST['corte_id']       ?? 0);
$efectivo_real = floatval($_POST['efectivo_real'] ?? 0);
$observaciones = trim($_POST['observaciones']    ?? '');

if ($corte_id <= 0) {
    echo json_encode(['ok' => false, 'msg' => 'Turno no válido']);
    exit;
}

// Verificar que el turno pertenece a esta tienda y está activo
$check = $conn->prepare("
    SELECT corte_caja_id, saldo_inicial
    FROM corte_caja
    WHERE corte_caja_id = ?
      AND tiendas_id    = ?
      AND fecha_fin IS NULL
");
$check->bind_param("ii", $corte_id, $tiendas_id);
$check->execute();
$result = $check->get_result();

if ($result->num_rows === 0) {
    echo json_encode(['ok' => false, 'msg' => 'El turno no existe, ya fue cerrado o no pertenece a esta tienda']);
    exit;
}

$turno = $result->fetch_assoc();

// Calcular total_sistema: ventas realizadas desde que inició el turno
$query_ventas = "
    SELECT COALESCE(SUM(v.total), 0) AS total_ventas
    FROM ventas v
    INNER JOIN corte_caja cc ON cc.corte_caja_id = ?
    WHERE v.tiendas_id = ?
      AND v.fecha >= cc.fecha_inicio
";
$stmt_v = $conn->prepare($query_ventas);
$stmt_v->bind_param("ii", $corte_id, $tiendas_id);
$stmt_v->execute();
$row_v = $stmt_v->get_result()->fetch_assoc();
$total_sistema = floatval($row_v['total_ventas']);

$total_real = floatval($turno['saldo_inicial']) + $efectivo_real;
$diferencia  = $efectivo_real - $total_sistema;
$obs         = $observaciones !== '' ? $observaciones : null;

$update = $conn->prepare("
    UPDATE corte_caja
    SET fecha_fin         = NOW(),
        ingresos_efectivo = ?,
        total_sistema     = ?,
        total_real        = ?,
        diferencia        = ?,
        observaciones     = ?
    WHERE corte_caja_id   = ?
");
$update->bind_param("ddddsi", $efectivo_real, $total_sistema, $total_real, $diferencia, $obs, $corte_id);

if ($update->execute()) {
    echo json_encode([
        'ok'            => true,
        'msg'           => 'Turno cerrado correctamente',
        'total_sistema' => $total_sistema,
        'total_real'    => $total_real,
        'diferencia'    => $diferencia
    ]);
} else {
    echo json_encode(['ok' => false, 'msg' => 'Error al cerrar el turno: ' . $conn->error]);
}
?>
