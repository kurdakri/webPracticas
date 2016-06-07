-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 06-06-2016 a las 18:16:47
-- Versión del servidor: 5.5.24-log
-- Versión de PHP: 5.4.3

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de datos: `mydb`
--
CREATE DATABASE IF NOT EXISTS mydb;
USE mydb;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `coordinador`
--


CREATE TABLE IF NOT EXISTS `coordinador` (
  `idCoordinador` int(11) NOT NULL AUTO_INCREMENT,
  `login` varchar(45) DEFAULT NULL,
  `password` varchar(45) DEFAULT NULL,
  `nombre` varchar(45) DEFAULT NULL,
  `email` varchar(45) DEFAULT NULL,
  `telefono` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`idCoordinador`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Volcado de datos para la tabla `coordinador`
--

INSERT INTO `coordinador` (`idCoordinador`, `login`, `password`, `nombre`, `email`, `telefono`) VALUES
(1, 'admin', 'admin', 'Administrador', 'myborrajo@gmail.com', '685561018');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empresa`
--

CREATE TABLE IF NOT EXISTS `empresa` (
  `idEmpresa` int(11) NOT NULL AUTO_INCREMENT,
  `centro` varchar(20) DEFAULT NULL,
  `telefono` varchar(45) DEFAULT NULL,
  `localidad` varchar(45) DEFAULT NULL,
  `provincia` varchar(45) DEFAULT NULL,
  `email` varchar(45) DEFAULT NULL,
  `calle` varchar(45) DEFAULT NULL,
  `nombre` varchar(45) DEFAULT NULL,
  `password` varchar(45) DEFAULT NULL,
  `login` varchar(45) DEFAULT NULL,
  `nombreTutor` varchar(45) DEFAULT NULL,
  `cargoTutor` varchar(45) DEFAULT NULL,
  `tareas` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`idEmpresa`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Volcado de datos para la tabla `empresa`
--

INSERT INTO `empresa` (`idEmpresa`, `centro`, `telefono`, `localidad`, `provincia`, `email`, `calle`, `nombre`, `password`, `login`, `nombreTutor`, `cargoTutor`, `tareas`) VALUES
(1, 'Petín', '988334433', 'Petín', 'Ourense', 'empresa1@gmail.com', 'Petín nº2', 'Empresa 1', 'emp1', 'emp1', 'Alfredo', 'Jefe', 'Dirección'),
(2, 'emp2', '988322222', 'O Barco', 'Oursene', 'emp2@gmail.com', 'Conde Fenosa', 'emp2', 'emp2', 'emp2', 'Federico', 'RRHH', 'RRHH');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estudiante`
--

CREATE TABLE IF NOT EXISTS `estudiante` (
  `idEstudiante` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(45) DEFAULT NULL,
  `apellidos` varchar(45) DEFAULT NULL,
  `dni` varchar(45) DEFAULT NULL,
  `fechaNac` date DEFAULT NULL,
  `email` varchar(45) DEFAULT NULL,
  `telefono` varchar(45) DEFAULT NULL,
  `login` varchar(45) DEFAULT NULL,
  `password` varchar(45) DEFAULT NULL,
  `campus` varchar(45) DEFAULT NULL,
  `facultad` varchar(45) DEFAULT NULL,
  `titulacion` varchar(45) DEFAULT NULL,
  `curso` varchar(10) DEFAULT NULL,
  `inicioTitulacion` varchar(10) DEFAULT NULL,
  `pAntes` int(11) DEFAULT NULL,
  `pAntesYear` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`idEstudiante`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=10 ;

--
-- Volcado de datos para la tabla `estudiante`
--

INSERT INTO `estudiante` (`idEstudiante`, `nombre`, `apellidos`, `dni`, `fechaNac`, `email`, `telefono`, `login`, `password`, `campus`, `facultad`, `titulacion`, `curso`, `inicioTitulacion`, `pAntes`, `pAntesYear`) VALUES
(1, 'Pedro', 'Sanchez', '3433344333T', '2016-06-17', 'ps@gmail.com', '666556655', 'est1', 'est1', 'Ourense', 'ESE', 'Grado', '4º', '2000', 0, ''),
(3, 'Est2', 'Est2', '43534534H', '2016-05-12', 'est2@gmail.com', '53521345235', 'est2', 'est2', 'Ourense', 'ESEI', 'Grado', '3', '2002', 0, ''),
(7, 'Mario', 'Yáñez Borrajo', '76723676T', '1987-09-28', 'myborrajo@esei.uvigo.es', '685561018', 'kurdakri', '1123581321', 'Ourense', 'ESEI', 'Grado', '4º', '2011', 0, ''),
(8, 'Federico', 'Jimenez', '33444553Y', '1956-10-08', 'fjimenez@gmail.com', '666666666', 'est3', 'est3', 'Ourense', 'ESEI', 'Master', '3º', '2012', 1, '2013'),
(9, 'Manolo', 'Kabezobolo', '43366545E', '1993-10-25', 'manolo@gmail.com', '666776677', 'est4', 'est4', 'Ourense', 'ESEI', 'Grado', '3º', '2011', 1, '2014');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `eventos`
--

CREATE TABLE IF NOT EXISTS `eventos` (
  `idEventos` int(11) NOT NULL AUTO_INCREMENT,
  `nombreEvento` varchar(45) DEFAULT NULL,
  `tipoEvento` varchar(45) DEFAULT NULL,
  `Fecha` date DEFAULT NULL,
  PRIMARY KEY (`idEventos`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=12 ;

--
-- Volcado de datos para la tabla `eventos`
--

INSERT INTO `eventos` (`idEventos`, `nombreEvento`, `tipoEvento`, `Fecha`) VALUES
(6, 'D5-Informe del tutor de la empresa', 'Empresa', '2016-11-24'),
(7, 'D6-Informe del estudiante de grado', 'Estudiante Grado', '2017-01-18'),
(8, 'D6-Informe del estudiante de master', 'Estudiante Master', '2017-01-01'),
(9, 'D6-Anexo obligatorio grado', 'Estudiante Grado', '2017-01-01'),
(10, 'D6-Anexo obligatorio master', 'Estudiante Master', '2017-01-01'),
(11, 'D7-Informe del tutor academico', 'Tutor', '2017-01-01');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `practicas`
--

CREATE TABLE IF NOT EXISTS `practicas` (
  `idPracticas` int(11) NOT NULL AUTO_INCREMENT,
  `titulo` varchar(45) DEFAULT NULL,
  `descripcion` text,
  `Empresa_idEmpresa` int(11) NOT NULL,
  `periodo` varchar(20) DEFAULT NULL,
  `titulacion` varchar(45) DEFAULT NULL,
  `inicio` date DEFAULT NULL,
  `fin` date DEFAULT NULL,
  `horario` varchar(45) DEFAULT NULL,
  `pformativo` text,
  PRIMARY KEY (`idPracticas`,`Empresa_idEmpresa`),
  KEY `fk_Practicas_Empresa_idx` (`Empresa_idEmpresa`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Volcado de datos para la tabla `practicas`
--

INSERT INTO `practicas` (`idPracticas`, `titulo`, `descripcion`, `Empresa_idEmpresa`, `periodo`, `titulacion`, `inicio`, `fin`, `horario`, `pformativo`) VALUES
(1, 'Proyecto de programación en Java', 'La práctica consiste en el desarrollo de un proyecto en Java en un grupo de trabajo formado por 12 personas.\r\nEl trabajo del alumno se tratará fundamentalmente de ayudar en las tareas de programación para los que la empresa lo requiera.', 2, 'segundo', 'indiferente', '2016-06-16', '2016-06-30', '09:00-17:00', 'El alumno desarrollará competencias de:\r\n- Trabajo en equipo\r\n- Liderazgo\r\n- Programación\r\n- Innovación');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `practicas_has_estudiante`
--

CREATE TABLE IF NOT EXISTS `practicas_has_estudiante` (
  `Practicas_idPracticas` int(11) NOT NULL,
  `Practicas_Empresa_idEmpresa` int(11) NOT NULL,
  `Estudiante_idEstudiante` int(11) NOT NULL,
  `Prioridad` int(11) DEFAULT NULL,
  PRIMARY KEY (`Practicas_idPracticas`,`Practicas_Empresa_idEmpresa`,`Estudiante_idEstudiante`),
  KEY `fk_Practicas_has_Estudiante_Estudiante1_idx` (`Estudiante_idEstudiante`),
  KEY `fk_Practicas_has_Estudiante_Practicas1_idx` (`Practicas_idPracticas`,`Practicas_Empresa_idEmpresa`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `practicas_has_estudiante`
--

INSERT INTO `practicas_has_estudiante` (`Practicas_idPracticas`, `Practicas_Empresa_idEmpresa`, `Estudiante_idEstudiante`, `Prioridad`) VALUES
(1, 2, 7, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `practicas_tutor_estudiante`
--

CREATE TABLE IF NOT EXISTS `practicas_tutor_estudiante` (
  `idPracticas_Tutor_Estudiante` int(11) NOT NULL AUTO_INCREMENT,
  `Tutor_idTutor` int(11) NOT NULL,
  `Practicas_idPracticas` int(11) NOT NULL,
  `Practicas_Empresa_idEmpresa` int(11) NOT NULL,
  `Estudiante_idEstudiante` int(11) NOT NULL,
  PRIMARY KEY (`idPracticas_Tutor_Estudiante`,`Tutor_idTutor`,`Practicas_idPracticas`,`Practicas_Empresa_idEmpresa`,`Estudiante_idEstudiante`),
  KEY `fk_Practicas_Tutor_Estudiante_Tutor1_idx` (`Tutor_idTutor`),
  KEY `fk_Practicas_Tutor_Estudiante_Practicas1_idx` (`Practicas_idPracticas`,`Practicas_Empresa_idEmpresa`),
  KEY `fk_Practicas_Tutor_Estudiante_Estudiante1_idx` (`Estudiante_idEstudiante`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Volcado de datos para la tabla `practicas_tutor_estudiante`
--

INSERT INTO `practicas_tutor_estudiante` (`idPracticas_Tutor_Estudiante`, `Tutor_idTutor`, `Practicas_idPracticas`, `Practicas_Empresa_idEmpresa`, `Estudiante_idEstudiante`) VALUES
(5, 2, 1, 2, 7);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tutor`
--

CREATE TABLE IF NOT EXISTS `tutor` (
  `idTutor` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(45) DEFAULT NULL,
  `apellidos` varchar(45) DEFAULT NULL,
  `dni` varchar(45) DEFAULT NULL,
  `telefono` varchar(45) DEFAULT NULL,
  `email` varchar(45) DEFAULT NULL,
  `login` varchar(45) DEFAULT NULL,
  `password` varchar(45) DEFAULT NULL,
  `departamento` varchar(45) DEFAULT NULL,
  `centro` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`idTutor`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Volcado de datos para la tabla `tutor`
--

INSERT INTO `tutor` (`idTutor`, `nombre`, `apellidos`, `dni`, `telefono`, `email`, `login`, `password`, `departamento`, `centro`) VALUES
(1, 'Jimena', 'López Díaz', '3433344333T', '676665566', 'jimenatutor1@gmail.com', 'tutor1', 'tutor1', 'Electrónica', 'ESEI'),
(2, 'tutor2', 'tutor2', '34344333H', '545454534', 'tutor2@gmail.com', 'tutor2', 'tutor2', 'Electrónica', 'ESEI');

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `practicas`
--
ALTER TABLE `practicas`
  ADD CONSTRAINT `fk_Practicas_Empresa` FOREIGN KEY (`Empresa_idEmpresa`) REFERENCES `empresa` (`idEmpresa`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `practicas_has_estudiante`
--
ALTER TABLE `practicas_has_estudiante`
  ADD CONSTRAINT `fk_Practicas_has_Estudiante_Estudiante1` FOREIGN KEY (`Estudiante_idEstudiante`) REFERENCES `estudiante` (`idEstudiante`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Practicas_has_Estudiante_Practicas1` FOREIGN KEY (`Practicas_idPracticas`, `Practicas_Empresa_idEmpresa`) REFERENCES `practicas` (`idPracticas`, `Empresa_idEmpresa`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `practicas_tutor_estudiante`
--
ALTER TABLE `practicas_tutor_estudiante`
  ADD CONSTRAINT `fk_Practicas_Tutor_Estudiante_Estudiante1` FOREIGN KEY (`Estudiante_idEstudiante`) REFERENCES `estudiante` (`idEstudiante`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Practicas_Tutor_Estudiante_Practicas1` FOREIGN KEY (`Practicas_idPracticas`, `Practicas_Empresa_idEmpresa`) REFERENCES `practicas` (`idPracticas`, `Empresa_idEmpresa`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Practicas_Tutor_Estudiante_Tutor1` FOREIGN KEY (`Tutor_idTutor`) REFERENCES `tutor` (`idTutor`) ON DELETE NO ACTION ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

-- PRIVILEGIOS DE USUARIO
CREATE USER 'mydbuser'@'localhost' IDENTIFIED BY 'mydbpass';
GRANT ALL PRIVILEGES ON mydb.* TO 'mydbuser'@'localhost';
FLUSH PRIVILEGES;
