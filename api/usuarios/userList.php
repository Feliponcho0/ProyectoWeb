<?php
    require_once '../../validations/conection.php';
    require_once '../../validations/check.php';

    $search = $_GET['search'] ?? '';
    $rol = $_GET['rol'] ?? 'todos';
    
    $sql = "SELECT u.*, t.nombre_tienda FROM usuarios u LEFT JOIN tiendas t ON u.tiendas_id = t.tiendas_id WHERE 1=1";
    
    if ($_SESSION['rol'] !== 'Administrador') {
        $sql .= " AND u.tiendas_id = " . intval($_SESSION['tiendas_id']);
    }
    
    // desactivar su 
    if ($_SESSION['rol'] === 'Administrador') {
        $sql .= " AND u.rol IN ('Gerente', 'Cajero')";
    } elseif ($_SESSION['rol'] === 'Gerente') {
        $sql .= " AND u.rol = 'Cajero'";
    }
    
    if ($rol !== 'todos' && $_SESSION['rol'] !== 'Administrador') {
        if ($_SESSION['rol'] === 'Gerente' && $rol === 'Cajero') {
        } elseif ($_SESSION['rol'] === 'Gerente' && $rol !== 'Cajero') {
            $sql .= " AND 1=0";
        }
    } elseif ($rol !== 'todos' && $_SESSION['rol'] === 'Administrador') {
        if ($rol === 'Gerente' || $rol === 'Cajero') {
            $sql .= " AND u.rol = '$rol'";
        } else {
            $sql .= " AND 1=0";
        }
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