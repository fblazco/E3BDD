CREATE OR REPLACE FUNCTION calcular_puntos_agenda(id_agenda INT)
RETURNS VOID AS $$
DECLARE
    id_usuario INT;
    total_monto NUMERIC;
    puntos_nuevos INT;
BEGIN
    -- Obtener usuario de la agenda
    SELECT usuario_id INTO id_usuario
    FROM agenda
    WHERE id = id_agenda;

    -- Calcular total monto de las reservas de esa agenda
    SELECT SUM(monto) INTO total_monto
    FROM reserva
    WHERE agenda_id = id_agenda;

    IF total_monto IS NOT NULL THEN
        puntos_nuevos := FLOOR(total_monto / 1000);

        -- Actualizar puntos del usuario
        UPDATE usuario
        SET puntos = COALESCE(puntos, 0) + puntos_nuevos
        WHERE id = id_usuario;
    END IF;
END;
$$ LANGUAGE plpgsql;

