<?php
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'trabajador') {
    die("Acceso no autorizado.");
}

require 'conexion.php';

$id_usuario = $_SESSION['usuario']['id'];
$destino = trim($_POST['ruta_destino']);

if (!isset($_FILES['archivo']) || $_FILES['archivo']['error'] !== 0) {
    die("Error al subir el archivo.");
}

$archivo_nombre = basename($_FILES['archivo']['name']);
$tmp_name = $_FILES['archivo']['tmp_name'];

// Asegura carpeta
if (!is_dir("uploads/temp")) {
    mkdir("uploads/temp", 0777, true);
}

$ruta_temporal = "uploads/temp/" . uniqid() . "_" . $archivo_nombre;
if (!move_uploaded_file($tmp_name, $ruta_temporal)) {
    die("Error al mover el archivo.");
}

// Guarda en la base de datos
$stmt = $conexion->prepare("INSERT INTO archivos (id_usuario, nombre_original, ruta_destino, estado) VALUES (?, ?, ?, 'pendiente')");
$stmt->bind_param("iss", $id_usuario, $archivo_nombre, $destino);
$stmt->execute();

header("Location: dashboard.php");