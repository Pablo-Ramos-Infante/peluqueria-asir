<?php session_start(); ?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>BarberPro</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="assets/css/styles.css">


</head>
<body>

<nav class="navbar navbar-dark">
    <div class="container d-flex justify-content-between align-items-center">
        <a class="navbar-brand" href="#">BarberPro</a>
        <div>
            <a href="reservar.php" class="btn btn-warning me-2">Reservar cita</a>
            <a href="login.php" class="btn btn-outline-light">Admin</a>
        </div>
    </div>
</nav>

<div class="container hero">
    <h1>Bienvenido a BarberPro</h1>
    <p>Sistema profesional de gestión de citas</p>
</div>

</body>
</html>
