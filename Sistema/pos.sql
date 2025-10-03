-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 03-10-2025 a las 22:27:30
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
-- Base de datos: `pos`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cierre_caja`
--

CREATE TABLE `cierre_caja` (
  `id` int(11) NOT NULL,
  `fecha_cierre` datetime NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `total_ventas` decimal(12,2) NOT NULL,
  `cantidad_ventas` int(11) NOT NULL,
  `ventas_json` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`ventas_json`)),
  `creado_en` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `cierre_caja`
--

INSERT INTO `cierre_caja` (`id`, `fecha_cierre`, `id_usuario`, `total_ventas`, `cantidad_ventas`, `ventas_json`, `creado_en`) VALUES
(3, '2025-09-30 16:18:28', 1, 6800.00, 3, '[{\"id_venta\":12,\"fecha\":\"2025-09-30 00:02:22\",\"total\":3000,\"metodo_pago\":\"Tarjeta\",\"productos\":[{\"id\":5,\"nombre\":\"Monster Naranja Ripper\",\"cantidad\":3,\"precio_base\":2000,\"precio_final\":1000,\"total_linea\":3000,\"promocion\":{\"id_promocion\":11,\"tipo\":\"descuento\",\"parametro\":50,\"etiqueta\":\"-50%\",\"detalle\":\"Descuento de 50% aplicado\",\"observacion\":\"\",\"precio_unit_base\":2000,\"precio_unit_final\":1000,\"paga_unidades\":3,\"gratis\":0}}]},{\"id_venta\":13,\"fecha\":\"2025-09-30 00:19:56\",\"total\":2000,\"metodo_pago\":\"Tarjeta\",\"productos\":[{\"id\":5,\"nombre\":\"Monster Naranja Ripper\",\"cantidad\":2,\"precio_base\":2000,\"precio_final\":1000,\"total_linea\":2000,\"promocion\":{\"id_promocion\":11,\"tipo\":\"descuento\",\"parametro\":50,\"etiqueta\":\"-50%\",\"detalle\":\"Descuento de 50% aplicado\",\"observacion\":\"\",\"precio_unit_base\":2000,\"precio_unit_final\":1000,\"paga_unidades\":2,\"gratis\":0}}]},{\"id_venta\":14,\"fecha\":\"2025-09-30 00:31:44\",\"total\":1800,\"metodo_pago\":\"Tarjeta\",\"productos\":[{\"id\":3,\"nombre\":\"Coca Cola Original\",\"cantidad\":2,\"precio_base\":1800,\"precio_final\":1800,\"total_linea\":1800,\"promocion\":{\"id_promocion\":10,\"tipo\":\"2x1\",\"parametro\":0,\"etiqueta\":\"2x1\",\"detalle\":\"Promo 2x1: 1 unidad(es) gratis\",\"observacion\":\"\",\"precio_unit_base\":1800,\"precio_unit_final\":1800,\"paga_unidades\":1,\"gratis\":1}}]}]', '2025-09-30 19:18:28'),
(5, '2025-10-03 17:11:18', 1, 35700.00, 9, '[{\"id_venta\":15,\"fecha\":\"2025-10-03 15:27:23\",\"total\":2800,\"metodo_pago\":\"Efectivo\",\"productos\":[{\"id\":2,\"nombre\":\"Coca Cola Original\",\"cantidad\":1,\"precio_base\":1000,\"precio_final\":1000,\"total_linea\":1000,\"promocion\":null},{\"id\":3,\"nombre\":\"Coca Cola Original\",\"cantidad\":1,\"precio_base\":1800,\"precio_final\":1800,\"total_linea\":1800,\"promocion\":null}]},{\"id_venta\":16,\"fecha\":\"2025-10-03 15:36:02\",\"total\":3000,\"metodo_pago\":\"Tarjeta\",\"productos\":[{\"id\":2,\"nombre\":\"Coca Cola Original\",\"cantidad\":3,\"precio_base\":1000,\"precio_final\":1000,\"total_linea\":3000,\"promocion\":null}]},{\"id_venta\":17,\"fecha\":\"2025-10-03 15:37:06\",\"total\":2000,\"metodo_pago\":\"Tarjeta\",\"productos\":[{\"id\":7,\"nombre\":\"Monster Ultra\",\"cantidad\":1,\"precio_base\":2000,\"precio_final\":2000,\"total_linea\":2000,\"promocion\":null}]},{\"id_venta\":18,\"fecha\":\"2025-10-03 15:37:46\",\"total\":6000,\"metodo_pago\":\"Tarjeta\",\"productos\":[{\"id\":6,\"nombre\":\"Monster Original\",\"cantidad\":3,\"precio_base\":2000,\"precio_final\":2000,\"total_linea\":6000,\"promocion\":null}]},{\"id_venta\":19,\"fecha\":\"2025-10-03 15:45:29\",\"total\":4000,\"metodo_pago\":\"Tarjeta\",\"productos\":[{\"id\":2,\"nombre\":\"Coca Cola Original\",\"cantidad\":4,\"precio_base\":1000,\"precio_final\":1000,\"total_linea\":4000,\"promocion\":null}]},{\"id_venta\":20,\"fecha\":\"2025-10-03 15:48:47\",\"total\":10000,\"metodo_pago\":\"Tarjeta\",\"productos\":[{\"id\":7,\"nombre\":\"Monster Ultra\",\"cantidad\":5,\"precio_base\":2000,\"precio_final\":2000,\"total_linea\":10000,\"promocion\":null}]},{\"id_venta\":21,\"fecha\":\"2025-10-03 16:43:46\",\"total\":3500,\"metodo_pago\":\"Tarjeta\",\"productos\":[{\"id\":6,\"nombre\":\"Monster Original\",\"cantidad\":3,\"precio_base\":2000,\"precio_final\":500,\"total_linea\":1500,\"promocion\":{\"id_promocion\":22,\"tipo\":\"precio_fijo\",\"parametro\":500,\"etiqueta\":\"Precio fijo\",\"detalle\":\"Precio fijo promocional $500\",\"observacion\":\"\",\"precio_unit_base\":2000,\"precio_unit_final\":500,\"paga_unidades\":3,\"gratis\":0}},{\"id\":5,\"nombre\":\"Monster Naranja Ripper\",\"cantidad\":2,\"precio_base\":2000,\"precio_final\":2000,\"total_linea\":2000,\"promocion\":{\"id_promocion\":20,\"tipo\":\"2x1\",\"parametro\":0,\"etiqueta\":\"2x1\",\"detalle\":\"Promo 2x1: 1 unidad(es) gratis\",\"observacion\":\"\",\"precio_unit_base\":2000,\"precio_unit_final\":2000,\"paga_unidades\":1,\"gratis\":1}}]},{\"id_venta\":22,\"fecha\":\"2025-10-03 17:07:18\",\"total\":1500,\"metodo_pago\":\"Efectivo\",\"productos\":[{\"id\":2,\"nombre\":\"Coca Cola Original\",\"cantidad\":1,\"precio_base\":1000,\"precio_final\":1000,\"total_linea\":1000,\"promocion\":null},{\"id\":6,\"nombre\":\"Monster Original\",\"cantidad\":1,\"precio_base\":2000,\"precio_final\":500,\"total_linea\":500,\"promocion\":{\"id_promocion\":22,\"tipo\":\"precio_fijo\",\"parametro\":500,\"etiqueta\":\"Precio fijo\",\"detalle\":\"Precio fijo promocional $500\",\"observacion\":\"\",\"precio_unit_base\":2000,\"precio_unit_final\":500,\"paga_unidades\":1,\"gratis\":0}}]},{\"id_venta\":23,\"fecha\":\"2025-10-03 17:08:36\",\"total\":2900,\"metodo_pago\":\"Tarjeta\",\"productos\":[{\"id\":13,\"nombre\":\"Red Bull Original\",\"cantidad\":2,\"precio_base\":2900,\"precio_final\":2900,\"total_linea\":2900,\"promocion\":{\"id_promocion\":24,\"tipo\":\"2x1\",\"parametro\":0,\"etiqueta\":\"2x1\",\"detalle\":\"Promo 2x1: 1 unidad(es) gratis\",\"observacion\":\"\",\"precio_unit_base\":2900,\"precio_unit_final\":2900,\"paga_unidades\":1,\"gratis\":1}}]}]', '2025-10-03 20:11:18');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `perdida`
--

CREATE TABLE `perdida` (
  `id` int(11) NOT NULL,
  `fecha` datetime NOT NULL DEFAULT current_timestamp(),
  `producto_id` int(11) NOT NULL,
  `producto_codigo` varchar(50) NOT NULL,
  `producto_nombre` varchar(100) NOT NULL,
  `cantidad` int(11) NOT NULL CHECK (`cantidad` > 0),
  `motivo` varchar(100) DEFAULT NULL,
  `observacion` text DEFAULT NULL,
  `usuario_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `perdida`
