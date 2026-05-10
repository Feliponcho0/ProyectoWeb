<?php
require_once '../../validations/conection.php';
$res = $conn->query("SELECT * FROM productos ORDER BY producto_id DESC");
$data = [];
while($row = $res->fetch_assoc()){
    $row['producto_id'] = (int)$row['producto_id'];
    $row['tiendas_id'] = (int)$row['tiendas_id'];
    $row['activo'] = (int)$row['activo']; 
    $data[] = $row;
}
echo json_encode(['ok' => true, 'data' => $data ], JSON_UNESCAPED_UNICODE);
?>