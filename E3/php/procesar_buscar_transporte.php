
<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header('Location: index.php?error=Debes iniciar sesión');
    exit();
}
$lugar_origen = $_POST['lugar_origen'] ?? '';
$lugar_llegada = $_POST['lugar_llegada'] ?? '';
$_SESSION['avion_origen'] = $lugar_origen;
$_SESSION['avion_llegada'] = $lugar_llegada;

$usuario_id = $_SESSION['usuario'];
$ciudad_origen= $_SESSION['avion_origen'];
$ciudad_llegada = $_SESSION['avion_llegada'];
$cantidad_personas=$_SESSION['cantidad_personas'];
require_once 'utils.php';

try {
    $db = conectarBD();

    $stmt = $db->prepare("
 SELECT 'avion' AS tipo, transporte.*, r.estado_disponibilidad FROM avion a
JOIN reserva r ON a.id = r.id
JOIN transporte ON a.id = transporte.id
WHERE transporte.lugar_llegada LIKE :aux 
AND transporte.lugar_origen LIKE :aux2
AND transporte.capacidad >= :aux3

UNION

SELECT 'bus' AS tipo, transporte.*, r.estado_disponibilidad 
FROM bus b
JOIN reserva r ON b.id = r.id
JOIN transporte ON transporte.id = b.id
WHERE transporte.lugar_llegada LIKE :aux 
AND transporte.lugar_origen LIKE :aux2
AND transporte.capacidad >= :aux3

UNION

SELECT 'tren' AS tipo, transporte.*, r.estado_disponibilidad
FROM tren t
JOIN reserva r ON t.id = r.id
JOIN transporte ON transporte.id = t.id
WHERE transporte.lugar_llegada LIKE :aux 
AND transporte.lugar_origen LIKE :aux2
AND transporte.capacidad >= :aux3
");
    $aux="%{$ciudad_llegada}%";
    $aux2="%{$ciudad_origen}%";
    $aux3=$cantidad_personas;
    $stmt->bindValue(':aux', $aux, PDO::PARAM_STR);
    $stmt->bindValue(':aux2', $aux2, PDO::PARAM_STR);
    $stmt->bindValue(':aux3', $aux3, PDO::PARAM_STR);
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if ($rows>0){
        $_SESSION['aviones_disponibles']=$rows;
    header('Location: show_transportes_disponibles.php');
        exit();
    }else {
        $_SESSION['error'] = "No se encontraron avionesen “{$ciudad}”";

        exit();
    }

} catch (Exception $e) {
    if ($db->inTransaction()) $db->rollBack();
    $_SESSION['error'] = 'Error al crear el viaje';
    header('Location: index.php');
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
