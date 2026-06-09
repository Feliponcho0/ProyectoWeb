<?php
require_once '../../validations/check.php';
require_once '../../validations/conection.php';

$tiendas_id = $_SESSION['tiendas_id'] ?? 0;
$corte_id = $_POST['corte_id'] ?? 0;
$efectivo_real = $_POST['efectivo_real'] ?? 0;
$observaciones = $_POST['observaciones'] ?? '';

if (!$corte_id) {
    echo json_encode(['ok' => false, 'msg' => 'Datos incompletos']);
    return;
}

// Obtener datos del corte
$queryCorte = "SELECT usuarios_id, fecha_inicio, saldo_inicial FROM corte_caja WHERE corte_caja_id = ?";
$stmtCorte = $conn->prepare($queryCorte);
$stmtCorte->bind_param("i", $corte_id);
$stmtCorte->execute();
$resultadoCorte = $stmtCorte->get_result();
$corte = $resultadoCorte->fetch_assoc();

// Obtener total de ventas del cajero durante el turno
$queryVentas = "SELECT SUM(total) as total_sistema FROM ventas WHERE usuarios_id = ? AND tiendas_id = ? AND fecha >= ?";
$stmtVentas = $conn->prepare($queryVentas);
$stmtVentas->bind_param("iis", $corte['usuarios_id'], $tiendas_id, $corte['fecha_inicio']);
$stmtVentas->execute();
$resultadoVentas = $stmtVentas->get_result();
$ventas = $resultadoVentas->fetch_assoc();
$total_sistema = $ventas['total_sistema'] ?? 0;
$saldo_inicial = $corte['saldo_inicial'] ?? 0;

// Calcular diferencia
$diferencia = $efectivo_real - ($total_sistema + $saldo_inicial);

// Cerrar turno
$queryUpdate = "UPDATE corte_caja SET fecha_fin = NOW(), ingresos_efectivo = ?, total_sistema = ?, total_real = ?, diferencia = ?, observaciones = ? WHERE corte_caja_id = ?";
$stmtUpdate = $conn->prepare($queryUpdate);
$stmtUpdate->bind_param("ddddsi", $efectivo_real, $total_sistema, $efectivo_real, $diferencia, $observaciones, $corte_id);
$stmtUpdate->execute();

echo json_encode(['ok' => true,'msg' => 'Turno cerrado correctamente','total_sistema' => number_format($total_sistema, 2),'total_real' => number_format($efectivo_real, 2),'diferencia' => number_format($diferencia, 2)
], JSON_UNESCAPED_UNICODE);
?>