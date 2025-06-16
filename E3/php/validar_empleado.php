<?php
session_start();
require_once 'utils.php';

$usuario = $_POST['empleado'] ?? '';
$contrasena = $_POST['pass'] ?? '';

$db = conectarBD();

$query = "
    SELECT p.correo, p.contrasena    
    FROM persona p
    JOIN empleado ON p.correo = empleado.correo
    WHERE p.username = :usuario AND p.contrasena = :contrasena
";

$stmt = $db->prepare($query);
$stmt->bindParam(':usuario', $usuario);
$stmt->bindParam(':contrasena', $contrasena);
$stmt->execute();

$resultado = $stmt->fetch();

if ($resultado) {
    $_SESSION['empleado'] = $usuario;
    $_SESSION['correo_empleado']= $resultado['correo'];
    header('Location: menu_empleado.php');
    exit();
} else {
    header('Location: index.php?error=Empleado no existe o contrasena errónea');
    exit();
}
?>
