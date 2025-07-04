<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header('Location: index.php');
    exit;
}

$_SESSION['usuario'] = $_SESSION['usuario'];
require 'conexion.php';

// echo '<pre>';print_r($_SESSION);die();

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Panel - <?php echo htmlspecialchars($_SESSION['usuario']['nombre']); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3>Bienvenido, <?php echo htmlspecialchars($_SESSION['usuario']['nombre']); ?></h3>
            <a href="logout.php" class="btn btn-danger">Cerrar sesión</a>
        </div>

        <?php if ($_SESSION['usuario']['rol'] === 'trabajador'): ?>
            <!-- Subida de archivo -->
            <div class="card mb-4">
                <div class="card-header">Subir archivo</div>
                <div class="card-body">
                    <form action="subir.php" method="POST" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="archivo" class="form-label">Selecciona archivo</label>
                            <input type="file" name="archivo" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="ruta_destino" class="form-label">Ruta destino</label>
                            <input type="text" name="ruta_destino" class="form-control" placeholder="/uploads/aprobados/"
                                required>
                        </div>
                        <button class="btn btn-primary">Subir</button>
                    </form>
                </div>
            </div>

            <!-- Archivos subidos -->
            <h5>Mis archivos</h5>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Archivo</th>
                        <th>Estado</th>
                        <th>Fecha</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $stmt = $conexion->prepare("SELECT * FROM archivos WHERE id_usuario = ? ORDER BY fecha_subida DESC");
                    $stmt->bind_param("i", $_SESSION['usuario']['id']);
                    $stmt->execute();
                    $res = $stmt->get_result();
                    while ($fila = $res->fetch_assoc()):
                        ?>
                        <tr>
                            <td><?= htmlspecialchars($fila['nombre_original']) ?></td>
                            <td><?= $fila['estado'] ?></td>
                            <td><?= $fila['fecha_subida'] ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>

        <?php else: ?>
            <!-- Aprobación de archivos -->
            <h5>Archivos pendientes</h5>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Usuario</th>
                        <th>Archivo</th>
                        <th>Ruta</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $query = "SELECT a.*, u.nombre FROM archivos a JOIN usuarios u ON a.id_usuario = u.id WHERE a.estado = 'pendiente'";
                    $res = $conexion->query($query);
                    while ($fila = $res->fetch_assoc()):
                        ?>
                        <tr>
                            <td><?= htmlspecialchars($fila['nombre']) ?></td>
                            <td><?= htmlspecialchars($fila['nombre_original']) ?></td>
                            <td><?= htmlspecialchars($fila['ruta_destino']) ?></td>
                            <td>
                                <a href="aprobar.php?id=<?= $fila['id'] ?>&accion=aprobar"
                                    class="btn btn-success btn-sm">Aprobar</a>
                                <a href="aprobar.php?id=<?= $fila['id'] ?>&accion=rechazar"
                                    class="btn btn-danger btn-sm">Rechazar</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</body>

</html>