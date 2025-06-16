<?php
session_start();

$nuevos = $_POST['seleccionados'] ?? [];

if (!isset($_SESSION['seleccionados_transporte'])) {
    $_SESSION['seleccionados_transporte'] = [];
}

$_SESSION['seleccionados_transporte'] = array_unique(array_merge(
    $_SESSION['seleccionados_transporte'],
    $nuevos
));

$finales = $_SESSION['seleccionados_transporte'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Confirmar Transportes Seleccionados</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="container">
        <h1>Transportes seleccionados</h1>

        <?php if (!empty($finales)): ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Precio por persona</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($finales as $valor): 
                        list($id, $precio) = explode('|', $valor);
                    ?>
                        <tr>
                            <td><?= htmlspecialchars((string)$id) ?></td>
                            <td><?= htmlspecialchars((string)$precio) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <a href="confirmar_eleccion.php" style="display:inline-block; margin-top:1em; padding:8px 16px; background:#007bff; color:white; text-decoration:none; border-radius:4px;">
                Confirmar selección
            </a>
        <?php else: ?>
            <p>No hay transportes seleccionados</p>
        <?php endif; ?>

        <p style="margin-top:1em;"><a href="show_transportes_disponibles.php">Volver a la lista</a></p>
    </div>
</body>
</html>

