-- phpMyAdmin SQL Dump
-- version 4.6.5.2
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 12-04-2017 a las 20:06:41
-- Versión del servidor: 10.1.21-MariaDB
-- Versión de PHP: 7.1.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `carfinder`
--

DELIMITER $$
--
-- Procedimientos
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `Borrar_Coche` (IN `Matr` VARCHAR(7), IN `Em` VARCHAR(40))  MODIFIES SQL DATA
BEGIN
IF (SELECT COUNT(*) FROM coches_usuario WHERE MATRICULA = `Matr`) =  1 THEN
    delete from coche where MATRICULA = `Matr`;
   ELSE
    delete from coches_usuario where MATRICULA = `Matr` AND USUARIO = `Em`;
  END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `Insertar_Coche` (IN `Em` VARCHAR(40), IN `Matr` VARCHAR(7), IN `Mar` VARCHAR(40), IN `Mod` VARCHAR(40))  MODIFIES SQL DATA
BEGIN                         
IF (SELECT COUNT(*) FROM coche WHERE MATRICULA = `Matr`) = 0 THEN
    INSERT INTO coche VALUES (`Matr`, `Mar`, `Mod`);
END IF;
                         
INSERT INTO coches_usuario VALUES (`Matr`, `Em`);
                         
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `Insertar_Posicion`(IN `Matr` VARCHAR(7), IN `Long` VARCHAR(40), IN `Lat` VARCHAR(40))
    MODIFIES SQL DATA
BEGIN                         
IF (SELECT COUNT(*) FROM mapa WHERE Coche = `Matr`) >= 1 THEN
    UPDATE mapa SET Longitud=`Long`, Latitud=`Lat`
		WHERE Coche = `Matr`;
ELSE
	INSERT INTO mapa VALUES (`Matr`, `Long`, `Lat`);
END IF;
                         
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `coche`
--

CREATE TABLE `coche` (
  `Matricula` varchar(7) NOT NULL,
  `Marca` varchar(40) NOT NULL,
  `Modelo` varchar(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `coches_usuario`
--

CREATE TABLE `coches_usuario` (
  `MATRICULA` varchar(7) NOT NULL,
  `USUARIO` varchar(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mapa`
--

CREATE TABLE `mapa` (
  `Coche` varchar(7) NOT NULL,
  `Longitud` varchar(40) NOT NULL,
  `Latitud` varchar(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `Email` varchar(40) NOT NULL,
  `Password` varchar(512) NOT NULL,
  `Nombre` varchar(20) NOT NULL,
  `Apellidos` varchar(40) NOT NULL,
  `Fecha_Nac` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `coche`
--
ALTER TABLE `coche`
  ADD PRIMARY KEY (`Matricula`),
  ADD UNIQUE KEY `Matricula` (`Matricula`);

--
-- Indices de la tabla `coches_usuario`
--
ALTER TABLE `coches_usuario`
  ADD PRIMARY KEY (`MATRICULA`,`USUARIO`),
  ADD KEY `MATRICULA` (`MATRICULA`),
  ADD KEY `USUARIO` (`USUARIO`);

--
-- Indices de la tabla `mapa`
--
ALTER TABLE `mapa`
  ADD UNIQUE KEY `Coche` (`Coche`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`Email`);

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `coches_usuario`
--
ALTER TABLE `coches_usuario`
  ADD CONSTRAINT `coches_usuario_ibfk_1` FOREIGN KEY (`USUARIO`) REFERENCES `usuario` (`Email`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `coches_usuario_ibfk_2` FOREIGN KEY (`MATRICULA`) REFERENCES `coche` (`Matricula`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `mapa`
--
ALTER TABLE `mapa`
  ADD CONSTRAINT `mapa_ibfk_1` FOREIGN KEY (`Coche`) REFERENCES `coches_usuario` (`MATRICULA`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
