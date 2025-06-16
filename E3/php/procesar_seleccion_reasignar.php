<?php
session_start();

//var_dump($_POST['seleccionados']); 
require_once 'utils.php';
$_SESSION['empleado_seleccionado'] = $_POST['empleado_seleccionado'] ?? [];
$empleado=$_SESSION['empleado_seleccionado'];
try {
    $db = conectarBD();

    $stmt = $db->prepare("
 SELECT * 
FROM transporte 
WHERE correo_empleado LIKE :aux 
    ");
    $aux="%{$empleado}%";
    $stmt->bindValue(':aux', $aux, PDO::PARAM_STR);
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if ($rows>0){
        $_SESSION['empleado_transportes_disponibles']=$rows;
        header('Location: show_objetivo_reasignar.php');
        exit();
    }else {
        $_SESSION['error'] = "No se encontraron transportes  “{$empleado}”";

        exit();
    }

} catch (Exception $e) {
    if ($db->inTransaction()) $db->rollBack();
    $_SESSION['error'] = 'Error al buscar datos del empleado';
    header('Location: reasignar_empleado.php');
    exit();
}
?>

