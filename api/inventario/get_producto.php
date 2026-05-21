<?php
    require_once "../../validations/check.php";
    require_once "../../validations/conection.php";  
    $id = (int)($_GET['id'] ?? 0);
    $cons = $conn->prepare("SELECT * FROM productos WHERE producto_id = ? LIMIT 1");
    $cons->bind_param('i', $id);
    $cons->execute();
    $res = $cons->get_result();
    $row = $res->fetch_assoc();
    if (!$row){
        echo json_encode(['ok' => false, 'msg' => 'Producto no encontrado'], JSON_UNESCAPED_UNICODE);
        exit;
    }
    
    echo json_encode(['ok' => true, 'data' => $row], JSON_UNESCAPED_UNICODE);
?>