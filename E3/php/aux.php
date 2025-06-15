<?php
session_start();

// Inicializar array en sesión
if (!isset($_SESSION['entradas'])) {
    $_SESSION['entradas'] = [];
}

// Si se envía el formulario:
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $nombre = trim($_POST['nombre'] ?? '');
    $edad = trim($_POST['edad'] ?? '');

    if ($action === 'agregar') {
        // Validar ambos campos
        if ($nombre === '' || $edad === '') {
            $_SESSION['msg_error'] = 'Debe completar nombre y edad para agregar.';
        } elseif (!ctype_digit($edad)) {
            $_SESSION['msg_error'] = 'Edad debe ser un número entero.';
        } else {
            // Agregar a la sesión
            $_SESSION['entradas'][] = ['nombre' => $nombre, 'edad' => $edad];
            unset($_SESSION['msg_error']);
        }
        // Redirigir para evitar reenvío de formulario al refrescar
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit();
    }
    elseif ($action === 'finalizar') {
        // Aquí procesas $_SESSION['entradas'] como lista final
        $lista = $_SESSION['entradas'];
        // Ejemplo: limpiar sesión y mostrar resultado
        unset($_SESSION['entradas']);
        // Mostrar resumen abajo:
        $_SESSION['resultado'] = $lista;
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit();
    }
}

// Mensajes y datos a mostrar
$msgError = $_SESSION['msg_error'] ?? null;
unset($_SESSION['msg_error']);
$listaActual = $_SESSION['entradas'] ?? [];
$resultado = $_SESSION['resultado'] ?? null;
unset($_SESSION['resultado']);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Agregar sin JavaScript</title>
    <style>
        body { font-family: sans-serif; max-width: 600px; margin: 2em auto; }
        form div { margin-top: 1em; }
        ul { margin-top: 0.5em; }
        .error { color: red; }
        table { border-collapse: collapse; width: 100%; margin-top: 1em; }
        th, td { border: 1px solid #ccc; padding: 0.5em; }
    </style>
</head>
<body>
    <h1>Ingreso de nombre y edad (sin JS)</h1>
    <?php if ($msgError): ?>
        <p class="error"><?= htmlspecialchars($msgError) ?></p>
    <?php endif; ?>
    <form method="post" action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>">
        <div>
            <label>Nombre:
                <input type="text" name="nombre" value="">
            </label>
        </div>
        <div>
            <label>Edad:
                <input type="text" name="edad" value="">
            </label>
        </div>
        <div>
            <!-- Botón Agregar -->
            <button type="submit" name="action" value="agregar">Agregar</button>
            <!-- Botón Finalizar solo si hay al menos uno -->
            <?php if (!empty($listaActual)): ?>
                <button type="submit" name="action" value="finalizar">Finalizar y enviar lista</button>
            <?php endif; ?>
        </div>
    </form>

    <?php if (!empty($listaActual)): ?>
        <h2>Lista actual</h2>
        <table>
            <thead><tr><th>Nombre</th><th>Edad</th></tr></thead>
            <tbody>
            <?php foreach ($listaActual as $item): ?>
                <tr>
                    <td><?= htmlspecialchars($item['nombre']) ?></td>
                    <td><?= htmlspecialchars($item['edad']) ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <?php if ($resultado !== null): ?>
        <h2>Resultado final</h2>
        <?php if (empty($resultado)): ?>
            <p>No se agregaron entradas.</p>
        <?php else: ?>
            <table>
                <thead><tr><th>Nombre</th><th>Edad</th></tr></thead>
                <tbody>
                <?php foreach ($resultado as $item): ?>
                    <tr>
                        <td><?= htmlspecialchars($item['nombre']) ?></td>
                        <td><?= htmlspecialchars($item['edad']) ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            <!-- Aquí procesas lo que necesites con $resultado -->
        <?php endif; ?>
    <?php endif; ?>
</body>
</html>

