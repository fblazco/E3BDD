<?php
session_start();
$_SESSION['detalle_panoramas'] = $_POST;
header('Location: buscar_transporte.php');
exit();
?>
