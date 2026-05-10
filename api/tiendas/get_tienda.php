<?php
    require_once "../../validations/conection.php"; 
    $id = (int)($_GET['id'] ?? 0);
    
    $cons = $connect->prepare("SELECT * FROM tiendas WHERE tiendas_id = ? LIMIT 1");
    $cons->bind_param('i', $id);
    $cons->execute();
    
    $res = $cons->get_result();
    $row = $res->fetch_assoc();
    
    if (!$row){
        echo json_encode(['ok' => false, 'msg' => 'Tienda no encontrada'], JSON_UNESCAPED_UNICODE);
        exit;
    }
    
    $row['tiendas_id'] = (int)$row['tiendas_id'];
    echo json_encode(['ok' => true, 'data' => $row], JSON_UNESCAPED_UNICODE);
?>