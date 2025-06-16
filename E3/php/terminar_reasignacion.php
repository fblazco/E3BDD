<?php
session_start();
require_once 'utils.php';

$correo_base = $_SESSION['empleado_seleccionado'];    
$correo_objetivo = $_SESSION['objetivo_seleccionado'];
$id_transporte = $_POST['viaje_seleccionado'];

try {
    $db = conectarBD();
    $db->beginTransaction();

    $stmt = $db->prepare("
        UPDATE transporte
        SET correo_empleado = :correo_objetivo
        WHERE id = :id_transporte
    ");

    $stmt->bindValue(':correo_objetivo', $correo_objetivo, PDO::PARAM_STR);
    $stmt->bindValue(':id_transporte', $id_transporte, PDO::PARAM_INT);
    $stmt->execute();

    $db->commit();

    header("Location: reasignar_empleado.php?exito=1");
    exit;

} catch (Exception $e) {
    if ($db->inTransaction()) {
        $db->rollBack();
    }
    echo "Error: " . htmlspecialchars($e->getMessage());
    exit;
}
?>

