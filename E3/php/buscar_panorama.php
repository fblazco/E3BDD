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
        <h1>Buscar Panoramas en:</h1>

        <form action="procesar_buscar_panorama.php" method="POST" class="formulario-grid-2x4">

                        <div class="form-group">
                <label for="lugar_panorama"></label>
                <input type="text" id="lugar_panorama" name="lugar_panorama" required
                    value="<?= htmlspecialchars($form_data['lugar_panorama'] ?? '') ?>" required>
            </div>
            <div class="grid-full center-content">
                <button type="submit">buscar</button>

                <p><a href="main.php">Volver al inicio</a></p>
            </div>
        </form>
    </div>
</body>
</html>
