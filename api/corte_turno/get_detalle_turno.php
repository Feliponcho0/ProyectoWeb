<?php
require_once "../../validations/check.php";
require_once "../../validations/conection.php";

header('Content-Type: application/json');

require_login();

if ($_SESSION['rol'] !== 'Gerente') {
    echo json_encode(['ok' => false, 'msg' => 'Sin permiso']);
    exit;
}

$tiendas_id = intval($_SESSION['tiendas_id'] ?? 0);
$corte_id   = intval($_GET['corte_id'] ?? 0);

if ($corte_id <= 0) {
    echo json_encode(['ok' => false, 'msg' => 'ID de turno no válido']);
    exit;
}

// Datos del turno
$stmt = $conn->prepare("
    SELECT 
        cc.*,
        u.nombre_usuario
    FROM corte_caja cc
    INNER JOIN usuarios u ON u.usuarios_id = cc.usuarios_id
    WHERE cc.corte_caja_id = ?
      AND cc.tiendas_id    = ?
");
$stmt->bind_param("ii", $corte_id, $tiendas_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(['ok' => false, 'msg' => 'Turno no encontrado']);
    exit;
}

$turno = $result->fetch_assoc();

// Ventas realizadas durante el turno
$stmt_ventas = $conn->prepare("
    SELECT 
        v.numero_ticket,
        v.total,
        v.fecha,
        COUNT(dv.detalle_venta_id) AS num_productos
    FROM ventas v
    LEFT JOIN detalle_venta dv ON dv.ventas_id = v.ventas_id
    WHERE v.tiendas_id = ?
      AND v.fecha     >= ?
      AND (? IS NULL OR v.fecha <= ?)
    GROUP BY v.ventas_id
    ORDER BY v.fecha ASC
");

$fecha_fin = $turno['fecha_fin'] ?? null;
$stmt_ventas->bind_param("isss", $tiendas_id, $turno['fecha_inicio'], $fecha_fin, $fecha_fin);
$stmt_ventas->execute();
$res_ventas = $stmt_ventas->get_result();

$ventas = [];
while ($v = $res_ventas->fetch_assoc()) {
    $ventas[] = $v;
}

echo json_encode([
    'ok'     => true,
    'turno'  => $turno,
    'ventas' => $ventas
]);
?>
