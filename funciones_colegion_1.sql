-- phpMyAdmin SQL Dump
-- version 4.0.10.14
-- http://www.phpmyadmin.net
--
-- Servidor: localhost:3306
-- Tiempo de generación: 10-04-2017 a las 04:23:06
-- Versión del servidor: 5.6.35-cll-lve
-- Versión de PHP: 5.6.20

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de datos: `colegion_1`
--

DELIMITER $$
--
-- Procedimientos
--
$$

CREATE DEFINER=`colegion_1`@`localhost` PROCEDURE `sp_actualizar_periodo_lectivo`(IN `IdPeriodoLectivo` INT, IN `AnioInicial` INT, IN `AnioFinal` INT)
    NO SQL
BEGIN
	-- Actualizo los campos de la tabla sw_periodo_lectivo
	UPDATE sw_periodo_lectivo SET
	pe_anio_inicio = AnioInicial,
	pe_anio_fin = AnioFinal
	WHERE id_periodo_lectivo = IdPeriodoLectivo;

END$$

CREATE DEFINER=`colegion_1`@`localhost` PROCEDURE `sp_actualizar_rubrica_estudiante`(IN `IdEstudiante` INT, IN `IdParalelo` INT, IN `IdAsignatura` INT, IN `IdRubricaPersonalizada` INT, IN `ReCalificacion` FLOAT, IN `IdAporteEvaluacion` INT, IN `AeCalificacion` INT)
    NO SQL
BEGIN

	UPDATE sw_rubrica_estudiante 
	   SET re_calificacion = ReCalificacion
	 WHERE id_estudiante = IdEstudiante
       AND id_paralelo = IdParalelo
       AND id_asignatura = IdAsignatura
	   AND id_rubrica_personalizada = IdRubricaPersonaliza;

	UPDATE sw_aporte_estudiante
	   SET ae_calificacion = AeCalificacion
	 WHERE id_estudiante = IdEstudiante
       AND id_paralelo = IdParalelo
       AND id_asignatura = IdAsignatura
	   AND id_aporte_evaluacion = IdAporteEvaluacion;

END$$

CREATE DEFINER=`colegion_1`@`localhost` PROCEDURE `sp_actualizar_usuario`(IN `IdUsuario` INT, IN `IdPerfil` INT, IN `UsTitulo` VARCHAR(5), IN `UsApellidos` VARCHAR(32), IN `UsNombres` VARCHAR(32), IN `UsFullname` VARCHAR(64), IN `UsLogin` VARCHAR(24), IN `UsPassword` VARCHAR(64))
    NO SQL
UPDATE sw_usuario 
   SET id_perfil = IdPerfil,
	   us_titulo = UsTitulo,
	   us_apellidos = UsApellidos,
	   us_nombres = UsNombres,
	   us_fullname = UsFullname,
	   us_login = UsLogin,
	   us_password = UsPassword
 WHERE id_usuario = IdUsuario$$

CREATE DEFINER=`colegion_1`@`localhost` PROCEDURE `sp_buscar_estudiantes_antiguos`(IN `IdPeriodoLectivo` INT, IN `PatronBusqueda` VARCHAR(32))
    NO SQL
BEGIN

	-- SET varPatron = CONCAT(PatronBusqueda,'%');

	-- Cursor que se va a utilizar en la busqueda de estudiantes antiguos
	SELECT e.id_estudiante,
		   es_apellidos,
		   es_nombres,
		   cu_nombre,
		   pa_nombre,
		   (SELECT es_promocionado(e.id_estudiante, IdPeriodoLectivo - 1) AS aprobado)
	  FROM sw_estudiante e,
		   sw_estudiante_periodo_lectivo ep,
		   sw_curso c,
		   sw_paralelo p
	 WHERE e.id_estudiante = ep.id_estudiante
	   AND ep.id_paralelo = p.id_paralelo
	   AND p.id_curso = c.id_curso
	   AND (e.es_apellidos LIKE CONCAT(PatronBusqueda,'%')
			OR e.es_nombres LIKE CONCAT(PatronBusqueda,'%'))
	   AND ep.id_periodo_lectivo = IdPeriodoLectivo - 1;

END$$

CREATE DEFINER=`colegion_1`@`localhost` PROCEDURE `sp_cerrar_periodos`()
    NO SQL
BEGIN
	DECLARE done INT DEFAULT 0;
	DECLARE IdAporteEvaluacion INT;

	DECLARE cAportesEvaluacion CURSOR FOR
		SELECT DISTINCT(id_aporte_evaluacion)
		  FROM sw_aporte_curso_cierre
		 WHERE ap_fecha_cierre = (SELECT curdate());

	DECLARE CONTINUE HANDLER FOR SQLSTATE '02000' SET done = 1;

	OPEN cAportesEvaluacion;

	REPEAT
		FETCH cAportesEvaluacion INTO IdAporteEvaluacion;
		UPDATE sw_aporte_curso_cierre
		   SET ap_estado = 'C'
		 WHERE id_aporte_evaluacion = IdAporteEvaluacion;
	UNTIL done END REPEAT;

	CLOSE cAportesEvaluacion;
END$$

CREATE DEFINER=`colegion_1`@`localhost` PROCEDURE `sp_insertar_institucion`(IN `In_nombre` VARCHAR(64), IN `In_direccion` VARCHAR(45), IN `In_telefono1` VARCHAR(12), IN `In_nom_rector` VARCHAR(45), IN `In_nom_secretario` VARCHAR(45))
    NO SQL
