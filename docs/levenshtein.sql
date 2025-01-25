DELIMITER $$

CREATE FUNCTION levenshtein(s1 VARCHAR(255), s2 VARCHAR(255), n INT)
    RETURNS INT
    DETERMINISTIC
BEGIN
    DECLARE s1_len, s2_len, i, j, c, c_temp, cost, c_min INT;
    DECLARE s1_char CHAR(1);
    DECLARE cv0, cv1 VARBINARY(256);

    -- Initialisiere Längen der Strings
    SET s1_len = CHAR_LENGTH(s1), s2_len = CHAR_LENGTH(s2);
    SET cv1 = 0x00; -- Temporärer Speicher für die vorherige Zeile
    SET j = 1, i = 1, c = 0, c_min = 0;

    -- Triviale Fälle: Wenn Strings gleich sind oder einer leer ist
    IF s1 = s2 THEN
        RETURN 0;
    ELSEIF s1_len = 0 THEN
        RETURN s2_len;
    ELSEIF s2_len = 0 THEN
        RETURN s1_len;
    END IF;

    -- Initialisiere die erste Zeile der Distanzmatrix
    WHILE j <= s2_len DO
            SET cv1 = CONCAT(cv1, UNHEX(HEX(j))); -- Füge die Werte 1 bis s2_len hinzu
            SET j = j + 1;
        END WHILE;

    -- Hauptlogik: Berechnung der Levenshtein-Distanz
    WHILE i <= s1_len AND c_min < n DO -- Stoppe, wenn die minimale Distanz größer oder gleich dem Limit ist
    SET s1_char = SUBSTRING(s1, i, 1); -- Zeichen aus s1
    SET c = i; -- Initialisiere die Kosten für die aktuelle Zeile
    SET c_min = i; -- Minimum für die aktuelle Zeile
    SET cv0 = UNHEX(HEX(i)); -- Starte mit der neuen Zeile
    SET j = 1;

    WHILE j <= s2_len DO
            -- Berechne Kosten (0 für gleiche Zeichen, 1 für unterschiedliche)
            IF s1_char = SUBSTRING(s2, j, 1) THEN
                SET cost = 0;
            ELSE
                SET cost = 1;
            END IF;

            -- Berechne die minimalen Kosten für die aktuelle Zelle
            SET c_temp = CONV(HEX(SUBSTRING(cv1, j, 1)), 16, 10) + cost;
            IF c > c_temp THEN SET c = c_temp; END IF;
            SET c_temp = CONV(HEX(SUBSTRING(cv1, j + 1, 1)), 16, 10) + 1;
            IF c > c_temp THEN SET c = c_temp; END IF;

            -- Speichere die aktuellen Kosten in der neuen Zeile
            SET cv0 = CONCAT(cv0, UNHEX(HEX(c)));
            SET j = j + 1;

            -- Aktualisiere das Minimum für die aktuelle Zeile
            IF c < c_min THEN
                SET c_min = c;
            END IF;
        END WHILE;

    -- Übertrage die neue Zeile in cv1
    SET cv1 = cv0;
    SET i = i + 1;
        END WHILE;

    -- Wenn die Schleife vorzeitig abbricht, ist c_min die minimale Distanz
    IF i <= s1_len THEN
        SET c = c_min;
    END IF;

    RETURN c;
END$$

DELIMITER ;