<?php
    require '../../validations/conection.php';

    $search = $_GET['search'] ?? '';
    $rol = $_GET['rol'] ?? 'todos';
    $sql = "SELECT * FROM usuarios WHERE 1=1";
    if ($rol !== 'todos') {
        $sql .= " AND rol = '$rol'";
    }
    if (!empty($search)) {
        $sql .= " AND nombre_usuario LIKE '%$search%'";
    }
    $sql .= " ORDER BY usuarios_id ASC";
    $res = $conn->query($sql);
    $data = [];
    while ($row = $res->fetch_assoc()) {
        $row['usuarios_id'] = (int)$row['usuarios_id'];
        $data[] = $row;
    }
    echo json_encode(['ok' => true, 'data' => $data], JSON_UNESCAPED_UNICODE);
?>