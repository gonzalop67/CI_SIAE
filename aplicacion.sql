-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 22-04-2017 a las 14:19:27
-- Versión del servidor: 5.5.24-log
-- Versión de PHP: 5.4.3

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de datos: `aplicacion`
--

DELIMITER $$
--
-- Funciones
--
CREATE DEFINER=`root`@`localhost` FUNCTION `secuencial_menu_nivel_perfil`(`IdPerfil` INT, `Nivel` INT) RETURNS int(11)
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

CREATE DEFINER=`root`@`localhost` FUNCTION `secuencial_menu_nivel_perfil_padre`(`Nivel` INT, `IdPerfil` INT, `Padre` INT) RETURNS int(11)
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

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sw_aporte_evaluacion`
--

CREATE TABLE IF NOT EXISTS `sw_aporte_evaluacion` (
  `id_aporte_evaluacion` int(11) NOT NULL AUTO_INCREMENT,
  `id_periodo_evaluacion` int(11) NOT NULL,
  `ap_nombre` varchar(24) DEFAULT NULL,
  `ap_abreviatura` varchar(8) DEFAULT NULL,
  `ap_tipo` tinyint(4) DEFAULT NULL,
  `ap_estado` varchar(1) DEFAULT NULL,
  `ap_fecha_apertura` date DEFAULT NULL,
  `ap_fecha_cierre` date DEFAULT NULL,
  `ap_fecha_inicio` date DEFAULT NULL,
  `ap_fecha_fin` date DEFAULT NULL,
  PRIMARY KEY (`id_aporte_evaluacion`),
  KEY `fk_sw_aporte_evaluacion_sw_periodo_evaluacion1_idx` (`id_periodo_evaluacion`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sw_institucion`
--

