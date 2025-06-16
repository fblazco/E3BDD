<?php
session_start();

require_once 'utils.php';

$reservas = $_SESSION['lista_reservas'] ?? [];

$idSeleccionado = $_POST['reserva_seleccionada'] ?? null;

if (!$idSeleccionado) {
    header("Location: show_reservas.php?error=No se seleccionó ninguna reserva");
    exit;
}

$filaSeleccionada = null;
foreach ($reservas as $reserva) {
    if ((string)$reserva['id'] === (string)$idSeleccionado) {
        $filaSeleccionada = $reserva;
        break;
    }
}

if (!$filaSeleccionada) {
    header("Location: show_reservas.php?error=Reserva no encontrada");
    exit;
}

$tipo = strtolower(trim($filaSeleccionada['tipo'] ?? ''));
$id = $filaSeleccionada['id']??'';
$db = conectarBD();
switch ($tipo) {
case 'hospedaje':
    $stmt = $db->prepare("
    SELECT *  
    FROM hospedaje h
    WHERE h.id = :aux 
    ");
    $stmt->bindValue(':aux', $id, PDO::PARAM_INT);
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if ($rows >0){
        $_SESSION['detalles']=$rows;
         header("Location: show_detalles_hospedaje.php?id=" . urlencode($idSeleccionado));
        break;

    }
case 'transporte':
    $stmt = $db->prepare("
    SELECT *  
    FROM transporte h
    WHERE h.id = :aux 
    ");
    $stmt->bindValue(':aux', $id, PDO::PARAM_INT);
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if ($rows >0){
        $_SESSION['detalles']=$rows;
         header("Location: show_detalles_hospedaje.php?id=" . urlencode($idSeleccionado));
        break;

    }
case 'panorama':
     $stmt = $db->prepare("
    SELECT *  
    FROM panorama h
    JOIN participante p 
    ON h.id = p.panorama_id
    WHERE h.id = :aux 
    ");
    $stmt->bindValue(':aux', $id, PDO::PARAM_INT);
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if ($rows >0){
        $_SESSION['detalles']=$rows;
         header("Location: show_detalles_hospedaje.php?id=" . urlencode($idSeleccionado));
        break;

    }   
default:
        header("Location: show_reservas.php?error=Tipo desconocido: " . urlencode($tipo));
        break;
}

exit;

