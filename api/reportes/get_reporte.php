<?php
require_once '../../validations/check.php';
require_once '../../validations/conection.php';

$tiendas_id = $_SESSION['tiendas_id'];
//$tiendas_id = 1;
$fecha_inicio = $_GET['fecha_inicio'] ?? '';
$fecha_fin = $_GET['fecha_fin'] ?? '';

//obtener ventas entre fechas
$queryReporte = "SELECT v.ventas_id, v.numero_ticket, v.total, v.fecha, u.nombre_usuario FROM ventas v INNER JOIN usuarios u ON 
v.usuarios_id = u.usuarios_id WHERE v.tiendas_id = ? AND DATE(v.fecha) BETWEEN ? AND ? ORDER BY v.fecha ASC";

$cons= $conn->prepare($queryReporte);
$cons->bind_param('iss', $tiendas_id, $fecha_inicio, $fecha_fin);
$cons->execute();
$resultado = $cons->get_result();

$data = [];
$total_general = 0;

while ($row = $resultado->fetch_assoc()) {
    $row['total'] = (float)$row['total'];
    $total_general += $row['total'];
    $data[] = $row;
}

echo json_encode(['ok' => true, 'data' => $data, 'total_general' => $total_general], JSON_UNESCAPED_UNICODE);
?>