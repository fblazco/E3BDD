<?php
session_start();

$nuevos = $_POST['seleccionados'] ?? [];

if (!isset($_SESSION['seleccionados_transporte'])) {
    $_SESSION['seleccionados_transporte'] = [];
}

$_SESSION['seleccionados_transporte'] = array_unique(array_merge(
    $_SESSION['seleccionados_transporte'],
    $nuevos
));

header('Location: buscar_transporte.php');
exit;