BEGIN
	IF (EXISTS (SELECT * FROM sw_institucion)) THEN
		UPDATE sw_institucion
		SET in_nombre = In_nombre,
		in_direccion = In_direccion,
		in_telefono1 = In_telefono1,
		in_nom_rector = In_nom_rector,
		in_nom_secretario = In_nom_secretario;
	ELSE
		INSERT INTO sw_institucion
		SET in_nombre = In_nombre,
		in_direccion = In_direccion,
		in_telefono1 = In_telefono1,
		in_nom_rector = In_nom_rector,
		in_nom_secretario = In_nom_secretario;
	END IF;
END$$

CREATE DEFINER=`colegion_1`@`localhost` PROCEDURE `sp_insertar_periodo_lectivo`(IN `AnioInicial` INT, IN `AnioFinal` INT)
    NO SQL
BEGIN

	DECLARE done INT DEFAULT 0;
	DECLARE IdAporteEvaluacion INT;

	DECLARE cAportesEvaluacion CURSOR FOR 
		SELECT a.id_aporte_evaluacion
		  FROM sw_aporte_evaluacion a,
			   sw_periodo_evaluacion p,
			   sw_periodo_lectivo pl
		 WHERE a.id_periodo_evaluacion = p.id_periodo_evaluacion
		   AND p.id_periodo_lectivo = pl.id_periodo_lectivo
		   AND pl.pe_anio_inicio = AnioInicial - 1
		   AND a.ap_tipo < 4;

	DECLARE CONTINUE HANDLER FOR SQLSTATE '02000' SET done = 1;

	-- Primero debo verificar si hay un periodo lectivo anterior
	
	SET @IdPeriodoLectivoAnterior = (SELECT id_periodo_lectivo
                                      FROM sw_periodo_lectivo
                                     WHERE pe_anio_inicio = AnioInicial - 1);

	-- SELECT @IdPeriodoLectivoAnterior;

	IF @IdPeriodoLectivoAnterior IS NOT NULL THEN
		-- Actualizo el estado del periodo lectivo anterior
		UPDATE sw_periodo_lectivo
		   SET pe_estado = 'T'
		 WHERE id_periodo_lectivo = @IdPeriodoLectivoAnterior;

		-- Aqui actualizo a 'C' todos los periodos de evaluacion
		-- menos el examen de gracia utilizando un cursor

		OPEN cAportesEvaluacion;

		REPEAT
			FETCH cAportesEvaluacion INTO IdAporteEvaluacion;
			UPDATE sw_aporte_curso_cierre
			   SET ap_estado = 'C'
			 WHERE id_aporte_evaluacion = IdAporteEvaluacion;
		UNTIL done END REPEAT;

		CLOSE cAportesEvaluacion;
	
	END IF;

	-- Finalmente inserto el nuevo periodo lectivo
	INSERT INTO sw_periodo_lectivo (pe_anio_inicio, pe_anio_fin, pe_estado)
	VALUES (AnioInicial, AnioFinal, 'A');

END$$

CREATE DEFINER=`colegion_1`@`localhost` PROCEDURE `sp_insertar_rubrica_estudiante`(IN `IdEstudiante` INT, IN `IdParalelo` INT, IN `IdAsignatura` INT, IN `IdRubricaPersonalizada` INT, IN `ReCalificacion` FLOAT, IN `IdAporteEvaluacion` INT, IN `AeCalificacion` FLOAT)
    NO SQL
BEGIN
	
    INSERT INTO sw_rubrica_estudiante 
		(id_estudiante,
		 id_paralelo,
		 id_asignatura,
		 id_rubrica_personalizada,
		 re_calificacion
		)
		VALUES
		(IdEstudiante,
		 IdParalelo,
		 IdAsignatura,
		 IdRubricaPersonalizada,
		 ReCalificacion
		);

	INSERT INTO sw_aporte_estudiante
	   SET id_aporte_evaluacion = IdAporteEvaluacion,
		   id_estudiante = IdEstudiante,
		   id_paralelo = IdParalelo,
		   id_asignatura = IdAsignatura,
		   ae_calificacion = AeCalificacion;

END$$

CREATE DEFINER=`colegion_1`@`localhost` PROCEDURE `sp_insertar_usuario`(
	in IdPeriodoLectivo int,
	in IdPerfil int,
	in UsTitulo varchar(5),
	in UsApellidos varchar(32),
	in UsNombres varchar(32),
	in UsFullname varchar(64),
	in UsLogin varchar(24),
	in UsPassword varchar(64)
)
BEGIN
	INSERT INTO sw_usuario (
		id_periodo_lectivo,
		id_perfil,
		us_titulo,
		us_apellidos,
		us_nombres,
		us_fullname,
		us_login,
		us_password
	) VALUES (
		IdPeriodoLectivo,
		IdPerfil,
		UsTitulo,
		UsApellidos,
		UsNombres,
		UsFullname,
		UsLogin,
		UsPassword
	);
END$$

--
-- Funciones
--
CREATE DEFINER=`colegion_1`@`localhost` FUNCTION `aprueba_todas_asignaturas`(`IdPeriodoLectivo` INT, `IdEstudiante` INT, `IdParalelo` INT) RETURNS tinyint(1)
    NO SQL
