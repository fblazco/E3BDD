<?php
session_start();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro de Usuario</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="container">
        <h1>Buscar Transporte</h1>

        <form action="procesar_buscar_transporte.php" method="POST" class="formulario-grid-2x4">

            <div class="form-group">
                <label for="lugar_origen">Lugar origen:</label>
                <input type="text" id="lugar_origen" name="lugar_origen"
                    value="<?= htmlspecialchars($form_data['lugar_origen'] ?? '') ?>" required>
            </div>

            <div class="form-group">
                <label for="lugar_llegada">lugar_llegada:</label>
                <input type="text" id="lugar_llegada" name="lugar_llegada" required
                    value="<?= htmlspecialchars($form_data['lugar_llegada'] ?? '') ?>" required>
            </div>
            <div class="grid-full center-content">
                <button type="submit">buscar</button>

                <p><a href="main.php">Volver al inicio</a></p>
            </div>
        </form>
    </div>
</body>
</html>
