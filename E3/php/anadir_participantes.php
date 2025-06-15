<?php
session_start();

if (!isset($_SESSION['entradas1'])) {
    $_SESSION['entradas1'] = [];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $nombre = trim($_POST['nombre'] ?? '');
    $edad = trim($_POST['edad'] ?? '');

    if ($action === 'agregar') {
        if ($nombre === '' || $edad === '') {
            $_SESSION['msg_error'] = 'Debe completar nombre y edad para agregar.';
        } elseif (!ctype_digit($edad)) {
            $_SESSION['msg_error'] = 'Edad debe ser un número entero.';
        } else {
            $_SESSION['entradas1'][] = ['nombre' => $nombre, 'edad' => $edad];
            unset($_SESSION['msg_error']);
        }
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit();
    }
    elseif ($action === 'finalizar') {
        $_SESSION['entradas']=$_SESSION['entradas1'];
        $_SESSION['entradas1']=[];

        header('Location: buscar_transporte.php');
        exit();
    }
}

$msgError = $_SESSION['msg_error'] ?? null;
unset($_SESSION['msg_error']);
$listaActual = $_SESSION['entradas1'] ?? [];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ingreso de nombre y edad </title>
    <link rel="stylesheet" href="../css/style.css">
    </head>
<body>
    <?php if ($msgError): ?>
        <p class="error"><?= htmlspecialchars($msgError) ?></p>
    <?php endif; ?>
    <form method="post" action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>">
        <div class="container">
    <h1>Ingreso de nombre y edad </h1>
            <label>Nombre:
                <input type="text" name="nombre" value="">
            </label>
            <label>Edad:
                <input type="text" name="edad" value="">
            </label>
        </div>
            <button type="submit" name="action" value="agregar">Agregar</button>
            <?php if (!empty($listaActual)): ?>
                <button type="submit" name="action" value="finalizar">Finalizar y buscar transportes</button>
            <?php endif; ?>
    </form>

        <div class="container">
    <?php if (!empty($listaActual)): ?>
        <h2>Lista actual</h2>
        <table>
            <thead><tr><th>Nombre</th><th>Edad</th></tr></thead>
            <tbody>
            <?php foreach ($listaActual as $item): ?>
                <tr>
                    <td><?= htmlspecialchars($item['nombre']) ?></td>
                    <td><?= htmlspecialchars($item['edad']) ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
                </div>
</body>
</html>