BEGIN
	DECLARE IdAsignatura INT;
	DECLARE aprueba BOOL DEFAULT TRUE; -- variable de salida de la funcion
	DECLARE done INT DEFAULT 0;
	DECLARE promedio FLOAT DEFAULT 0;

	-- Aqui determino si el estudiante aprueba en todas las asignaturas
	DECLARE cAsignaturas CURSOR FOR
		SELECT id_asignatura 
		  FROM sw_paralelo_asignatura 
		 WHERE id_paralelo = IdParalelo;
	
	DECLARE CONTINUE HANDLER FOR SQLSTATE '02000' SET done = 1;

	OPEN cAsignaturas;

	Lazo: LOOP
		FETCH cAsignaturas INTO IdAsignatura;
		IF done THEN
			CLOSE cAsignaturas;
			LEAVE Lazo;
		END IF;
		SET promedio = (SELECT calcular_promedio_anual(IdPeriodoLectivo,IdEstudiante,IdParalelo,IdAsignatura));
		IF promedio < 7 THEN
			SET done = 1;
			SET aprueba = FALSE;
		END IF;
	END LOOP Lazo;

	RETURN aprueba;
	
END$$

CREATE DEFINER=`colegion_1`@`localhost` FUNCTION `aprueba_todos_remediales`(`IdPeriodoLectivo` INT, `IdEstudiante` INT, `IdParalelo` INT) RETURNS tinyint(4)
    NO SQL
BEGIN
	DECLARE IdAsignatura INT;
	DECLARE aprueba BOOL DEFAULT TRUE; -- variable de salida de la funcion
	DECLARE done INT DEFAULT 0;
	DECLARE promedio FLOAT DEFAULT 0;
	DECLARE examen_supletorio FLOAT DEFAULT 0;
	DECLARE examen_remedial FLOAT DEFAULT 0;

	-- Aqui determino si el estudiante aprueba en todas las asignaturas
	DECLARE cAsignaturas CURSOR FOR
		SELECT id_asignatura 
		  FROM sw_paralelo_asignatura 
		 WHERE id_paralelo = IdParalelo;
	
	DECLARE CONTINUE HANDLER FOR SQLSTATE '02000' SET done = 1;

	OPEN cAsignaturas;

	Lazo: LOOP
		FETCH cAsignaturas INTO IdAsignatura;
		IF done THEN
			CLOSE cAsignaturas;
			LEAVE Lazo;
		END IF;
		SET promedio = (SELECT calcular_promedio_anual(IdPeriodoLectivo,IdEstudiante,IdParalelo,IdAsignatura));
		IF (promedio >= 5 AND promedio < 7) AND (7 - promedio > 0.01) THEN -- tiene que rendir el examen supletorio
			SET examen_supletorio = (SELECT calcular_examen_supletorio(IdPeriodoLectivo,IdEstudiante,IdParalelo,IdAsignatura,2));
			IF examen_supletorio < 7 THEN
				-- tiene que rendir el examen remedial
				SET examen_remedial = (SELECT calcular_examen_supletorio(IdPeriodoLectivo,IdEstudiante,IdParalelo,IdAsignatura,3));
				IF examen_remedial < 7 THEN
					SET done = 1;
					SET aprueba = FALSE;
				END IF;
			END IF;
		ELSE 
			IF promedio > 0 AND promedio < 5 THEN -- tiene que rendir el examen remedial
				SET examen_remedial = (SELECT calcular_examen_supletorio(IdPeriodoLectivo,IdEstudiante,IdParalelo,IdAsignatura,3));
				IF examen_remedial < 7 THEN
					SET done = 1;
					SET aprueba = FALSE;
				END IF;
			END IF;
		END IF;
	END LOOP Lazo;

	RETURN aprueba;

END$$

CREATE DEFINER=`colegion_1`@`localhost` FUNCTION `aprueba_todos_supletorios`(`IdPeriodoLectivo` INT, `IdEstudiante` INT, `IdParalelo` INT) RETURNS tinyint(4)
    NO SQL
BEGIN
	DECLARE IdAsignatura INT;
	DECLARE aprueba BOOL DEFAULT TRUE; -- variable de salida de la funcion
	DECLARE done INT DEFAULT 0;
	DECLARE promedio FLOAT DEFAULT 0;
	DECLARE examen_supletorio FLOAT DEFAULT 0;

	-- Aqui determino si el estudiante aprueba en todas las asignaturas
	DECLARE cAsignaturas CURSOR FOR
		SELECT id_asignatura 
		  FROM sw_paralelo_asignatura 
		 WHERE id_paralelo = IdParalelo;
	
	DECLARE CONTINUE HANDLER FOR SQLSTATE '02000' SET done = 1;

	OPEN cAsignaturas;

	Lazo: LOOP
		FETCH cAsignaturas INTO IdAsignatura;
		IF done THEN
			CLOSE cAsignaturas;
			LEAVE Lazo;
		END IF;
		SET promedio = (SELECT calcular_promedio_anual(IdPeriodoLectivo,IdEstudiante,IdParalelo,IdAsignatura));
		IF (promedio >= 5 AND promedio < 7) AND (7 - promedio > 0.01) THEN -- tiene que rendir el examen supletorio
			SET examen_supletorio = (SELECT calcular_examen_supletorio(IdPeriodoLectivo,IdEstudiante,IdParalelo,IdAsignatura,2));
			IF examen_supletorio < 7 THEN
				SET done = 1;
				SET aprueba = FALSE;
			END IF;
		ELSE IF promedio < 5 THEN -- tiene que rendir el examen remedial
				SET done = 1;
				SET aprueba = FALSE;
			 END IF;
		END IF;
	END LOOP Lazo;

	RETURN aprueba;
	
END$$

CREATE DEFINER=`colegion_1`@`localhost` FUNCTION `calcular_comp_asignatura`(`IdPeriodoEvaluacion` INT, `IdEstudiante` INT, `IdParalelo` INT, `IdAsignatura` INT) RETURNS float
    NO SQL
