<?php
    require_once "../../validations/check.php";
    require_once "../../validations/conection.php";  
    $codigo = $_GET['codigo'] ?? 0;
    $tiendas_id = $_SESSION['tiendas_id'] ?? 0;

    $cons = $conn->prepare("SELECT * FROM productos WHERE codigo = ? AND tiendas_id = ?");
    $cons->bind_param('si', $codigo, $tiendas_id);
    $cons->execute();
    $res = $cons->get_result();
    $row = $res->fetch_assoc();
    if (!$row){
        echo json_encode(['ok' => false, 'msg' => 'Producto no encontrado'], JSON_UNESCAPED_UNICODE);
        exit;
    }
    
    echo json_encode(['ok' => true, 'data' => $row], JSON_UNESCAPED_UNICODE);

?>