DELIMITER $$
--
-- Procedimientos
--
$$

CREATE DEFINER=`aplicacion`@`localhost` FUNCTION `secuencial_menu_nivel_perfil`(`IdPerfil` INT, `Nivel` INT) RETURNS int(11)
    NO SQL
BEGIN
	DECLARE Secuencial INT;
	
	SET Secuencial = (
		SELECT MAX(mnu_orden)
		  FROM sw_menu
		 WHERE id_perfil = IdPerfil
           AND mnu_nivel = Nivel);
           
    SET Secuencial = IFNULL(Secuencial, 0);

	RETURN Secuencial + 1;
END$$

DELIMITER ;