BEGIN
	DECLARE done INT DEFAULT 0;
	DECLARE promedio_quimestre FLOAT;     
	DECLARE promedio_aporte FLOAT;
	DECLARE IdAporteEvaluacion INT;
	DECLARE Calificacion FLOAT;
	DECLARE Suma FLOAT DEFAULT 0;
	DECLARE Contador INT DEFAULT 0;
	DECLARE Total_Aportes INT DEFAULT 0;
	DECLARE Promedio FLOAT DEFAULT 0;
	
	DECLARE cAportesEvaluacion CURSOR FOR
	SELECT id_aporte_evaluacion
	  FROM sw_aporte_evaluacion
	 WHERE id_periodo_evaluacion = IdPeriodoEvaluacion
       AND ap_tipo = 1;

	DECLARE CONTINUE HANDLER FOR SQLSTATE '02000' SET done = 1;

	OPEN cAportesEvaluacion;

	Lazo1: LOOP
		FETCH cAportesEvaluacion INTO IdAporteEvaluacion;
		IF done THEN
			CLOSE cAportesEvaluacion;
			LEAVE Lazo1;
		END IF;
		
		SET Calificacion = (
		SELECT co_calificacion
		  FROM sw_calificacion_comportamiento
		 WHERE id_estudiante = IdEstudiante
		   AND id_paralelo = IdParalelo
		   AND id_asignatura = IdAsignatura
		   AND id_aporte_evaluacion = IdAporteEvaluacion);
           
        SET Calificacion = IFNULL(Calificacion, 0);

		SET Suma = Suma + Calificacion;
		SET Contador = Contador + 1;
	END LOOP Lazo1;

	SET Promedio = Suma / Contador;

	RETURN Promedio;
END$$

CREATE DEFINER=`colegion_1`@`localhost` FUNCTION `calcular_examen_supletorio`(`IdPeriodoLectivo` INT, `IdEstudiante` INT, `IdParalelo` INT, `IdAsignatura` INT, `PePrincipal` INT) RETURNS float
    NO SQL
BEGIN
	DECLARE IdRubricaEvaluacion INT DEFAULT 0;
	DECLARE examen_supletorio FLOAT DEFAULT 0; -- variable de salida de la funcion

	-- Aqui obtengo el valor del examen supletorio, si existe
	SET IdRubricaEvaluacion = (SELECT id_rubrica_evaluacion 
								   FROM sw_rubrica_evaluacion r, 
									    sw_aporte_evaluacion a, 
										sw_periodo_evaluacion p 
								  WHERE r.id_aporte_evaluacion = a.id_aporte_evaluacion 
									AND a.id_periodo_evaluacion = p.id_periodo_evaluacion 
									AND p.pe_principal = PePrincipal AND p.id_periodo_lectivo = IdPeriodoLectivo);

	SET examen_supletorio = (SELECT re_calificacion
							   FROM sw_rubrica_estudiante 
							  WHERE id_estudiante = IdEstudiante 
								AND id_paralelo = IdParalelo 
								AND id_asignatura = IdAsignatura 
								AND id_rubrica_personalizada = IdRubricaEvaluacion);
	
	RETURN IFNULL(examen_supletorio, 0);
END$$

CREATE DEFINER=`colegion_1`@`localhost` FUNCTION `calcular_promedio_anual`(`IdPeriodoLectivo` INT, `IdEstudiante` INT, `IdParalelo` INT, `IdAsignatura` INT) RETURNS float
    NO SQL
BEGIN
	DECLARE done INT DEFAULT 0;
	DECLARE promedio_anual FLOAT; -- variable de salida de la funcion
	DECLARE promedio_quimestre FLOAT;
	DECLARE IdPeriodoEvaluacion INT;
	DECLARE Suma FLOAT DEFAULT 0;
	DECLARE Contador INT DEFAULT 0;
	
	-- Aqui calculo el promedio anual utilizando un cursor
	DECLARE cPeriodosEvaluacion CURSOR FOR
		SELECT id_periodo_evaluacion
		  FROM sw_periodo_evaluacion 
		 WHERE id_periodo_lectivo = IdPeriodoLectivo
		   AND pe_principal = 1;

	DECLARE CONTINUE HANDLER FOR SQLSTATE '02000' SET done = 1;

	OPEN cPeriodosEvaluacion;

	Lazo: LOOP
		FETCH cPeriodosEvaluacion INTO IdPeriodoEvaluacion;
		IF done THEN
			CLOSE cPeriodosEvaluacion;
			LEAVE Lazo;
		END IF;
		SET promedio_quimestre = (SELECT calcular_promedio_quimestre(IdPeriodoEvaluacion,IdEstudiante,IdParalelo,IdAsignatura));
		SET Suma = Suma + promedio_quimestre;
		SET Contador = Contador + 1;
	END LOOP Lazo;

	SELECT Suma / Contador INTO promedio_anual;

	RETURN promedio_anual;
END$$

CREATE DEFINER=`colegion_1`@`localhost` FUNCTION `calcular_promedio_anual_proyectos`(`IdPeriodoLectivo` INT, `IdEstudiante` INT) RETURNS float
    NO SQL
