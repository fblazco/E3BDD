<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header('Location: index.php?error=Debes iniciar sesión primero');
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel Principal</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
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
                        <th>Precio Noche</th>
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
<td>
    <?= isset($precio) && $precio!== null ? htmlspecialchars((string)$precio) : '<i>valor no definido</i>' ?>
</td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No hay hospedajes seleccionados.</p>
        <?php endif; ?>
        <p><a href="show_hospedajes.php">Volver a Hospedajes</a></p>
    </div>

<div class="container">
        <h1>Panoramas seleccionados</h1>

        <?php
        $seleccionados_guardados = $_SESSION['panoramas_seleccionados'] ?? [];
        ?>

        <?php if (!empty($seleccionados_guardados)): ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Precio por persona</th>
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
<td>
    <?= isset($precio) && $precio!== null ? htmlspecialchars((string)$precio) : '<i>valor no definido</i>' ?>
</td>
 
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No hay panoramas seleccionados.</p>
        <?php endif; ?>

        <p><a href="show_panoramas.php">Volver a Panoramas</a></p>

    </div>
<div class="container">
        <h1>Participantes seleccionados:</h1>

        <?php
        $seleccionados_guardados = $_SESSION['entradas'] ?? [];
        ?>

        <?php if (!empty($seleccionados_guardados)): ?>
            <table>
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Edad</th>
                    </tr>
                </thead>
                <tbody>
                    
        <?php foreach ($seleccionados_guardados as $item): ?>
                <tr>
                    <td><?= htmlspecialchars($item['nombre']) ?></td>
                    <td><?= htmlspecialchars($item['edad']) ?></td>
                </tr>
            <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No hay integrantes seleccionados.</p>
        <?php endif; ?>

        <p><a href="show_panoramas.php">Volver a Panoramas</a></p>

    </div>


<div class="container">
        <h1>Transportes seleccionados</h1>

        <?php
        $seleccionados_guardados = $_SESSION['transportes_seleccionados'] ?? [];
        ?>

        <?php if (!empty($seleccionados_guardados)): ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Precio por asiento</th>
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
<td>
    <?= isset($precio) && $precio!== null ? htmlspecialchars((string)$precio) : '<i>valor no definido</i>' ?>
</td>
 
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        
        <?php else: ?>
            <p>No hay Transportes seleccionados.</p>
        <?php endif; ?>

        <p><a href="show_transportes.php">Volver a Transportes</a></p>
 <div style="margin-top: 1em;">
  <a href="procesar_confirmar_eleccion.php"
     onclick="return confirm('CUIDADO ESTA ACCION ES IRREVERSIBLE');"
     style="display:inline-block; padding:8px 16px; background:#007bff; color:white; text-decoration:none; border-radius:4px;">
    Confirmar Viaje (Agenda)
  </a>
</div>
        
</body>
</html>


