
<?php
session_start();
$detalle = $_SESSION['detalle_panoramas'] ?? null;
?>
<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header('Location: index.php?error=Debes iniciar sesión');
    exit();
}
$usuario_id = $_SESSION['usuario'];
$ciudad = $_SESSION['ciudad'];
require_once 'utils.php';

try {
    $db = conectarBD();

    $stmt = $db->prepare("
 SELECT transporte.*, reserva.estado_disponibilidad
 FROM transporte JOIN reserva ON transporte.id = reserva.id
WHERE lugar_llegada LIKE :aux 
    ");
    $aux="%{$ciudad}%";
    $stmt->bindValue(':aux', $aux, PDO::PARAM_STR);
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if ($rows>0){
        $_SESSION['transportes_disponibles']=$rows;
        header('Location: show_transportes.php');
        exit();
    }else {
        $_SESSION['error'] = "No se encontraron panoramas en “{$ciudad}”";

        exit();
    }

} catch (Exception $e) {
    if ($db->inTransaction()) $db->rollBack();
    $_SESSION['error'] = 'Error al crear el viaje';
    header('Location: main.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Detalle del Hospedaje</title>
</head>
<body>
    <h1>Detalle del Hospedaje</h1>
    <?php if ($detalle): ?>
        <ul>
            <?php foreach ($detalle as $k => $v): ?>
                <li><strong><?= htmlspecialchars($k) ?>:</strong> <?= htmlspecialchars($v) ?></li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>No se recibió información del hospedaje.</p>
    <?php endif; ?>
</body>
</html>
