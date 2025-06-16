<?php
session_start();

//var_dump($_POST['seleccionados']); 

$_SESSION['hospedajes_seleccionados'] = $_POST['seleccionados'] ?? [];
?>

<!DOCTYPE html>
<html lang="es">
<head>
</head>
   <meta charset="UTF-8">
    <title>Confirmar Panoramas Seleccionados</title>
    <link rel="stylesheet" href="../css/style.css">
<body>
    <div class="container">
        <h1>Hospedajes seleccionados</h1>

        <?php
        $seleccionados_guardados = $_SESSION['hospedajes_seleccionados'] ?? [];
        ?>

        <?php if (!empty($seleccionados_guardados)): ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($seleccionados_guardados as $valor): 
                        list($id, $precio) = explode('|', $valor);
                        ?>
                        <tr>
<td>
    <?= isset($id) && $id !== null ? htmlspecialchars((string)$id) : '<i>valor no definido</i>' ?>
</td>

                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
<div style="margin-top: 1em;">
    <a href="buscar_panorama.php" style="display:inline-block; padding:8px 16px; background:#007bff; color:white; text-decoration:none; border-radius:4px;">
        Confirmar selección
    </a>
</div>
         
        <?php else: ?>
            <p>No hay hospedajes seleccionados.</p>
        <?php endif; ?>

        <p><a href="show_hospedajes.php">Volver a la lista</a></p>
    </div>
</body>
</html>