--

INSERT INTO `perdida` (`id`, `fecha`, `producto_id`, `producto_codigo`, `producto_nombre`, `cantidad`, `motivo`, `observacion`, `usuario_id`) VALUES
(1, '2025-09-23 03:45:53', 1, '12345', 'Coca Cola Original', 1, 'rotura', 'Botella quebrada', 1),
(2, '2025-09-23 13:39:09', 1, '12345', 'Coca Cola Original', 2, 'consumo_interno', 'Bebida para la once', 1),
(3, '2025-09-29 14:18:12', 2, '23456', 'Coca Cola Original', 2, 'merma', '', 1),
(4, '2025-09-29 14:21:04', 2, '23456', 'Coca Cola Original', 2, 'perdida', '', 1),
(5, '2025-09-29 15:29:24', 2, '23456', 'Coca Cola Original', 2, 'consumo_interno', '', 2),
(6, '2025-09-29 15:32:32', 5, '56789', 'Monster Naranja Ripper', 2, 'consumo_interno', '', 2),
(7, '2025-10-03 15:59:04', 8, '89012', 'Monster Energy Absolutely Zero', 3, 'rotura', '', 1),
(8, '2025-10-03 16:18:07', 6, '67890', 'Monster Original', 1, 'consumo_interno', '', 1),
(9, '2025-10-03 17:14:45', 6, '67890', 'Monster Original', 2, 'merma', '', 1),
(10, '2025-10-03 17:15:07', 7, '78901', 'Monster Ultra', 1, 'consumo_interno', '', 1),
(11, '2025-10-03 17:15:49', 9, '87654', 'Whisky Jack Daniel\'s Tennessee ', 1, 'rotura', 'Se cayo de la estanteria', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `producto`
--

CREATE TABLE `producto` (
  `id` int(11) NOT NULL,
  `codigo` varchar(50) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `formato` varchar(50) DEFAULT NULL,
  `tamano` varchar(50) DEFAULT NULL,
  `marca` varchar(50) DEFAULT NULL,
  `cantidad` int(11) DEFAULT 0,
  `stock_minimo` int(11) NOT NULL DEFAULT 0,
  `precio_compra` decimal(10,2) NOT NULL,
  `precio_venta` decimal(10,2) NOT NULL,
  `fecha_recepcion` date DEFAULT NULL,
  `proveedor` varchar(100) DEFAULT NULL,
  `fecha_vencimiento` date DEFAULT NULL,
  `imagen` varchar(255) DEFAULT NULL,
  `estado` tinyint(1) DEFAULT 1,
  `fecha_ingreso` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `producto`
--

INSERT INTO `producto` (`id`, `codigo`, `nombre`, `formato`, `tamano`, `marca`, `cantidad`, `stock_minimo`, `precio_compra`, `precio_venta`, `fecha_recepcion`, `proveedor`, `fecha_vencimiento`, `imagen`, `estado`, `fecha_ingreso`) VALUES
(1, '12345', 'Coca Cola Original', 'Botella', '3L', 'Coca-cola', 20, 10, 1550.00, 3000.00, '2025-10-03', 'Los Hermanos Torres', '2025-12-13', 'vistas/imagenes/productos/prod_68d22e046f77f.jpg', 0, '2025-09-23 02:19:55'),
(2, '23456', 'Coca Cola Original', 'Lata', '350 ml', 'Coca - cola', 18, 10, 500.00, 1000.00, '2025-10-03', 'Distribuidora Ilusionista', '2026-01-02', 'vistas/imagenes/productos/prod_68d22eacc973b.png', 1, '2025-09-23 02:22:43'),
(3, '34567', 'Coca Cola Original', 'Botella', '1.5 L', 'Coca-cola', 26, 10, 800.00, 1800.00, NULL, 'Los Hermanos Torres', '2025-10-01', 'vistas/imagenes/productos/prod_68d2339fcd4c7.jpg', 1, '2025-09-23 02:26:06'),
(4, '45678', 'Coca Cola Original', 'Vidrio', '237 ml', 'Coca-cola', 15, 10, 100.00, 600.00, '2025-10-03', 'Los Hermanos Torres', '2025-12-19', 'vistas/imagenes/productos/prod_68d2377a10dea.jpg', 1, '2025-09-23 02:58:44'),
(5, '56789', 'Monster Naranja Ripper', 'Lata', '437 ml', 'Monster', 21, 20, 600.00, 2000.00, '2025-09-18', 'Los Angeles', '2025-12-27', 'vistas/imagenes/productos/prod_68d2384cc4412.jpg', 1, '2025-09-23 03:03:56'),
(6, '67890', 'Monster Original', 'Lata', '437 ml', 'Monster', 28, 15, 600.00, 2000.00, NULL, 'Los Angeles', '2025-11-22', 'vistas/imagenes/productos/prod_68d23d2f38973.jpg', 1, '2025-09-23 03:06:52'),
(7, '78901', 'Monster Ultra', 'Lata', '437 ml', 'Monster', 28, 10, 600.00, 2000.00, '2025-10-03', 'Los Angeles', '2025-12-12', 'vistas/imagenes/productos/prod_68d23cf9e2776.jpg', 1, '2025-09-23 03:23:53'),
(8, '89012', 'Monster Energy Absolutely Zero', 'Lata', '437 ml', 'Monster', 20, 15, 600.00, 2000.00, '2025-09-27', 'Los Angeles', '2025-12-27', 'vistas/imagenes/productos/prod_68d23e2e1cb6b.jpg', 1, '2025-09-23 03:29:02'),
(9, '87654', 'Whisky Jack Daniel\'s Tennessee ', 'Botella', '750 cc', 'Jack Daniel\'s', 20, 10, 15000.00, 26500.00, NULL, 'Los Angeles', '2025-09-30', 'vistas/imagenes/productos/prod_68d95d88f2cc8.jpg', 1, '2025-09-28 13:08:40'),
(11, '98536', 'Cerveza Heineken', 'Lata', ' 470 CC', 'Heineken', 30, 10, 700.00, 1500.00, NULL, 'Los Angeles', '2025-12-31', 'vistas/imagenes/productos/prod_68e02fa3aa786.jpg', 0, '2025-09-28 13:18:30'),
(12, '12311', 'Pisco 35', 'Botella Vidrio', '1 L', 'Mistral', 20, 0, 3000.00, 7500.00, '2025-10-03', 'Los Angeles', '2025-11-20', 'vistas/imagenes/productos/prod_68dacd01f2b9e.jpg', 1, '2025-09-29 14:43:39'),
(13, '00001', 'Red Bull Original', 'Lata', '473 ml', 'Red Bull', 28, 15, 1000.00, 2900.00, NULL, 'Los Angeles', NULL, 'vistas/imagenes/productos/prod_68e01ff9129e9.jpg', 1, '2025-10-03 15:54:41'),
(14, '00002', 'Red Bull Morada', 'Lata', '250 ml', 'Red Bull', 20, 0, 1000.00, 1800.00, NULL, 'Los Angeles', '2025-12-31', 'vistas/imagenes/productos/prod_68e02fff1aab5.jpg', 1, '2025-10-03 17:20:15');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `promocion`
--

CREATE TABLE `promocion` (
  `id` int(11) NOT NULL,
  `tipo` enum('descuento','2x1','precio_fijo') NOT NULL,
  `parametro` decimal(10,2) DEFAULT NULL,
  `fecha_inicio` datetime NOT NULL,
  `fecha_fin` datetime NOT NULL,
  `observacion` text DEFAULT NULL,
  `creado_en` datetime NOT NULL DEFAULT current_timestamp(),
  `estado` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `promocion`
--

INSERT INTO `promocion` (`id`, `tipo`, `parametro`, `fecha_inicio`, `fecha_fin`, `observacion`, `creado_en`, `estado`) VALUES
(8, '2x1', NULL, '2025-09-23 15:51:00', '2025-09-24 15:51:00', NULL, '2025-09-23 15:51:33', 1),
(9, '2x1', NULL, '2025-09-27 14:59:00', '2025-09-28 14:59:00', NULL, '2025-09-27 14:59:21', 1),
(10, '2x1', NULL, '2025-09-29 22:53:00', '2025-09-30 22:53:00', NULL, '2025-09-29 22:53:58', 1),
(11, 'descuento', 50.00, '2025-09-29 23:17:00', '2025-09-30 23:17:00', NULL, '2025-09-29 23:17:12', 1),
(12, 'precio_fijo', 6590.00, '2025-09-29 23:52:00', '2025-09-30 23:52:00', NULL, '2025-09-29 23:53:48', 1),
(13, '2x1', NULL, '2025-09-30 01:40:00', '2025-09-30 01:42:00', NULL, '2025-09-30 01:40:44', 1),
(20, '2x1', NULL, '2025-10-03 16:39:00', '2025-10-04 16:39:00', NULL, '2025-10-03 16:39:24', 1),
(21, 'descuento', 50.00, '2025-10-03 16:40:00', '2025-10-04 16:40:00', NULL, '2025-10-03 16:40:25', 1),
(22, 'precio_fijo', 500.00, '2025-10-03 16:41:00', '2025-10-04 16:41:00', NULL, '2025-10-03 16:41:58', 1),
(23, '2x1', NULL, '2025-10-03 16:43:00', '2025-10-04 16:42:00', NULL, '2025-10-03 16:42:38', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `promocion_producto`
--

CREATE TABLE `promocion_producto` (
  `id` int(11) NOT NULL,
  `id_promocion` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `promocion_producto`
--

INSERT INTO `promocion_producto` (`id`, `id_promocion`, `id_producto`) VALUES
(8, 8, 6),
(9, 9, 6),
(10, 10, 3),
(11, 11, 5),
(12, 12, 12),
(13, 13, 6),
(20, 20, 5),
(21, 21, 13),
(22, 22, 6),
(23, 23, 12);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `usuario` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `rol` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`id`, `nombre`, `usuario`, `password`, `rol`) VALUES
(1, 'Almendra Manriquez Torres', 'admin', '$2a$07$usesomesillystringforewOdLB5CheF5NZbm8TQfHJwIPWk0j23q', 'Administrador'),
(2, 'Matias Carrasco', 'mati', '$2a$07$usesomesillystringforeWbVzYlorgmbfLnSWRwHce7G62sVyNeG', 'Vendedor'),
(6, 'Joaquin Soto', 'joaco', '$2a$07$usesomesillystringforec7DcqHJRJWB3Wv5BkpSw7wuX961psXy', 'Vendedor');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `venta`
--

CREATE TABLE `venta` (
  `id` int(11) NOT NULL,
  `id_cierre_caja` int(10) UNSIGNED DEFAULT NULL,
  `id_usuario` int(11) NOT NULL,
  `productos` text NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `metodo_pago` varchar(100) NOT NULL,
  `observacion` text DEFAULT NULL,
  `fecha` datetime NOT NULL,
  `promociones_aplicadas` longtext DEFAULT NULL,
  `creado_en` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `venta`
--

INSERT INTO `venta` (`id`, `id_cierre_caja`, `id_usuario`, `productos`, `total`, `metodo_pago`, `observacion`, `fecha`, `promociones_aplicadas`, `creado_en`) VALUES
(1, NULL, 1, '[{\"id\":\"1\",\"nombre\":\"Coca Cola Original\",\"cantidad\":\"1\",\"precioUnitario\":\"3000\",\"precioTotal\":\"3.000\"},{\"id\":\"2\",\"nombre\":\"Coca Cola Original\",\"cantidad\":\"1\",\"precioUnitario\":\"1000\",\"precioTotal\":\"1.000\"}]', 4000.00, 'Tarjeta', '', '2025-09-23 03:36:33', NULL, '2025-09-23 06:36:33'),
(2, NULL, 1, '[{\"id\":\"1\",\"nombre\":\"Coca Cola Original\",\"cantidad\":\"1\",\"precioUnitario\":\"3000\",\"precioTotal\":\"3.000\"}]', 3000.00, 'Efectivo', '', '2025-09-23 13:44:30', NULL, '2025-09-23 16:44:30'),
(3, NULL, 1, '[{\"id\":\"1\",\"nombre\":\"Coca Cola Original\",\"cantidad\":\"1\",\"precioUnitario\":\"3000\",\"precioTotal\":\"3.000\"},{\"id\":\"2\",\"nombre\":\"Coca Cola Original\",\"cantidad\":\"1\",\"precioUnitario\":\"1000\",\"precioTotal\":\"1.000\"}]', 4000.00, 'Efectivo', '', '2025-09-23 13:46:28', NULL, '2025-09-23 16:46:28'),
(4, NULL, 1, '[{\"id\":\"1\",\"nombre\":\"Coca Cola Original\",\"cantidad\":\"1\",\"precioUnitario\":\"3000\",\"precioTotal\":\"3.000\"},{\"id\":\"2\",\"nombre\":\"Coca Cola Original\",\"cantidad\":\"1\",\"precioUnitario\":\"1000\",\"precioTotal\":\"1.000\"}]', 4000.00, 'Efectivo', '', '2025-09-23 13:49:33', NULL, '2025-09-23 16:49:33'),
(5, NULL, 1, '[{\"id\":6,\"nombre\":\"Monster Original\",\"cantidad\":2,\"precio_base\":2000,\"precio_final\":2000,\"total_linea\":2000,\"promocion\":{\"id_promocion\":9,\"tipo\":\"2x1\",\"parametro\":0,\"etiqueta\":\"2x1\",\"detalle\":\"Promo 2x1: 1 unidad(es) gratis\",\"observacion\":\"\",\"precio_unit_base\":2000,\"precio_unit_final\":2000,\"paga_unidades\":1,\"gratis\":1}}]', 2000.00, 'Tarjeta', '', '2025-09-27 14:59:44', '[{\"id_producto\":6,\"nombre\":\"Monster Original\",\"id_promocion\":9,\"tipo\":\"2x1\",\"parametro\":0,\"etiqueta\":\"2x1\",\"detalle\":\"Promo 2x1: 1 unidad(es) gratis\",\"observacion\":\"\",\"precio_base\":2000,\"precio_final\":2000,\"cantidad\":2,\"paga_unidades\":1,\"gratis\":1,\"total_linea\":2000}]', '2025-09-27 17:59:44'),
(6, NULL, 1, '[{\"id\":1,\"nombre\":\"Coca Cola Original\",\"cantidad\":1,\"precio_base\":3000,\"precio_final\":3000,\"total_linea\":3000,\"promocion\":null},{\"id\":2,\"nombre\":\"Coca Cola Original\",\"cantidad\":1,\"precio_base\":1000,\"precio_final\":1000,\"total_linea\":1000,\"promocion\":null}]', 4000.00, 'Tarjeta', '', '2025-09-27 15:11:27', '[]', '2025-09-27 18:11:27'),
(7, NULL, 1, '[{\"id\":1,\"nombre\":\"Coca Cola Original\",\"cantidad\":5,\"precio_base\":3000,\"precio_final\":3000,\"total_linea\":15000,\"promocion\":null}]', 15000.00, 'Tarjeta', '', '2025-09-27 15:34:03', '[]', '2025-09-27 18:34:03'),
(8, NULL, 1, '[{\"id\":1,\"nombre\":\"Coca Cola Original\",\"cantidad\":1,\"precio_base\":3000,\"precio_final\":3000,\"total_linea\":3000,\"promocion\":null}]', 3000.00, 'Tarjeta', '', '2025-09-27 23:53:04', '[]', '2025-09-28 02:53:04'),
(9, 2, 1, '[{\"id\":1,\"nombre\":\"Coca Cola Original\",\"cantidad\":1,\"precio_base\":3000,\"precio_final\":3000,\"total_linea\":3000,\"promocion\":null},{\"id\":2,\"nombre\":\"Coca Cola Original\",\"cantidad\":1,\"precio_base\":1000,\"precio_final\":1000,\"total_linea\":1000,\"promocion\":null}]', 4000.00, 'Tarjeta', 'Esto es una prueba', '2025-09-29 12:04:15', '[]', '2025-09-29 15:04:15'),
(10, 2, 1, '[{\"id\":11,\"nombre\":\"Cerveza Heineken\",\"cantidad\":1,\"precio_base\":990,\"precio_final\":990,\"total_linea\":990,\"promocion\":null}]', 990.00, 'Tarjeta', '', '2025-09-29 12:04:58', '[]', '2025-09-29 15:04:58'),
(11, 2, 1, '[{\"id\":11,\"nombre\":\"Cerveza Heineken\",\"cantidad\":1,\"precio_base\":990,\"precio_final\":990,\"total_linea\":990,\"promocion\":null}]', 990.00, 'Tarjeta', '', '2025-09-29 12:07:01', '[]', '2025-09-29 15:07:01'),
(12, 3, 1, '[{\"id\":5,\"nombre\":\"Monster Naranja Ripper\",\"cantidad\":3,\"precio_base\":2000,\"precio_final\":1000,\"total_linea\":3000,\"promocion\":{\"id_promocion\":11,\"tipo\":\"descuento\",\"parametro\":50,\"etiqueta\":\"-50%\",\"detalle\":\"Descuento de 50% aplicado\",\"observacion\":\"\",\"precio_unit_base\":2000,\"precio_unit_final\":1000,\"paga_unidades\":3,\"gratis\":0}}]', 3000.00, 'Tarjeta', '', '2025-09-30 00:02:22', '[{\"id_producto\":5,\"nombre\":\"Monster Naranja Ripper\",\"id_promocion\":11,\"tipo\":\"descuento\",\"parametro\":50,\"etiqueta\":\"-50%\",\"detalle\":\"Descuento de 50% aplicado\",\"observacion\":\"\",\"precio_base\":2000,\"precio_final\":1000,\"cantidad\":3,\"paga_unidades\":3,\"gratis\":0,\"total_linea\":3000}]', '2025-09-30 03:02:22'),
(13, 3, 1, '[{\"id\":5,\"nombre\":\"Monster Naranja Ripper\",\"cantidad\":2,\"precio_base\":2000,\"precio_final\":1000,\"total_linea\":2000,\"promocion\":{\"id_promocion\":11,\"tipo\":\"descuento\",\"parametro\":50,\"etiqueta\":\"-50%\",\"detalle\":\"Descuento de 50% aplicado\",\"observacion\":\"\",\"precio_unit_base\":2000,\"precio_unit_final\":1000,\"paga_unidades\":2,\"gratis\":0}}]', 2000.00, 'Tarjeta', '', '2025-09-30 00:19:56', '[{\"id_producto\":5,\"nombre\":\"Monster Naranja Ripper\",\"id_promocion\":11,\"tipo\":\"descuento\",\"parametro\":50,\"etiqueta\":\"-50%\",\"detalle\":\"Descuento de 50% aplicado\",\"observacion\":\"\",\"precio_base\":2000,\"precio_final\":1000,\"cantidad\":2,\"paga_unidades\":2,\"gratis\":0,\"total_linea\":2000}]', '2025-09-30 03:19:56'),
(14, 3, 1, '[{\"id\":3,\"nombre\":\"Coca Cola Original\",\"cantidad\":2,\"precio_base\":1800,\"precio_final\":1800,\"total_linea\":1800,\"promocion\":{\"id_promocion\":10,\"tipo\":\"2x1\",\"parametro\":0,\"etiqueta\":\"2x1\",\"detalle\":\"Promo 2x1: 1 unidad(es) gratis\",\"observacion\":\"\",\"precio_unit_base\":1800,\"precio_unit_final\":1800,\"paga_unidades\":1,\"gratis\":1}}]', 1800.00, 'Tarjeta', '', '2025-09-30 00:31:44', '[{\"id_producto\":3,\"nombre\":\"Coca Cola Original\",\"id_promocion\":10,\"tipo\":\"2x1\",\"parametro\":0,\"etiqueta\":\"2x1\",\"detalle\":\"Promo 2x1: 1 unidad(es) gratis\",\"observacion\":\"\",\"precio_base\":1800,\"precio_final\":1800,\"cantidad\":2,\"paga_unidades\":1,\"gratis\":1,\"total_linea\":1800}]', '2025-09-30 03:31:44'),
(15, 5, 1, '[{\"id\":2,\"nombre\":\"Coca Cola Original\",\"cantidad\":1,\"precio_base\":1000,\"precio_final\":1000,\"total_linea\":1000,\"promocion\":null},{\"id\":3,\"nombre\":\"Coca Cola Original\",\"cantidad\":1,\"precio_base\":1800,\"precio_final\":1800,\"total_linea\":1800,\"promocion\":null}]', 2800.00, 'Efectivo', '', '2025-10-03 15:27:23', '[]', '2025-10-03 18:27:23'),
(16, 5, 1, '[{\"id\":2,\"nombre\":\"Coca Cola Original\",\"cantidad\":3,\"precio_base\":1000,\"precio_final\":1000,\"total_linea\":3000,\"promocion\":null}]', 3000.00, 'Tarjeta', '', '2025-10-03 15:36:02', '[]', '2025-10-03 18:36:02'),
(17, 5, 1, '[{\"id\":7,\"nombre\":\"Monster Ultra\",\"cantidad\":1,\"precio_base\":2000,\"precio_final\":2000,\"total_linea\":2000,\"promocion\":null}]', 2000.00, 'Tarjeta', 'Esto es una prueba.', '2025-10-03 15:37:06', '[]', '2025-10-03 18:37:06'),
(18, 5, 1, '[{\"id\":6,\"nombre\":\"Monster Original\",\"cantidad\":3,\"precio_base\":2000,\"precio_final\":2000,\"total_linea\":6000,\"promocion\":null}]', 6000.00, 'Tarjeta', '', '2025-10-03 15:37:46', '[]', '2025-10-03 18:37:46'),
(19, 5, 1, '[{\"id\":2,\"nombre\":\"Coca Cola Original\",\"cantidad\":4,\"precio_base\":1000,\"precio_final\":1000,\"total_linea\":4000,\"promocion\":null}]', 4000.00, 'Tarjeta', '', '2025-10-03 15:45:29', '[]', '2025-10-03 18:45:29'),
(20, 5, 1, '[{\"id\":7,\"nombre\":\"Monster Ultra\",\"cantidad\":5,\"precio_base\":2000,\"precio_final\":2000,\"total_linea\":10000,\"promocion\":null}]', 10000.00, 'Tarjeta', '', '2025-10-03 15:48:47', '[]', '2025-10-03 18:48:47'),
(21, 5, 1, '[{\"id\":6,\"nombre\":\"Monster Original\",\"cantidad\":3,\"precio_base\":2000,\"precio_final\":500,\"total_linea\":1500,\"promocion\":{\"id_promocion\":22,\"tipo\":\"precio_fijo\",\"parametro\":500,\"etiqueta\":\"Precio fijo\",\"detalle\":\"Precio fijo promocional $500\",\"observacion\":\"\",\"precio_unit_base\":2000,\"precio_unit_final\":500,\"paga_unidades\":3,\"gratis\":0}},{\"id\":5,\"nombre\":\"Monster Naranja Ripper\",\"cantidad\":2,\"precio_base\":2000,\"precio_final\":2000,\"total_linea\":2000,\"promocion\":{\"id_promocion\":20,\"tipo\":\"2x1\",\"parametro\":0,\"etiqueta\":\"2x1\",\"detalle\":\"Promo 2x1: 1 unidad(es) gratis\",\"observacion\":\"\",\"precio_unit_base\":2000,\"precio_unit_final\":2000,\"paga_unidades\":1,\"gratis\":1}}]', 3500.00, 'Tarjeta', '', '2025-10-03 16:43:46', '[{\"id_producto\":6,\"nombre\":\"Monster Original\",\"id_promocion\":22,\"tipo\":\"precio_fijo\",\"parametro\":500,\"etiqueta\":\"Precio fijo\",\"detalle\":\"Precio fijo promocional $500\",\"observacion\":\"\",\"precio_base\":2000,\"precio_final\":500,\"cantidad\":3,\"paga_unidades\":3,\"gratis\":0,\"total_linea\":1500},{\"id_producto\":5,\"nombre\":\"Monster Naranja Ripper\",\"id_promocion\":20,\"tipo\":\"2x1\",\"parametro\":0,\"etiqueta\":\"2x1\",\"detalle\":\"Promo 2x1: 1 unidad(es) gratis\",\"observacion\":\"\",\"precio_base\":2000,\"precio_final\":2000,\"cantidad\":2,\"paga_unidades\":1,\"gratis\":1,\"total_linea\":2000}]', '2025-10-03 19:43:46'),
(22, 5, 1, '[{\"id\":2,\"nombre\":\"Coca Cola Original\",\"cantidad\":1,\"precio_base\":1000,\"precio_final\":1000,\"total_linea\":1000,\"promocion\":null},{\"id\":6,\"nombre\":\"Monster Original\",\"cantidad\":1,\"precio_base\":2000,\"precio_final\":500,\"total_linea\":500,\"promocion\":{\"id_promocion\":22,\"tipo\":\"precio_fijo\",\"parametro\":500,\"etiqueta\":\"Precio fijo\",\"detalle\":\"Precio fijo promocional $500\",\"observacion\":\"\",\"precio_unit_base\":2000,\"precio_unit_final\":500,\"paga_unidades\":1,\"gratis\":0}}]', 1500.00, 'Efectivo', '', '2025-10-03 17:07:18', '[{\"id_producto\":6,\"nombre\":\"Monster Original\",\"id_promocion\":22,\"tipo\":\"precio_fijo\",\"parametro\":500,\"etiqueta\":\"Precio fijo\",\"detalle\":\"Precio fijo promocional $500\",\"observacion\":\"\",\"precio_base\":2000,\"precio_final\":500,\"cantidad\":1,\"paga_unidades\":1,\"gratis\":0,\"total_linea\":500}]', '2025-10-03 20:07:18'),
(23, 5, 1, '[{\"id\":13,\"nombre\":\"Red Bull Original\",\"cantidad\":2,\"precio_base\":2900,\"precio_final\":2900,\"total_linea\":2900,\"promocion\":{\"id_promocion\":24,\"tipo\":\"2x1\",\"parametro\":0,\"etiqueta\":\"2x1\",\"detalle\":\"Promo 2x1: 1 unidad(es) gratis\",\"observacion\":\"\",\"precio_unit_base\":2900,\"precio_unit_final\":2900,\"paga_unidades\":1,\"gratis\":1}}]', 2900.00, 'Tarjeta', 'Se agrego el 2x1', '2025-10-03 17:08:36', '[{\"id_producto\":13,\"nombre\":\"Red Bull Original\",\"id_promocion\":24,\"tipo\":\"2x1\",\"parametro\":0,\"etiqueta\":\"2x1\",\"detalle\":\"Promo 2x1: 1 unidad(es) gratis\",\"observacion\":\"\",\"precio_base\":2900,\"precio_final\":2900,\"cantidad\":2,\"paga_unidades\":1,\"gratis\":1,\"total_linea\":2900}]', '2025-10-03 20:08:36'),
(24, NULL, 6, '[{\"id\":2,\"nombre\":\"Coca Cola Original\",\"cantidad\":1,\"precio_base\":1000,\"precio_final\":1000,\"total_linea\":1000,\"promocion\":null}]', 1000.00, 'Tarjeta', '', '2025-10-03 17:24:01', '[]', '2025-10-03 20:24:01');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `cierre_caja`
--
ALTER TABLE `cierre_caja`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_cierre_usuario` (`id_usuario`);

--
-- Indices de la tabla `perdida`
--
ALTER TABLE `perdida`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_perdida_fecha` (`fecha`),
  ADD KEY `idx_perdida_producto` (`producto_id`),
  ADD KEY `idx_perdida_usuario` (`usuario_id`);

--
-- Indices de la tabla `producto`
--
ALTER TABLE `producto`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `codigo` (`codigo`);

--
-- Indices de la tabla `promocion`
--
ALTER TABLE `promocion`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `promocion_producto`
--
ALTER TABLE `promocion_producto`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_prom_prod` (`id_promocion`,`id_producto`),
  ADD KEY `fk_pp_prod` (`id_producto`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `venta`
--
ALTER TABLE `venta`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `cierre_caja`
--
ALTER TABLE `cierre_caja`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `perdida`
--
ALTER TABLE `perdida`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `producto`
--
ALTER TABLE `producto`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de la tabla `promocion`
--
ALTER TABLE `promocion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT de la tabla `promocion_producto`
--
ALTER TABLE `promocion_producto`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `venta`
--
ALTER TABLE `venta`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `cierre_caja`
--
ALTER TABLE `cierre_caja`
  ADD CONSTRAINT `fk_cierre_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id`);

--
-- Filtros para la tabla `perdida`
--
ALTER TABLE `perdida`
  ADD CONSTRAINT `fk_perdida_producto` FOREIGN KEY (`producto_id`) REFERENCES `producto` (`id`),
  ADD CONSTRAINT `fk_perdida_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuario` (`id`);

--
-- Filtros para la tabla `promocion_producto`
--
ALTER TABLE `promocion_producto`
  ADD CONSTRAINT `fk_pp_prod` FOREIGN KEY (`id_producto`) REFERENCES `producto` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_pp_prom` FOREIGN KEY (`id_promocion`) REFERENCES `promocion` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `venta`
--
ALTER TABLE `venta`
  ADD CONSTRAINT `venta_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
