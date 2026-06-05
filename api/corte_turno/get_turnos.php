<?php
require_once '../../validations/check.php';
require_once '../../validations/conection.php';

$tiendas_id = $_SESSION['tiendas_id'] ?? 0;

// Verificar si el cajero ya tiene un turno abierto hoy
$query = "SELECT u.usuarios_id, u.nombre_usuario, c.corte_caja_id, c.saldo_inicial, c.total_sistema, c.ingresos_efectivo, c.diferencia, c.fecha_inicio, c.fecha_fin FROM usuarios u LEFT JOIN corte_caja c ON u.usuarios_id = c.usuarios_id AND DATE(c.fecha_inicio) = CURDATE() AND c.tiendas_id = ?
AND c.corte_caja_id = (SELECT MAX(cc.corte_caja_id) FROM corte_caja cc WHERE cc.usuarios_id = u.usuarios_id AND DATE(cc.fecha_inicio) = CURDATE()) WHERE u.rol = 'Cajero' AND u.tiendas_id = ? AND u.activo = 1 ORDER BY u.nombre_usuario ASC";

$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $tiendas_id, $tiendas_id);
$stmt->execute();
$resultado = $stmt->get_result();

$data = [];
while ($row = $resultado->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode(['ok' => true, 'data' => $data], JSON_UNESCAPED_UNICODE);
?>