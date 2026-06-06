<?php
require_once '../../validations/check.php';
require_once '../../validations/conection.php';

$usuarios_id = $_SESSION['id_usuario'];

// obtiene el total de ventas del día y el número de ventas del día para el cajero
$query = "SELECT COUNT(*) as num_ventas, SUM(total) as total_dia FROM ventas WHERE usuarios_id = ? AND DATE(fecha) = CURDATE()";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $usuarios_id);
$stmt->execute();
$resultado = $stmt->get_result()->fetch_assoc();

echo json_encode(['ok' => true, 'data' => ['num_ventas' => $resultado['num_ventas'],'total_dia'  => $resultado['total_dia'] ?? 0]], JSON_UNESCAPED_UNICODE);
?>