<?php
require_once("../config/database.php");

/* Validar que los datos existen */
if (
    !isset($_POST['nombre'], $_POST['telefono'], $_POST['servicio_id'], $_POST['fecha'], $_POST['hora'])
) {
    header("Location: reservar.php");
    exit;
}

/* Sanitizar datos */
$nombre = htmlspecialchars(trim($_POST['nombre']));
$telefono = htmlspecialchars(trim($_POST['telefono']));
$servicio_id = intval($_POST['servicio_id']);
$fecha = $_POST['fecha'];
$hora = $_POST['hora'];

/* Validar formato de fecha */
if (!preg_match("/^\d{4}-\d{2}-\d{2}$/", $fecha)) {
    $mensaje = "❌ Fecha no válida.";
    $tipo = "danger";
} 
/* Validar formato de hora */
elseif (!preg_match("/^\d{2}:\d{2}$/", $hora)) {
    $mensaje = "❌ Hora no válida.";
    $tipo = "danger";
} 
else {

    /* Comprobar si ya existe una cita en esa fecha y hora */
    $check = $pdo->prepare("SELECT COUNT(*) FROM citas WHERE fecha = ? AND hora = ?");
    $check->execute([$fecha, $hora]);

    if ($check->fetchColumn() > 0) {
        $mensaje = "❌ Esa fecha y hora ya están reservadas.";
        $tipo = "danger";
    } else {
        /* Insertar nueva cita */
        $stmt = $pdo->prepare("
            INSERT INTO citas (nombre, telefono, servicio_id, fecha, hora) 
            VALUES (?, ?, ?, ?, ?)
        ");
        $stmt->execute([$nombre, $telefono, $servicio_id, $fecha, $hora]);

        $mensaje = "✅ Cita reservada correctamente.";
        $tipo = "success";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Resultado reserva</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Redirección automática -->
    <meta http-equiv="refresh" content="3;url=index.php">

    <!-- Estilos -->
    <link rel="stylesheet" href="assets/css/styles.css">
</head>

<body class="bg-dark text-white d-flex justify-content-center align-items-center vh-100">

<div class="card text-center p-4 shadow" style="max-width: 400px;">
    <div class="alert alert-<?= $tipo ?>">
        <?= $mensaje ?>
    </div>

    <p>Serás redirigido automáticamente...</p>
    <a href="index.php" class="btn btn-primary">Volver ahora</a>
</div>

</body>
</html>
