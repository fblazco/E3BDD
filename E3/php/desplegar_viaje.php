
<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header('Location: index.php?error=Debes iniciar sesión');
    exit();
}
$correo_usuario= $_SESSION['email'];


require_once 'utils.php';
$_SESSION['form_data']= $_POST;

try {
    $db = conectarBD();

    $stmt = $db->prepare("
    SELECT id, etiqueta
FROM agenda
where correo_usuario LIKE :aux 
    ");
    $aux="%{$correo_usuario}%";
    $aux2=$cantidad_personas;
    $stmt->bindValue(':aux', $aux, PDO::PARAM_STR);
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if ($rows>0){
        $_SESSION['agendas_usuario']=$rows;
        header('Location: show_agendas.php');
        exit();
    }else {
        $_SESSION['error'] = "No se encontraron hospedajes en “{$ciudad}”";
        header('Location: desplegar_viaje.php');
        
        exit();
    }

} catch (Exception $e) {
    if ($db->inTransaction()) $db->rollBack();
    $_SESSION['error'] = 'Error al crear el viaje';
    header('Location: main.php');
    exit();
}
?>
