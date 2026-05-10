<?php
    require '../conection.php';
    header('Content-Type: application/json');

    $res = $conn->query("SELECT tiendas_id, nombre_tienda, rfc, activo FROM tiendas ORDER BY tiendas_id DESC");

    $data = [];
    while ($row = $res->fetch_assoc()) {
        $data[] = $row;
    }

    echo json_encode(['ok' => true, 'data' => $data], JSON_UNESCAPED_UNICODE);
?>