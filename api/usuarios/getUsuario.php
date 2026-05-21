<?php
    require_once '../../validations/conection.php';

    $usuarios_id = (int)($_GET['usuarios_id'] ?? 0);

    $stmt = $conn->prepare("SELECT * FROM usuarios WHERE usuarios_id = ?");
    $stmt->bind_param("i", $usuarios_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if (!$row) {
        echo json_encode(['ok' => false, 'msg' => 'No existe el registro']);
        exit;
    }

    $row['usuarios_id'] = (int)$row['usuarios_id'];
    $row['activo'] = (int)$row['activo'];
    echo json_encode(['ok' => true, 'data' => $row]);
?>