
<?php
session_start();
if (!isset($_SESSION['empleado'])) {
    header('Location: index.php?error=Debes iniciar sesión');
    exit();
}
$objetivo = $_POST['empleado_eliminar'];
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
    $aux="%{$objetivo}%";
    $aux2=$me;
    $stmt->bindValue(':aux', $aux, PDO::PARAM_STR);
    $stmt->bindValue(':me', $aux2, PDO::PARAM_STR);
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    

    if ($rows>0){
        $_SESSION['lista_eliminar']=$rows;
        header('Location: show_eliminar.php');
        exit();
    }else {
        $_SESSION['error'] = "No se encontraron empleados“{$objetivo}”";

        exit();
    }

} catch (Exception $e) {
    if ($db->inTransaction()) $db->rollBack();
    $_SESSION['error'] = 'Error al buscar el empleado';
    header('Location: index.php');
    exit();
}
?>
