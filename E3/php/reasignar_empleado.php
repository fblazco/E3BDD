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
        <h1>Buscar empleado base y objetivo por nombre:</h1>

        <form action="procesar_reasignar_empleado.php" method="POST" class="formulario-grid-2x4">

    <div class="form-group">
                <label for="empleado_reasignar"></label>
<input type="text" id="empleado_reasignar" name="empleado_reasignar" required value="<?= htmlspecialchars($form_data['empleado_reasignar'] ?? '') ?>" required>
            </div>
<div class="form-group">
                <label for="empleado_objetivo"></label>
<input type="text" id="empleado_objetivo" name="empleado_objetivo" required value="<?= htmlspecialchars($form_data['empleado_objetivo'] ?? '') ?>" required>

            </div>
       <div class="grid-full center-content">
                <button type="submit">buscar</button>

                <p><a href="menu_empleado.php">Volver al inicio</a></p>
            </div>
        </form>
    </div>
</body>
</html>
