<?php
$host = "localhost";
$usuario = "root"; // cambia si tienes otro usuario
$clave = "";       // cambia si tu MySQL tiene contraseña
$bd = "remain";

$conexion = new mysqli($host, $usuario, $clave, $bd);

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}