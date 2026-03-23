<?php
session_start();
require_once("../config/database.php");

$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $usuario = $_POST['usuario'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE usuario = ?");
    $stmt->execute([$usuario]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['admin'] = $usuario;
        header("Location: panel.php");
        exit;
    } else {
        $mensaje = "Credenciales incorrectas";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Iniciar Sesión</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-dark text-white">

<div class="container mt-5" style="max-width: 400px;">
    <h2 class="text-center text-warning mb-4">Iniciar Sesión</h2>

    <form method="POST">
        <input type="text" name="usuario" class="form-control mb-3" placeholder="Usuario" required>
        <input type="password" name="password" class="form-control mb-3" placeholder="Contraseña" required>

        <button type="submit" class="btn btn-warning w-100">Entrar</button>

        <!-- BOTÓN NUEVO -->
        <a href="index.php" class="btn btn-secondary w-100 mt-3">Volver al inicio</a>
    </form>

    <?php if ($mensaje): ?>
        <div class="alert alert-danger text-center mt-3"><?= $mensaje ?></div>
    <?php endif; ?>
</div>

</body>
</html>

