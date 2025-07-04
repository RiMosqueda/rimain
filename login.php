<?php
session_start();
require 'conexion.php';

$correo = $_POST['correo'];
$clave = $_POST['clave'];

$sql = "SELECT * FROM usuarios WHERE correo = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("s", $correo);
$stmt->execute();
$resultado = $stmt->get_result();

if ($usuario = $resultado->fetch_assoc()) {
    if (password_verify($clave, $usuario['clave'])) {
        $_SESSION['usuario'] = $usuario;
        header("Location: dashboard.php");
        exit;
    }
}

echo "<script>alert('Credenciales incorrectas'); location.href='index.php';</script>";