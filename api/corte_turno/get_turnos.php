<?php
require_once "../../validations/check.php";
require_once "../../validations/conection.php";

header('Content-Type: application/json');

require_login();

if ($_SESSION['rol'] !== 'Gerente') {
    echo json_encode(['ok' => false, 'msg' => 'Sin permiso']);
    exit;
}

$tiendas_id = intval($_SESSION['tiendas_id'] ?? 0);

if ($tiendas_id <= 0) {
    echo json_encode(['ok' => false, 'msg' => 'Tienda no válida']);
    exit;
}

// Obtener todos los cajeros activos de la tienda con su turno de HOY (si existe)
$query = "
    SELECT 
        u.usuarios_id,
        u.nombre_usuario,
        cc.corte_caja_id,
        cc.fecha_inicio,
        cc.fecha_fin,
        cc.saldo_inicial,
        cc.ingresos_efectivo,
        cc.total_sistema,
        cc.total_real,
        cc.diferencia,
        cc.observaciones,
        CASE 
            WHEN cc.corte_caja_id IS NULL THEN 'pendiente'
            WHEN cc.fecha_fin IS NULL THEN 'activo'
            ELSE 'cerrado'
        END AS estado
    FROM usuarios u
    LEFT JOIN corte_caja cc 
        ON cc.usuarios_id = u.usuarios_id 
        AND DATE(cc.fecha_inicio) = CURDATE()
        AND cc.tiendas_id = ?
    WHERE u.rol = 'Cajero'
      AND u.tiendas_id = ?
      AND u.activo = 1
    ORDER BY u.nombre_usuario ASC
";

$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $tiendas_id, $tiendas_id);
$stmt->execute();
$result = $stmt->get_result();

$turnos = [];
while ($row = $result->fetch_assoc()) {
    $turnos[] = $row;
}

echo json_encode(['ok' => true, 'data' => $turnos]);
?>
