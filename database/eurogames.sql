-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 02-06-2024 a las 14:06:48
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `eurogames`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categoria`
--

CREATE TABLE `categoria` (
  `id_categoria` int(11) NOT NULL,
  `nombre_categoria` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `categoria`
--

INSERT INTO `categoria` (`id_categoria`, `nombre_categoria`) VALUES
(1, 'dados'),
(2, 'cartas'),
(3, 'tablero'),
(4, 'oferta'),
(5, 'novedad'),
(6, 'dados - cartas'),
(7, 'dados - tablero'),
(8, 'dados - oferta'),
(9, 'dados - novedad'),
(10, 'cartas - tablero'),
(11, 'cartas - oferta'),
(12, 'cartas - novedad'),
(13, 'tablero - oferta'),
(14, 'tablero - novedad'),
(15, 'oferta - novedad'),
(16, 'dados - cartas - tablero'),
(17, 'dados - cartas - oferta'),
(18, 'dados - cartas - novedad'),
(19, 'dados - tablero - oferta'),
(20, 'dados - tablero - novedad'),
(21, 'dados - oferta - novedad'),
(22, 'cartas - tablero - oferta'),
(23, 'cartas - tablero - novedad'),
(24, 'cartas - oferta - novedad'),
(25, 'tablero - oferta - novedad'),
(26, 'dados - cartas - tablero - oferta'),
(27, 'dados - cartas - tablero - novedad'),
(28, 'dados - cartas - oferta - novedad'),
(29, 'dados - tablero - oferta - novedad'),
(30, 'cartas - tablero - oferta - novedad'),
(31, 'dados - cartas - tablero - oferta - novedad');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_pedido`
--

