<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header('Location: index.php?error=Debes iniciar sesión');
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Detalles del Viaje</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="container">
        <h1>Detalles de Viaje</h1>
        <!-- Aquí se mostrarán los detalles del viaje -->
        <!-- La idea es que rellenen con lo solicitado en la sección 2.3 del enunciado -->
        <!-- Apoyate con los estilos css para que se vea bonito :) -->
        <?php
    require_once 'utils.php';
    $correo_usuario=$_SESSION['email'];
    try {
        $db = conectarBD();

        $stmt = $db->prepare("
     SELECT *
    FROM agenda 
    WHERE agenda.correo_usuario LIKE :email 
    ");
    $aux="%{$ciudad_llegada}%";
    $aux2="%{$ciudad_origen}%";
    $aux3=$cantidad_personas;
    $stmt->bindValue(':aux', $aux, PDO::PARAM_STR);
    $stmt->bindValue(':aux2', $aux2, PDO::PARAM_STR);
    $stmt->bindValue(':aux3', $aux3, PDO::PARAM_STR);
    $stmt->bindValue(':fecha_inicio', $fecha_i);
    $stmt->bindValue(':fecha_termino', $fecha_t);
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if ($rows>0){
        $_SESSION['aviones_disponibles']=$rows;
    header('Location: show_transportes_disponibles.php');
        exit();
    }else {
        $_SESSION['error'] = "No se encontraron avionesen “{$ciudad}”";

        exit();
    }

} catch (Exception $e) {
    if ($db->inTransaction()) $db->rollBack();
    $_SESSION['error'] = 'Error al crear el viaje';
    header('Location: index.php');
    exit();
}
        

        ?>
    </div>
</body>
</html>
