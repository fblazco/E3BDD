
<?php
session_start();
if (!isset($_SESSION['empleado'])) {
    header('Location: index.php?error=Debes iniciar sesión');
    exit();
}
$lugar_origen = $_POST['empleado_reasignar'] ?? '';
$objetivo = $_POST['empleado_objetivo'];
$_SESSION['empleado_objetivo']=$objetivo;
$_SESSION['empleado_reasignar'] = $lugar_origen;
$me=$_SESSION['empleado'];
require_once 'utils.php';

try {
    $db = conectarBD();

    $stmt = $db->prepare("
SELECT * 
FROM empleado e 
JOIN persona p
ON p.correo = e.correo
WHERE p.nombre LIKE :aux
AND p.correo NOT LIKE :me
");
    $aux="%{$lugar_origen}%";
    $aux2=$lugar_origen;
    $stmt->bindValue(':aux', $aux, PDO::PARAM_STR);
    $stmt->bindValue(':me', $aux2, PDO::PARAM_STR);
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $stmt = $db->prepare("
SELECT * 
FROM empleado e 
JOIN persona p
ON p.correo = e.correo
WHERE p.nombre LIKE :aux
AND p.correo NOT LIKE :me
");
    $aux="%{$objetivo}%";
    $aux2="%{$me}%";
    $stmt->bindValue(':aux', $aux, PDO::PARAM_STR);
    $stmt->bindValue(':me', $aux2, PDO::PARAM_STR);
    $stmt->execute();
    $rows2 = $stmt->fetchAll(PDO::FETCH_ASSOC);
 



    if ($rows2>0 and $rows>0){
        $_SESSION['empleados_disponibles']=$rows;
        $_SESSION['objetivos_disponibles']=$rows2;
    header('Location: show_empleados_reasignar.php');
        exit();
    }else {
        $_SESSION['error'] = "No se encontraron empleados“{$ciudad}”";

        exit();
    }

} catch (Exception $e) {
    if ($db->inTransaction()) $db->rollBack();
    $_SESSION['error'] = 'Error al buscar el empleado';
    header('Location: index.php');
    exit();
}
?>

