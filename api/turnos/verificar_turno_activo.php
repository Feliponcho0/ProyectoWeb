<?php
    require_once "../../validations/check.php";
    require_once "../../validations/conection.php";

    header('Content-Type: application/json');

    $usuarios_id = $_SESSION['id_usuario'] ?? 0;
    $rol = $_SESSION['rol'] ?? '';

    if ($rol === 'Gerente') {
        echo json_encode(['ok' => true, 'tiene_turno' => true, 'msg' => 'Permiso concedido']);
        exit;
    }

    if ($rol === 'Cajero') {
        $query = "SELECT corte_caja_id, saldo_inicial, total_sistema 
                FROM corte_caja 
                WHERE usuarios_id = ? AND fecha_fin IS NULL";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $usuarios_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $turno = $result->fetch_assoc();
        
        if ($turno) {
            echo json_encode([
                'ok' => true, 
                'tiene_turno' => true, 
                'turno_id' => $turno['corte_caja_id'],
                'saldo_inicial' => $turno['saldo_inicial'],
                'total_ventas' => $turno['total_sistema'] - $turno['saldo_inicial'],
                'msg' => 'Turno activo'
            ]);
        } else {
            echo json_encode([
                'ok' => false, 
                'tiene_turno' => false, 
                'msg' => 'No tienes un turno activo. Solicita al gerente que inicie tu turno.'
            ]);
        }
        exit;
    }

    echo json_encode(['ok' => false, 'tiene_turno' => false, 'msg' => 'Rol no válido']);
?>