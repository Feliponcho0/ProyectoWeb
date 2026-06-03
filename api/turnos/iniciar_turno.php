<?php
    require_once "../../validations/check.php";
    require_once "../../validations/conection.php";

    header('Content-Type: application/json');

    if ($_SESSION['rol'] !== 'Gerente') {
        echo json_encode(['ok' => false, 'msg' => 'No tienes permiso para realizar esta acción']);
        exit;
    }

    $cajero_id = $_POST['cajero_id'] ?? 0;
    $saldo_inicial = $_POST['saldo_inicial'] ?? 0;
    $tiendas_id = $_SESSION['tiendas_id'] ?? 0;

    if ($cajero_id <= 0 || $saldo_inicial <= 0) {
        echo json_encode(['ok' => false, 'msg' => 'Datos incompletos']);
        exit;
    }
    
    $check = $conn->prepare("SELECT usuarios_id FROM usuarios WHERE usuarios_id = ? AND tiendas_id = ? AND rol = 'Cajero' AND activo = 1");
    $check->bind_param("ii", $cajero_id, $tiendas_id);
    $check->execute();
    $result = $check->get_result();

    if ($result->num_rows === 0) {
        echo json_encode(['ok' => false, 'msg' => 'Cajero no válido']);
        exit;
    }

    $check_turno = $conn->prepare("SELECT corte_caja_id FROM corte_caja WHERE usuarios_id = ? AND fecha_fin IS NULL");
    $check_turno->bind_param("i", $cajero_id);
    $check_turno->execute();
    $turno_activo = $check_turno->get_result();

    if ($turno_activo->num_rows > 0) {
        echo json_encode(['ok' => false, 'msg' => 'Este cajero ya tiene un turno activo']);
        exit;
    }

    $query = "INSERT INTO corte_caja (fecha_inicio, saldo_inicial, ingresos_efectivo, total_sistema, total_real, diferencia, observaciones, usuarios_id, tiendas_id) VALUES (NOW(), ?, 0, 0, 0, 0, NULL, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("dii", $saldo_inicial, $cajero_id, $tiendas_id);

    if ($stmt->execute()) {
        echo json_encode(['ok' => true, 'msg' => 'Turno iniciado correctamente', 'corte_id' => $conn->insert_id]);
    } else {
        echo json_encode(['ok' => false, 'msg' => 'Error al iniciar turno: ' . $conn->error]);
    }
?>