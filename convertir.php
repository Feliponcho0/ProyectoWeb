<?php
require_once 'validations/conection.php';

$nombre = 'adminContra';
$password_ingresada = 'HWD7sjL8';

$stmt = $conn->prepare("SELECT usuarios_id, nombre_usuario, password, rol, tiendas_id FROM usuarios WHERE nombre_usuario = ?");
$stmt->bind_param("s", $nombre);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if ($user) {
    echo "<pre>";
    echo "Usuario: " . $user['nombre_usuario'] . "\n";
    echo "Rol: " . $user['rol'] . "\n";
    echo "Tienda ID: " . ($user['tiendas_id'] ?? 'NULL') . "\n";
    echo "Hash en BD: " . $user['password'] . "\n";
    echo "password_verify('HWD7sjL8', hash): " . (password_verify($password_ingresada, $user['password']) ? '✅ FUNCIONA' : '❌ NO funciona') . "\n";
    echo "</pre>";
    
    if (!password_verify($password_ingresada, $user['password'])) {
        echo "<br>🔧 Solución: Actualizar contraseña manualmente:<br>";
        echo "<code>UPDATE usuarios SET password = '" . password_hash($password_ingresada, PASSWORD_DEFAULT) . "' WHERE nombre_usuario = 'adminContra';</code>";
    }
} else {
    echo "Usuario 'adminContra' no encontrado";
}
?>