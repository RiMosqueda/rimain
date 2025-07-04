<?php
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'admin') {
    die("Acceso no autorizado.");
}

require 'conexion.php';

$id = (int) $_GET['id'];
$accion = $_GET['accion'];

$sql = "SELECT a.*, u.nombre, u.id as id_usuario FROM archivos a JOIN usuarios u ON a.id_usuario = u.id WHERE a.id = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$res = $stmt->get_result();

if (!$archivo = $res->fetch_assoc()) {
    die("Archivo no encontrado.");
}

if ($accion === 'aprobar') {
    // Mueve el archivo a su destino .....

    // Entra logica para subir el archivo al servidor y ruta...

    $conexion->query("UPDATE archivos SET estado = 'aprobado' WHERE id = $id");

} elseif ($accion === 'rechazar') {
    $conexion->query("UPDATE archivos SET estado = 'rechazado' WHERE id = $id");
}

header("Location: dashboard.php");