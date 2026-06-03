<?php
require_once "../../validations/check.php";
    require_once "../../validations/conection.php";

    header('Content-Type: application/json');

    if ($_SESSION['rol'] !== 'Gerente') {
        echo json_encode(['ok' => false, 'msg' => 'No tienes permiso para realizar esta acción']);
        exit;
    }

    $corte_id = $_POST['corte_id'] ?? 0;
    $total_real = $_POST['total_real'] ?? 0;
    $observaciones = $_POST['observaciones'] ?? '';
    $tiendas_id = $_SESSION['tiendas_id'] ?? 0;

    if ($corte_id <= 0) {
        echo json_encode(['ok' => false, 'msg' => 'Datos incompletos']);
        exit;
    }

    $query = "SELECT * FROM corte_caja WHERE corte_caja_id = ? AND tiendas_id = ? AND fecha_fin IS NULL";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $corte_id, $tiendas_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $turno = $result->fetch_assoc();

    if (!$turno) {
        echo json_encode(['ok' => false, 'msg' => 'Turno no encontrado o ya cerrado']);
        exit;
    }

    $diferencia = $total_real - $turno['total_sistema'];

    $update = "UPDATE corte_caja SET fecha_fin = NOW(), total_real = ?, diferencia = ?, observaciones = ? WHERE corte_caja_id = ?";
    $stmt = $conn->prepare($update);
    $stmt->bind_param("ddsi", $total_real, $diferencia, $observaciones, $corte_id);

    if ($stmt->execute()) {
        echo json_encode(['ok' => true, 'msg' => 'Turno cerrado correctamente', 'diferencia' => $diferencia]);
    } else {
        echo json_encode(['ok' => false, 'msg' => 'Error al cerrar turno: ' . $conn->error]);
    }
?>