BEGIN
	DECLARE done INT DEFAULT 0;
	DECLARE promedio_anual FLOAT; 	
	DECLARE promedio_quimestre FLOAT;
	DECLARE IdPeriodoEvaluacion INT;
	DECLARE Suma FLOAT DEFAULT 0;
	DECLARE Contador INT DEFAULT 0;
	DECLARE IdClub INT;
	
	DECLARE cPeriodosEvaluacion CURSOR FOR
	SELECT id_periodo_evaluacion
	  FROM sw_periodo_evaluacion 
	 WHERE id_periodo_lectivo = IdPeriodoLectivo
	   AND pe_principal = 1;

	DECLARE CONTINUE HANDLER FOR SQLSTATE '02000' SET done = 1;

	OPEN cPeriodosEvaluacion;

	-- Obtener el id_club correspondiente
	SET IdClub = (SELECT id_club FROM sw_estudiante_club
                   WHERE id_estudiante = IdEstudiante
                     AND id_periodo_lectivo = IdPeriodoLectivo);

	Lazo: LOOP
		FETCH cPeriodosEvaluacion INTO IdPeriodoEvaluacion;
		IF done THEN
			CLOSE cPeriodosEvaluacion;
			LEAVE Lazo;
		END IF;
		SET promedio_quimestre = (SELECT calcular_promedio_quimestre_club(
									IdPeriodoEvaluacion,IdEstudiante,IdClub));
		SET Suma = Suma + promedio_quimestre;
		SET Contador = Contador + 1;
	END LOOP Lazo;

	SELECT Suma / Contador INTO promedio_anual;

	RETURN promedio_anual;
END$$

CREATE DEFINER=`colegion_1`@`localhost` FUNCTION `calcular_promedio_aporte`(`IdAporteEvaluacion` INT, `IdEstudiante` INT, `IdParalelo` INT, `IdAsignatura` INT) RETURNS float
    NO SQL
BEGIN
	DECLARE done INT DEFAULT 0;
	DECLARE promedio_aporte FLOAT; 	DECLARE IdRubricaEvaluacion INT;
	DECLARE ReCalificacion FLOAT;
	DECLARE Suma FLOAT DEFAULT 0;
	DECLARE Contador INT DEFAULT 0;

	DECLARE cRubricasEvaluacion CURSOR FOR
	SELECT id_rubrica_evaluacion
	  FROM sw_rubrica_evaluacion
	 WHERE id_aporte_evaluacion = IdAporteEvaluacion;

	DECLARE CONTINUE HANDLER FOR SQLSTATE '02000' SET done = 1;

	OPEN cRubricasEvaluacion;

	Lazo1: LOOP
		FETCH cRubricasEvaluacion INTO IdRubricaEvaluacion;
		IF done THEN
			CLOSE cRubricasEvaluacion;
			LEAVE Lazo1;
		END IF;

		SET ReCalificacion = (
		SELECT re_calificacion
		  FROM sw_rubrica_estudiante
		 WHERE id_estudiante = IdEstudiante
		   AND id_paralelo = IdParalelo
		   AND id_asignatura = IdAsignatura
		   AND id_rubrica_personalizada = IdRubricaEvaluacion);
           
        SET ReCalificacion = IFNULL(ReCalificacion, 0);

		SET Suma = Suma + ReCalificacion;
		SET Contador = Contador + 1;
	END LOOP Lazo1;

	SELECT Suma / Contador INTO promedio_aporte;
	
	RETURN promedio_aporte;
END$$

CREATE DEFINER=`colegion_1`@`localhost` FUNCTION `calcular_promedio_aporte_club`(`IdAporteEvaluacion` INT, `IdEstudiante` INT, `IdClub` INT) RETURNS float
    READS SQL DATA
    DETERMINISTIC
BEGIN
	DECLARE done INT DEFAULT 0;
	DECLARE promedio_aporte FLOAT;
    DECLARE IdRubricaEvaluacion INT;
	DECLARE ReCalificacion FLOAT;
	DECLARE Suma FLOAT DEFAULT 0;
	DECLARE Contador INT DEFAULT 0;

	DECLARE cRubricasEvaluacion CURSOR FOR
	SELECT id_rubrica_evaluacion
	  FROM sw_rubrica_evaluacion
	 WHERE id_aporte_evaluacion = IdAporteEvaluacion;

	DECLARE CONTINUE HANDLER FOR SQLSTATE '02000' SET done = 1;

	OPEN cRubricasEvaluacion;

	Lazo1: LOOP
		FETCH cRubricasEvaluacion INTO IdRubricaEvaluacion;
		IF done THEN
			CLOSE cRubricasEvaluacion;
			LEAVE Lazo1;
		END IF;

		SET ReCalificacion = (
		SELECT rc_calificacion
		  FROM sw_rubrica_club
		 WHERE id_estudiante = IdEstudiante
		   AND id_club = IdClub
		   AND id_rubrica_evaluacion = IdRubricaEvaluacion);
           
        SET ReCalificacion = IFNULL(ReCalificacion, 0);

		SET Suma = Suma + ReCalificacion;
		SET Contador = Contador + 1;
	END LOOP Lazo1;

	SELECT Suma / Contador INTO promedio_aporte;
	
	RETURN promedio_aporte;
END$$

CREATE DEFINER=`colegion_1`@`localhost` FUNCTION `calcular_promedio_final`(`IdPeriodoLectivo` INT, `IdEstudiante` INT, `IdParalelo` INT, `IdAsignatura` INT) RETURNS float
    NO SQL
