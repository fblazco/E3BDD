<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header('Location: index.php?error=Debes iniciar sesión');
    exit();
}
$usuario_id = $_SESSION['usuario'];

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /php/crear_viaje.php?mensaje=' . urlencode("Método inválido"));
    exit();
}


// Obtener datos del formulario
$nombre_viaje = $_POST['nombre_viaje'] ?? '';
$fecha_inicio = $_POST['fecha_inicio'] ?? null;
$fecha_fin = $_POST['fecha_fin'] ?? null;
// Participantes manuales
$participantes_nombres = $_POST['participantes_nombres'] ?? [];
$participantes_emails = $_POST['participantes_emails'] ?? [];
// Agenda
$agenda_fechas = $_POST['agenda_fecha'] ?? [];
$agenda_descs = $_POST['agenda_descripcion'] ?? [];
$agenda_trans_ids = $_POST['agenda_transporte_id'] ?? [];
$agenda_pano_ids = $_POST['agenda_panorama_id'] ?? [];
$agenda_montos = $_POST['agenda_monto'] ?? [];

try {
    // Iniciar transacción
    $pdo->beginTransaction();

    // 1) Insertar nuevo viaje
    $stmt = $pdo->prepare("INSERT INTO viajes (usuario_id, nombre, fecha_inicio, fecha_fin, creado_en) VALUES (?, ?, ?, ?, NOW())");
    $stmt->execute([$usuario_id, $nombre_viaje, $fecha_inicio, $fecha_fin]);
    $viaje_id = $pdo->lastInsertId();

    // 2) Insertar participantes
    // Opción: CSV subido
    if (isset($_FILES['archivo_participantes']) && is_uploaded_file($_FILES['archivo_participantes']['tmp_name'])) {
        $tmp = $_FILES['archivo_participantes']['tmp_name'];
        // Leer CSV línea por línea
        if (($handle = fopen($tmp, "r")) !== FALSE) {
            // Asume encabezado en la primera línea o no; ajusta según tu CSV
            // Ejemplo: la primera columna es correo, segunda nombre
            $first = true;
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                if ($first) { $first = false; continue; }
                $correo = trim($data[0]);
                $nombre = trim($data[1] ?? '');
                if ($correo) {
                    // Verificar o crear usuario si no existe
                    $stmtU = $pdo->prepare("SELECT id FROM usuarios WHERE email = ?");
                    $stmtU->execute([$correo]);
                    $row = $stmtU->fetch(PDO::FETCH_ASSOC);
                    if ($row) {
                        $part_id = $row['id'];
                    } else {
                        // Crear usuario
                        $stmtInsU = $pdo->prepare("INSERT INTO usuarios (nombre, email, creado_en) VALUES (?, ?, NOW())");
                        $stmtInsU->execute([$nombre, $correo]);
                        $part_id = $pdo->lastInsertId();
                    }
                    // Insertar en participantes del viaje
                    $stmtP = $pdo->prepare("INSERT INTO participantes (viaje_id, usuario_id) VALUES (?, ?)");
                    $stmtP->execute([$viaje_id, $part_id]);
                }
            }
            fclose($handle);
        }
    }
    // Opcional: participantes manuales
    for ($i = 0; $i < count($participantes_emails); $i++) {
        $email = trim($participantes_emails[$i]);
        $nombre = trim($participantes_nombres[$i] ?? '');
        if ($email) {
            // Verificar o crear usuario
            $stmtU = $pdo->prepare("SELECT id FROM usuarios WHERE email = ?");
            $stmtU->execute([$email]);
            $row = $stmtU->fetch(PDO::FETCH_ASSOC);
            if ($row) {
                $part_id = $row['id'];
            } else {
                $stmtInsU = $pdo->prepare("INSERT INTO usuarios (nombre, email, creado_en) VALUES (?, ?, NOW())");
                $stmtInsU->execute([$nombre, $email]);
                $part_id = $pdo->lastInsertId();
            }
            // Insertar relación
            $stmtP = $pdo->prepare("INSERT INTO participantes (viaje_id, usuario_id) VALUES (?, ?)");
            $stmtP->execute([$viaje_id, $part_id]);
        }
    }

    // 3) Insertar agenda y reservas asociadas
    for ($i = 0; $i < count($agenda_fechas); $i++) {
        $f = $agenda_fechas[$i];
        $desc = $agenda_descs[$i];
        $trans_id = $agenda_trans_ids[$i] ?: null;
        $pano_id = $agenda_pano_ids[$i] ?: null;
        $monto = is_numeric($agenda_montos[$i]) ? (float)$agenda_montos[$i] : 0;
        // Insertar en tabla agenda
        $stmtA = $pdo->prepare("INSERT INTO agenda (viaje_id, fecha_actividad, descripcion, transporte_id, panorama_id, monto_reserva) VALUES (?, ?, ?, ?, ?, ?)");
        $stmtA->execute([$viaje_id, $f, $desc, $trans_id, $pano_id, $monto]);
        // Si necesitas insertar en tabla reservas independientemente:
        // Si hay monto > 0, crear registro en reservas
        if ($monto > 0) {
            $stmtR = $pdo->prepare("INSERT INTO reservas (viaje_id, usuario_id, monto, fecha_reserva) VALUES (?, ?, ?, NOW())");
            $stmtR->execute([$viaje_id, $usuario_id, $monto]);
        }
    }

    // 4) Commit de la transacción
    $pdo->commit();

    // 5) Llamar al SP1 para calcular y agregar puntos al usuario; asumimos que SP1 existe
    // Por ejemplo: CALL sp_calcular_puntos_usuario(?);
    $stmtSP = $pdo->prepare("CALL sp_calcular_puntos_usuario(?)");
    $stmtSP->execute([$usuario_id]);

    // Redirigir con éxito
    header('Location: /php/crear_viaje.php?mensaje=' . urlencode("Agenda creada correctamente"));
    exit();

} catch (Exception $e) {
    // Rollback en caso de error
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    // Redirigir con mensaje de error
    $msg = "No se pudo crear el viaje: " . $e->getMessage();
    header('Location: crear_viaje.php?mensaje=' . urlencode($msg));
    exit();
}
?>
