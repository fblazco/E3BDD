
<?php
session_start();
require_once 'utils.php'; 
$objetivo_eliminar=$_POST['seleccionado_eliminar'];
try {
    $db = conectarBD();
    $db->beginTransaction();

    $stmt = $db->query("
    DELETE * FROM empleado WHERE empleado.correo LIKE :objetivo    
    ");
    $stmt->bindParam(':objetivo',$objetivo_eliminar,PDO::PARAM_STR);
    


    $db->commit();
    header('menu_empleado.php');
} catch (Exception $e) {
    if ($db->inTransaction()) {
        $db->rollBack();
    }
    echo "Error: " . htmlspecialchars($e->getMessage());
    exit;
}