BEGIN
	DECLARE promedio_final FLOAT DEFAULT 0; 	DECLARE examen_supletorio FLOAT DEFAULT 0;
	DECLARE examen_remedial FLOAT DEFAULT 0;
	DECLARE examen_de_gracia FLOAT DEFAULT 0;

	SET promedio_final = (SELECT calcular_promedio_anual(IdPeriodoLectivo,IdEstudiante,IdParalelo,IdAsignatura));
	IF promedio_final >= 5 AND promedio_final < 7 THEN 		
		SET examen_supletorio = (SELECT calcular_examen_supletorio(IdPeriodoLectivo,IdEstudiante,IdParalelo,IdAsignatura,2));
		IF examen_supletorio >= 7 THEN
			SET promedio_final = 7;
		ELSE
			SET examen_remedial = (SELECT calcular_examen_supletorio(IdPeriodoLectivo,IdEstudiante,IdParalelo,IdAsignatura,3));
			IF examen_remedial >= 7 THEN
				SET promedio_final = 7;
			ELSE
				SET examen_de_gracia = (SELECT calcular_examen_supletorio(IdPeriodoLectivo,IdEstudiante,IdParalelo,IdAsignatura,4));
				IF examen_de_gracia >= 7 THEN
					SET promedio_final = 7;
				END IF;
			END IF;
		END IF;
	ELSE 
		IF promedio_final > 0 AND promedio_final < 5 THEN
			SET examen_remedial = (SELECT calcular_examen_supletorio(IdPeriodoLectivo,IdEstudiante,IdParalelo,IdAsignatura,3));
			IF examen_remedial >= 7 THEN
				SET promedio_final = 7;
			ELSE
				SET examen_de_gracia = (SELECT calcular_examen_supletorio(IdPeriodoLectivo,IdEstudiante,IdParalelo,IdAsignatura,4));
				IF examen_de_gracia >= 7 THEN
					SET promedio_final = 7;
				END IF;
			END IF;
		END IF;
	END IF;

	RETURN promedio_final;

END$$

CREATE DEFINER=`colegion_1`@`localhost` FUNCTION `calcular_promedio_general`(`IdPeriodoLectivo` INT, `IdEstudiante` INT, `IdParalelo` INT) RETURNS float
    NO SQL
BEGIN
	DECLARE done INT DEFAULT 0;
	DECLARE promedio_general float DEFAULT 0; -- variable de salida de la funcion
	DECLARE suma FLOAT DEFAULT 0;
	DECLARE contador INT DEFAULT 0;
	DECLARE IdAsignatura INT;

	-- Aqui determino si el estudiante aprueba en todas las asignaturas
	DECLARE cAsignaturas CURSOR FOR
		SELECT id_asignatura 
		  FROM sw_paralelo_asignatura 
		 WHERE id_paralelo = IdParalelo;
	
	DECLARE CONTINUE HANDLER FOR SQLSTATE '02000' SET done = 1;

	OPEN cAsignaturas;

	Lazo: LOOP
		FETCH cAsignaturas INTO IdAsignatura;
		IF done THEN
			CLOSE cAsignaturas;
			LEAVE Lazo;
		END IF;
		SET suma = suma + (SELECT calcular_promedio_final(IdPeriodoLectivo,IdEstudiante,IdParalelo,IdAsignatura));
		SET contador = contador + 1;
	END LOOP Lazo;

	SET promedio_general = suma / contador;

	RETURN promedio_general;
END$$

CREATE DEFINER=`colegion_1`@`localhost` FUNCTION `calcular_promedio_quimestre`(`IdPeriodoEvaluacion` INT, `IdEstudiante` INT, `IdParalelo` INT, `IdAsignatura` INT) RETURNS float
    NO SQL
BEGIN
    DECLARE done INT DEFAULT 0;
    DECLARE promedio_quimestre FLOAT; -- variable de salida de la funcion
    DECLARE promedio_aporte FLOAT;
    DECLARE IdAporteEvaluacion INT;
    DECLARE Suma FLOAT DEFAULT 0;
    DECLARE Contador INT DEFAULT 0;
    DECLARE Total_Aportes INT DEFAULT 0;
    DECLARE Examen FLOAT DEFAULT 0;
    DECLARE Promedio FLOAT DEFAULT 0;
    
    -- Declaracion del cursor que se va a utilizar
    DECLARE cAportesEvaluacion CURSOR FOR
    	SELECT id_aporte_evaluacion
          FROM sw_aporte_evaluacion
         WHERE id_periodo_evaluacion = IdPeriodoEvaluacion;
         
    DECLARE CONTINUE HANDLER FOR SQLSTATE '02000' SET done = 1;
    
    SET Total_Aportes = (SELECT COUNT(*) FROM sw_aporte_evaluacion WHERE id_periodo_evaluacion = IdPeriodoEvaluacion);
    
    OPEN cAportesEvaluacion;
    
    REPEAT
    	FETCH cAportesEvaluacion INTO IdAporteEvaluacion;
        
        SELECT calcular_promedio_aporte (IdAporteEvaluacion, IdEstudiante, IdParalelo, IdAsignatura) INTO promedio_aporte;
        
        SET Contador = Contador + 1;
        
        IF Contador <= Total_Aportes - 1 THEN
        	SET Suma = Suma + promedio_aporte;
        ELSE
        	SET Examen = promedio_aporte;
        END IF;
    UNTIL done END REPEAT;
    
    CLOSE cAportesEvaluacion;
    
    SET Promedio = Suma / (Total_Aportes - 1);
    
    SELECT 0.8 * Promedio + 0.2 * Examen INTO promedio_quimestre;
    
    RETURN promedio_quimestre;
    
END$$

CREATE DEFINER=`colegion_1`@`localhost` FUNCTION `calcular_promedio_quimestre_club`(`IdPeriodoEvaluacion` INT, `IdEstudiante` INT, `IdClub` INT) RETURNS float
    NO SQL
