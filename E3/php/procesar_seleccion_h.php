<?php
session_start();

// Verificar que vengan datos de selección
$seleccionados = $_POST['seleccionados'] ?? [];
$data = $_POST['data'] ?? [];

if (!isset($_SESSION['hospedajes_seleccionados'])) {
    $_SESSION['hospedajes_seleccionados'] = [];
}

foreach ($seleccionados as $id) {
    // Asegurarse de que exista en data
    if (isset($data[$id])) {
        $fila = $data[$id];
        // Para evitar duplicados, se puede verificar si ya está en la sesión
        $yaExiste = false;
        foreach ($_SESSION['hospedajes_seleccionados'] as $guardado) {
            // Comparar por id idéntico
            if (isset($guardado['id']) && $guardado['id'] == $id) {
                $yaExiste = true;
                break;
            }
        }
        if (!$yaExiste) {
            $_SESSION['hospedajes_seleccionados'][] = $fila;
        }
    }
}

// Después de agregar, redirigir o mostrar confirmación
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Confirmar Hospedajes Seleccionados</title>
    <style>
        .container { max-width: 800px; margin: 1em auto; padding: 0 1em; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 8px; border: 1px solid #ccc; text-align: left; }
        tr:hover { background-color: #f5f5f5; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Hospedajes seleccionados</h1>
        <?php $seleccionados_guardados = $_SESSION['hospedajes_seleccionados']; ?>
        <?php if (!empty($seleccionados_guardados)): ?>
            <table>
                <thead>
                    <tr>
                        <?php $cols = array_keys($seleccionados_guardados[0]); foreach ($cols as $col): ?>
                            <th><?= htmlspecialchars($col) ?></th>
                        <?php endforeach; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($seleccionados_guardados as $fila): ?>
                        <tr>
                            <?php foreach ($cols as $col): ?>
                                <td><?= htmlspecialchars((string)$fila[$col]) ?></td>
                            <?php endforeach; ?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No hay hospedajes seleccionados.</p>
        <?php endif; ?>

        <p><a href="show_hospedajes.php">Volver a la lista</a></p>
    </div>
</body>
</html>

