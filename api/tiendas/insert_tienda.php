<?php
require_once "../../validations/check.php"; 
require_once "../../validations/conection.php";

$nombre_tienda = $_POST['nombre_tienda'] ?? '';
$rfc = $_POST['rfc'] ?? '';
$codigo_postal = $_POST['codigo_postal'] ?? '';
$calle = $_POST['calle'] ?? '';
$colonia = $_POST['colonia'] ?? '';
$estado = $_POST['estado'] ?? '';
$telefono = $_POST['telefono'] ?? '';   

if(empty($nombre_tienda) || empty($rfc) || empty($codigo_postal) || empty($calle) || empty($colonia) || empty($estado) || empty($telefono)) {
    echo json_encode([
        "ok" => false,
        "msg" => "Todos los campos son obligatorios"
    ]);
    exit;
}

$query="INSERT INTO tiendas (nombre_tienda, rfc, codigo_postal, calle, colonia, estado, telefono) VALUES(?, ?, ?, ?, ?, ?, ?)";
$insert_tiendas=$conn->prepare($query);
$insert_tiendas->bind_param("sssssss", $nombre_tienda, $rfc, $codigo_postal, $calle, $colonia, $estado, $telefono);

$res = [];
if($insert_tiendas->execute()){
    $res["ok"] = true; // 
    $res["msg"] = "Tienda insertada correctamente";
} else {
    $res["ok"] = false;
    $res["msg"] = "Error al insertar: " . $insert_tiendas->error;
}
echo json_encode($res);
?>