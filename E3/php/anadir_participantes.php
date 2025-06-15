<?php
session_start();

$cantidad_personas = $_SESSION['cantidad_personas'] ?? 0;

if (!isset($_SESSION['entradas1'])) {
    $_SESSION['entradas1'] = [];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $nombre = trim($_POST['nombre'] ?? '');
    $edad = trim($_POST['edad'] ?? '');

    if ($action === 'agregar') {
        if ($nombre === '' || $edad === '') {
            $_SESSION['msg_error'] = 'Debe completar nombre y edad para agregar.';
        } elseif (!ctype_digit($edad)) {
            $_SESSION['msg_error'] = 'Edad debe ser un número entero.';
        } else {
            $_SESSION['entradas1'][] = ['nombre' => $nombre, 'edad' => $edad];
            unset($_SESSION['msg_error']);
        }
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit();
    } elseif ($action === 'reset') {
        $_SESSION['entradas1'] = [];
        $_SESSION['msg_error'] = 'Lista reiniciada correctamente.';
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit();
    }
}

$listaActual = $_SESSION['entradas1'] ?? [];
$msgError = $_SESSION['msg_error'] ?? null;
unset($_SESSION['msg_error']);

// Si ya se ingresaron todos los participantes, pasar automáticamente
if (count($listaActual) >= $cantidad_personas) {
    $_SESSION['entradas'] = $listaActual;
    $_SESSION['entradas1'] = [];
    header('Location: buscar_transporte.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ingreso de Participantes</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <h1>Ingreso de Participantes</h1>

    <?php if ($msgError): ?>
        <p style="color: red;"><?= htmlspecialchars($msgError) ?></p>
    <?php endif; ?>

    <p>Participantes ingresados: <?= count($listaActual) ?> / <?= $cantidad_personas ?></p>

    <form method="post">
        <label for="nombre">Nombre:</label>
        <input type="text" name="nombre" id="nombre" required>
        <br>
        <label for="edad">Edad:</label>
        <input type="text" name="edad" id="edad" required>
        <br><br>
        <button type="submit" name="action" value="agregar">Agregar</button>
        <button type="submit" name="action" value="reset">Resetear</button>
    </form>

    <?php if (!empty($listaActual)): ?>
        <h2>Personas ingresadas:</h2>
        <ul>
            <?php foreach ($listaActual as $persona): ?>
                <li><?= htmlspecialchars($persona['nombre']) ?> (<?= htmlspecialchars($persona['edad']) ?> años)</li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</body>
</html>

