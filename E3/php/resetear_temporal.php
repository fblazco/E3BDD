<?php
session_start();


    $_SESSION['seleccionados_transporte'] = [];
header('Location: buscar_transporte.php');
exit;

