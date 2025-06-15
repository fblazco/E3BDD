<?php
session_start();
require_once 'utils.php'; 

var_dump($_SESSION['hospedajes_seleccionados']); 
var_dump($_SESSION['panoramas_seleccionados']); 
var_dump($_SESSION['entradas']); 
var_dump($_SESSION['transportes_seleccionados']);
var_dump($_SESSION['email']);
var_dump($_SESSION['etiqueta']);
$correo_usuario=$_SESSION['email'];
$etiqueta=$_SESSION['etiqueta'];
$cantidad_personas=$_SESSION['cantidad_personas'];
try {
    $db = conectarBD();
    $db->beginTransaction();

    $stmt = $db->query("SELECT MAX(id) AS max_id FROM agenda");
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $nextId = ($row && $row['max_id'] !== null) ? ((int)$row['max_id'] + 1) : 1;

    $insert = $db->prepare("
        INSERT INTO agenda (id, correo_usuario, etiqueta)
        VALUES (:id, :correo, :etiqueta)
    ");

    $insert->bindValue(':id', $nextId, PDO::PARAM_INT);
    $insert->bindValue(':correo', $correo_usuario, PDO::PARAM_STR);
    $insert->bindValue(':etiqueta', $etiqueta, PDO::PARAM_STR);
    $insert->execute();
    $ids = array_merge(
        $_SESSION['hospedajes_seleccionados'] ?? [],
        $_SESSION['panoramas_seleccionados'] ?? [],
        $_SESSION['transportes_seleccionados'] ?? []
    );
    $updateReservaStmt = $db->prepare("
        UPDATE reserva
        SET 
            agenda_id = :agenda_id,
            cantidad_personas = :cantidad_personas,
            fecha = CURRENT_DATE
        WHERE id = :reserva_id
    ");

    foreach ($ids as $lista_id) {
        $updateReservaStmt->bindValue(':agenda_id', $nextId, PDO::PARAM_INT);
        $updateReservaStmt->bindValue(':reserva_id', $lista_id, PDO::PARAM_STR);
        $updateReservaStmt->bindValue(':cantidad_personas', $cantidad_personas, PDO::PARAM_INT);
        $updateReservaStmt->execute();
    }
    
     if (!empty($_SESSION['entradas']) && is_array($_SESSION['entradas'])) {
        $participanteStmt = $db->prepare("
            INSERT INTO participante (id, panorama_id, nombre, edad)
            VALUES (:id, :panorama_id, :nombre, :edad)
        ");
        foreach ($_SESSION['entradas'] as $entrada) {
            $nombre = $entrada['nombre'] ?? null;
            $edad = $entrada['edad'] ?? null;
            $stmt = $db->query("SELECT MAX(id) AS max_id FROM participante");
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $nextId = ($row && $row['max_id'] !== null) ? ((int)$row['max_id'] + 1) : 1;

            foreach ($_SESSION['panoramas_seleccionados'] ?? [] as $panorama_id) {
                $participanteStmt->bindValue(':panorama_id', $panorama_id, PDO::PARAM_INT);
                $participanteStmt->bindValue(':nombre', $nombre, PDO::PARAM_STR);
                $participanteStmt->bindValue(':edad', $edad, PDO::PARAM_INT);
                $participanteStmt->bindValue(':id', $nextId, PDO::PARAM_INT);
                $participanteStmt->execute();
            }
        }
    }



    $db->commit();
} catch (Exception $e) {
    if ($db->inTransaction()) {
        $db->rollBack();
    }
    echo "Error: " . htmlspecialchars($e->getMessage());
    exit;
}

