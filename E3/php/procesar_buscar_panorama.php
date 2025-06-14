<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header('Location: index.php?error=Debes iniciar sesión');
    exit();
}
$usuario_id = $_SESSION['usuario'];
$_SESSION['lugar_panorama']=$_POST['lugar_panorama'];
$ciudad = $_SESSION['lugar_panorama'];
require_once 'utils.php';

try {
    $db = conectarBD();

    $stmt = $db->prepare("
 SELECT  panorama.*, reserva.estado_disponibilidad
FROM panorama JOIN reserva ON panorama.id = reserva.id 
WHERE ubicacion LIKE :aux
    ");
    $aux="%{$ciudad}%";
    $stmt->bindValue(':aux', $aux, PDO::PARAM_STR);
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if ($rows>0){
        $_SESSION['panoramas_disponibles']=$rows;
        header('Location: show_panoramas.php');
        exit();
    }else {
        $_SESSION['error'] = "No se encontraron panoramas en “{$ciudad}”";

        exit();
    }

} catch (Exception $e) {
    if ($db->inTransaction()) $db->rollBack();
    $_SESSION['error'] = 'Error al crear el viaje';
    header('Location: crear_viaje.php');
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

