<?php
require_once __DIR__ . '/../config/database.php';

/* Obtener servicios disponibles */
$stmt = $pdo->prepare("SELECT * FROM servicios ORDER BY nombre");
$stmt->execute();
$servicios = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reservar cita</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Estilos propios -->
    <link rel="stylesheet" href="assets/css/styles.css">

    <script>
    /* Cargar horas disponibles según la fecha */
    function cargarHoras() {
        let fecha = document.getElementById("fecha").value;
        if (fecha === "") return;

        fetch("horas_disponibles.php?fecha=" + fecha)
        .then(response => response.json())
        .then(data => {

            let contenedor = document.getElementById("horas");
            contenedor.innerHTML = "";

            /* Fines de semana */
            if (data.length === 0) {
                contenedor.innerHTML = "<div class='alert alert-warning'>No disponible fines de semana</div>";
                return;
            }

            let manana = [];
            let tarde = [];

            /* Separar mañana y tarde */
            data.forEach(h => {
                if (h.hora < "13:00") {
                    manana.push(h);
                } else {
                    tarde.push(h);
                }
            });

            /* Día completo ocupado */
            if (data.every(h => h.ocupada)) {
                contenedor.innerHTML = "<div class='dia-completo'>Día completo</div>";
                return;
            }

            /* Crear secciones */
            function crearSeccion(titulo, horas) {
                if (horas.length === 0) return "";

                let html = "<div class='seccion-horas fade-in'>";
                html += "<div class='titulo-seccion'>" + titulo + "</div>";
                html += "<div class='grid-horas'>";

                horas.forEach(hora => {
                    let clase = hora.ocupada ? "btn-secondary" : "btn-success";
                    let disabled = hora.ocupada ? "disabled" : "";

                    html += `
                        <button type="button" class="btn ${clase}" ${disabled}
                        onclick="seleccionarHora(this, '${hora.hora}')">
                            ${hora.hora}
                        </button>
                    `;
                });

                html += "</div></div>";
                return html;
            }

            contenedor.innerHTML += crearSeccion("MAÑANA", manana);
            contenedor.innerHTML += crearSeccion("TARDE", tarde);
        });
    }

    /* Seleccionar hora */
    function seleccionarHora(boton, hora) {
        document.querySelectorAll("#horas button").forEach(b => {
            b.classList.remove("btn-primary");
            if (!b.disabled) {
                b.classList.add("btn-success");
            }
        });

        boton.classList.remove("btn-success");
        boton.classList.add("btn-primary");

        document.getElementById("horaSeleccionada").value = hora;
    }

    /* Bloquear fines de semana desde el cliente */
    function validarFecha() {
        let fechaInput = document.getElementById("fecha");
        let fecha = new Date(fechaInput.value);
        let dia = fecha.getDay(); // 0 domingo, 6 sábado

        if (dia === 0 || dia === 6) {
            alert("Solo se puede reservar de lunes a viernes.");
            fechaInput.value = "";
            document.getElementById("horas").innerHTML = "";
        }
    }
    </script>
</head>

<body class="bg-dark text-white">

<div class="container mt-4">

    <h2 class="mb-4">Reservar cita</h2>

    <form action="procesar_reserva.php" method="POST">

        <input type="text" 
               name="nombre" 
               class="form-control mb-3" 
               placeholder="Nombre" 
               required>

        <input type="text" 
               name="telefono" 
               class="form-control mb-3" 
               placeholder="Teléfono" 
               required>

        <select name="servicio_id" class="form-control mb-3" required>
            <?php foreach ($servicios as $s): ?>
                <option value="<?= $s['id'] ?>">
                    <?= htmlspecialchars($s['nombre']) ?> - <?= $s['precio'] ?>€
                </option>
            <?php endforeach; ?>
        </select>

        <label class="mt-2">Selecciona día:</label>
        <input type="date" 
               id="fecha" 
               name="fecha" 
               class="form-control mb-3"
               onchange="validarFecha(); cargarHoras();" 
               required>

        <label>Selecciona hora:</label>
        <div id="horas" class="mb-3"></div>

        <input type="hidden" name="hora" id="horaSeleccionada" required>

        <button type="submit" class="btn btn-warning w-100">Reservar</button>

    </form>

</div>

</body>
</html>
