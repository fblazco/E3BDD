
<?php
session_start();
$error = $_GET['error'] ?? null;
$avion_origen= $_SESSION['avion_origen'] ?? '';
$avion_llegada= $_SESSION['avion_llegada'] ?? '';
$hospedajes = $_SESSION['aviones_disponibles']?? []; 
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
            max-height: 300px; /* altura deseada */
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


        <?php if (!empty($hospedajes)): ?>
            <div class="lista-hospedajes">
                <table>
                    <thead>
                        <tr>
                            <?php
                            $cols = array_keys($hospedajes[0]);
                            foreach ($cols as $col): ?>
                                <th><?= htmlspecialchars($col) ?></th>
                            <?php endforeach; ?>
                        </tr>
                    </thead>
                   <tbody>
    <?php foreach ($hospedajes as $index => $fila): ?>
        <?php
            $esDisponible = strtolower(trim($fila['estado_disponibilidad'])) === 'disponible';
            $attrs = $esDisponible ? "onclick=\"document.getElementById('form-$index').submit()\" style=\"cursor: pointer;\"" : "";
        ?>
        <tr <?= $attrs ?>>
            <?php foreach ($cols as $col): ?>
                <td><?= htmlspecialchars((string)$fila[$col]) ?></td>
            <?php endforeach; ?>
        </tr>
        <?php if ($esDisponible): ?>
            <form id="form-<?= $index ?>" action="ver_hospedaje.php" method="post" style="display:none;">
                <?php foreach ($fila as $key => $value): ?>
                    <input type="hidden" name="<?= htmlspecialchars($key) ?>" value="<?= htmlspecialchars($value) ?>">
                <?php endforeach; ?>
            </form>
        <?php endif; ?>
    <?php endforeach; ?>
</tbody>

                </table>
            </div>
        <?php else: ?>
            <p>No hay hospedajes disponibles para mostrar.</p>
        <?php endif; ?>



                <p><a href="main.php">Volver al inicio</a></p>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const audio = document.getElementById('audio-bg');
        const btnPlay = document.getElementById('btn-play-audio');
        const btnPause = document.getElementById('btn-pause-audio');

        audio.addEventListener('errselect_avionor', () => {
            console.error('Error al cargar audio:', audio.error && audio.error.code);
        });

        btnPlay.addEventListener('click', () => {
            audio.play().then(() => {
                btnPlay.style.display = 'none';
                btnPause.style.display = 'inline-block';
            }).catch(err => {
                console.error('Falló audio.play():', err);
            });
        });

        btnPause.addEventListener('click', () => {
            audio.pause();
            btnPause.style.display = 'none';
            btnPlay.style.display = 'inline-block';
        });

        audio.addEventListener('ended', () => {
            btnPause.style.display = 'none';
            btnPlay.style.display = 'inline-block';
        });
    });
    </script>
</body>
</html>
