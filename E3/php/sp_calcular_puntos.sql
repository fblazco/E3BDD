CREATE OR REPLACE FUNCTION calcular_puntos_agenda(id_agenda INT)
RETURNS VOID AS $$
DECLARE
    correo_usuario TEXT;
    total_monto NUMERIC;
    puntos_nuevos INT;
BEGIN
    SELECT correo_usuario INTO correo_usuario 
    FROM agenda
    WHERE id = id_agenda;

    SELECT SUM(monto) INTO total_monto
    FROM reserva
    WHERE agenda_id = id_agenda;

    IF total_monto IS NOT NULL THEN
        puntos_nuevos := FLOOR(total_monto / 1000);

        UPDATE usuario
        SET puntos = COALESCE(puntos, 0) + puntos_nuevos
        WHERE correo = correo_usuario;
    END IF;
END;
$$ LANGUAGE plpgsql;

