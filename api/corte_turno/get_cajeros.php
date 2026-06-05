<?php 
require_once '../../validations/check.php';
require_once '../../validations/conection.php';

$tiendas_id = $_SESSION['tiendas_id'] ?? 0;

$query = "SELECT usuarios_id, nombre_usuario FROM usuarios WHERE rol = 'Cajero' AND tiendas_id = ? AND activo = 1 ORDER BY nombre_usuario ASC";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $tiendas_id);
$stmt->execute();
$resultado = $stmt->get_result();

$data = [];
while ($row = $resultado->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode(['ok' => true, 'data' => $data], JSON_UNESCAPED_UNICODE);
?>
