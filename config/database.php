$host = "sql306.infinityfree.com";
$dbname = "if0_41459020_peluqueria";
$user = "if0_41459020";
$pass = "BarberPro2026";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}
