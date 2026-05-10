<?php
    require_once '../conection.php';
    session_start();
    header('Content-Type: application/json');

    if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'Administrador') {
        echo json_encode(['success' => false, 'message' => 'No autorizado']);
        exit;
    }

    $nombre_tienda = trim($_POST['nombre_tienda'] ?? '');
    $rfc = trim($_POST['rfc'] ?? '');
    $direccion = trim($_POST['direccion'] ?? '');
    $telefono = trim($_POST['telefono'] ?? '');

    if (empty($nombre_tienda) || empty($rfc)) {
        echo json_encode(['success' => false, 'message' => 'Nombre y RFC son obligatorios']);
        exit;
    }

    $stmt = $conn->prepare("INSERT INTO tiendas (nombre_tienda, rfc, direccion, telefono, activo) VALUES (?, ?, ?, ?, 1)");
    $stmt->bind_param("ssss", $nombre_tienda, $rfc, $direccion, $telefono);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Tienda creada correctamente']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al crear tienda: ' . $conn->error]);
    }
?>