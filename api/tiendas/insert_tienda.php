<?php
require_once "../../validations/check.php"; 
require_once "../../validations/conection.php";

$nombre_tienda = $_POST['nombre_tienda'] ?? '';
$rfc = $_POST['rfc'] ?? '';
$direccion = $_POST['direccion'] ?? '';
$telefono = $_POST['telefono'] ?? '';   

if(empty($nombre_tienda) || empty($rfc) || empty($direccion) || empty($telefono)) {
    echo json_encode([
        "ok" => false,
        "msg" => "Todos los campos son obligatorios"
    ]);
    exit;
}

$query="INSERT INTO tiendas (nombre_tienda, rfc, direccion, telefono) VALUES(?, ?, ?, ?)";
$insert_tiendas=$conn->prepare($query);
$insert_tiendas->bind_param("ssss", $nombre_tienda, $rfc, $direccion, $telefono);

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