<?php
require_once "../../validations/check.php"; 
require_once '../../validations/conection.php';
$id_tiendaASIG= $_SESSION['tiendas_id'] ?? 0;
$query ="SELECT * FROM productos where tiendas_id = ? ORDER BY producto_id ASC";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id_tiendaASIG);
$stmt->execute();
$res = $stmt->get_result();

$data = [];
while($row = $res->fetch_assoc()){
    $row['producto_id'] = (int)$row['producto_id'];
    $row['tiendas_id'] = (int)$row['tiendas_id'];
    $row['activo'] = (int)$row['activo']; 
    $data[] = $row;
}
echo json_encode(['ok' => true, 'data' => $data ], JSON_UNESCAPED_UNICODE);
?>