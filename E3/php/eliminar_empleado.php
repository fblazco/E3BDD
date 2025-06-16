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
        <h1>Buscar empleado para despedir:</h1>

        <form action="procesar_eliminar.php" method="POST" class="formulario-grid-2x4">

    <div class="form-group">
                <label for="empleado_eliminar"></label>
<input type="text" id="empleado_eliminar" name="empleado_eliminar" required value="<?= htmlspecialchars($form_data['empleado_eliminar'] ?? '') ?>" required>
            </div>

       <div class="grid-full center-content">
                <button type="submit">buscar</button>

                <p><a href="menu_empleado.php">Volver al inicio</a></p>
            </div>
        </form>
    </div>
</body>
</html>
