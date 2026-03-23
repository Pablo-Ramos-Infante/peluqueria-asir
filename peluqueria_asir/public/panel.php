<?php
session_start();
require_once("../config/database.php");

/* Comprobar si el admin está logueado */
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}

/* Obtener todas las citas con su servicio */
$stmt = $pdo->prepare("
    SELECT 
        c.id,
        c.nombre,
        c.telefono,
        s.nombre AS servicio,
        s.precio,
        c.fecha,
        c.hora,
        c.estado,
        c.creado_en
    FROM citas c
    LEFT JOIN servicios s ON c.servicio_id = s.id
    ORDER BY c.creado_en DESC
");
$stmt->execute();
$citas = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel Administración</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Estilos propios -->
    <link rel="stylesheet" href="assets/css/styles.css">
</head>

<body class="bg-dark text-white">

<div class="container mt-5">

    <div class="panel-header d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">Panel de Administración</h2>

        <div>
            <span class="me-3">Bienvenido, <?= htmlspecialchars($_SESSION['admin']) ?></span>
            <a href="logout.php" class="btn btn-danger">Cerrar sesión</a>
        </div>
    </div>

    <?php if (count($citas) > 0): ?>

        <table class="table table-dark table-bordered table-hover text-center align-middle">
            <thead class="table-secondary text-dark">
                <tr>
                    <th>ID</th>
                    <th>Cliente</th>
                    <th>Teléfono</th>
                    <th>Servicio</th>
                    <th>Precio (€)</th>
                    <th>Fecha</th>
                    <th>Hora</th>
                    <th>Estado</th>
                    <th>Creada el</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($citas as $c): ?>
                <tr>
                    <td><?= $c['id'] ?></td>
                    <td><?= htmlspecialchars($c['nombre']) ?></td>
                    <td><?= htmlspecialchars($c['telefono']) ?></td>

                    <!-- Mostrar servicio o texto alternativo -->
                    <td><?= $c['servicio'] ? htmlspecialchars($c['servicio']) : "<i>Sin servicio</i>" ?></td>

                    <!-- Mostrar precio si existe -->
                    <td><?= $c['precio'] !== null ? $c['precio'] : "-" ?></td>

                    <td><?= $c['fecha'] ?></td>
                    <td><?= $c['hora'] ?></td>

                    <td>
                        <?php
                        switch ($c['estado']) {
                            case 'pendiente':
                                echo "<span class='badge bg-warning text-dark'>Pendiente</span>";
                                break;
                            case 'confirmada':
                                echo "<span class='badge bg-success'>Confirmada</span>";
                                break;
                            default:
                                echo "<span class='badge bg-danger'>Cancelada</span>";
                                break;
                        }
                        ?>
                    </td>

                    <td><?= $c['creado_en'] ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

    <?php else: ?>

        <div class="alert alert-info text-center">
            No hay citas registradas.
        </div>

    <?php endif; ?>

        <div class="text-center mt-4">
            <a href="index.php" class="btn btn-secondary">Volver al inicio</a>
        </div>

</div>

</body>
</html>