BEGIN
    DECLARE done INT DEFAULT 0;
    DECLARE promedio_quimestre FLOAT;
	DECLARE promedio_aporte FLOAT;
    DECLARE IdAporteEvaluacion INT;
    DECLARE Suma FLOAT DEFAULT 0;
    DECLARE Contador INT DEFAULT 0;
    DECLARE Total_Aportes INT DEFAULT 0;
    DECLARE Examen FLOAT DEFAULT 0;
    DECLARE Promedio FLOAT DEFAULT 0;
    
        DECLARE cAportesEvaluacion CURSOR FOR
    	SELECT id_aporte_evaluacion
          FROM sw_aporte_evaluacion
         WHERE id_periodo_evaluacion = IdPeriodoEvaluacion;
         
    DECLARE CONTINUE HANDLER FOR SQLSTATE '02000' SET done = 1;
    
    SET Total_Aportes = (SELECT COUNT(*) FROM sw_aporte_evaluacion WHERE id_periodo_evaluacion = IdPeriodoEvaluacion);
    
    OPEN cAportesEvaluacion;
    
    REPEAT
    	FETCH cAportesEvaluacion INTO IdAporteEvaluacion;
        
        SELECT calcular_promedio_aporte_club (IdAporteEvaluacion, IdEstudiante, IdClub) INTO promedio_aporte;
        
        SET Contador = Contador + 1;
        
        IF Contador <= Total_Aportes - 1 THEN
        	SET Suma = Suma + promedio_aporte;
        ELSE
        	SET Examen = promedio_aporte;
        END IF;
    UNTIL done END REPEAT;
    
    CLOSE cAportesEvaluacion;
    
    SET Promedio = Suma / (Total_Aportes - 1);
    
    SELECT 0.8 * Promedio + 0.2 * Examen INTO promedio_quimestre;
    
    RETURN promedio_quimestre;
    
END$$

CREATE DEFINER=`colegion_1`@`localhost` FUNCTION `contar_remediales_no_aprobados`(`IdPeriodoLectivo` INT, `IdEstudiante` INT, `IdParalelo` INT) RETURNS int(11)
    NO SQL
BEGIN
	DECLARE IdAsignatura INT;
	DECLARE contador INT DEFAULT 0; -- variable de salida de la funcion
	DECLARE done INT DEFAULT 0;
	DECLARE promedio FLOAT DEFAULT 0;
	DECLARE examen_supletorio FLOAT DEFAULT 0;
	DECLARE examen_remedial FLOAT DEFAULT 0;

	-- Aqui determino si el estudiante aprueba en todas las asignaturas
	DECLARE cAsignaturas CURSOR FOR
		SELECT id_asignatura 
		  FROM sw_paralelo_asignatura 
		 WHERE id_paralelo = IdParalelo;
	
	DECLARE CONTINUE HANDLER FOR SQLSTATE '02000' SET done = 1;

	OPEN cAsignaturas;

	Lazo: LOOP
		FETCH cAsignaturas INTO IdAsignatura;
		IF done THEN
			CLOSE cAsignaturas;
			LEAVE Lazo;
		END IF;
		SET promedio = (SELECT calcular_promedio_anual(IdPeriodoLectivo,IdEstudiante,IdParalelo,IdAsignatura));
		IF (promedio >= 5 AND promedio < 7) AND (7 - promedio > 0.01) THEN -- tiene que rendir el examen supletorio
			SET examen_supletorio = (SELECT calcular_examen_supletorio(IdPeriodoLectivo,IdEstudiante,IdParalelo,IdAsignatura,2));
			IF examen_supletorio < 7 THEN
				-- tiene que rendir el examen remedial
				SET examen_remedial = (SELECT calcular_examen_supletorio(IdPeriodoLectivo,IdEstudiante,IdParalelo,IdAsignatura,3));
				IF examen_remedial < 7 THEN
					SET contador = contador + 1;
				END IF;
			END IF;
		ELSE 
			IF promedio > 0 AND promedio < 5 THEN -- tiene que rendir el examen remedial
				SET examen_remedial = (SELECT calcular_examen_supletorio(IdPeriodoLectivo,IdEstudiante,IdParalelo,IdAsignatura,3));
				IF examen_remedial < 7 THEN
					SET contador = contador + 1;
				END IF;
			END IF;
		END IF;
	END LOOP Lazo;

	RETURN contador;

END$$

CREATE DEFINER=`colegion_1`@`localhost` FUNCTION `contar_supletorios`(`IdPeriodoLectivo` INT, `IdEstudiante` INT, `IdParalelo` INT) RETURNS int(11)
    NO SQL
BEGIN
	DECLARE IdAsignatura INT;
	DECLARE contador INT DEFAULT 0; 	
	DECLARE done INT DEFAULT 0;
	DECLARE promedio FLOAT DEFAULT 0;

	DECLARE cAsignaturas CURSOR FOR
	SELECT id_asignatura 
	FROM sw_paralelo_asignatura 
	WHERE id_paralelo = IdParalelo;
	
	DECLARE CONTINUE HANDLER FOR SQLSTATE '02000' SET done = 1;

	OPEN cAsignaturas;

	Lazo: LOOP
		FETCH cAsignaturas INTO IdAsignatura;
		IF done THEN
			CLOSE cAsignaturas;
			LEAVE Lazo;
		END IF;
		SET promedio = (SELECT calcular_promedio_anual(IdPeriodoLectivo,IdEstudiante,IdParalelo,IdAsignatura));
		IF promedio > 5 AND promedio < 7 THEN 			
			SET contador = contador + 1;
		END IF;
	END LOOP Lazo;

	RETURN contador;

END$$

CREATE DEFINER=`colegion_1`@`localhost` FUNCTION `determinar_asignatura_de_gracia`(`IdPeriodoLectivo` INT, `IdEstudiante` INT, `IdParalelo` INT) RETURNS int(11)
    NO SQL
