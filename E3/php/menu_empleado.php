<?php
session_start();
if (!isset($_SESSION['empleado'])) {
    header('Location: index.php?error=Debes iniciar sesión primero');
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel Principal</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="container">
        <h1>Hola, <?= htmlspecialchars($_SESSION['empleado']) ?></h1>

        <div class="menu">
            <a href="reasignar_empleado.php" class="btn">Reasignar Empleado</a>
            <a href="eliminar_empleado.php" class="btn">Despedir Empleado</a>
            <a href="cerrar_sesion.php" class="btn btn-secondary">Cerrar Sesión</a>
        </div>
    </div>
</body>
</html>
