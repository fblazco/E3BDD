<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header('Location: index.php?error=Debes iniciar sesión');
    exit();
}
$usuario_id = $_SESSION['usuario'];
require_once 'utils.php';
// datos formulario
$nombre_viaje = $_POST['nombre'];
$descripcion_viaje =$_POST['descripcion'];
$fecha_inicio = $_POST['fecha_inicio'];
$fecha_termino = $_POST['fecha_fin'];
$ciudad = $_POST['ciudad'];
$organizador = $_POST['organizador'];
// se debe crear una agenda con los datos
$_SESSION['form_data']= $_POST;

try {
    $db = conectarBD();

    $stmt = $db->prepare("
    SELECT hospedaje.* , reserva.estado_disponibilidad    
FROM hospedaje JOIN reserva ON reserva.id = hospedaje.id 
where ubicacion LIKE :aux 
    ");
    $aux="%{$ciudad}%";
    $stmt->bindValue(':aux', $aux, PDO::PARAM_STR);
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if ($rows>0){
        $_SESSION['nombre_viaje']=$nombre_viaje;
        $_SESSION['descripcion_viaje']=$descripcion_viaje;
        $_SESSION['fecha_inicio']=$fecha_inicio;
        $_SESSION['fecha_termino']=$fecha_termino;
        $_SESSION['ciudad']=$ciudad;
        $_SESSION['organizador']=$organizador;
        $_SESSION['hospedajes_disponibles']=$rows;
        header('Location: show_hospedajes.php');

        exit();
    }else {
        $_SESSION['error'] = "No se encontraron hospedajes en “{$ciudad}”";
        header('Location: crear_viaje.php');
        exit();
    }

} catch (Exception $e) {
    if ($db->inTransaction()) $db->rollBack();
    $_SESSION['error'] = 'Error al crear el viaje';
    header('Location: crear_viaje.php');
    exit();
}
?>

