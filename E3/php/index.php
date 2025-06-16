<?php
session_start();
$error = $_GET['error'] ?? null;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Inicio de sesión - Booked.com</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="container">
        <h1>Bienvenido a BOOKED.com</h1>

        <form action="/php/validar_login.php" method="POST" class="formulario">
            <label for="usuario">Usuario:</label>
            <input type="text" id="usuario" name="usuario" required>

            <label for="contrasena">Contraseña:</label>
            <input type="password" id="contrasena" name="contrasena" required>

            <button type="submit">Iniciar sesión</button>
        </form>

        <p>¿No tienes cuenta? <a href="/php/registro.php">Regístrate aquí</a></p>

        <?php if ($error): ?>
            <p class="error"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>

        <div class="audio-control" style="margin-top:1em;">
            <audio id="audio-bg" src="/php/musica.mp3" preload="none"></audio>

            <button id="btn-play-audio" type="button">Reproducir música</button>
            <button id="btn-pause-audio" type="button" style="display: none;">Pausar música</button>
        </div>
    </div>
    <div class="container">
        <h1>Log Empleados</h1>

        <form action="/php/validar_empleado.php" method="POST" class="formulario">
            <label for="empleado">Usuario:</label>
            <input type="text" id="empleado" name="empleado" required>


            <label for="pass">Contraseña:</label>
            <input type="password" id="pass" name="pass" required>

            <button type="submit">Iniciar sesión</button>
        </form>


        <?php if ($error): ?>
            <p class="error"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>

   </div>


    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const audio = document.getElementById('audio-bg');
        const btnPlay = document.getElementById('btn-play-audio');
        const btnPause = document.getElementById('btn-pause-audio');

        audio.addEventListener('error', () => {
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

