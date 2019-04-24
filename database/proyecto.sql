-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 06-04-2019 a las 18:11:27
-- Versión del servidor: 10.1.35-MariaDB
-- Versión de PHP: 7.2.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `proyecto`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `camara`
--

CREATE TABLE `camara` (
  `id` int(8) NOT NULL,
  `nombre` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  `usuario_email` varchar(255) COLLATE utf8_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `camara`
--

INSERT INTO `camara` (`id`, `nombre`, `usuario_email`) VALUES
(1, 'Comedor', 'echofox@hotmail.com'),
(2, 'Habitacion', 'echofox@hotmail.com');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `compuesta`
--

CREATE TABLE `compuesta` (
  `escena_id` int(8) NOT NULL,
  `programa_id` int(8) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `dispositivo`
--

CREATE TABLE `dispositivo` (
  `id` int(8) NOT NULL,
  `nombre` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  `habitacion` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  `encendido` tinyint(1) NOT NULL,
  `num_encendidos` int(8) NOT NULL,
  `tiempo_encendido` int(8) NOT NULL,
  `temperatura` decimal(4,2) DEFAULT NULL,
  `usuario_email` varchar(255) COLLATE utf8_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `dispositivo`
--

INSERT INTO `dispositivo` (`id`, `nombre`, `habitacion`, `encendido`, `num_encendidos`, `tiempo_encendido`, `temperatura`, `usuario_email`) VALUES
(2, 'Luces', 'Comedor', 1, 0, 0, NULL, 'echofox@hotmail.com'),
(3, 'Enchufe 1', 'Comedor', 0, 0, 0, NULL, 'echofox@hotmail.com'),
(4, 'Luces', 'Dormitorio', 0, 0, 0, '0.00', 'echofox@hotmail.com'),
(5, 'Lavadora', 'Cocina', 0, 0, 0, NULL, 'echofox@hotmail.com'),
(6, 'Nevera', 'Cocina', 0, 0, 0, NULL, 'echofox@hotmail.com'),
(7, 'Luces', 'Cocina', 0, 0, 0, NULL, 'echofox@hotmail.com'),
(8, 'Luces', 'Comedor', 0, 0, 0, NULL, 'ianmolinav@hotmail.com'),
(9, 'Enchufe', 'Comedor', 0, 0, 0, NULL, 'ianmolinav@hotmail.com'),
(10, 'Television', 'Comedor', 0, 0, 0, NULL, 'ianmolinav@hotmail.com'),
(11, 'Persianas', 'Comedor', 0, 0, 0, NULL, 'ianmolinav@hotmail.com'),
(12, 'Ventilador', 'Comedor', 0, 0, 0, NULL, 'ianmolinav@hotmail.com'),
(13, 'Luces', 'Cocina', 0, 0, 0, NULL, 'ianmolinav@hotmail.com'),
(14, 'Nevera', 'Cocina', 0, 0, 0, NULL, 'ianmolinav@hotmail.com'),
(15, 'Persianas', 'Cocina', 0, 0, 0, NULL, 'ianmolinav@hotmail.com'),
(16, 'Luces', 'Habitacion', 0, 0, 0, NULL, 'ianmolinav@hotmail.com'),
(17, 'Persiana', 'Habitacion', 0, 0, 0, NULL, 'ianmolinav@hotmail.com'),
(18, 'Ventilador', 'Habitacion', 0, 0, 0, '0.00', 'ianmolinav@hotmail.com'),
(19, 'Ordenador', 'Habitacion', 0, 0, 0, NULL, 'ianmolinav@hotmail.com'),
(20, 'Luces', 'Baño', 0, 2, 0, NULL, 'ianmolinav@hotmail.com'),
(21, 'Enchufes', 'Baño', 0, 0, 0, NULL, 'ianmolinav@hotmail.com'),
(22, 'Calefactor', 'Baño', 0, 0, 0, NULL, 'ianmolinav@hotmail.com'),
(23, 'Luces2', 'Comedor', 0, 0, 0, NULL, 'ianmolinav@hotmail.com');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `escena`
--

CREATE TABLE `escena` (
  `id` int(8) NOT NULL,
  `nombre` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  `activa` tinyint(1) NOT NULL,
  `usuario_email` varchar(255) COLLATE utf8_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mensaje`
--

CREATE TABLE `mensaje` (
  `id` int(8) NOT NULL,
  `texto` text COLLATE utf8_spanish_ci NOT NULL,
  `de` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  `para` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  `fecha` datetime NOT NULL,
  `leido` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `mensaje`
--

INSERT INTO `mensaje` (`id`, `texto`, `de`, `para`, `fecha`, `leido`) VALUES
(1, 'Hola que tal?', 'echofox@hotmail.com', 'admin@smartliving.es', '2019-04-02 11:30:21', 1),
(2, 'Muy bien y tu?', 'admin@smartliving.es', 'echofox@hotmail.com', '2019-04-02 12:50:00', 1),
(3, 'Bien', 'echofox@hotmail.com', 'admin@smartliving.es', '2019-04-02 13:18:00', 1),
(4, 'Vale', 'echofox@hotmail.com', 'admin@smartliving.es', '2019-04-02 13:36:32', 1),
(5, 'De acuerdo', 'admin@smartliving.es', 'echofox@hotmail.com', '2019-04-02 17:14:57', 1),
(6, 'Muy bien', 'admin@smartliving.es', 'echofox@hotmail.com', '2019-04-02 17:20:10', 1),
(7, 'Muy guapo', 'admin@smartliving.es', 'echofox@hotmail.com', '2019-04-02 17:24:34', 1),
(8, 'Vale guay', 'echofox@hotmail.com', 'admin@smartliving.es', '2019-04-02 17:28:23', 1),
(9, 'To flama', 'echofox@hotmail.com', 'admin@smartliving.es', '2019-04-02 17:28:31', 1),
(10, 'Me parece bien', 'admin@smartliving.es', 'echofox@hotmail.com', '2019-04-02 17:40:03', 1),
(11, 'qwer', 'admin@smartliving.es', 'echofox@hotmail.com', '2019-04-02 17:40:22', 1),
(12, 'ertyrety', 'admin@smartliving.es', 'echofox@hotmail.com', '2019-04-02 17:40:49', 1),
(13, 'qewrqwer', 'ianmolinav@hotmail.com', 'admin@smartliving.es', '2019-04-02 18:09:40', 1),
(14, 'qwerqwerqwer', 'ianmolinav@hotmail.com', 'admin@smartliving.es', '2019-04-02 18:09:42', 1),
(15, 'qwerqwerqwer', 'ianmolinav@hotmail.com', 'admin@smartliving.es', '2019-04-02 18:09:50', 1),
(17, 'asdfasdfasdf', 'ianmolinav@hotmail.com', 'admin@smartliving.es', '2019-04-02 18:10:43', 1),
(18, 'dgfhdghfdfgh', 'ianmolinav@hotmail.com', 'admin@smartliving.es', '2019-04-02 18:21:19', 1),
(19, 'sdfgdsfgdsfgdsfg', 'ianmolinav@hotmail.com', 'admin@smartliving.es', '2019-04-02 18:21:55', 1),
(20, 'asdfasdf', 'ianmolinav@hotmail.com', 'admin@smartliving.es', '2019-04-02 18:24:57', 1),
(21, 'ianmolinav@hotmail.com', 'admin@smartliving.es', 'ianmolinav@hotmail.com', '2019-04-02 18:28:02', 1),
(22, 'qwerqwer', 'admin@smartliving.es', 'ianmolinav@hotmail.com', '2019-04-03 09:35:35', 1),
(23, 'sdfgsdgf', 'echofox@hotmail.com', 'admin@smartliving.es', '2019-04-03 09:56:50', 1),
(24, 'asdfasdf', 'echofox@hotmail.com', 'admin@smartliving.es', '2019-04-03 10:26:32', 1),
(25, 'aqawretrewt', 'echofox@hotmail.com', 'admin@smartliving.es', '2019-04-03 10:26:37', 1),
(26, 'cvbncvbn', 'echofox@hotmail.com', 'admin@smartliving.es', '2019-04-03 10:26:54', 1),
(27, 'xzcvbxcvbxcvb', 'echofox@hotmail.com', 'admin@smartliving.es', '2019-04-03 10:31:03', 1),
(28, 'sdfgdsfgdsfgs', 'echofox@hotmail.com', 'admin@smartliving.es', '2019-04-03 10:32:12', 1),
(29, 'wertwertewrt', 'echofox@hotmail.com', 'admin@smartliving.es', '2019-04-03 10:32:35', 1),
(30, 'hgdghdfghdfgh', 'echofox@hotmail.com', 'admin@smartliving.es', '2019-04-03 11:12:33', 1),
(31, 'mnvmbvnmvbnm', 'echofox@hotmail.com', 'admin@smartliving.es', '2019-04-03 15:25:10', 1),
(32, 'asdfasdfasdf', 'echofox@hotmail.com', 'admin@smartliving.es', '2019-04-03 16:23:27', 1),
(33, 'fgdsdgfsdgf', 'admin@smartliving.es', 'ianmolinav@hotmail.com', '2019-04-04 10:04:04', 1),
(34, 'puipuiopuio', 'admin@smartliving.es', 'ianmolinav@hotmail.com', '2019-04-04 10:08:37', 1),
(35, 'sdfgdsfgdsfg', 'admin@smartliving.es', 'ianmolinav@hotmail.com', '2019-04-04 10:12:05', 1),
(36, 'sdgfdsfgdsfg', 'admin@smartliving.es', 'ianmolinav@hotmail.com', '2019-04-04 10:13:08', 1),
(37, 'goltuilulgli', 'admin@smartliving.es', 'echofox@hotmail.com', '2019-04-04 13:24:58', 1),
(38, 'pedro sanchez presidente', 'admin@smartliving.es', 'echofox@hotmail.com', '2019-04-05 13:56:07', 1),
(39, 'lo se lo se', 'echofox@hotmail.com', 'admin@smartliving.es', '2019-04-05 13:56:28', 1),
(40, 'asdfasdfasdf', 'admin@smartliving.es', 'echofox@hotmail.com', '2019-04-05 18:11:53', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `programa`
--

CREATE TABLE `programa` (
  `id` int(8) NOT NULL,
  `dispositivo_id` int(8) NOT NULL,
  `inicio` datetime NOT NULL,
  `fin` datetime NOT NULL,
  `temperatura` int(8) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `programa`
--

INSERT INTO `programa` (`id`, `dispositivo_id`, `inicio`, `fin`, `temperatura`) VALUES
(1, 5, '2019-04-10 14:25:00', '2019-04-17 14:25:00', NULL),
(2, 3, '2019-04-10 14:25:00', '2019-04-24 14:25:00', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `email` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  `dni` varchar(10) COLLATE utf8_spanish_ci NOT NULL,
  `nombre` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  `foto` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  `pass` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  `activo` tinyint(1) NOT NULL,
  `puerto` int(8) NOT NULL,
  `admin` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`email`, `dni`, `nombre`, `foto`, `pass`, `activo`, `puerto`, `admin`) VALUES
('admin@smartliving.es', '00000000-X', 'Admin. Smart Living', './imgs/generic.png', '$2y$10$GOPSNBJqoZi1h04G7kD10uy4xUG0BdpucF14R0SBxCGSvl4c6aZcq', 1, 45999, 1),
('echofox@hotmail.com', '11111111-Q', 'Pedro Sanchez', 'imgs/users/1554391939_generic.png', '$2y$10$/ZxLLxeTb7uLF0zGKmvrO.0RG5hnPSil5nRBtvuEoZJwnmjyCWftO', 1, 37174, 0),
('ianmolinav@hotmail.com', '35597515-R', 'Ian Molina', './imgs/generic.png', '$2y$10$DtKoKEMFaa4N4B0TOfPT7OBFGsYk7OkRPgue2QzTgOedmS9MjKb0q', 1, 36160, 0);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `camara`
--
ALTER TABLE `camara`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_email` (`usuario_email`);

--
-- Indices de la tabla `compuesta`
--
ALTER TABLE `compuesta`
  ADD KEY `escena_id` (`escena_id`),
  ADD KEY `programa_id` (`programa_id`);

--
-- Indices de la tabla `dispositivo`
--
ALTER TABLE `dispositivo`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_email` (`usuario_email`);

--
-- Indices de la tabla `escena`
--
ALTER TABLE `escena`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `mensaje`
--
ALTER TABLE `mensaje`
  ADD PRIMARY KEY (`id`),
  ADD KEY `de` (`de`),
  ADD KEY `para` (`para`);

--
-- Indices de la tabla `programa`
--
ALTER TABLE `programa`
  ADD PRIMARY KEY (`id`),
  ADD KEY `dispositivo_id` (`dispositivo_id`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`email`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `camara`
--
ALTER TABLE `camara`
  MODIFY `id` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `dispositivo`
--
ALTER TABLE `dispositivo`
  MODIFY `id` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT de la tabla `escena`
--
ALTER TABLE `escena`
  MODIFY `id` int(8) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `mensaje`
--
ALTER TABLE `mensaje`
  MODIFY `id` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT de la tabla `programa`
--
ALTER TABLE `programa`
  MODIFY `id` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `camara`
--
ALTER TABLE `camara`
  ADD CONSTRAINT `camara_ibfk_1` FOREIGN KEY (`usuario_email`) REFERENCES `usuario` (`email`);

--
-- Filtros para la tabla `compuesta`
--
ALTER TABLE `compuesta`
  ADD CONSTRAINT `compuesta_ibfk_1` FOREIGN KEY (`escena_id`) REFERENCES `escena` (`id`),
  ADD CONSTRAINT `compuesta_ibfk_2` FOREIGN KEY (`programa_id`) REFERENCES `programa` (`id`);

--
-- Filtros para la tabla `dispositivo`
--
ALTER TABLE `dispositivo`
  ADD CONSTRAINT `dispositivo_ibfk_1` FOREIGN KEY (`usuario_email`) REFERENCES `usuario` (`email`);

--
-- Filtros para la tabla `mensaje`
--
ALTER TABLE `mensaje`
  ADD CONSTRAINT `mensaje_ibfk_1` FOREIGN KEY (`de`) REFERENCES `usuario` (`email`),
  ADD CONSTRAINT `mensaje_ibfk_2` FOREIGN KEY (`para`) REFERENCES `usuario` (`email`);

--
-- Filtros para la tabla `programa`
--
ALTER TABLE `programa`
  ADD CONSTRAINT `programa_ibfk_1` FOREIGN KEY (`dispositivo_id`) REFERENCES `dispositivo` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
