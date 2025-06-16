<?php
session_start();
$error = $_GET['error'] ?? null;
$hospedajes = $_SESSION['agendas_usuario']?? [];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>- Booked.com</title>
    <link rel="stylesheet" href="../css/style.css">

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
<h1>Agendas asociadas al correo: <?php echo htmlspecialchars($_SESSION['email']); ?></h1>       
 <?php if (!empty($hospedajes)): ?>
<form action="procesar_agenda_seleccionada.php" method="post">

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
                <tr>
            <td>
        <input type="radio" name="agenda_seleccionada" value="<?= htmlspecialchars($fila['id']) ?>" required>

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
            <p>El Usuario no tiene Viajes Agendados</p>
        <?php endif; ?>

                <p><a href="main.php">Volver al inicio</a></p>
 </body>

</html>
