<?php
    require_once '../../validations/conection.php';
    require_once '../../validations/check.php';

    $search = $_GET['search'] ?? '';
    $rol = $_GET['rol'] ?? 'todos';
    
    $sql = "SELECT u.*, t.nombre_tienda FROM usuarios u LEFT JOIN tiendas t ON u.tiendas_id = t.tiendas_id WHERE 1=1";
    
    if ($_SESSION['rol'] !== 'Administrador') {
        $sql .= " AND u.tiendas_id = " . intval($_SESSION['tiendas_id']);
    }
    
    if ($rol !== 'todos') {
        $sql .= " AND u.rol = '$rol'";
    }
    
    if (!empty($search)) {
        $sql .= " AND u.nombre_usuario LIKE '%$search%'";
    }
    
    $sql .= " ORDER BY u.usuarios_id ASC";
    $res = $conn->query($sql);
    $data = [];
    while ($row = $res->fetch_assoc()) {
        $row['usuarios_id'] = (int)$row['usuarios_id'];
        $row['activo'] = (int)$row['activo'];
        $data[] = $row;
    }
    echo json_encode(['ok' => true, 'data' => $data], JSON_UNESCAPED_UNICODE);
?>