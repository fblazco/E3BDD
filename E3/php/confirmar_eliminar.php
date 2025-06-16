<?php
session_start();
require_once 'utils.php'; 

$objetivo_eliminar = $_POST['correo_eliminar'];

try {
    $db = conectarBD();
    $db->beginTransaction();

    $stmt1 = $db->prepare("
        UPDATE transporte
        SET correo_empleado =NULL 
        WHERE correo_empleado = :correo_eliminar
    ");
    $stmt1->bindParam(':correo_eliminar', $objetivo_eliminar, PDO::PARAM_STR);
    $stmt1->execute();

    $stmt = $db->prepare("
        DELETE FROM empleado WHERE correo = :correo
    ");
    $stmt->bindParam(':correo', $objetivo_eliminar, PDO::PARAM_STR);
    $stmt->execute(); 

    $db->commit();

    header('Location: menu_empleado.php');
    exit;

} catch (Exception $e) {
    if ($db->inTransaction()) {
        $db->rollBack();
    }
    echo "Error: " . htmlspecialchars($e->getMessage());
    exit;
}

