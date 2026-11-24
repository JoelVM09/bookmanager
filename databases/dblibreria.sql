-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 24-11-2026 a las 00:04:53
-- Versión del servidor: 10.11.19-MariaDB
-- Versión de PHP: 8.3.37

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `dblibreria`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `publicadores`
--

DROP TABLE IF EXISTS `publicadores`;
CREATE TABLE `publicadores` (
  `id_pub` char(4) NOT NULL,
  `nombre_pub` varchar(40) NOT NULL,
  `ciudad` varchar(30) DEFAULT NULL,
  `provincia` varchar(30) DEFAULT NULL,
  `pais` varchar(30) DEFAULT 'Republica Dominicana'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `publicadores`
--

INSERT INTO `publicadores` (`id_pub`, `nombre_pub`, `ciudad`, `provincia`, `pais`) VALUES
('0101', 'Editora Quisqueyana', 'Santo Domingo', 'Distrito Nacional', 'Republica Dominicana'),
('0102', 'Ediciones del Cibao', 'Santiago de los Caballeros', 'Santiago', 'Republica Dominicana'),
('0103', 'Editorial Caribe Sur', 'San Pedro de Macoris', 'San Pedro de Macoris', 'Republica Dominicana'),
('0104', 'Editora Playa Dorada', 'Puerto Plata', 'Puerto Plata', 'Republica Dominicana'),
('0105', 'Grupo Editorial Altagracia', 'La Romana', 'La Romana', 'Republica Dominicana');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tiendas`
--

DROP TABLE IF EXISTS `tiendas`;
CREATE TABLE `tiendas` (
  `id_tienda` char(4) NOT NULL,
  `nombre_tienda` varchar(40) NOT NULL,
  `direccion` varchar(60) DEFAULT NULL,
  `ciudad` varchar(30) DEFAULT NULL,
  `provincia` varchar(30) DEFAULT NULL,
  `codigo_postal` char(5) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tiendas`
--

INSERT INTO `tiendas` (`id_tienda`, `nombre_tienda`, `direccion`, `ciudad`, `provincia`, `codigo_postal`) VALUES
('T001', 'Libreria El Conde', 'Calle El Conde #105, Zona Colonial', 'Santo Domingo', 'Distrito Nacional', '10210'),
('T002', 'Libros del Cibao', 'Av. 27 de Febrero #48, Los Jardines', 'Santiago de los Caballeros', 'Santiago', '51000'),
('T003', 'Libreria Malecon', 'Av. George Washington #350', 'Santo Domingo', 'Distrito Nacional', '10204'),
('T004', 'Papeleria y Libros Punta Cana', 'Plaza Bavaro, Local 12, Bavaro', 'Higuey', 'La Altagracia', '23000'),
('T005', 'Libreria La Vega Cultural', 'Calle Duarte #22, Centro', 'La Vega', 'La Vega', '41000');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `titulos`
--

DROP TABLE IF EXISTS `titulos`;
CREATE TABLE `titulos` (
  `id_titulo` varchar(6) NOT NULL,
  `titulo` varchar(80) NOT NULL,
  `tipo` varchar(12) NOT NULL DEFAULT 'indefinido',
  `id_pub` char(4) DEFAULT NULL,
  `precio` decimal(8,2) DEFAULT NULL,
  `notas` varchar(200) DEFAULT NULL,
  `fecha_pub` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `titulos`
--

INSERT INTO `titulos` (`id_titulo`, `titulo`, `tipo`, `id_pub`, `precio`, `notas`, `fecha_pub`) VALUES
('AL5001', 'Beisbol y Cultura Dominicana', 'deportes', '0105', 550.00, 'La historia del beisbol y sus grandes figuras.', '2017-08-25'),
('CI2001', 'Cuentos del Cibao', 'literatura', '0102', 400.00, 'Antologia de cuentos de autores cibaeños.', '2016-11-20'),
('CI2002', 'Programacion Web con PHP', 'computacion', '0102', 1200.00, 'Guia practica para principiantes.', '2022-02-10'),
('CS3001', 'Negocios en el Caribe', 'negocios', '0103', 950.00, 'Estrategias para emprender en la region.', '2019-06-05'),
('PD4001', 'Guia de Playas de la Costa Norte', 'turismo', '0104', 700.00, 'Las mejores playas de Puerto Plata y Samana.', '2021-12-01'),
('QU1001', 'Historia Esencial de Santo Domingo', 'historia', '0101', 650.00, 'Recorrido por la historia de la ciudad primada de America.', '2018-03-15'),
('QU1002', 'Cocina Dominicana Tradicional', 'cocina', '0101', 850.00, 'Recetas de sancocho, mangu y moro de guandules.', '2020-09-01');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `publicadores`
--
ALTER TABLE `publicadores`
  ADD PRIMARY KEY (`id_pub`);

--
-- Indices de la tabla `tiendas`
--
ALTER TABLE `tiendas`
  ADD PRIMARY KEY (`id_tienda`);

--
-- Indices de la tabla `titulos`
--
ALTER TABLE `titulos`
  ADD PRIMARY KEY (`id_titulo`),
  ADD KEY `fk_titulos_publicadores` (`id_pub`);

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `titulos`
--
ALTER TABLE `titulos`
  ADD CONSTRAINT `fk_titulos_publicadores` FOREIGN KEY (`id_pub`) REFERENCES `publicadores` (`id_pub`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
