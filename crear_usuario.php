<?php

// die();

require 'conexion.php';

$nombre = 'Ricardo Mosqueda';
$correo = 'desarrollo2@lc-software.com';
$clave_plana = 'worker123'; // Puedes cambiar esta contraseña
$rol = 'trabajador'; // 'admin' o 'trabajador'

$clave_hash = password_hash($clave_plana, PASSWORD_DEFAULT);

$stmt = $conexion->prepare("INSERT INTO usuarios (nombre, correo, clave, rol) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssss", $nombre, $correo, $clave_hash, $rol);

if ($stmt->execute()) {
    echo "Usuario creado correctamente.";
} else {
    echo "Error: " . $stmt->error;
}
?>