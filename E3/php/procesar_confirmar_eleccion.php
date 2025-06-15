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

    $db->commit();
} catch (Exception $e) {
    if ($db->inTransaction()) {
        $db->rollBack();
    }
    echo "Error: " . htmlspecialchars($e->getMessage());
    exit;
}

