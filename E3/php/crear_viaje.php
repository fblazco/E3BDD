<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header('Location: index.php?error=Debes iniciar sesión');
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear Viaje</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        .error { color: red; margin-top: 10px; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Crear nuevo viaje</h1>

        <p id="mensajeError" class="error" style="display:none;"></p>

        <form id="formularioViaje" action="procesar_crear_viaje.php" method="POST" class="formulario">
            <label for="nombre">Nombre del viaje:</label>
            <input type="text" id="nombre" name="nombre" required>

            <label for="etiqueta">Etiqueta:</label>
            <textarea id="etiqueta" name="etiqueta" rows="4" required></textarea>

            <label for="fecha_inicio">Fecha de inicio:</label>
            <input type="date" id="fecha_inicio" name="fecha_inicio" required>

            <label for="fecha_fin">Fecha de término:</label>
            <input type="date" id="fecha_fin" name="fecha_fin" required>

            <label for="ciudad">Ciudad hospedaje:</label>
            <input type="text" id="ciudad" name="ciudad" required>

            <label for="cantidad">Numero de acompanantes:</label>
            <input type="text" id="cantidad" name="cantidad" required>

            <button type="submit">Crear viaje</button>
        </form>

        <p><a href="main.php">Volver al inicio</a></p>
    </div>

    <script>
        const form = document.getElementById('formularioViaje');
        const mensajeError = document.getElementById('mensajeError');

        form.addEventListener('submit', function (e) {
            const fechaInicio = new Date(document.getElementById('fecha_inicio').value);
            const fechaFin = new Date(document.getElementById('fecha_fin').value);

            if (isNaN(fechaInicio) || isNaN(fechaFin)) {
                mensajeError.textContent = 'Ingrresa ambas fechas';
                mensajeError.style.display = 'block';
                e.preventDefault();
                return;
            }

            if (fechaInicio >= fechaFin) {
                mensajeError.textContent = 'Fecha mal ingresada';
                mensajeError.style.display = 'block';
                e.preventDefault(); 
            } else {
                mensajeError.style.display = 'none'; 
            }
        });
    </script>
</body>
</html>

