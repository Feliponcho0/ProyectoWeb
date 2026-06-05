<?php
require_once '../../validations/check.php';
require_once '../../validations/conection.php';

$tiendas_id = $_SESSION['tiendas_id'] ?? 0;
$cajero_id = $_POST['cajero_id'] ?? 0;
$saldo_inicial = $_POST['saldo_inicial'] ?? 0;

if (!$cajero_id || !$saldo_inicial) {
    echo json_encode(['ok' => false, 'msg' => 'Datos incompletos']);
    return;
}

// Verificar si el cajero ya tiene un turno abierto hoy
$queryVerificar = "SELECT corte_caja_id FROM corte_caja WHERE usuarios_id = ? AND tiendas_id = ? AND DATE(fecha_inicio) = CURDATE() AND fecha_fin IS NULL";
$stmtVerificar = $conn->prepare($queryVerificar);
$stmtVerificar->bind_param("ii", $cajero_id, $tiendas_id);
$stmtVerificar->execute();
$resultadoVerificar = $stmtVerificar->get_result();

if ($resultadoVerificar->num_rows > 0) {
    echo json_encode(['ok' => false, 'msg' => 'El cajero ya tiene un turno abierto.']);
    return;
}

// Insertar turno
$queryInsert = "INSERT INTO corte_caja (fecha_inicio, saldo_inicial, tiendas_id, usuarios_id) VALUES (NOW(), ?, ?, ?)";
$stmtInsert = $conn->prepare($queryInsert);
$stmtInsert->bind_param("dii", $saldo_inicial, $tiendas_id, $cajero_id);
$stmtInsert->execute();

$corte_id = $conn->insert_id;

echo json_encode(['ok' => true, 'msg' => 'Turno iniciado correctamente', 'data' => $corte_id], JSON_UNESCAPED_UNICODE);
?>

