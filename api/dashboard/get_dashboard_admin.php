<?php
require_once '../../validations/check.php';
require_once '../../validations/conection.php';

// obtiene el número total de tiendas activas
$queryTiendas = "SELECT COUNT(*) as total_tiendas FROM tiendas WHERE activo = 1";
$stmtTiendas = $conn->prepare($queryTiendas);
$stmtTiendas->execute();
$resultadoTiendas = $stmtTiendas->get_result();
$tiendas = $resultadoTiendas->fetch_assoc();

// obtiene el número total de usuarios activos
$queryUsuarios = "SELECT COUNT(*) as total_usuarios FROM usuarios WHERE activo = 1";
$stmtUsuarios = $conn->prepare($queryUsuarios);
$stmtUsuarios->execute();
$resultadoUsuarios = $stmtUsuarios->get_result();
$usuarios = $resultadoUsuarios->fetch_assoc();

// obtiene el número total de ventas del día
$queryVentasTiendas = "SELECT t.nombre_tienda, COALESCE(SUM(v.total), 0) as total_dia, COUNT(v.ventas_id) as num_ventas FROM tiendas t
LEFT JOIN ventas v ON t.tiendas_id = v.tiendas_id AND DATE(v.fecha) = CURDATE() WHERE t.activo = 1 GROUP BY t.tiendas_id ORDER BY total_dia DESC";
$stmtVentasTiendas = $conn->prepare($queryVentasTiendas);
$stmtVentasTiendas->execute();
$resultadoVentasTiendas = $stmtVentasTiendas->get_result();

$ventasTiendas = [];
// recorre los resultados y los almacena en un areglo
while($row = $resultadoVentasTiendas->fetch_assoc()){
    $ventasTiendas[] = $row;
}

echo json_encode(['ok' => true, 'data' => ['total_tiendas' => $tiendas['total_tiendas'], 'total_usuarios' => $usuarios['total_usuarios'], 'ventas_tiendas' => $ventasTiendas]], JSON_UNESCAPED_UNICODE);
?>