
<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header('Location: index.php?error=Debes iniciar sesión');
    exit();
}
$usuario= $_SESSION['usuario'];
$_SESSION['agenda_seleccionada']=$_POST['agenda_seleccionada'];
$agenda_s= $_SESSION['agenda_seleccionada'];
require_once 'utils.php';

try {
    $db = conectarBD();
$stmt = $db->prepare("
    SELECT 'hospedaje' AS tipo, h.nombre_hospedaje AS info, r.*
    FROM hospedaje h
    JOIN reserva r ON h.id = r.id
    WHERE r.agenda_id = :agenda_id

    UNION

    SELECT 'transporte' AS tipo, t.empresa AS info, r.*
    FROM transporte t
    JOIN reserva r ON t.id = r.id
    WHERE r.agenda_id = :agenda_id

    UNION

    SELECT 'panorama' AS tipo, p.nombre AS info, r.*
    FROM panorama p
    JOIN reserva r ON p.id = r.id
    WHERE r.agenda_id = :agenda_id
");

    $stmt->bindValue(':agenda_id', $agenda_s, PDO::PARAM_INT);
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($rows>0){
        $_SESSION['lista_reservas']=$rows;
        header('Location: show_reservas.php');
        exit();
    }else {
        $_SESSION['error'] = "Error";

    header('Location: desplegar_viaje.php');
        exit();
    }

} catch (Exception $e) {
    if ($db->inTransaction()) $db->rollBack();
    $_SESSION['error'] = 'Error ';
    header('Location: desplegar_viaje.php');
    exit();
}
?>

