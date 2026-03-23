<?php
$host = "caboose.proxy.rlwy.net";
$port = "16512";
$dbname = "railway";
$user = "root";
$pass = "GwVmpMbqCXgmjAwmUkxorGhefYiEApWM";

try {
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}

