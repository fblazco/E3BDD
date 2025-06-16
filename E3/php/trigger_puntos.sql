CREATE OR REPLACE FUNCTION trigger_calcular_puntos()
RETURNS TRIGGER AS $$
BEGIN
    IF NEW.agenda_id IS NOT NULL THEN
        PERFORM calcular_puntos_agenda(NEW.agenda_id);
    END IF;
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

DROP TRIGGER IF EXISTS tr_agregar_puntos ON reserva;

CREATE TRIGGER tr_agregar_puntos
AFTER INSERT OR UPDATE ON reserva
FOR EACH ROW
EXECUTE FUNCTION trigger_calcular_puntos();

