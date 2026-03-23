<?php
require_once("../config/database.php");

/* Validar fecha recibida */
if (!isset($_GET['fecha']) || empty($_GET['fecha'])) {
    echo json_encode([]);
    exit;
}

$fecha = $_GET['fecha'];

/* Comprobar formato correcto de fecha (YYYY-MM-DD) */
if (!preg_match("/^\d{4}-\d{2}-\d{2}$/", $fecha)) {
    echo json_encode([]);
    exit;
}

/* Comprobar si es fin de semana */
$diaSemana = date("N", strtotime($fecha)); // 6 = sábado, 7 = domingo

if ($diaSemana >= 6) {
    echo json_encode([]);
    exit;
}

/* Generar horas disponibles */
$horas = [];

/* Horario mañana 09:00 - 13:00 */
$inicioManana = strtotime("09:00");
$finManana = strtotime("13:00");

while ($inicioManana < $finManana) {
    $horas[] = date("H:i", $inicioManana);
    $inicioManana = strtotime("+30 minutes", $inicioManana);
}

/* Horario tarde 16:00 - 20:00 */
$inicioTarde = strtotime("16:00");
$finTarde = strtotime("20:00");

while ($inicioTarde < $finTarde) {
    $horas[] = date("H:i", $inicioTarde);
    $inicioTarde = strtotime("+30 minutes", $inicioTarde);
}

/* Obtener horas ocupadas en BD */
$stmt = $pdo->prepare("SELECT hora FROM citas WHERE fecha = ?");
$stmt->execute([$fecha]);
$ocupadas = $stmt->fetchAll(PDO::FETCH_COLUMN);

/* Comprobar si la fecha es hoy para bloquear horas pasadas */
$hoy = date("Y-m-d");
$horaActual = date("H:i");

$resultado = [];

foreach ($horas as $h) {

    $ocupada = in_array($h, $ocupadas);

    /* Bloquear horas pasadas si es hoy */
    if ($fecha === $hoy && $h <= $horaActual) {
        $ocupada = true;
    }

    $resultado[] = [
        "hora" => $h,
        "ocupada" => $ocupada
    ];
}

echo json_encode($resultado);
?>
