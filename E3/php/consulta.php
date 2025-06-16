<?php
session_start();
require_once 'utils.php';

$db = conectarBD();
$tablas = [
      'agenda', 'habitacion', 'participante', 'avion', 'reserva',
 'airbnb', 'bus', 'tren', 'review', 'persona', 'panorama',
 'usuario', 'transporte', 'seguro', 'empleado', 'hospedaje', 'hotel'
];
try {
    $stmtTablas = $db->query("
        SELECT table_name 
        FROM information_schema.tables 
        WHERE table_schema = 'public'
    ");
    $tablas = $stmtTablas->fetchAll(PDO::FETCH_COLUMN);
} catch (Exception $e) {
    $tablas = [];
}

$columnaSel = $_POST['columna'] ?? '';
$tablaSel = $_POST['tabla'] ?? '';
$whereCampo = $_POST['campo'] ?? '';
$whereValor = $_POST['valor'] ?? '';
$resultados = [];
$error = '';

$columnaSel = preg_replace('/[^a-zA-Z0-9_]/', '', $columnaSel);
$tablaSel = preg_replace('/[^a-zA-Z0-9_]/', '', $tablaSel);
$whereCampo = preg_replace('/[^a-zA-Z0-9_]/', '', $whereCampo);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $sql = "SELECT $columnaSel FROM $tablaSel WHERE $whereCampo LIKE :valor";
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':valor', "%$whereValor%", PDO::PARAM_STR);
        $stmt->execute();
        $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        $error = "Error en la consulta: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Consulta Inestructurada</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        .formulario, table {
            margin: 1em auto;
            max-width: 800px;
        }
        input, select {
            width: 100%;
            padding: 6px;
            margin-bottom: 10px;
        }
        table {
            border-collapse: collapse;
            width: 100%;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 8px;
        }
        .error {
            color: red;
            font-weight: bold;
            text-align: center;
        }
    </style>
</head>
<body>
<div class="container" >
    <h1 style="text-align:center;">Consulta Inestructurada Guiada</h1>

    <?php if ($error): ?>
        <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST" class="formulario">
        <div class="form-group">
            <label for="columna">Columna a mostrar (SELECT):</label>
            <input type="text" name="columna" id="columna" required value="<?= htmlspecialchars($columnaSel) ?>">
        </div>

        <div class="form-group">
            <label for="tabla">Tabla (FROM):</label>
            <select name="tabla" id="tabla" required>
                <option value="">-- Seleccionar tabla --</option>
                <?php foreach ($tablas as $tabla): ?>
                    <option value="<?= $tabla ?>" <?= $tablaSel === $tabla ? 'selected' : '' ?>>
                        <?= $tabla ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="campo">Campo para condición (WHERE):</label>
            <input type="text" name="campo" id="campo" required value="<?= htmlspecialchars($whereCampo) ?>">
        </div>

        <div class="form-group">
            <label for="valor">Valor a buscar (LIKE '%valor%'):</label>
            <input type="text" name="valor" id="valor" required value="<?= htmlspecialchars($whereValor) ?>">
        </div>

        <button type="submit">Consultar</button>
    </form>

    <?php if ($resultados): ?>
        <h2 style="text-align:center;">Resultados</h2>
        <table>
            <thead>
                <tr>
                    <th><?= htmlspecialchars($columnaSel) ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($resultados as $fila): ?>
                    <tr>
                        <td><?= htmlspecialchars($fila[$columnaSel] ?? '') ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php elseif ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
        <p style="text-align:center;">No se encontraron resultados.</p>
    <?php endif; ?>   <p><a href="main.php">Volver al inicio</a></p></div>
</body>
</html>

