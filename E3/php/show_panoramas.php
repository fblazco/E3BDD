<?php
session_start();
$error = $_GET['error'] ?? null;
$ciudad = $_SESSION['lugar_panorama'] ?? '';
$hospedajes = $_SESSION['panoramas_disponibles']?? []; 
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Inicio de sesión - Booked.com</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
            </div>
    <div class="container">
<div class="audio-control" style="margin-top:1em;">
            <audio id="audio-bg" src="/php/musica.mp3" preload="none"></audio>

            <button id="btn-play-audio" type="button">Reproducir música</button>
            <button id="btn-pause-audio" type="button" style="display: none;">Pausar música</button>
        </div>
    </div>
<?php
?>
<!DOCTYPE html>
<html lang="es">
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
        <h1>Panoramas en: <?= htmlspecialchars($ciudad) ?></h1>

        <?php if (!empty($hospedajes)): ?>
<form action="procesar_seleccion_p.php" method="post">
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
        ?>
        <tr>
            <td>
<input type="checkbox" name="seleccionados[]" value="<?= htmlspecialchars($fila['id'] . '|' . $fila['precio_persona']) ?>">

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
        <button type="submit">Confirmar selección</button>
    </div>
</form>
 

        <?php else: ?>
            <p>No hay hospedajes disponibles para mostrar.</p>
        <?php endif; ?>



                <p><a href="main.php">Volver al inicio</a></p>
    </body>
</html>