CREATE TABLE IF NOT EXISTS `sw_institucion` (
  `id_institucion` int(11) NOT NULL AUTO_INCREMENT,
  `in_nombre` varchar(64) NOT NULL,
  `in_direccion` varchar(64) DEFAULT NULL,
  `in_telefono` varchar(12) DEFAULT NULL,
  `in_nom_rector` varchar(45) DEFAULT NULL,
  `in_nom_secretario` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id_institucion`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Volcado de datos para la tabla `sw_institucion`
--

INSERT INTO `sw_institucion` (`id_institucion`, `in_nombre`, `in_direccion`, `in_telefono`, `in_nom_rector`, `in_nom_secretario`) VALUES
(1, 'UNIDAD EDUCATIVA PCEI FISCAL SALAMANCA', 'Calle el Tiempo y Pasaje Mónaco', '2256-104', 'Mag. Edison Cuyo', 'Mag. Ana Pilataxi');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sw_menu`
--

CREATE TABLE IF NOT EXISTS `sw_menu` (
  `id_menu` int(11) NOT NULL AUTO_INCREMENT,
  `id_perfil` int(11) NOT NULL,
  `mnu_texto` varchar(32) NOT NULL,
  `mnu_enlace` varchar(64) NOT NULL,
  `mnu_nivel` int(11) NOT NULL,
  `mnu_orden` int(11) NOT NULL,
  `mnu_padre` int(11) NOT NULL,
  PRIMARY KEY (`id_menu`,`id_perfil`),
  KEY `fk_sw_menu_sw_perfil_idx` (`id_perfil`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=13 ;

--
-- Volcado de datos para la tabla `sw_menu`
--

INSERT INTO `sw_menu` (`id_menu`, `id_perfil`, `mnu_texto`, `mnu_enlace`, `mnu_nivel`, `mnu_orden`, `mnu_padre`) VALUES
(1, 1, 'Cambiar la clave', 'usuario/cambiarClave', 1, 1, 0),
(3, 1, 'Configuración', '#', 1, 2, 0),
(4, 1, 'Perfiles', 'perfil', 2, 3, 3),
(5, 1, 'Usuarios', 'usuario', 2, 4, 3),
(6, 1, 'Menús', 'menu', 2, 5, 3),
(7, 1, 'Institución', 'institucion', 2, 1, 3),
(8, 1, 'Periodos Lectivos', 'periodo_lectivo', 2, 2, 3),
(9, 2, 'Cambiar la clave', 'usuario/cambiarClave', 1, 1, 0),
(10, 2, 'Definir Rúbricas', '#', 1, 2, 0),
(11, 2, 'Periodos de Evaluación', 'periodo_evaluacion', 2, 1, 10),
(12, 2, 'Aportes de Evaluación', 'aporte_evaluacion', 2, 2, 10);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sw_perfil`
--

CREATE TABLE IF NOT EXISTS `sw_perfil` (
  `id_perfil` int(11) NOT NULL AUTO_INCREMENT,
  `pe_nombre` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id_perfil`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=10 ;

--
-- Volcado de datos para la tabla `sw_perfil`
--

INSERT INTO `sw_perfil` (`id_perfil`, `pe_nombre`) VALUES
(1, 'ADMINISTRADOR'),
(2, 'AUTORIDAD'),
(3, 'DOCENTE'),
(4, 'ESTUDIANTE'),
(5, 'INSPECCIÓN'),
(6, 'SECRETARÍA'),
(7, 'TUTOR'),
(8, 'REPRESENTANTE');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sw_periodo_evaluacion`
--

CREATE TABLE IF NOT EXISTS `sw_periodo_evaluacion` (
  `id_periodo_evaluacion` int(11) NOT NULL AUTO_INCREMENT,
  `id_periodo_lectivo` int(11) NOT NULL,
  `id_institucion` int(11) NOT NULL,
  `pe_nombre` varchar(24) DEFAULT NULL,
  `pe_abreviatura` varchar(6) DEFAULT NULL,
  `pe_principal` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`id_periodo_evaluacion`),
  KEY `fk_sw_periodo_evaluacion_sw_periodo_lectivo1_idx` (`id_periodo_lectivo`,`id_institucion`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Volcado de datos para la tabla `sw_periodo_evaluacion`
--

INSERT INTO `sw_periodo_evaluacion` (`id_periodo_evaluacion`, `id_periodo_lectivo`, `id_institucion`, `pe_nombre`, `pe_abreviatura`, `pe_principal`) VALUES
(1, 1, 1, 'PRIMER QUIMESTRE', '1ER.Q.', 1),
(2, 1, 1, 'SEGUNDO QUIMESTRE', '2DO.Q.', 1),
(3, 1, 1, 'EXAMEN SUPLETORIO', 'SUPLE.', 2),
(4, 1, 1, 'EXAMEN REMEDIAL', 'REMED.', 3),
(5, 1, 1, 'EXAMEN DE GRACIA', 'GRACIA', 4);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sw_periodo_lectivo`
--

CREATE TABLE IF NOT EXISTS `sw_periodo_lectivo` (
  `id_periodo_lectivo` int(11) NOT NULL AUTO_INCREMENT,
  `id_institucion` int(11) NOT NULL,
  `pe_anio_inicio` int(11) NOT NULL,
  `pe_anio_fin` int(11) NOT NULL,
  `pe_estado` char(1) NOT NULL,
  PRIMARY KEY (`id_periodo_lectivo`,`id_institucion`),
  KEY `fk_sw_periodo_lectivo_sw_institucion_idx` (`id_institucion`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Volcado de datos para la tabla `sw_periodo_lectivo`
--

INSERT INTO `sw_periodo_lectivo` (`id_periodo_lectivo`, `id_institucion`, `pe_anio_inicio`, `pe_anio_fin`, `pe_estado`) VALUES
(1, 1, 2016, 2017, 'A');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sw_producto`
--

CREATE TABLE IF NOT EXISTS `sw_producto` (
  `pr_nombre` varchar(24) NOT NULL,
  `pr_descripcion` varchar(128) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `sw_producto`
--

INSERT INTO `sw_producto` (`pr_nombre`, `pr_descripcion`) VALUES
('S. I. A. E. I.', 'Sistema Integrado de Administración Estudiantil Institucional');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sw_tipo_educacion`
--

CREATE TABLE IF NOT EXISTS `sw_tipo_educacion` (
  `id_tipo_educacion` int(11) NOT NULL AUTO_INCREMENT,
  `id_periodo_lectivo` int(11) NOT NULL,
  `id_institucion` int(11) NOT NULL,
  `te_nombre` varchar(45) NOT NULL,
  `te_bachillerato` tinyint(1) NOT NULL,
  PRIMARY KEY (`id_tipo_educacion`),
  KEY `fk_sw_tipo_educacion_sw_periodo_lectivo_idx` (`id_periodo_lectivo`,`id_institucion`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sw_usuario`
--

CREATE TABLE IF NOT EXISTS `sw_usuario` (
  `id_usuario` int(11) NOT NULL AUTO_INCREMENT,
  `id_perfil` int(11) NOT NULL,
  `id_institucion` int(11) NOT NULL,
  `us_titulo` varchar(5) DEFAULT NULL,
  `us_login` varchar(16) DEFAULT NULL,
  `us_password` varchar(64) DEFAULT NULL,
  `us_fullname` varchar(64) DEFAULT NULL,
  PRIMARY KEY (`id_usuario`,`id_perfil`,`id_institucion`),
  KEY `fk_usuario_perfil_idx` (`id_perfil`),
  KEY `fk_usuario_institucion_idx` (`id_institucion`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Volcado de datos para la tabla `sw_usuario`
--

INSERT INTO `sw_usuario` (`id_usuario`, `id_perfil`, `id_institucion`, `us_titulo`, `us_login`, `us_password`, `us_fullname`) VALUES
(1, 1, 1, 'ING.', 'administrador', 'AlhnlffT1WlPf0wsWGnJBTGDhEmD4vG+UwQrCxhpy9k=', 'GONZALO PEÑAHERRERA'),
(2, 2, 1, 'MG.', 'edisonc', 'X+Ve3aHrlWQVWMUAAKNo6utBd+BspS3v7wgCxzR2NMs=', 'EDISON CUYO');

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `sw_aporte_evaluacion`
--
ALTER TABLE `sw_aporte_evaluacion`
  ADD CONSTRAINT `fk_sw_aporte_evaluacion_sw_periodo_evaluacion1` FOREIGN KEY (`id_periodo_evaluacion`) REFERENCES `sw_periodo_evaluacion` (`id_periodo_evaluacion`) ON DELETE CASCADE;

--
-- Filtros para la tabla `sw_menu`
--
ALTER TABLE `sw_menu`
  ADD CONSTRAINT `fk_sw_menu_sw_perfil` FOREIGN KEY (`id_perfil`) REFERENCES `sw_perfil` (`id_perfil`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `sw_periodo_evaluacion`
--
ALTER TABLE `sw_periodo_evaluacion`
  ADD CONSTRAINT `fk_sw_periodo_evaluacion_sw_periodo_lectivo1` FOREIGN KEY (`id_periodo_lectivo`, `id_institucion`) REFERENCES `sw_periodo_lectivo` (`id_periodo_lectivo`, `id_institucion`) ON DELETE CASCADE;

--
-- Filtros para la tabla `sw_periodo_lectivo`
--
ALTER TABLE `sw_periodo_lectivo`
  ADD CONSTRAINT `fk_sw_periodo_lectivo_sw_institucion` FOREIGN KEY (`id_institucion`) REFERENCES `sw_institucion` (`id_institucion`);

--
-- Filtros para la tabla `sw_tipo_educacion`
--
ALTER TABLE `sw_tipo_educacion`
  ADD CONSTRAINT `fk_sw_tipo_educacion_sw_periodo_lectivo` FOREIGN KEY (`id_periodo_lectivo`, `id_institucion`) REFERENCES `sw_periodo_lectivo` (`id_periodo_lectivo`, `id_institucion`) ON DELETE CASCADE;

--
-- Filtros para la tabla `sw_usuario`
--
ALTER TABLE `sw_usuario`
  ADD CONSTRAINT `sw_usuario_ibfk_1` FOREIGN KEY (`id_perfil`) REFERENCES `sw_perfil` (`id_perfil`),
  ADD CONSTRAINT `sw_usuario_ibfk_2` FOREIGN KEY (`id_institucion`) REFERENCES `sw_institucion` (`id_institucion`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