BEGIN
	DECLARE IdAsignatura INT;
	DECLARE vid_asignatura INT DEFAULT 0; -- variable de salida de la funcion
	DECLARE contador INT DEFAULT 0;
	DECLARE done INT DEFAULT 0;
	DECLARE promedio FLOAT DEFAULT 0;
	DECLARE examen_supletorio FLOAT DEFAULT 0;
	DECLARE examen_remedial FLOAT DEFAULT 0;

	-- Aqui determino si el estudiante aprueba en todas las asignaturas
	DECLARE cAsignaturas CURSOR FOR
		SELECT id_asignatura 
		  FROM sw_paralelo_asignatura 
		 WHERE id_paralelo = IdParalelo;
	
	DECLARE CONTINUE HANDLER FOR SQLSTATE '02000' SET done = 1;

	SET contador = (SELECT contar_remediales_no_aprobados(IdPeriodoLectivo,IdEstudiante,IdParalelo));

	IF contador = 1 THEN

		OPEN cAsignaturas;

		Lazo: LOOP
			FETCH cAsignaturas INTO IdAsignatura;
			IF done THEN
				CLOSE cAsignaturas;
				LEAVE Lazo;
			END IF;
			SET promedio = (SELECT calcular_promedio_anual(IdPeriodoLectivo,IdEstudiante,IdParalelo,IdAsignatura));
			IF promedio > 5 AND promedio < 7 THEN -- tiene que rendir el examen supletorio
				SET examen_supletorio = (SELECT calcular_examen_supletorio(IdPeriodoLectivo,IdEstudiante,IdParalelo,IdAsignatura,2));
				IF examen_supletorio < 7 THEN
					-- tiene que rendir el examen remedial
					SET examen_remedial = (SELECT calcular_examen_supletorio(IdPeriodoLectivo,IdEstudiante,IdParalelo,IdAsignatura,3));
					IF examen_remedial < 7 THEN
						SET vid_asignatura = IdAsignatura;
                        SET done = 1;
					END IF;
				END IF;
			ELSE 
				IF promedio > 0 AND promedio < 5 THEN -- tiene que rendir el examen remedial
					SET examen_remedial = (SELECT calcular_examen_supletorio(IdPeriodoLectivo,IdEstudiante,IdParalelo,IdAsignatura,3));
					IF examen_remedial < 7 THEN
						SET vid_asignatura = IdAsignatura;
                        SET done = 1;
					END IF;
				END IF;
			END IF;
		END LOOP Lazo;

	END IF;

	RETURN vid_asignatura;

END$$

CREATE DEFINER=`colegion_1`@`localhost` FUNCTION `es_promocionado`(`IdEstudiante` INT, `IdPeriodoLectivo` INT, `IdParalelo` INT) RETURNS tinyint(4)
    NO SQL
BEGIN
	DECLARE aprueba BOOL DEFAULT TRUE; -- variable de salida de la funcion
	-- DECLARE IdParalelo INT DEFAULT 0;
	DECLARE promedio FLOAT DEFAULT 0;
	DECLARE done INT DEFAULT 0;
	DECLARE IdAsignatura INT;

	DECLARE cAsignaturas CURSOR FOR
	 SELECT id_asignatura
	   FROM sw_paralelo_asignatura
	  WHERE id_paralelo = IdParalelo;

	DECLARE CONTINUE HANDLER FOR SQLSTATE '02000' SET done = 1;

	OPEN cAsignaturas;

	Lazo: LOOP
		FETCH cAsignaturas INTO IdAsignatura;
		IF done THEN
			CLOSE cAsignaturas;
			LEAVE Lazo;
		END IF;
		SET promedio = (SELECT calcular_promedio_final(IdPeriodoLectivo,IdEstudiante,IdParalelo,IdAsignatura));
		IF promedio < 7 THEN
			SET done = 1;
			SET aprueba = FALSE;
		END IF;
	END LOOP Lazo;

	RETURN aprueba;

END$$

$$

CREATE DEFINER=`colegion_1`@`localhost` FUNCTION `secuencial_curso_asignatura`(`IdCurso` INT) RETURNS int(11)
    NO SQL
BEGIN
	DECLARE Secuencial INT;
	
	SET Secuencial = (
		SELECT MAX(ac_orden)
		  FROM sw_asignatura_curso
		 WHERE id_curso = IdCurso);
           
    SET Secuencial = IFNULL(Secuencial, 0);

	RETURN Secuencial + 1;
END$$

CREATE DEFINER=`colegion_1`@`localhost` FUNCTION `secuencial_hora_clase_dia_semana`(`IdDiaSemana` INT) RETURNS int(11)
    NO SQL
BEGIN

	DECLARE Secuencial INT;
	
	SET Secuencial = (
		SELECT MAX(hc_ordinal)
		  FROM sw_hora_clase
		 WHERE id_dia_semana = IdDiaSemana);
           
    SET Secuencial = IFNULL(Secuencial, 0);

	RETURN Secuencial + 1;

END$$

CREATE DEFINER=`colegion_1`@`localhost` FUNCTION `secuencial_menu_nivel_perfil`(`IdPerfil` INT, `Nivel` INT) RETURNS int(11)
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

CREATE DEFINER=`colegion_1`@`localhost` FUNCTION `secuencial_menu_nivel_perfil_padre`(`Nivel` INT, `IdPerfil` INT, `Padre` INT) RETURNS int(11)
    NO SQL
BEGIN
	DECLARE Secuencial INT;
	
	SET Secuencial = (
		SELECT MAX(mnu_orden)
		  FROM sw_menu
		 WHERE id_perfil = IdPerfil
           AND mnu_nivel = Nivel
           AND mnu_padre = Padre);
           
    SET Secuencial = IFNULL(Secuencial, 0);

	RETURN Secuencial + 1;
END$$

DELIMITER ;

