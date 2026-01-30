-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3306
-- Tiempo de generación: 30-01-2026 a las 12:43:50
-- Versión del servidor: 8.3.0
-- Versión de PHP: 8.2.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `bdblog`
--

DELIMITER $$
--
-- Procedimientos
--
DROP PROCEDURE IF EXISTS `sp_insert_log`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_insert_log` (IN `p_usuario` VARCHAR(50), IN `p_operacion` VARCHAR(50))   BEGIN
  INSERT INTO logs (fecha_hora, usuario, operacion)
  VALUES (NOW(), p_usuario, p_operacion);
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias`
--

DROP TABLE IF EXISTS `categorias`;
CREATE TABLE IF NOT EXISTS `categorias` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nombre` (`nombre`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `categorias`
--

INSERT INTO `categorias` (`id`, `nombre`) VALUES
(4, 'Música'),
(1, 'Noticias'),
(3, 'Opinión'),
(2, 'Tutoriales');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `entradas`
--

DROP TABLE IF EXISTS `entradas`;
CREATE TABLE IF NOT EXISTS `entradas` (
  `id` int NOT NULL AUTO_INCREMENT,
  `usuario_id` int NOT NULL,
  `categoria_id` int NOT NULL,
  `titulo` varchar(200) COLLATE utf8mb4_general_ci NOT NULL,
  `imagen` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `descripcion` text COLLATE utf8mb4_general_ci NOT NULL,
  `fecha` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_entradas_usuario` (`usuario_id`),
  KEY `fk_entradas_categoria` (`categoria_id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `entradas`
--

INSERT INTO `entradas` (`id`, `usuario_id`, `categoria_id`, `titulo`, `imagen`, `descripcion`, `fecha`) VALUES
(1, 2, 4, 'Concierto de Roger Waters', NULL, '<p>EN<strong> Wizink</strong> <strong>Center</strong></p>', '2026-01-28 21:38:10'),
(3, 2, 4, 'UUUUUUUUUUU', 'img_697a9b387ed353.91320541.jpg', '<p>asdfasdfasdf</p>', '2026-01-29 00:08:43'),
(4, 1, 2, 'BBBBBBBBBBB', NULL, '<p>bbbbbbbbbbbbbb</p>', '2026-01-29 00:09:27'),
(7, 1, 2, 'Python total', NULL, '<p>Todo sobre python</p>', '2026-01-29 01:02:08'),
(8, 1, 4, 'ZZZZZZ', NULL, '<p>WWWWWWWWWWW</p>', '2026-01-29 01:53:57'),
(9, 1, 3, 'El lenguaje de programación de moda?', 'img_697ac22fd01c15.56826719.jpg', '<p>VVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVV</p>', '2026-01-29 03:13:03'),
(10, 1, 1, 'El declive de Open AI', 'img_697ca452189f88.08797178.jpg', '<p>La empresa norteamericana no es capaz obtener los beneficios esperados después de la inversión</p>', '2026-01-30 13:29:57'),
(11, 2, 2, 'Wamp Server', 'img_697ca4d083deb7.13909739.jpg', '<p>WAMP SERVER</p>', '2026-01-30 13:32:16'),
(12, 2, 2, 'JWT Tutorial', 'img_697ca5154d61c3.76123331.jpg', '<p>JWT Tutorial</p>', '2026-01-30 13:33:25'),
(13, 2, 2, 'Tutorial básico de Laravel', 'img_697ca5896593b0.82973342.jpg', '<p><a href=\"https://www.youtube.com/watch?v=cDEVWbz2PpQ\">https://www.youtube.com/watch?v=cDEVWbz2PpQ</a> &nbsp; &nbsp;</p><figure class=\"media\"><oembed url=\"https://www.youtube.com/watch?v=cDEVWbz2PpQ\"></oembed></figure>', '2026-01-30 13:35:06'),
(14, 2, 2, 'Tutorial de Vue JS', 'img_697ca5e7e74550.26070317.jpg', '<p>&nbsp; &nbsp;<a href=\"https://www.youtube.com/watch?v=Kt2E8nblvXU\">https://www.youtube.com/watch?v=Kt2E8nblvXU</a> &nbsp;</p><figure class=\"media\"><oembed url=\"https://www.youtube.com/watch?v=Kt2E8nblvXU\"></oembed></figure>', '2026-01-30 13:36:55');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `logs`
--

DROP TABLE IF EXISTS `logs`;
CREATE TABLE IF NOT EXISTS `logs` (
  `id` int NOT NULL AUTO_INCREMENT,
  `fecha_hora` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `usuario` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `operacion` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `logs`
--

INSERT INTO `logs` (`id`, `fecha_hora`, `usuario`, `operacion`) VALUES
(1, '2026-01-29 01:53:57', 'admin', 'CREAR_ENTRADA'),
(2, '2026-01-29 01:54:19', 'admin', 'ELIMINAR_ENTRADA'),
(3, '2026-01-29 01:54:57', 'admin', 'EDITAR_ENTRADA'),
(4, '2026-01-29 03:00:50', 'admin', 'ELIMINAR_ENTRADA'),
(5, '2026-01-29 03:13:03', 'admin', 'CREAR_ENTRADA'),
(6, '2026-01-30 13:29:57', 'admin', 'CREAR_ENTRADA'),
(7, '2026-01-30 13:30:10', 'admin', 'EDITAR_ENTRADA'),
(8, '2026-01-30 13:32:16', 'user1', 'CREAR_ENTRADA'),
(9, '2026-01-30 13:33:25', 'user1', 'CREAR_ENTRADA'),
(10, '2026-01-30 13:35:06', 'user1', 'CREAR_ENTRADA'),
(11, '2026-01-30 13:35:21', 'user1', 'EDITAR_ENTRADA'),
(12, '2026-01-30 13:36:55', 'user1', 'CREAR_ENTRADA');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
CREATE TABLE IF NOT EXISTS `usuarios` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nick` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `nombre` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `apellidos` varchar(150) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(120) COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `imagen_avatar` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `perfil` enum('admin','user') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'user',
  PRIMARY KEY (`id`),
  UNIQUE KEY `nick` (`nick`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nick`, `nombre`, `apellidos`, `email`, `password`, `imagen_avatar`, `perfil`) VALUES
(1, 'admin', 'Miguel', 'Administrador', 'admin@blog.com', '$2y$10$BMFQLlGW44nYoUt/T8kpYO4F/6HIbpq4wmeNGm9Tvq9VS3EwVczia', NULL, 'admin'),
(2, 'user1', 'Usuario', 'Normal', 'user@blog.com', '$2y$10$H5hjNVWCINlz2qeHci0Wtub.GUOXfgx77CPLmZ7iQMfW7FfdCRv5a', NULL, 'user');

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `entradas`
--
ALTER TABLE `entradas`
  ADD CONSTRAINT `fk_entradas_categoria` FOREIGN KEY (`categoria_id`) REFERENCES `categorias` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_entradas_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
