<?php
    require_once "../../validations/check.php";
    require_once "../../validations/conection.php";  
    $codigo = $_GET['codigo'] ?? 0;
    $cons = $conn->prepare("SELECT * FROM productos WHERE codigo = ?");
    $cons->bind_param('s', $codigo);
    $cons->execute();
    $res = $cons->get_result();
    $row = $res->fetch_assoc();
    if (!$row){
        echo json_encode(['ok' => false, 'msg' => 'Producto no encontrado'], JSON_UNESCAPED_UNICODE);
        exit;
    }
    
    echo json_encode(['ok' => true, 'data' => $row], JSON_UNESCAPED_UNICODE);

?>