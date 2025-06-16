<?php
session_start();
$error = $_GET['error'] ?? null;
$avion_origen = $_SESSION['avion_origen'] ?? '';
$avion_llegada = $_SESSION['avion_llegada'] ?? '';
$hospedajes = $_SESSION['aviones_disponibles'] ?? []; 
$a = $_SESSION['fecha_inicio'] ?? '';
$b = $_SESSION['fecha_termino'] ?? '';
$seleccionadosPrevios = $_SESSION['seleccionados_transporte'] ?? [];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Transportes disponibles - Booked.com</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        .lista-hospedajes {
            max-height: 300px;
            overflow-y: auto;
            border: 1px solid #ccc;
            padding: 0;
            margin: 1em 0;
        }
        .lista-hospedajes table {
            width: 100%;
            border-collapse: collapse;
        }
        .lista-hospedajes th, .lista-hospedajes td {
            padding: 8px;
            border-bottom: 1px solid #eee;
            text-align: left;
        }
        .lista-hospedajes tr:hover {
            background-color: #f5f5f5;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Transportes disponibles en: <?= htmlspecialchars($avion_origen) ?> - <?= htmlspecialchars($avion_llegada) ?></h1>
        <h2>Entre las fechas <?= htmlspecialchars($a) ?> al <?= htmlspecialchars($b) ?></h2>

        <?php if (!empty($hospedajes)): ?>
        <form id="form-transporte" method="post">
            <div class="lista-hospedajes">
                <table>
                    <thead>
                        <tr>
                            <th>Seleccionar</th>
                            <?php
                            $cols = array_keys($hospedajes[0]);
                            foreach ($cols as $col): ?>
                                <th><?= htmlspecialchars($col) ?></th>
                            <?php endforeach; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($hospedajes as $fila): ?>
                            <?php 
                                $esDisponible = strtolower(trim($fila['estado_disponibilidad'])) === 'disponible'; 
                                if (!$esDisponible) continue;

                                $valueActual = $fila['id'] . '|' . $fila['precio_asiento'];
                                $isChecked = in_array($valueActual, $seleccionadosPrevios);
                            ?>
                            <tr>
                                <td>
                                    <input type="checkbox" name="seleccionados[]" value="<?= htmlspecialchars($valueActual) ?>" <?= $isChecked ? 'checked' : '' ?>>
                                </td>
                                <?php foreach ($cols as $col): ?>
                                    <td><?= htmlspecialchars((string)$fila[$col]) ?></td>
                                <?php endforeach; ?>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div style="margin-top: 1em;">
                <button type="submit" formaction="guardar_temporal.php">Realizar otra busqueda manteniendo seleccionados</button>
		<button type="submit" formaction="procesar_seleccion_t.php">Confirmar seleccionados</button>
                <button type="submit" formaction="resetear_temporal.php">Reset Seleccionados</button>
	    </div>

        </form>
        <?php else: ?>
            <p>No hay transportes disponibles para mostrar.</p>
        <?php endif; ?>

        <?php if (!empty($seleccionadosPrevios)): ?>
            <h3>Transportes seleccionados hasta ahora:</h3>
            <ul>
                <?php foreach ($seleccionadosPrevios as $item): ?>
                    <li><?= htmlspecialchars($item) ?></li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>

        <p><a href="buscar_transporte.php">Volver </a></p>
    </div>
</body>
</html>