CREATE TABLE `detalle_pedido` (
  `id_detalle` int(11) NOT NULL,
  `cantidad` int(10) NOT NULL,
  `id_juego` int(11) DEFAULT NULL,
  `id_pedido` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `detalle_pedido`
--

INSERT INTO `detalle_pedido` (`id_detalle`, `cantidad`, `id_juego`, `id_pedido`) VALUES
(2, 2, 1, 2),
(3, 20, 2, 1),
(4, 1, 12, 4),
(5, 1, 3, 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estado`
--

CREATE TABLE `estado` (
  `id_estado` int(11) NOT NULL,
  `nombre_estado` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `estado`
--

INSERT INTO `estado` (`id_estado`, `nombre_estado`) VALUES
(1, 'Realizado'),
(2, 'Confirmado'),
(3, 'En preparación'),
(4, 'Pedido enviado'),
(5, 'En distribución'),
(6, 'En proceso de entrega'),
(7, 'Entregado'),
(8, 'Listo para retiro en tienda'),
(9, 'Retirado en tienda'),
(10, 'cancelado');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `juego`
--

CREATE TABLE `juego` (
  `id_juego` int(11) NOT NULL,
  `nombre_juego` varchar(150) NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  `descripcion` varchar(350) NOT NULL,
  `stock` int(250) NOT NULL,
  `editor` varchar(250) NOT NULL,
  `anio_edicion` varchar(10) NOT NULL,
  `cantidad_jugadores` varchar(10) NOT NULL,
  `foto` varchar(300) NOT NULL,
  `edad_minima` int(11) NOT NULL,
  `duracion_minutos` varchar(250) NOT NULL,
  `id_categoria` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `juego`
--

INSERT INTO `juego` (`id_juego`, `nombre_juego`, `precio`, `descripcion`, `stock`, `editor`, `anio_edicion`, `cantidad_jugadores`, `foto`, `edad_minima`, `duracion_minutos`, `id_categoria`) VALUES
(1, 'Carcassonne', 29.99, 'Carcassonne es un juego de mesa diseñado por Klaus-Jürgen Wrede. Es considerado un clásico de los juegos de mesa modernos. Los jugadores construyen el tablero mientras juegan, añadiendo losetas que representan campos, ciudades, caminos y monasterios.Esta edición incluye las miniexpansiones El río y el abad', 50, 'Devir', '2024', '2-5', '../img/carcassonne.png', 10, '30', 13),
(2, 'Catan', 44.99, 'Los colonos de Catán es un juego de mesa de tipo juego de mesa. Fue creado por Klaus Teuber en 1995 y se considera el primer título de la serie de juegos de la serie de los colonos de Catán. El juego se juega en un tablero modular hecho de 19 hexágonos que representan islas.', 30, 'Devir', '2024', '3-4', '../img/catan2.png', 10, '60 ', 26),
(3, 'Aventureros al Tren', 39.99, 'Ticket to Ride es un juego de mesa de ferrocarriles diseñado por Alan R. Moon y publicado en 2004 por Days of Wonder. Utiliza una variante del concepto de rutas trazadas y está considerado como un juego de estilo europeo. El juego también ha dado lugar a varias expansiones y adaptaciones en línea.', 40, 'Days of Wonder', '2004', '2-5', '../img/aventurerosAlTren2.png', 8, '45', 10),
(4, 'Puerto Rico', 49.99, 'Puerto Rico es un juego de mesa de 2002 diseñado por Andreas Seyfarth que fue uno de los primeros ganadores del prestigioso premio Spiel des Jahres en 2003. Los jugadores asumen el papel de colonos en la isla de Puerto Rico durante el periodo de la colonización española.', 20, 'Alea', '2023', '2-5', '../img/puertoRico.png', 14, '90', 10),
(5, 'Agricola', 54.99, 'Agricola es un juego de mesa diseñado por Uwe Rosenberg. Publicado por primera vez en Alemania en 2007, el juego está enmarcado en el contexto de la agricultura en el siglo XVII. Los jugadores asumen el papel de agricultores que deben mejorar su granja y criar animales para obtener la mayor cantidad de recursos al final del juego.', 25, 'Lookout Games', '2018', '1-4', '../img/agricola2.png', 10, '90', 10),
(6, '7 Wonders', 34.99, '7 Wonders es un juego de mesa diseñado por Antoine Bauza. Publicado por primera vez en 2010 por Repos Production, el juego se centra en la construcción de maravillas antiguas y el desarrollo de civilizaciones a lo largo de tres eras.', 60, 'Asmodee', '2010', '3-7', '../img/sevenWonders2.png', 10, '30', 2),
(7, 'Pandemic', 64.99, 'Pandemic es un juego de mesa de cooperación diseñado por Matt Leacock. Publicado en 2008 por Z-Man Games, el juego presenta a los jugadores como miembros de un equipo de especialistas en enfermedades que deben colaborar para contener y erradicar brotes de enfermedades en todo el mundo.', 35, 'Z-Man Games', '2021', '2-4', '../img/pandemic2.png', 8, '45', 22),
(8, 'Terraforming Mars', 59.99, 'Terraforming Mars es un juego de mesa diseñado por Jacob Fryxelius. Publicado en 2016 por FryxGames y Stronghold Games, los jugadores asumen el papel de corporaciones que trabajan juntas para terraformar el planeta Marte, desarrollando infraestructuras y desencadenando eventos para hacerlo habitable.', 15, 'FryxGames', '2016', '1-5', '../img/terraformingMars2.png', 10, '90', 30),
(9, 'Wingspan', 49.99, 'Wingspan es un juego de mesa diseñado por Elizabeth Hargrave. Publicado en 2019 por Stonemaier Games, el juego se centra en la observación de aves y la construcción de un hábitat para atraer a diferentes especies de aves. Los jugadores compiten por puntos al final de cuatro rondas.', 10, 'Maldito Games', '2019', '1-5', '../img/wingspan2.png', 10, '45', 27),
(10, 'Gloomhaven', 74.99, 'Gloomhaven es un juego de mesa diseñado por Isaac Childres. Publicado en 2017 por Cephalofair Games, es un juego de rol de mesa de estilo euro-aventura cooperativo, en el que un grupo de jugadores controla a los personajes que embarcan en una serie de misiones en una campaña en curso.', 45, 'Asmodee', '2017', '1-4', '../img/gloomhaven2.png', 14, '90 ', 16),
(11, 'The Red Cathedral', 25.00, '\"The Red Cathedral\" nos introduce en el complicado mundo de la construcción de las catedrales y, concretamente, en la belleza de la espectacular Catedral de San Basilio. Utiliza tus dotes arquitectónicas y tu capacidad de trabajo para construir, poco a poco, esta maravilla estructural.\r\n', 20, 'Devir', '2024', '1-4', '../img/redCathedral.png', 10, '60', 2),
(12, 'Stone Age', 39.00, 'Stone Age es un juego de gestión de recursos en el que dirigirás a una tribu de trogloditas y tendrás que encargarte de conseguir conseguir materias primas como madera, oro o piedra, para así poder construir tu aldea y mantenerla alimentada.\r\n\r\n¡Viaja en el tiempo y descubre cómo se vivía en la Edad de Piedra con Stone Age!', 10, 'Devir', '2019', '2-4', '../img/stoneAge2.png', 10, '60', 26),
(13, '7 wonders duel', 27.00, '\"7 Wonders Duel\" es un juego de mesa diseñado por Antoine Bauza y Bruno Cathala, publicado por Repos Production en 2015. Es una versión para dos jugadores del popular juego \"7 Wonders\", adaptado para ofrecer una experiencia estratégica y competitiva en un formato más compacto.\r\n7 Wonders Duel te ofrece un sistema totalmente nuevo de robo de cartas,', 15, 'Asmodee', '2022', '2', '../img/sevenWondersDuel2.png', 10, '30 ', 12),
(14, 'ff', 20.04, 'fadfa', 2, 'x', '2020', '3', '../img/VintageGo2.jpg', 8, '5656', 1),
(15, 'ff', 20.04, 'fadfa', 0, 'x', '2020', '3', '../img/VintageGo2.jpg', 8, '5656', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedido`
--

CREATE TABLE `pedido` (
  `id_pedido` int(11) NOT NULL,
  `fecha_pedido` date NOT NULL DEFAULT current_timestamp(),
  `direccion_envio` varchar(250) NOT NULL,
  `id_usuario` int(250) NOT NULL,
  `cif_nif` varchar(255) NOT NULL,
  `id_estado` int(11) NOT NULL,
  `provincia` varchar(150) NOT NULL,
  `localidad` varchar(150) NOT NULL,
  `codigo_postal` varchar(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pedido`
--

INSERT INTO `pedido` (`id_pedido`, `fecha_pedido`, `direccion_envio`, `id_usuario`, `cif_nif`, `id_estado`, `provincia`, `localidad`, `codigo_postal`) VALUES
(1, '2024-04-03', 'Calle Primavera 12, Piso 1, Puerta A', 40, '', 7, 'SEVILLA', 'SEVILLA', '41001'),
(2, '2024-04-12', 'Plaza de la Luna 7, Piso 2', 1, '', 9, 'MADRID', 'ALCOBENDAS', '28100'),
(3, '2024-04-08', 'Calle del Olivo 22, Depto 4 B', 3, '', 7, 'MADRID', 'MADRID', '28001'),
(4, '2024-04-15', 'Calle del Bosque 8, Piso 3', 5, '', 10, 'ZARAGOZA', 'HUESCA', '22001');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `id_usuario` int(250) NOT NULL,
  `nombre_usuario` varchar(150) NOT NULL,
  `apellidos_usuario` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `telefono` varchar(15) NOT NULL,
  `es_administrador` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`id_usuario`, `nombre_usuario`, `apellidos_usuario`, `email`, `password`, `telefono`, `es_administrador`) VALUES
(1, 'Juan ', 'Perez Flores', 'juan@eurogames.com', 'pass123', '123456789', 1),
(2, 'María Emilia', 'Garcia Machado', 'maria@example.com', '$2y$10$gTJ/lWieP.X3AIK7SZLAPup.MY/rfVm77GqtN6/Zgx77Iow7GYtWG', '987654321', 0),
(3, 'Pedro ', 'Lopez Cuadradro', 'pedro@example.com', '$2y$10$1ochYufOdGvY4WQsHcX7z.vOWoPhm6BYprtkh7MO70BZxr4rtaoZ$2y$10$1ochYufOdGvY4WQsHcX7z.vOWoPhm6BYprtkh7MO70BZxr4rtaoZe', '456789123', 0),
(4, 'Laura Rocio', 'Martinez Hernandez', 'laura@example.com', '$2y$10$uauStDvzamCCCiiB1mSQse2vL45B63scaYtzUiEOTdL841HS56CcW', '789123456', 0),
(5, 'Ana Clara', 'Rodriguez Perez', 'ana@example.com', '$2y$10$AjKMfQiOFpCh/yWIyeVzeORWoxDylkJWkmTiRMW6Orsep3qNfA3gi', '654987321', 0),
(6, 'Empresa Falsa SA', '', 'compras@empresafalsasa.com', '$2y$10$b6VkRbqOtveOM9LUza4o2ersOX8M191Rsw43ryGY49Ktp5FU2wBWW', '6359999999', 0),
(37, 'f', 'f', 'culos@sucio.com', '$2y$10$mzFt/2EodYRKklK7aN7kt.Z8wmV0yAtpwt3uFGvNBbEpkjaVysDbe', '654321789', 0),
(38, 'afadf', 'fff', 'fdfd8@x.com', '$2y$10$TTVWSkhZrE/dnGAVuNUfbeO/89Xt0tRFmgOwmsPMR7uGqcuYZisNG', '123456789', 0),
(39, 'Manolita Manuela', 'ff', 'ignabasso@gmail.com', '$2y$10$6x4Fdme5BgToxue8jtqmjOnRhm8PR7N9tW4eAe2ldE.xWoA4HWOX2', '123456789', 0),
(40, 'FFFF', 'FFFF', 'p@x.com', '$2y$10$6x4Fdme5BgToxue8jtqmjOnRhm8PR7N9tW4eAe2ldE.xWoA4HWOX2', '654321123', 0),
(41, 'MARIA MERCEDES', 'rodrigo', 'admin@x.com', '$2y$10$6x4Fdme5BgToxue8jtqmjOnRhm8PR7N9tW4eAe2ldE.xWoA4HWOX2', '6359456464', 1),
(42, 'daffadf', 'fdafafafa', 'email@x.com', '$2y$10$rpLFQVyzeCR8cV4C.K9AYOIh9Vi56Qsm3YWFzycbQysG9soySYImu', 'fdafaf', 0);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `categoria`
--
ALTER TABLE `categoria`
  ADD PRIMARY KEY (`id_categoria`),
  ADD UNIQUE KEY `id_categoria` (`id_categoria`);

--
-- Indices de la tabla `detalle_pedido`
--
ALTER TABLE `detalle_pedido`
  ADD PRIMARY KEY (`id_detalle`),
  ADD UNIQUE KEY `id_detalle` (`id_detalle`),
  ADD KEY `id_juego` (`id_juego`),
  ADD KEY `id_pedido` (`id_pedido`);

--
-- Indices de la tabla `estado`
--
ALTER TABLE `estado`
  ADD PRIMARY KEY (`id_estado`);

--
-- Indices de la tabla `juego`
--
ALTER TABLE `juego`
  ADD PRIMARY KEY (`id_juego`),
  ADD UNIQUE KEY `id_juego` (`id_juego`),
  ADD KEY `id_categoria` (`id_categoria`);

--
-- Indices de la tabla `pedido`
--
ALTER TABLE `pedido`
  ADD PRIMARY KEY (`id_pedido`),
  ADD KEY `id_usuario` (`id_usuario`),
  ADD KEY `id_estado` (`id_estado`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id_usuario`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `id_usuario` (`id_usuario`),
  ADD UNIQUE KEY `email_2` (`email`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `categoria`
--
ALTER TABLE `categoria`
  MODIFY `id_categoria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT de la tabla `detalle_pedido`
--
ALTER TABLE `detalle_pedido`
  MODIFY `id_detalle` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `estado`
--
ALTER TABLE `estado`
  MODIFY `id_estado` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `juego`
--
ALTER TABLE `juego`
  MODIFY `id_juego` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT de la tabla `pedido`
--
ALTER TABLE `pedido`
  MODIFY `id_pedido` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id_usuario` int(250) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `detalle_pedido`
--
ALTER TABLE `detalle_pedido`
  ADD CONSTRAINT `detalle_pedido_ibfk_1` FOREIGN KEY (`id_juego`) REFERENCES `juego` (`id_juego`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `detalle_pedido_ibfk_2` FOREIGN KEY (`id_pedido`) REFERENCES `pedido` (`id_pedido`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `juego`
--
ALTER TABLE `juego`
  ADD CONSTRAINT `categoria_id_categoria_juego` FOREIGN KEY (`id_categoria`) REFERENCES `categoria` (`id_categoria`);

--
-- Filtros para la tabla `pedido`
--
ALTER TABLE `pedido`
  ADD CONSTRAINT `pedido_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`),
  ADD CONSTRAINT `pedido_ibfk_2` FOREIGN KEY (`id_estado`) REFERENCES `estado` (`id_estado`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
