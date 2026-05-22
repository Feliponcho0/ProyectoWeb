<?php
    require_once "../../validations/conection.php";
    require_once "../../validations/check.php";

    header('Content-Type: application/json');

    if (!$conn) {
        echo json_encode(['ok' => false, 'msg' => 'Error de conexión']);
        exit;
    }

    $query = "SELECT tiendas_id, nombre_tienda as nombre, rfc FROM tiendas WHERE estatus = 1 ORDER BY nombre_tienda ASC";
    $res = $conn->query($query);

    if (!$res) {
        echo json_encode(['ok' => false, 'msg' => 'Error en consulta']);
        exit;
    }

    $data = [];
    while($row = $res->fetch_assoc()){
        $data[] = [
            'tiendas_id' => (int)$row['tiendas_id'],
            'nombre' => (string)$row['nombre'],
            'rfc' => (string)$row['rfc']
        ];
    }

    echo json_encode(['ok' => true, 'data' => $data], JSON_UNESCAPED_UNICODE);
?>