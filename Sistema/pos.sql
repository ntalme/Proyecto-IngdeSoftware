-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 24-10-2025 a las 18:43:50
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
  `total_general` decimal(12,2) NOT NULL,
  `cantidad_ventas` int(11) NOT NULL,
  `ventas_json` longtext DEFAULT NULL,
  `boletas_digitales` decimal(10,2) DEFAULT 0.00,
  `retencion_boletas` decimal(10,2) DEFAULT 0.00,
  `retencion_tarjeta` decimal(10,2) DEFAULT 0.00,
  `descuento_contadora` decimal(10,2) DEFAULT 0.00,
  `total_final` decimal(12,2) NOT NULL,
  `ganancia_dia` decimal(10,2) NOT NULL,
  `reinversion` decimal(10,2) NOT NULL,
  `observaciones` text DEFAULT NULL,
  `es_domingo` tinyint(1) DEFAULT 0,
  `ventas_tarjeta_cantidad` int(11) DEFAULT 0,
  `ventas_tarjeta_monto` decimal(12,2) DEFAULT 0.00,
  `ventas_efectivo_cantidad` int(11) DEFAULT 0,
  `ventas_efectivo_monto` decimal(12,2) DEFAULT 0.00,
  `descuento_luz` decimal(10,2) DEFAULT 0.00,
  `reinversion_semana` decimal(10,2) DEFAULT 0.00,
  `reinversion_semana_final` decimal(10,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `cierre_caja`
--

INSERT INTO `cierre_caja` (`id`, `fecha_cierre`, `id_usuario`, `total_general`, `cantidad_ventas`, `ventas_json`, `boletas_digitales`, `retencion_boletas`, `retencion_tarjeta`, `descuento_contadora`, `total_final`, `ganancia_dia`, `reinversion`, `observaciones`, `es_domingo`, `ventas_tarjeta_cantidad`, `ventas_tarjeta_monto`, `ventas_efectivo_cantidad`, `ventas_efectivo_monto`, `descuento_luz`, `reinversion_semana`, `reinversion_semana_final`) VALUES
(1, '2025-10-13 14:11:44', 1, 98000.00, 7, '[{\"id\":1,\"fecha\":\"2025-10-13 15:05:49\",\"total\":\"7600.00\",\"metodo_pago\":\"Tarjeta\",\"productos\":\"[{\\\"id\\\":11,\\\"nombre\\\":\\\"Cerveza Heineken\\\",\\\"cantidad\\\":1,\\\"precio_base\\\":1000,\\\"precio_final\\\":1000,\\\"total_linea\\\":1000,\\\"promocion\\\":null},{\\\"id\\\":2,\\\"nombre\\\":\\\"Coca Cola Original\\\",\\\"cantidad\\\":3,\\\"precio_base\\\":1000,\\\"precio_final\\\":1000,\\\"total_linea\\\":3000,\\\"promocion\\\":null},{\\\"id\\\":3,\\\"nombre\\\":\\\"Coca Cola Original\\\",\\\"cantidad\\\":2,\\\"precio_base\\\":1800,\\\"precio_final\\\":1800,\\\"total_linea\\\":3600,\\\"promocion\\\":null}]\"},{\"id\":2,\"fecha\":\"2025-10-13 15:05:58\",\"total\":\"31200.00\",\"metodo_pago\":\"Tarjeta\",\"productos\":\"[{\\\"id\\\":9,\\\"nombre\\\":\\\"Whisky Jack Daniel\'s Tennessee \\\",\\\"cantidad\\\":1,\\\"precio_base\\\":26500,\\\"precio_final\\\":26500,\\\"total_linea\\\":26500,\\\"promocion\\\":null},{\\\"id\\\":13,\\\"nombre\\\":\\\"Red Bull Original\\\",\\\"cantidad\\\":1,\\\"precio_base\\\":2900,\\\"precio_final\\\":2900,\\\"total_linea\\\":2900,\\\"promocion\\\":null},{\\\"id\\\":3,\\\"nombre\\\":\\\"Coca Cola Original\\\",\\\"cantidad\\\":1,\\\"precio_base\\\":1800,\\\"precio_final\\\":1800,\\\"total_linea\\\":1800,\\\"promocion\\\":null}]\"},{\"id\":3,\"fecha\":\"2025-10-13 15:06:18\",\"total\":\"8400.00\",\"metodo_pago\":\"Efectivo\",\"productos\":\"[{\\\"id\\\":11,\\\"nombre\\\":\\\"Cerveza Heineken\\\",\\\"cantidad\\\":1,\\\"precio_base\\\":1000,\\\"precio_final\\\":1000,\\\"total_linea\\\":1000,\\\"promocion\\\":null},{\\\"id\\\":2,\\\"nombre\\\":\\\"Coca Cola Original\\\",\\\"cantidad\\\":2,\\\"precio_base\\\":1000,\\\"precio_final\\\":1000,\\\"total_linea\\\":2000,\\\"promocion\\\":null},{\\\"id\\\":3,\\\"nombre\\\":\\\"Coca Cola Original\\\",\\\"cantidad\\\":3,\\\"precio_base\\\":1800,\\\"precio_final\\\":1800,\\\"total_linea\\\":5400,\\\"promocion\\\":null}]\"},{\"id\":4,\"fecha\":\"2025-10-13 15:06:40\",\"total\":\"8000.00\",\"metodo_pago\":\"Tarjeta\",\"productos\":\"[{\\\"id\\\":8,\\\"nombre\\\":\\\"Monster Energy Absolutely Zero\\\",\\\"cantidad\\\":1,\\\"precio_base\\\":2000,\\\"precio_final\\\":2000,\\\"total_linea\\\":2000,\\\"promocion\\\":null},{\\\"id\\\":5,\\\"nombre\\\":\\\"Monster Naranja Ripper\\\",\\\"cantidad\\\":2,\\\"precio_base\\\":2000,\\\"precio_final\\\":2000,\\\"total_linea\\\":4000,\\\"promocion\\\":null},{\\\"id\\\":6,\\\"nombre\\\":\\\"Monster Original\\\",\\\"cantidad\\\":1,\\\"precio_base\\\":2000,\\\"precio_final\\\":2000,\\\"total_linea\\\":2000,\\\"promocion\\\":null}]\"},{\"id\":5,\"fecha\":\"2025-10-13 15:06:52\",\"total\":\"11000.00\",\"metodo_pago\":\"Tarjeta\",\"productos\":\"[{\\\"id\\\":6,\\\"nombre\\\":\\\"Monster Original\\\",\\\"cantidad\\\":1,\\\"precio_base\\\":2000,\\\"precio_final\\\":2000,\\\"total_linea\\\":2000,\\\"promocion\\\":null},{\\\"id\\\":11,\\\"nombre\\\":\\\"Cerveza Heineken\\\",\\\"cantidad\\\":9,\\\"precio_base\\\":1000,\\\"precio_final\\\":1000,\\\"total_linea\\\":9000,\\\"promocion\\\":null}]\"},{\"id\":6,\"fecha\":\"2025-10-13 15:06:59\",\"total\":\"2400.00\",\"metodo_pago\":\"Efectivo\",\"productos\":\"[{\\\"id\\\":4,\\\"nombre\\\":\\\"Coca Cola Original\\\",\\\"cantidad\\\":1,\\\"precio_base\\\":600,\\\"precio_final\\\":600,\\\"total_linea\\\":600,\\\"promocion\\\":null},{\\\"id\\\":3,\\\"nombre\\\":\\\"Coca Cola Original\\\",\\\"cantidad\\\":1,\\\"precio_base\\\":1800,\\\"precio_final\\\":1800,\\\"total_linea\\\":1800,\\\"promocion\\\":null}]\"},{\"id\":7,\"fecha\":\"2025-10-13 15:07:11\",\"total\":\"29400.00\",\"metodo_pago\":\"Tarjeta\",\"productos\":\"[{\\\"id\\\":9,\\\"nombre\\\":\\\"Whisky Jack Daniel\'s Tennessee \\\",\\\"cantidad\\\":1,\\\"precio_base\\\":26500,\\\"precio_final\\\":26500,\\\"total_linea\\\":26500,\\\"promocion\\\":null},{\\\"id\\\":13,\\\"nombre\\\":\\\"Red Bull Original\\\",\\\"cantidad\\\":1,\\\"precio_base\\\":2900,\\\"precio_final\\\":2900,\\\"total_linea\\\":2900,\\\"promocion\\\":null}]\"}]', 15000.00, 3000.00, 17440.00, 5000.00, 72560.00, 14512.00, 58048.00, 'Sin observaciones.', 0, 5, 87200.00, 2, 10800.00, 0.00, 0.00, 0.00),
(2, '2025-10-14 14:16:43', 1, 161500.00, 5, '[{\"id\":8,\"fecha\":\"2025-10-14 15:12:49\",\"total\":\"6000.00\",\"metodo_pago\":\"Tarjeta\",\"productos\":\"[{\\\"id\\\":7,\\\"nombre\\\":\\\"Monster Ultra\\\",\\\"cantidad\\\":3,\\\"precio_base\\\":2000,\\\"precio_final\\\":2000,\\\"total_linea\\\":6000,\\\"promocion\\\":null}]\"},{\"id\":9,\"fecha\":\"2025-10-14 15:13:03\",\"total\":\"54800.00\",\"metodo_pago\":\"Tarjeta\",\"productos\":\"[{\\\"id\\\":9,\\\"nombre\\\":\\\"Whisky Jack Daniel\'s Tennessee \\\",\\\"cantidad\\\":2,\\\"precio_base\\\":26500,\\\"precio_final\\\":26500,\\\"total_linea\\\":53000,\\\"promocion\\\":null},{\\\"id\\\":14,\\\"nombre\\\":\\\"Red Bull Morada\\\",\\\"cantidad\\\":1,\\\"precio_base\\\":1800,\\\"precio_final\\\":1800,\\\"total_linea\\\":1800,\\\"promocion\\\":null}]\"},{\"id\":10,\"fecha\":\"2025-10-14 15:13:16\",\"total\":\"10000.00\",\"metodo_pago\":\"Efectivo\",\"productos\":\"[{\\\"id\\\":6,\\\"nombre\\\":\\\"Monster Original\\\",\\\"cantidad\\\":5,\\\"precio_base\\\":2000,\\\"precio_final\\\":2000,\\\"total_linea\\\":10000,\\\"promocion\\\":null}]\"},{\"id\":11,\"fecha\":\"2025-10-14 15:15:44\",\"total\":\"39400.00\",\"metodo_pago\":\"Efectivo\",\"productos\":\"[{\\\"id\\\":7,\\\"nombre\\\":\\\"Monster Ultra\\\",\\\"cantidad\\\":4,\\\"precio_base\\\":2000,\\\"precio_final\\\":2000,\\\"total_linea\\\":8000,\\\"promocion\\\":null},{\\\"id\\\":9,\\\"nombre\\\":\\\"Whisky Jack Daniel\'s Tennessee \\\",\\\"cantidad\\\":1,\\\"precio_base\\\":26500,\\\"precio_final\\\":26500,\\\"total_linea\\\":26500,\\\"promocion\\\":null},{\\\"id\\\":13,\\\"nombre\\\":\\\"Red Bull Original\\\",\\\"cantidad\\\":1,\\\"precio_base\\\":2900,\\\"precio_final\\\":2900,\\\"total_linea\\\":2900,\\\"promocion\\\":null},{\\\"id\\\":6,\\\"nombre\\\":\\\"Monster Original\\\",\\\"cantidad\\\":1,\\\"precio_base\\\":2000,\\\"precio_final\\\":2000,\\\"total_linea\\\":2000,\\\"promocion\\\":null}]\"},{\"id\":12,\"fecha\":\"2025-10-14 15:16:12\",\"total\":\"51300.00\",\"metodo_pago\":\"Tarjeta\",\"productos\":\"[{\\\"id\\\":8,\\\"nombre\\\":\\\"Monster Energy Absolutely Zero\\\",\\\"cantidad\\\":3,\\\"precio_base\\\":2000,\\\"precio_final\\\":2000,\\\"total_linea\\\":6000,\\\"promocion\\\":null},{\\\"id\\\":13,\\\"nombre\\\":\\\"Red Bull Original\\\",\\\"cantidad\\\":4,\\\"precio_base\\\":2900,\\\"precio_final\\\":2900,\\\"total_linea\\\":11600,\\\"promocion\\\":null},{\\\"id\\\":9,\\\"nombre\\\":\\\"Whisky Jack Daniel\'s Tennessee \\\",\\\"cantidad\\\":1,\\\"precio_base\\\":26500,\\\"precio_final\\\":26500,\\\"total_linea\\\":26500,\\\"promocion\\\":null},{\\\"id\\\":14,\\\"nombre\\\":\\\"Red Bull Morada\\\",\\\"cantidad\\\":4,\\\"precio_base\\\":1800,\\\"precio_final\\\":1800,\\\"total_linea\\\":7200,\\\"promocion\\\":null}]\"}]', 0.00, 0.00, 22420.00, 5000.00, 134080.00, 26816.00, 107264.00, 'Sin observaciones.', 0, 3, 112100.00, 2, 49400.00, 0.00, 0.00, 0.00),
(3, '2025-10-15 14:21:37', 1, 208600.00, 5, '[{\"id\":13,\"fecha\":\"2025-10-15 15:20:11\",\"total\":\"11200.00\",\"metodo_pago\":\"Efectivo\",\"productos\":\"[{\\\"id\\\":11,\\\"nombre\\\":\\\"Cerveza Heineken\\\",\\\"cantidad\\\":1,\\\"precio_base\\\":1000,\\\"precio_final\\\":1000,\\\"total_linea\\\":1000,\\\"promocion\\\":null},{\\\"id\\\":2,\\\"nombre\\\":\\\"Coca Cola Original\\\",\\\"cantidad\\\":2,\\\"precio_base\\\":1000,\\\"precio_final\\\":1000,\\\"total_linea\\\":2000,\\\"promocion\\\":null},{\\\"id\\\":3,\\\"nombre\\\":\\\"Coca Cola Original\\\",\\\"cantidad\\\":2,\\\"precio_base\\\":1800,\\\"precio_final\\\":1800,\\\"total_linea\\\":3600,\\\"promocion\\\":null},{\\\"id\\\":4,\\\"nombre\\\":\\\"Coca Cola Original\\\",\\\"cantidad\\\":1,\\\"precio_base\\\":600,\\\"precio_final\\\":600,\\\"total_linea\\\":600,\\\"promocion\\\":null},{\\\"id\\\":8,\\\"nombre\\\":\\\"Monster Energy Absolutely Zero\\\",\\\"cantidad\\\":2,\\\"precio_base\\\":2000,\\\"precio_final\\\":2000,\\\"total_linea\\\":4000,\\\"promocion\\\":null}]\"},{\"id\":14,\"fecha\":\"2025-10-15 15:20:21\",\"total\":\"8000.00\",\"metodo_pago\":\"Tarjeta\",\"productos\":\"[{\\\"id\\\":6,\\\"nombre\\\":\\\"Monster Original\\\",\\\"cantidad\\\":2,\\\"precio_base\\\":2000,\\\"precio_final\\\":2000,\\\"total_linea\\\":4000,\\\"promocion\\\":null},{\\\"id\\\":7,\\\"nombre\\\":\\\"Monster Ultra\\\",\\\"cantidad\\\":2,\\\"precio_base\\\":2000,\\\"precio_final\\\":2000,\\\"total_linea\\\":4000,\\\"promocion\\\":null}]\"},{\"id\":15,\"fecha\":\"2025-10-15 15:20:35\",\"total\":\"94000.00\",\"metodo_pago\":\"Tarjeta\",\"productos\":\"[{\\\"id\\\":9,\\\"nombre\\\":\\\"Whisky Jack Daniel\'s Tennessee \\\",\\\"cantidad\\\":3,\\\"precio_base\\\":26500,\\\"precio_final\\\":26500,\\\"total_linea\\\":79500,\\\"promocion\\\":null},{\\\"id\\\":13,\\\"nombre\\\":\\\"Red Bull Original\\\",\\\"cantidad\\\":5,\\\"precio_base\\\":2900,\\\"precio_final\\\":2900,\\\"total_linea\\\":14500,\\\"promocion\\\":null}]\"},{\"id\":16,\"fecha\":\"2025-10-15 15:20:55\",\"total\":\"7200.00\",\"metodo_pago\":\"Efectivo\",\"productos\":\"[{\\\"id\\\":3,\\\"nombre\\\":\\\"Coca Cola Original\\\",\\\"cantidad\\\":4,\\\"precio_base\\\":1800,\\\"precio_final\\\":1800,\\\"total_linea\\\":7200,\\\"promocion\\\":null}]\"},{\"id\":17,\"fecha\":\"2025-10-15 15:21:21\",\"total\":\"88200.00\",\"metodo_pago\":\"Efectivo\",\"productos\":\"[{\\\"id\\\":13,\\\"nombre\\\":\\\"Red Bull Original\\\",\\\"cantidad\\\":3,\\\"precio_base\\\":2900,\\\"precio_final\\\":2900,\\\"total_linea\\\":8700,\\\"promocion\\\":null},{\\\"id\\\":9,\\\"nombre\\\":\\\"Whisky Jack Daniel\'s Tennessee \\\",\\\"cantidad\\\":3,\\\"precio_base\\\":26500,\\\"precio_final\\\":26500,\\\"total_linea\\\":79500,\\\"promocion\\\":null}]\"}]', 14000.00, 2800.00, 20400.00, 5000.00, 180400.00, 36080.00, 144320.00, 'Sin observaciones.', 0, 2, 102000.00, 3, 106600.00, 0.00, 0.00, 0.00),
(5, '2025-10-16 15:19:51', 1, 95400.00, 8, '[{\"id\":22,\"fecha\":\"2025-10-16 15:20:59\",\"total\":\"3200.00\",\"metodo_pago\":\"Tarjeta\",\"productos\":\"[{\\\"id\\\":6,\\\"nombre\\\":\\\"Monster Original\\\",\\\"cantidad\\\":4,\\\"precio_base\\\":2000,\\\"precio_final\\\":800,\\\"total_linea\\\":3200,\\\"promocion\\\":{\\\"id_promocion\\\":40,\\\"tipo\\\":\\\"descuento\\\",\\\"parametro\\\":60,\\\"etiqueta\\\":\\\"-60%\\\",\\\"detalle\\\":\\\"Descuento de 60% aplicado\\\",\\\"observacion\\\":\\\"\\\",\\\"precio_unit_base\\\":2000,\\\"precio_unit_final\\\":800,\\\"paga_unidades\\\":4,\\\"gratis\\\":0}}]\"},{\"id\":23,\"fecha\":\"2025-10-16 15:47:12\",\"total\":\"4200.00\",\"metodo_pago\":\"Tarjeta\",\"productos\":\"[{\\\"id\\\":11,\\\"nombre\\\":\\\"Cerveza Heineken\\\",\\\"cantidad\\\":3,\\\"precio_base\\\":1400,\\\"precio_final\\\":1400,\\\"total_linea\\\":4200,\\\"promocion\\\":null}]\"},{\"id\":24,\"fecha\":\"2025-10-16 15:47:23\",\"total\":\"53000.00\",\"metodo_pago\":\"Tarjeta\",\"productos\":\"[{\\\"id\\\":9,\\\"nombre\\\":\\\"Whisky Jack Daniel\'s Tennessee \\\",\\\"cantidad\\\":3,\\\"precio_base\\\":26500,\\\"precio_final\\\":26500,\\\"total_linea\\\":53000,\\\"promocion\\\":{\\\"id_promocion\\\":39,\\\"tipo\\\":\\\"2x1\\\",\\\"parametro\\\":0,\\\"etiqueta\\\":\\\"2x1\\\",\\\"detalle\\\":\\\"Promo 2x1: 1 unidad(es) gratis\\\",\\\"observacion\\\":\\\"\\\",\\\"precio_unit_base\\\":26500,\\\"precio_unit_final\\\":26500,\\\"paga_unidades\\\":2,\\\"gratis\\\":1}}]\"},{\"id\":25,\"fecha\":\"2025-10-16 15:47:33\",\"total\":\"4800.00\",\"metodo_pago\":\"Efectivo\",\"productos\":\"[{\\\"id\\\":6,\\\"nombre\\\":\\\"Monster Original\\\",\\\"cantidad\\\":1,\\\"precio_base\\\":2000,\\\"precio_final\\\":800,\\\"total_linea\\\":800,\\\"promocion\\\":{\\\"id_promocion\\\":40,\\\"tipo\\\":\\\"descuento\\\",\\\"parametro\\\":60,\\\"etiqueta\\\":\\\"-60%\\\",\\\"detalle\\\":\\\"Descuento de 60% aplicado\\\",\\\"observacion\\\":\\\"\\\",\\\"precio_unit_base\\\":2000,\\\"precio_unit_final\\\":800,\\\"paga_unidades\\\":1,\\\"gratis\\\":0}},{\\\"id\\\":7,\\\"nombre\\\":\\\"Monster Ultra\\\",\\\"cantidad\\\":1,\\\"precio_base\\\":2000,\\\"precio_final\\\":2000,\\\"total_linea\\\":2000,\\\"promocion\\\":null},{\\\"id\\\":8,\\\"nombre\\\":\\\"Monster Energy Absolutely Zero\\\",\\\"cantidad\\\":1,\\\"precio_base\\\":2000,\\\"precio_final\\\":2000,\\\"total_linea\\\":2000,\\\"promocion\\\":null}]\"},{\"id\":26,\"fecha\":\"2025-10-16 15:52:32\",\"total\":\"2400.00\",\"metodo_pago\":\"Tarjeta\",\"productos\":\"[{\\\"id\\\":4,\\\"nombre\\\":\\\"Coca Cola Original\\\",\\\"cantidad\\\":1,\\\"precio_base\\\":600,\\\"precio_final\\\":600,\\\"total_linea\\\":600,\\\"promocion\\\":null},{\\\"id\\\":3,\\\"nombre\\\":\\\"Coca Cola Original\\\",\\\"cantidad\\\":1,\\\"precio_base\\\":1800,\\\"precio_final\\\":1800,\\\"total_linea\\\":1800,\\\"promocion\\\":null}]\"},{\"id\":27,\"fecha\":\"2025-10-16 16:13:17\",\"total\":\"6000.00\",\"metodo_pago\":\"Tarjeta\",\"productos\":\"[{\\\"id\\\":7,\\\"nombre\\\":\\\"Monster Ultra\\\",\\\"cantidad\\\":3,\\\"precio_base\\\":2000,\\\"precio_final\\\":2000,\\\"total_linea\\\":6000,\\\"promocion\\\":null}]\"},{\"id\":28,\"fecha\":\"2025-10-16 16:19:20\",\"total\":\"10200.00\",\"metodo_pago\":\"Tarjeta\",\"productos\":\"[{\\\"id\\\":8,\\\"nombre\\\":\\\"Monster Energy Absolutely Zero\\\",\\\"cantidad\\\":3,\\\"precio_base\\\":2000,\\\"precio_final\\\":2000,\\\"total_linea\\\":6000,\\\"promocion\\\":null},{\\\"id\\\":4,\\\"nombre\\\":\\\"Coca Cola Original\\\",\\\"cantidad\\\":1,\\\"precio_base\\\":600,\\\"precio_final\\\":600,\\\"total_linea\\\":600,\\\"promocion\\\":null},{\\\"id\\\":3,\\\"nombre\\\":\\\"Coca Cola Original\\\",\\\"cantidad\\\":2,\\\"precio_base\\\":1800,\\\"precio_final\\\":1800,\\\"total_linea\\\":3600,\\\"promocion\\\":null}]\"},{\"id\":29,\"fecha\":\"2025-10-16 16:19:47\",\"total\":\"11600.00\",\"metodo_pago\":\"Efectivo\",\"productos\":\"[{\\\"id\\\":13,\\\"nombre\\\":\\\"Red Bull Original\\\",\\\"cantidad\\\":4,\\\"precio_base\\\":2900,\\\"precio_final\\\":2900,\\\"total_linea\\\":11600,\\\"promocion\\\":null}]\"}]', 0.00, 0.00, 15800.00, 5000.00, 74600.00, 14920.00, 59680.00, 'Sin observaciones.', 0, 6, 79000.00, 2, 16400.00, 0.00, 369312.00, 369312.00),
(6, '2025-10-20 19:51:39', 1, 12200.00, 2, '[{\"id\":32,\"fecha\":\"2025-10-20 14:11:55\",\"total\":\"4200.00\",\"metodo_pago\":\"Tarjeta\",\"productos\":\"[{\\\"id\\\":11,\\\"nombre\\\":\\\"Cerveza Heineken\\\",\\\"cantidad\\\":3,\\\"precio_base\\\":1400,\\\"precio_final\\\":1400,\\\"total_linea\\\":4200,\\\"promocion\\\":null}]\"},{\"id\":33,\"fecha\":\"2025-10-20 14:12:07\",\"total\":\"8000.00\",\"metodo_pago\":\"Efectivo\",\"productos\":\"[{\\\"id\\\":6,\\\"nombre\\\":\\\"Monster Original\\\",\\\"cantidad\\\":1,\\\"precio_base\\\":2000,\\\"precio_final\\\":2000,\\\"total_linea\\\":2000,\\\"promocion\\\":null},{\\\"id\\\":7,\\\"nombre\\\":\\\"Monster Ultra\\\",\\\"cantidad\\\":2,\\\"precio_base\\\":2000,\\\"precio_final\\\":2000,\\\"total_linea\\\":4000,\\\"promocion\\\":null},{\\\"id\\\":8,\\\"nombre\\\":\\\"Monster Energy Absolutely Zero\\\",\\\"cantidad\\\":1,\\\"precio_base\\\":2000,\\\"precio_final\\\":2000,\\\"total_linea\\\":2000,\\\"promocion\\\":null}]\"}]', 0.00, 0.00, 840.00, 5000.00, 6360.00, 1272.00, 5088.00, 'Sin observaciones.', 0, 1, 4200.00, 1, 8000.00, 0.00, 5088.00, 5088.00),
(8, '2025-10-21 15:40:39', 1, 169600.00, 10, '[{\"id\":34,\"fecha\":\"2025-10-21 12:29:39\",\"total\":\"13800.00\",\"metodo_pago\":\"Tarjeta\",\"productos\":\"[{\\\"id\\\":11,\\\"nombre\\\":\\\"Cerveza Heineken\\\",\\\"cantidad\\\":2,\\\"precio_base\\\":1400,\\\"precio_final\\\":1400,\\\"total_linea\\\":2800,\\\"promocion\\\":null},{\\\"id\\\":1,\\\"nombre\\\":\\\"Coca Cola Original\\\",\\\"cantidad\\\":3,\\\"precio_base\\\":3000,\\\"precio_final\\\":3000,\\\"total_linea\\\":9000,\\\"promocion\\\":null},{\\\"id\\\":2,\\\"nombre\\\":\\\"Coca Cola Original\\\",\\\"cantidad\\\":2,\\\"precio_base\\\":1000,\\\"precio_final\\\":1000,\\\"total_linea\\\":2000,\\\"promocion\\\":null}]\"},{\"id\":35,\"fecha\":\"2025-10-21 12:29:50\",\"total\":\"10000.00\",\"metodo_pago\":\"Tarjeta\",\"productos\":\"[{\\\"id\\\":6,\\\"nombre\\\":\\\"Monster Original\\\",\\\"cantidad\\\":2,\\\"precio_base\\\":2000,\\\"precio_final\\\":2000,\\\"total_linea\\\":4000,\\\"promocion\\\":null},{\\\"id\\\":5,\\\"nombre\\\":\\\"Monster Naranja Ripper\\\",\\\"cantidad\\\":3,\\\"precio_base\\\":2000,\\\"precio_final\\\":2000,\\\"total_linea\\\":6000,\\\"promocion\\\":null}]\"},{\"id\":36,\"fecha\":\"2025-10-21 12:30:01\",\"total\":\"83300.00\",\"metodo_pago\":\"Tarjeta\",\"productos\":\"[{\\\"id\\\":9,\\\"nombre\\\":\\\"Whisky Jack Daniel\'s Tennessee \\\",\\\"cantidad\\\":3,\\\"precio_base\\\":26500,\\\"precio_final\\\":26500,\\\"total_linea\\\":79500,\\\"promocion\\\":null},{\\\"id\\\":7,\\\"nombre\\\":\\\"Monster Ultra\\\",\\\"cantidad\\\":1,\\\"precio_base\\\":2000,\\\"precio_final\\\":2000,\\\"total_linea\\\":2000,\\\"promocion\\\":null},{\\\"id\\\":14,\\\"nombre\\\":\\\"Red Bull Morada\\\",\\\"cantidad\\\":1,\\\"precio_base\\\":1800,\\\"precio_final\\\":1800,\\\"total_linea\\\":1800,\\\"promocion\\\":null}]\"},{\"id\":37,\"fecha\":\"2025-10-21 12:30:10\",\"total\":\"3000.00\",\"metodo_pago\":\"Efectivo\",\"productos\":\"[{\\\"id\\\":2,\\\"nombre\\\":\\\"Coca Cola Original\\\",\\\"cantidad\\\":3,\\\"precio_base\\\":1000,\\\"precio_final\\\":1000,\\\"total_linea\\\":3000,\\\"promocion\\\":null}]\"},{\"id\":38,\"fecha\":\"2025-10-21 12:31:14\",\"total\":\"26500.00\",\"metodo_pago\":\"Tarjeta\",\"productos\":\"[{\\\"id\\\":9,\\\"nombre\\\":\\\"Whisky Jack Daniel\'s Tennessee \\\",\\\"cantidad\\\":2,\\\"precio_base\\\":26500,\\\"precio_final\\\":13250,\\\"total_linea\\\":26500,\\\"promocion\\\":{\\\"id_promocion\\\":42,\\\"tipo\\\":\\\"descuento\\\",\\\"parametro\\\":50,\\\"etiqueta\\\":\\\"-50%\\\",\\\"detalle\\\":\\\"Descuento de 50% aplicado\\\",\\\"observacion\\\":\\\"\\\",\\\"precio_unit_base\\\":26500,\\\"precio_unit_final\\\":13250,\\\"paga_unidades\\\":2,\\\"gratis\\\":0}}]\"},{\"id\":39,\"fecha\":\"2025-10-21 13:37:58\",\"total\":\"4000.00\",\"metodo_pago\":\"Tarjeta\",\"productos\":\"[{\\\"id\\\":1,\\\"nombre\\\":\\\"Coca Cola Original\\\",\\\"cantidad\\\":1,\\\"precio_base\\\":3000,\\\"precio_final\\\":3000,\\\"total_linea\\\":3000,\\\"promocion\\\":null},{\\\"id\\\":2,\\\"nombre\\\":\\\"Coca Cola Original\\\",\\\"cantidad\\\":1,\\\"precio_base\\\":1000,\\\"precio_final\\\":1000,\\\"total_linea\\\":1000,\\\"promocion\\\":null}]\"},{\"id\":40,\"fecha\":\"2025-10-21 13:40:14\",\"total\":\"1400.00\",\"metodo_pago\":\"Tarjeta\",\"productos\":\"[{\\\"id\\\":11,\\\"nombre\\\":\\\"Cerveza Heineken\\\",\\\"cantidad\\\":1,\\\"precio_base\\\":1400,\\\"precio_final\\\":1400,\\\"total_linea\\\":1400,\\\"promocion\\\":null}]\"},{\"id\":41,\"fecha\":\"2025-10-21 13:54:42\",\"total\":\"12000.00\",\"metodo_pago\":\"Efectivo\",\"productos\":\"[{\\\"id\\\":1,\\\"nombre\\\":\\\"Coca Cola Original\\\",\\\"cantidad\\\":4,\\\"precio_base\\\":3000,\\\"precio_final\\\":3000,\\\"total_linea\\\":12000,\\\"promocion\\\":null}]\"},{\"id\":42,\"fecha\":\"2025-10-21 13:55:18\",\"total\":\"12600.00\",\"metodo_pago\":\"Tarjeta\",\"productos\":\"[{\\\"id\\\":11,\\\"nombre\\\":\\\"Cerveza Heineken\\\",\\\"cantidad\\\":9,\\\"precio_base\\\":1400,\\\"precio_final\\\":1400,\\\"total_linea\\\":12600,\\\"promocion\\\":null}]\"},{\"id\":43,\"fecha\":\"2025-10-21 14:45:07\",\"total\":\"3000.00\",\"metodo_pago\":\"Tarjeta\",\"productos\":\"[{\\\"id\\\":1,\\\"nombre\\\":\\\"Coca Cola Original\\\",\\\"cantidad\\\":1,\\\"precio_base\\\":3000,\\\"precio_final\\\":3000,\\\"total_linea\\\":3000,\\\"promocion\\\":null}]\"}]', 20000.00, 4000.00, 30920.00, 5000.00, 129680.00, 25936.00, 103744.00, 'Sin observaciones.', 0, 8, 154600.00, 2, 15000.00, 0.00, 108832.00, 108832.00),
(10, '2025-10-22 13:37:26', 1, 166700.00, 5, '[{\"id\":49,\"fecha\":\"2025-10-22 13:35:34\",\"total\":\"13400.00\",\"metodo_pago\":\"Tarjeta\",\"productos\":\"[{\\\"id\\\":1,\\\"nombre\\\":\\\"Coca Cola Original\\\",\\\"cantidad\\\":3,\\\"precio_base\\\":3000,\\\"precio_final\\\":3000,\\\"total_linea\\\":9000,\\\"promocion\\\":null},{\\\"id\\\":3,\\\"nombre\\\":\\\"Coca Cola Original\\\",\\\"cantidad\\\":1,\\\"precio_base\\\":1800,\\\"precio_final\\\":1800,\\\"total_linea\\\":1800,\\\"promocion\\\":null},{\\\"id\\\":4,\\\"nombre\\\":\\\"Coca Cola Original\\\",\\\"cantidad\\\":1,\\\"precio_base\\\":600,\\\"precio_final\\\":600,\\\"total_linea\\\":600,\\\"promocion\\\":null},{\\\"id\\\":8,\\\"nombre\\\":\\\"Monster Energy Absolutely Zero\\\",\\\"cantidad\\\":1,\\\"precio_base\\\":2000,\\\"precio_final\\\":2000,\\\"total_linea\\\":2000,\\\"promocion\\\":null}]\"},{\"id\":50,\"fecha\":\"2025-10-22 13:35:40\",\"total\":\"106000.00\",\"metodo_pago\":\"Tarjeta\",\"productos\":\"[{\\\"id\\\":9,\\\"nombre\\\":\\\"Whisky Jack Daniel\'s Tennessee \\\",\\\"cantidad\\\":4,\\\"precio_base\\\":26500,\\\"precio_final\\\":26500,\\\"total_linea\\\":106000,\\\"promocion\\\":null}]\"},{\"id\":51,\"fecha\":\"2025-10-22 13:35:55\",\"total\":\"12000.00\",\"metodo_pago\":\"Efectivo\",\"productos\":\"[{\\\"id\\\":3,\\\"nombre\\\":\\\"Coca Cola Original\\\",\\\"cantidad\\\":1,\\\"precio_base\\\":1800,\\\"precio_final\\\":1800,\\\"total_linea\\\":1800,\\\"promocion\\\":null},{\\\"id\\\":4,\\\"nombre\\\":\\\"Coca Cola Original\\\",\\\"cantidad\\\":2,\\\"precio_base\\\":600,\\\"precio_final\\\":600,\\\"total_linea\\\":1200,\\\"promocion\\\":null},{\\\"id\\\":8,\\\"nombre\\\":\\\"Monster Energy Absolutely Zero\\\",\\\"cantidad\\\":4,\\\"precio_base\\\":2000,\\\"precio_final\\\":2000,\\\"total_linea\\\":8000,\\\"promocion\\\":null},{\\\"id\\\":13,\\\"nombre\\\":\\\"Red Bull Original\\\",\\\"cantidad\\\":2,\\\"precio_base\\\":2900,\\\"precio_final\\\":500,\\\"total_linea\\\":1000,\\\"promocion\\\":{\\\"id_promocion\\\":43,\\\"tipo\\\":\\\"precio_fijo\\\",\\\"parametro\\\":500,\\\"etiqueta\\\":\\\"Precio fijo\\\",\\\"detalle\\\":\\\"Precio fijo promocional $500\\\",\\\"observacion\\\":\\\"\\\",\\\"precio_unit_base\\\":2900,\\\"precio_unit_final\\\":500,\\\"paga_unidades\\\":2,\\\"gratis\\\":0}}]\"},{\"id\":52,\"fecha\":\"2025-10-22 13:36:01\",\"total\":\"28300.00\",\"metodo_pago\":\"Tarjeta\",\"productos\":\"[{\\\"id\\\":14,\\\"nombre\\\":\\\"Red Bull Morada\\\",\\\"cantidad\\\":1,\\\"precio_base\\\":1800,\\\"precio_final\\\":1800,\\\"total_linea\\\":1800,\\\"promocion\\\":null},{\\\"id\\\":9,\\\"nombre\\\":\\\"Whisky Jack Daniel\'s Tennessee \\\",\\\"cantidad\\\":1,\\\"precio_base\\\":26500,\\\"precio_final\\\":26500,\\\"total_linea\\\":26500,\\\"promocion\\\":null}]\"},{\"id\":53,\"fecha\":\"2025-10-22 13:36:09\",\"total\":\"7000.00\",\"metodo_pago\":\"Efectivo\",\"productos\":\"[{\\\"id\\\":1,\\\"nombre\\\":\\\"Coca Cola Original\\\",\\\"cantidad\\\":1,\\\"precio_base\\\":3000,\\\"precio_final\\\":3000,\\\"total_linea\\\":3000,\\\"promocion\\\":null},{\\\"id\\\":2,\\\"nombre\\\":\\\"Coca Cola Original\\\",\\\"cantidad\\\":4,\\\"precio_base\\\":1000,\\\"precio_final\\\":1000,\\\"total_linea\\\":4000,\\\"promocion\\\":null}]\"}]', 0.00, 0.00, 29540.00, 5000.00, 132160.00, 26432.00, 105728.00, 'Sin observaciones.', 0, 3, 147700.00, 2, 19000.00, 0.00, 214560.00, 214560.00),
(11, '2025-10-26 14:51:28', 1, 111200.00, 5, '[{\"id\":54,\"fecha\":\"2025-10-26 14:50:54\",\"total\":\"9000.00\",\"metodo_pago\":\"Tarjeta\",\"productos\":\"[{\\\"id\\\":1,\\\"nombre\\\":\\\"Coca Cola Original\\\",\\\"cantidad\\\":3,\\\"precio_base\\\":3000,\\\"precio_final\\\":3000,\\\"total_linea\\\":9000,\\\"promocion\\\":null}]\"},{\"id\":55,\"fecha\":\"2025-10-26 14:51:00\",\"total\":\"7800.00\",\"metodo_pago\":\"Tarjeta\",\"productos\":\"[{\\\"id\\\":3,\\\"nombre\\\":\\\"Coca Cola Original\\\",\\\"cantidad\\\":1,\\\"precio_base\\\":1800,\\\"precio_final\\\":1800,\\\"total_linea\\\":1800,\\\"promocion\\\":null},{\\\"id\\\":8,\\\"nombre\\\":\\\"Monster Energy Absolutely Zero\\\",\\\"cantidad\\\":1,\\\"precio_base\\\":2000,\\\"precio_final\\\":2000,\\\"total_linea\\\":2000,\\\"promocion\\\":null},{\\\"id\\\":5,\\\"nombre\\\":\\\"Monster Naranja Ripper\\\",\\\"cantidad\\\":1,\\\"precio_base\\\":2000,\\\"precio_final\\\":2000,\\\"total_linea\\\":2000,\\\"promocion\\\":null},{\\\"id\\\":6,\\\"nombre\\\":\\\"Monster Original\\\",\\\"cantidad\\\":1,\\\"precio_base\\\":2000,\\\"precio_final\\\":2000,\\\"total_linea\\\":2000,\\\"promocion\\\":null}]\"},{\"id\":56,\"fecha\":\"2025-10-26 14:51:07\",\"total\":\"84200.00\",\"metodo_pago\":\"Tarjeta\",\"productos\":\"[{\\\"id\\\":9,\\\"nombre\\\":\\\"Whisky Jack Daniel\'s Tennessee \\\",\\\"cantidad\\\":3,\\\"precio_base\\\":26500,\\\"precio_final\\\":26500,\\\"total_linea\\\":79500,\\\"promocion\\\":null},{\\\"id\\\":13,\\\"nombre\\\":\\\"Red Bull Original\\\",\\\"cantidad\\\":1,\\\"precio_base\\\":2900,\\\"precio_final\\\":2900,\\\"total_linea\\\":2900,\\\"promocion\\\":null},{\\\"id\\\":14,\\\"nombre\\\":\\\"Red Bull Morada\\\",\\\"cantidad\\\":1,\\\"precio_base\\\":1800,\\\"precio_final\\\":1800,\\\"total_linea\\\":1800,\\\"promocion\\\":null}]\"},{\"id\":57,\"fecha\":\"2025-10-26 14:51:15\",\"total\":\"3800.00\",\"metodo_pago\":\"Efectivo\",\"productos\":\"[{\\\"id\\\":2,\\\"nombre\\\":\\\"Coca Cola Original\\\",\\\"cantidad\\\":2,\\\"precio_base\\\":1000,\\\"precio_final\\\":1000,\\\"total_linea\\\":2000,\\\"promocion\\\":null},{\\\"id\\\":3,\\\"nombre\\\":\\\"Coca Cola Original\\\",\\\"cantidad\\\":1,\\\"precio_base\\\":1800,\\\"precio_final\\\":1800,\\\"total_linea\\\":1800,\\\"promocion\\\":null}]\"},{\"id\":58,\"fecha\":\"2025-10-26 14:51:23\",\"total\":\"6400.00\",\"metodo_pago\":\"Efectivo\",\"productos\":\"[{\\\"id\\\":4,\\\"nombre\\\":\\\"Coca Cola Original\\\",\\\"cantidad\\\":1,\\\"precio_base\\\":600,\\\"precio_final\\\":600,\\\"total_linea\\\":600,\\\"promocion\\\":null},{\\\"id\\\":3,\\\"nombre\\\":\\\"Coca Cola Original\\\",\\\"cantidad\\\":1,\\\"precio_base\\\":1800,\\\"precio_final\\\":1800,\\\"total_linea\\\":1800,\\\"promocion\\\":null},{\\\"id\\\":2,\\\"nombre\\\":\\\"Coca Cola Original\\\",\\\"cantidad\\\":1,\\\"precio_base\\\":1000,\\\"precio_final\\\":1000,\\\"total_linea\\\":1000,\\\"promocion\\\":null},{\\\"id\\\":1,\\\"nombre\\\":\\\"Coca Cola Original\\\",\\\"cantidad\\\":1,\\\"precio_base\\\":3000,\\\"precio_final\\\":3000,\\\"total_linea\\\":3000,\\\"promocion\\\":null}]\"}]', 0.00, 0.00, 20200.00, 5000.00, 86000.00, 17200.00, 68800.00, 'Se aplicó descuento dominical de $80.000 por pago de luz (restado de la reinversión semanal).', 1, 3, 101000.00, 2, 10200.00, 80000.00, 283360.00, 203360.00),
(13, '2025-10-27 22:08:45', 1, 114200.00, 4, '[{\"id\":70,\"fecha\":\"2025-10-27 22:04:45\",\"total\":\"2400.00\",\"metodo_pago\":\"Efectivo\",\"productos\":\"[{\\\"id\\\":11,\\\"nombre\\\":\\\"Cerveza Heineken\\\",\\\"cantidad\\\":1,\\\"precio_base\\\":1400,\\\"precio_final\\\":1400,\\\"total_linea\\\":1400,\\\"promocion\\\":null},{\\\"id\\\":2,\\\"nombre\\\":\\\"Coca Cola Original\\\",\\\"cantidad\\\":1,\\\"precio_base\\\":1000,\\\"precio_final\\\":1000,\\\"total_linea\\\":1000,\\\"promocion\\\":null}]\"},{\"id\":71,\"fecha\":\"2025-10-27 22:04:52\",\"total\":\"4000.00\",\"metodo_pago\":\"Tarjeta\",\"productos\":\"[{\\\"id\\\":7,\\\"nombre\\\":\\\"Monster Ultra\\\",\\\"cantidad\\\":1,\\\"precio_base\\\":2000,\\\"precio_final\\\":2000,\\\"total_linea\\\":2000,\\\"promocion\\\":null},{\\\"id\\\":6,\\\"nombre\\\":\\\"Monster Original\\\",\\\"cantidad\\\":1,\\\"precio_base\\\":2000,\\\"precio_final\\\":2000,\\\"total_linea\\\":2000,\\\"promocion\\\":null}]\"},{\"id\":72,\"fecha\":\"2025-10-27 22:05:24\",\"total\":\"1800.00\",\"metodo_pago\":\"Tarjeta\",\"productos\":\"[{\\\"id\\\":3,\\\"nombre\\\":\\\"Coca Cola Original\\\",\\\"cantidad\\\":2,\\\"precio_base\\\":1800,\\\"precio_final\\\":1800,\\\"total_linea\\\":1800,\\\"promocion\\\":{\\\"id_promocion\\\":51,\\\"tipo\\\":\\\"2x1\\\",\\\"parametro\\\":0,\\\"etiqueta\\\":\\\"2x1\\\",\\\"detalle\\\":\\\"Promo 2x1: 1 unidad(es) gratis\\\",\\\"observacion\\\":\\\"\\\",\\\"precio_unit_base\\\":1800,\\\"precio_unit_final\\\":1800,\\\"paga_unidades\\\":1,\\\"gratis\\\":1}}]\"},{\"id\":73,\"fecha\":\"2025-10-27 22:05:29\",\"total\":\"106000.00\",\"metodo_pago\":\"Tarjeta\",\"productos\":\"[{\\\"id\\\":9,\\\"nombre\\\":\\\"Whisky Jack Daniel\'s Tennessee \\\",\\\"cantidad\\\":4,\\\"precio_base\\\":26500,\\\"precio_final\\\":26500,\\\"total_linea\\\":106000,\\\"promocion\\\":null}]\"}]', 20000.00, 4000.00, 22360.00, 5000.00, 82840.00, 16568.00, 66272.00, 'Sin observaciones.', 0, 3, 111800.00, 1, 2400.00, 0.00, 66272.00, 66272.00),
(14, '2025-10-24 13:42:47', 1, 55900.00, 12, '[{\"id\":59,\"fecha\":\"2025-10-24 19:13:34\",\"total\":\"2800.00\",\"metodo_pago\":\"Efectivo\",\"productos\":\"[{\\\"id\\\":2,\\\"nombre\\\":\\\"Coca Cola Original\\\",\\\"cantidad\\\":1,\\\"precio_base\\\":1000,\\\"precio_final\\\":1000,\\\"total_linea\\\":1000,\\\"promocion\\\":null},{\\\"id\\\":3,\\\"nombre\\\":\\\"Coca Cola Original\\\",\\\"cantidad\\\":1,\\\"precio_base\\\":1800,\\\"precio_final\\\":1800,\\\"total_linea\\\":1800,\\\"promocion\\\":null}]\"},{\"id\":60,\"fecha\":\"2025-10-24 19:21:46\",\"total\":\"3600.00\",\"metodo_pago\":\"Tarjeta\",\"productos\":\"[{\\\"id\\\":14,\\\"nombre\\\":\\\"Red Bull Morada\\\",\\\"cantidad\\\":2,\\\"precio_base\\\":1800,\\\"precio_final\\\":1800,\\\"total_linea\\\":3600,\\\"promocion\\\":null}]\"},{\"id\":61,\"fecha\":\"2025-10-24 19:23:01\",\"total\":\"2000.00\",\"metodo_pago\":\"Tarjeta\",\"productos\":\"[{\\\"id\\\":7,\\\"nombre\\\":\\\"Monster Ultra\\\",\\\"cantidad\\\":1,\\\"precio_base\\\":2000,\\\"precio_final\\\":2000,\\\"total_linea\\\":2000,\\\"promocion\\\":null}]\"},{\"id\":62,\"fecha\":\"2025-10-24 19:23:37\",\"total\":\"1000.00\",\"metodo_pago\":\"Tarjeta\",\"productos\":\"[{\\\"id\\\":2,\\\"nombre\\\":\\\"Coca Cola Original\\\",\\\"cantidad\\\":1,\\\"precio_base\\\":1000,\\\"precio_final\\\":1000,\\\"total_linea\\\":1000,\\\"promocion\\\":null}]\"},{\"id\":63,\"fecha\":\"2025-10-24 19:25:28\",\"total\":\"4000.00\",\"metodo_pago\":\"Tarjeta\",\"productos\":\"[{\\\"id\\\":6,\\\"nombre\\\":\\\"Monster Original\\\",\\\"cantidad\\\":1,\\\"precio_base\\\":2000,\\\"precio_final\\\":2000,\\\"total_linea\\\":2000,\\\"promocion\\\":null},{\\\"id\\\":5,\\\"nombre\\\":\\\"Monster Naranja Ripper\\\",\\\"cantidad\\\":1,\\\"precio_base\\\":2000,\\\"precio_final\\\":2000,\\\"total_linea\\\":2000,\\\"promocion\\\":null}]\"},{\"id\":64,\"fecha\":\"2025-10-24 19:36:49\",\"total\":\"26500.00\",\"metodo_pago\":\"Efectivo\",\"productos\":\"[{\\\"id\\\":9,\\\"nombre\\\":\\\"Whisky Jack Daniel\'s Tennessee \\\",\\\"cantidad\\\":1,\\\"precio_base\\\":26500,\\\"precio_final\\\":26500,\\\"total_linea\\\":26500,\\\"promocion\\\":null}]\"},{\"id\":65,\"fecha\":\"2025-10-24 20:24:31\",\"total\":\"4000.00\",\"metodo_pago\":\"Tarjeta\",\"productos\":\"[{\\\"id\\\":5,\\\"nombre\\\":\\\"Monster Naranja Ripper\\\",\\\"cantidad\\\":4,\\\"precio_base\\\":2000,\\\"precio_final\\\":1000,\\\"total_linea\\\":4000,\\\"promocion\\\":{\\\"id_promocion\\\":47,\\\"tipo\\\":\\\"descuento\\\",\\\"parametro\\\":50,\\\"etiqueta\\\":\\\"-50%\\\",\\\"detalle\\\":\\\"Descuento de 50% aplicado\\\",\\\"observacion\\\":\\\"\\\",\\\"precio_unit_base\\\":2000,\\\"precio_unit_final\\\":1000,\\\"paga_unidades\\\":4,\\\"gratis\\\":0}}]\"},{\"id\":66,\"fecha\":\"2025-10-24 20:27:16\",\"total\":\"1000.00\",\"metodo_pago\":\"Efectivo\",\"productos\":\"[{\\\"id\\\":2,\\\"nombre\\\":\\\"Coca Cola Original\\\",\\\"cantidad\\\":1,\\\"precio_base\\\":1000,\\\"precio_final\\\":1000,\\\"total_linea\\\":1000,\\\"promocion\\\":null}]\"},{\"id\":67,\"fecha\":\"2025-10-24 20:30:27\",\"total\":\"1000.00\",\"metodo_pago\":\"Efectivo\",\"productos\":\"[{\\\"id\\\":2,\\\"nombre\\\":\\\"Coca Cola Original\\\",\\\"cantidad\\\":1,\\\"precio_base\\\":1000,\\\"precio_final\\\":1000,\\\"total_linea\\\":1000,\\\"promocion\\\":null}]\"},{\"id\":68,\"fecha\":\"2025-10-24 21:17:38\",\"total\":\"2600.00\",\"metodo_pago\":\"Tarjeta\",\"productos\":\"[{\\\"id\\\":8,\\\"nombre\\\":\\\"Monster Energy Absolutely Zero\\\",\\\"cantidad\\\":1,\\\"precio_base\\\":2000,\\\"precio_final\\\":2000,\\\"total_linea\\\":2000,\\\"promocion\\\":null},{\\\"id\\\":4,\\\"nombre\\\":\\\"Coca Cola Original\\\",\\\"cantidad\\\":1,\\\"precio_base\\\":600,\\\"precio_final\\\":600,\\\"total_linea\\\":600,\\\"promocion\\\":null}]\"},{\"id\":69,\"fecha\":\"2025-10-24 21:19:51\",\"total\":\"5000.00\",\"metodo_pago\":\"Efectivo\",\"productos\":\"[{\\\"id\\\":18,\\\"nombre\\\":\\\"Pisco Mistral 35\\\",\\\"cantidad\\\":2,\\\"precio_base\\\":5000,\\\"precio_final\\\":5000,\\\"total_linea\\\":5000,\\\"promocion\\\":{\\\"id_promocion\\\":50,\\\"tipo\\\":\\\"2x1\\\",\\\"parametro\\\":0,\\\"etiqueta\\\":\\\"2x1\\\",\\\"detalle\\\":\\\"Promo 2x1: 1 unidad(es) gratis\\\",\\\"observacion\\\":\\\"\\\",\\\"precio_unit_base\\\":5000,\\\"precio_unit_final\\\":5000,\\\"paga_unidades\\\":1,\\\"gratis\\\":1}}]\"},{\"id\":74,\"fecha\":\"2025-10-24 13:42:18\",\"total\":\"2400.00\",\"metodo_pago\":\"Efectivo\",\"productos\":\"[{\\\"id\\\":11,\\\"nombre\\\":\\\"Cerveza Heineken\\\",\\\"cantidad\\\":1,\\\"precio_base\\\":1400,\\\"precio_final\\\":1400,\\\"total_linea\\\":1400,\\\"promocion\\\":null},{\\\"id\\\":2,\\\"nombre\\\":\\\"Coca Cola Original\\\",\\\"cantidad\\\":1,\\\"precio_base\\\":1000,\\\"precio_final\\\":1000,\\\"total_linea\\\":1000,\\\"promocion\\\":null}]\"}]', 0.00, 0.00, 3440.00, 5000.00, 47460.00, 9492.00, 37968.00, 'Sin observaciones.', 0, 6, 17200.00, 6, 38700.00, 0.00, 321328.00, 321328.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `historial_cambio`
--

CREATE TABLE `historial_cambio` (
  `id` int(11) NOT NULL,
  `usuario` varchar(100) NOT NULL,
  `modulo` varchar(100) NOT NULL,
  `tipo_accion` enum('INSERT','UPDATE','DELETE') NOT NULL,
  `id_registro_afectado` int(11) NOT NULL,
  `valor_anterior` text DEFAULT NULL,
  `valor_nuevo` text DEFAULT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `historial_cambio`
--

INSERT INTO `historial_cambio` (`id`, `usuario`, `modulo`, `tipo_accion`, `id_registro_afectado`, `valor_anterior`, `valor_nuevo`, `fecha`) VALUES
(1, 'admin', 'Productos', 'UPDATE', 11, 'Campos modificados', '{\n    \"precio_venta\": {\n        \"antes\": \"1200.00\",\n        \"después\": \"1400.00\"\n    }\n}', '2025-10-16 03:24:27'),
(2, 'admin', 'Promociones', 'INSERT', 39, NULL, '{\"tipo\":\"2x1\",\"parametro\":\"Sin información\",\"fecha_inicio\":\"2025-10-16 14:17:00\",\"fecha_fin\":\"2025-10-17 14:17:00\",\"productos_asociados\":\"(9) Whisky Jack Daniel\'s Tennessee \",\"observacion\":\"Sin información\"}', '2025-10-16 18:17:13'),
(3, 'admin', 'Promociones', 'INSERT', 40, NULL, '{\"tipo\":\"descuento\",\"parametro\":60,\"fecha_inicio\":\"2025-10-16 14:17:00\",\"fecha_fin\":\"2025-10-17 14:17:00\",\"productos_asociados\":\"(6) Monster Original\",\"observacion\":\"Sin información\"}', '2025-10-16 18:17:43'),
(4, 'admin', 'Productos', 'UPDATE', 11, 'Campos modificados', '{\n    \"precio_venta\": {\n        \"antes\": \"1400.00\",\n        \"después\": \"1000.00\"\n    }\n}', '2025-10-16 18:35:52'),
(5, 'admin', 'Productos', 'UPDATE', 11, 'Campos modificados', '{\n    \"precio_venta\": {\n        \"antes\": \"1000.00\",\n        \"después\": \"1400.00\"\n    }\n}', '2025-10-16 18:41:19'),
(6, 'admin', 'Stock', 'UPDATE', 13, '{\"id\":13,\"codigo\":\"00001\",\"nombre\":\"Red Bull Original\",\"formato\":\"Lata\",\"tamano\":\"473 ml\",\"marca\":\"Red Bull\",\"cantidad\":27,\"stock_minimo\":15,\"precio_compra\":\"1000.00\",\"precio_venta\":\"2900.00\",\"fecha_recepcion\":\"2025-10-21\",\"proveedor\":\"Los Angeles\",\"fecha_vencimiento\":\"2025-12-21\",\"imagen\":\"vistas\\/imagenes\\/productos\\/prod_68e01ff9129e9.jpg\",\"estado\":1,\"fecha_ingreso\":\"2025-10-03 15:54:41\"}', '{\"producto\":\"Red Bull Original\",\"cantidad_agregada\":\"20\",\"fecha_recepcion\":\"2025-10-21\",\"fecha_vencimiento\":\"2025-12-21\"}', '2025-10-21 15:25:40'),
(7, 'admin', 'Stock', 'UPDATE', 11, '{\"id\":11,\"codigo\":\"98536\",\"nombre\":\"Cerveza Heineken\",\"formato\":\"Lata\",\"tamano\":\" 470 CC\",\"marca\":\"Heineken\",\"cantidad\":22,\"stock_minimo\":10,\"precio_compra\":\"500.00\",\"precio_venta\":\"1400.00\",\"fecha_recepcion\":\"2025-10-21\",\"proveedor\":\"Los Angeles\",\"fecha_vencimiento\":\"2026-01-21\",\"imagen\":\"vistas\\/imagenes\\/productos\\/prod_68e02fa3aa786.jpg\",\"estado\":1,\"fecha_ingreso\":\"2025-09-28 13:18:30\"}', '{\"producto\":\"Cerveza Heineken\",\"cantidad_agregada\":\"20\",\"fecha_recepcion\":\"2025-10-21\",\"fecha_vencimiento\":\"2026-01-21\"}', '2025-10-21 15:25:59'),
(8, 'admin', 'Stock', 'UPDATE', 9, '{\"id\":9,\"codigo\":\"87654\",\"nombre\":\"Whisky Jack Daniel\'s Tennessee \",\"formato\":\"Botella\",\"tamano\":\"750 cc\",\"marca\":\"Jack Daniel\'s\",\"cantidad\":50,\"stock_minimo\":10,\"precio_compra\":\"15000.00\",\"precio_venta\":\"26500.00\",\"fecha_recepcion\":\"2025-10-21\",\"proveedor\":\"Los Angeles\",\"fecha_vencimiento\":\"2027-02-16\",\"imagen\":\"vistas\\/imagenes\\/productos\\/prod_68d95d88f2cc8.jpg\",\"estado\":1,\"fecha_ingreso\":\"2025-09-28 13:08:40\"}', '{\"producto\":\"Whisky Jack Daniel\'s Tennessee \",\"cantidad_agregada\":\"50\",\"fecha_recepcion\":\"2025-10-21\",\"fecha_vencimiento\":\"2027-02-16\"}', '2025-10-21 15:26:22'),
(9, 'admin', 'Stock', 'UPDATE', 5, '{\"id\":5,\"codigo\":\"56789\",\"nombre\":\"Monster Naranja Ripper\",\"formato\":\"Lata\",\"tamano\":\"437 ml\",\"marca\":\"Monster\",\"cantidad\":30,\"stock_minimo\":20,\"precio_compra\":\"600.00\",\"precio_venta\":\"2000.00\",\"fecha_recepcion\":\"2025-10-21\",\"proveedor\":\"Los Angeles\",\"fecha_vencimiento\":\"2025-12-31\",\"imagen\":\"vistas\\/imagenes\\/productos\\/prod_68d2384cc4412.jpg\",\"estado\":1,\"fecha_ingreso\":\"2025-09-23 03:03:56\"}', '{\"producto\":\"Monster Naranja Ripper\",\"cantidad_agregada\":\"30\",\"fecha_recepcion\":\"2025-10-21\",\"fecha_vencimiento\":\"2025-12-31\"}', '2025-10-21 15:26:35'),
(10, 'admin', 'Stock', 'UPDATE', 3, '{\"id\":3,\"codigo\":\"34567\",\"nombre\":\"Coca Cola Original\",\"formato\":\"Botella\",\"tamano\":\"1.5 L\",\"marca\":\"Coca-cola\",\"cantidad\":35,\"stock_minimo\":10,\"precio_compra\":\"800.00\",\"precio_venta\":\"1800.00\",\"fecha_recepcion\":\"2025-10-21\",\"proveedor\":\"Los Hermanos Torres\",\"fecha_vencimiento\":\"2026-01-05\",\"imagen\":\"vistas\\/imagenes\\/productos\\/prod_68d2339fcd4c7.jpg\",\"estado\":1,\"fecha_ingreso\":\"2025-09-23 02:26:06\"}', '{\"producto\":\"Coca Cola Original\",\"cantidad_agregada\":\"35\",\"fecha_recepcion\":\"2025-10-21\",\"fecha_vencimiento\":\"2026-01-05\"}', '2025-10-21 15:26:51'),
(11, 'admin', 'Stock', 'UPDATE', 8, '{\"id\":8,\"codigo\":\"89012\",\"nombre\":\"Monster Energy Absolutely Zero\",\"formato\":\"Lata\",\"tamano\":\"437 ml\",\"marca\":\"Monster\",\"cantidad\":29,\"stock_minimo\":15,\"precio_compra\":\"600.00\",\"precio_venta\":\"2000.00\",\"fecha_recepcion\":\"2025-10-21\",\"proveedor\":\"Los Angeles\",\"fecha_vencimiento\":\"2025-12-31\",\"imagen\":\"vistas\\/imagenes\\/productos\\/prod_68d23e2e1cb6b.jpg\",\"estado\":1,\"fecha_ingreso\":\"2025-09-23 03:29:02\"}', '{\"producto\":\"Monster Energy Absolutely Zero\",\"cantidad_agregada\":\"25\",\"fecha_recepcion\":\"2025-10-21\",\"fecha_vencimiento\":\"2025-12-31\"}', '2025-10-21 15:27:08'),
(12, 'admin', 'Stock', 'UPDATE', 2, '{\"id\":2,\"codigo\":\"23456\",\"nombre\":\"Coca Cola Original\",\"formato\":\"Lata\",\"tamano\":\"350 ml\",\"marca\":\"Coca - cola\",\"cantidad\":40,\"stock_minimo\":15,\"precio_compra\":\"500.00\",\"precio_venta\":\"1000.00\",\"fecha_recepcion\":\"2025-10-21\",\"proveedor\":\"Distribuidora Ilusionista\",\"fecha_vencimiento\":\"2026-02-02\",\"imagen\":\"vistas\\/imagenes\\/productos\\/prod_68d22eacc973b.png\",\"estado\":1,\"fecha_ingreso\":\"2025-09-23 02:22:43\"}', '{\"producto\":\"Coca Cola Original\",\"cantidad_agregada\":\"40\",\"fecha_recepcion\":\"2025-10-21\",\"fecha_vencimiento\":\"2026-02-02\"}', '2025-10-21 15:27:24'),
(13, 'admin', 'Stock', 'UPDATE', 7, '{\"id\":7,\"codigo\":\"78901\",\"nombre\":\"Monster Ultra\",\"formato\":\"Lata\",\"tamano\":\"437 ml\",\"marca\":\"Monster\",\"cantidad\":28,\"stock_minimo\":10,\"precio_compra\":\"600.00\",\"precio_venta\":\"2000.00\",\"fecha_recepcion\":\"2025-10-21\",\"proveedor\":\"Los Angeles\",\"fecha_vencimiento\":\"2026-02-11\",\"imagen\":\"vistas\\/imagenes\\/productos\\/prod_68d23cf9e2776.jpg\",\"estado\":1,\"fecha_ingreso\":\"2025-09-23 03:23:53\"}', '{\"producto\":\"Monster Ultra\",\"cantidad_agregada\":\"25\",\"fecha_recepcion\":\"2025-10-21\",\"fecha_vencimiento\":\"2026-02-11\"}', '2025-10-21 15:28:41'),
(14, 'admin', 'Stock', 'UPDATE', 6, '{\"id\":6,\"codigo\":\"67890\",\"nombre\":\"Monster Original\",\"formato\":\"Lata\",\"tamano\":\"437 ml\",\"marca\":\"Monster\",\"cantidad\":28,\"stock_minimo\":15,\"precio_compra\":\"600.00\",\"precio_venta\":\"2000.00\",\"fecha_recepcion\":\"2025-10-21\",\"proveedor\":\"Los Angeles\",\"fecha_vencimiento\":\"2025-02-05\",\"imagen\":\"vistas\\/imagenes\\/productos\\/prod_68d23d2f38973.jpg\",\"estado\":1,\"fecha_ingreso\":\"2025-09-23 03:06:52\"}', '{\"producto\":\"Monster Original\",\"cantidad_agregada\":\"25\",\"fecha_recepcion\":\"2025-10-21\",\"fecha_vencimiento\":\"2025-02-05\"}', '2025-10-21 15:28:52'),
(15, 'admin', 'Stock', 'UPDATE', 4, '{\"id\":4,\"codigo\":\"45678\",\"nombre\":\"Coca Cola Original\",\"formato\":\"Vidrio\",\"tamano\":\"237 ml\",\"marca\":\"Coca-cola\",\"cantidad\":29,\"stock_minimo\":10,\"precio_compra\":\"100.00\",\"precio_venta\":\"600.00\",\"fecha_recepcion\":\"2025-10-21\",\"proveedor\":\"Los Hermanos Torres\",\"fecha_vencimiento\":\"2025-12-31\",\"imagen\":\"vistas\\/imagenes\\/productos\\/prod_68d2377a10dea.jpg\",\"estado\":1,\"fecha_ingreso\":\"2025-09-23 02:58:44\"}', '{\"producto\":\"Coca Cola Original\",\"cantidad_agregada\":\"25\",\"fecha_recepcion\":\"2025-10-21\",\"fecha_vencimiento\":\"2025-12-31\"}', '2025-10-21 15:29:02'),
(16, 'admin', 'Promociones', 'INSERT', 41, NULL, '{\"tipo\":\"2x1\",\"parametro\":\"Sin información\",\"fecha_inicio\":\"2025-10-21 12:30:00\",\"fecha_fin\":\"2025-10-22 12:30:00\",\"productos_asociados\":\"(5) Monster Naranja Ripper\",\"observacion\":\"Sin información\"}', '2025-10-21 15:30:22'),
(17, 'admin', 'Promociones', 'INSERT', 42, NULL, '{\"tipo\":\"descuento\",\"parametro\":50,\"fecha_inicio\":\"2025-10-21 12:30:00\",\"fecha_fin\":\"2025-10-22 12:31:00\",\"productos_asociados\":\"(9) Whisky Jack Daniel\'s Tennessee \",\"observacion\":\"Sin información\"}', '2025-10-21 15:31:03'),
(18, 'admin', 'Productos', 'UPDATE', 6, 'Campos modificados', '{\n    \"fecha_vencimiento\": {\n        \"antes\": \"2025-02-05\",\n        \"después\": \"2026-02-05\"\n    }\n}', '2025-10-21 16:52:19'),
(19, 'admin', 'Promociones', 'INSERT', 43, NULL, '{\"tipo\":\"precio_fijo\",\"parametro\":500,\"fecha_inicio\":\"2025-10-21 14:02:00\",\"fecha_fin\":\"2025-10-22 14:02:00\",\"productos_asociados\":\"(13) Red Bull Original\",\"observacion\":\"Sin información\"}', '2025-10-21 17:02:09'),
(20, 'admin', 'Promociones', 'INSERT', 44, NULL, '{\"tipo\":\"2x1\",\"parametro\":\"Sin información\",\"fecha_inicio\":\"2025-10-21 14:06:00\",\"fecha_fin\":\"2025-10-21 14:07:00\",\"productos_asociados\":\"(14) Red Bull Morada\",\"observacion\":\"Sin información\"}', '2025-10-21 17:06:34'),
(21, 'admin', 'Promociones', 'INSERT', 45, NULL, '{\"tipo\":\"2x1\",\"parametro\":\"Sin información\",\"fecha_inicio\":\"2025-10-21 14:10:00\",\"fecha_fin\":\"2025-10-21 14:12:00\",\"productos_asociados\":\"(14) Red Bull Morada\",\"observacion\":\"Sin información\"}', '2025-10-21 17:10:32'),
(22, 'admin', 'Stock', 'UPDATE', 1, '{\"id\":1,\"codigo\":\"12345\",\"nombre\":\"Coca Cola Original\",\"formato\":\"Botella\",\"tamano\":\"3L\",\"marca\":\"Coca-cola\",\"cantidad\":20,\"stock_minimo\":10,\"precio_compra\":\"1550.00\",\"precio_venta\":\"3000.00\",\"fecha_recepcion\":\"2025-10-24\",\"proveedor\":\"Los Hermanos Torres\",\"fecha_vencimiento\":\"2026-02-19\",\"imagen\":\"vistas\\/imagenes\\/productos\\/prod_68d22e046f77f.jpg\",\"estado\":1,\"fecha_ingreso\":\"2025-09-23 02:19:55\"}', '{\"producto\":\"Coca Cola Original\",\"cantidad_agregada\":\"20\",\"fecha_recepcion\":\"2025-10-24\",\"fecha_vencimiento\":\"2026-02-19\"}', '2025-10-24 22:43:50'),
(23, 'admin', 'Stock', 'UPDATE', 1, NULL, '{\"producto\":\"Coca Cola Original\",\"nuevo_stock_minimo\":15}', '2025-10-24 22:44:45'),
(24, 'mati', 'Usuarios', 'UPDATE', 2, '{\"id\":2,\"nombre\":\"Matias Caceres\",\"usuario\":\"mati\",\"password\":\"$2a$07$usesomesillystringforeWbVzYlorgmbfLnSWRwHce7G62sVyNeG\",\"rol\":\"Administrador\"}', '{\"rol\":{\"antes\":\"Administrador\",\"después\":\"Vendedor\"}}', '2025-10-24 22:46:39'),
(25, 'admin', 'Productos', 'INSERT', 999999, NULL, '{\"codigo\":\"999999\",\"nombre\":\"Pisco Mistral 35º\",\"formato\":\"Botella\",\"tamano\":\"1 L\",\"marca\":\"Mistral\",\"cantidad\":30,\"precio_compra\":\"3000\",\"precio_venta\":\"5000\",\"fecha_vencimiento\":null,\"proveedor\":\"Los Angeles\",\"imagen\":null}', '2025-10-24 22:49:12'),
(26, 'admin', 'Stock', 'UPDATE', 11, '{\"id\":11,\"codigo\":\"98536\",\"nombre\":\"Cerveza Heineken\",\"formato\":\"Lata\",\"tamano\":\" 470 CC\",\"marca\":\"Heineken\",\"cantidad\":20,\"stock_minimo\":10,\"precio_compra\":\"500.00\",\"precio_venta\":\"1400.00\",\"fecha_recepcion\":\"2025-10-24\",\"proveedor\":\"Los Angeles\",\"fecha_vencimiento\":\"2026-01-25\",\"imagen\":\"vistas\\/imagenes\\/productos\\/prod_68e02fa3aa786.jpg\",\"estado\":0,\"fecha_ingreso\":\"2025-09-28 13:18:30\"}', '{\"producto\":\"Cerveza Heineken\",\"cantidad_agregada\":\"10\",\"fecha_recepcion\":\"2025-10-24\",\"fecha_vencimiento\":\"2026-01-25\"}', '2025-10-24 22:50:16'),
(27, 'admin', 'Productos', 'UPDATE', 11, 'Campos modificados', '{\n    \"fecha_vencimiento\": {\n        \"antes\": \"2026-01-25\",\n        \"después\": \"2026-02-21\"\n    }\n}', '2025-10-24 22:51:54'),
(28, 'admin', 'Productos', 'UPDATE', 11, 'Campos modificados', '{\n    \"cantidad\": {\n        \"antes\": 20,\n        \"después\": \"10\"\n    }\n}', '2025-10-24 22:54:59'),
(29, 'admin', 'Productos', 'UPDATE', 11, 'Campos modificados', '{\n    \"cantidad\": {\n        \"antes\": 10,\n        \"después\": \"20\"\n    }\n}', '2025-10-24 22:56:25'),
(30, 'admin', 'Productos', 'UPDATE', 18, 'Campos modificados', '{\n    \"nombre\": {\n        \"antes\": \"Pisco Mistral 35º\",\n        \"después\": \"Pisco Mistral 35\"\n    },\n    \"imagen\": {\n        \"antes\": null,\n        \"después\": \"vistas\\/imagenes\\/productos\\/prod_68fc05d94a74b.png\"\n    }\n}', '2025-10-24 23:03:53'),
(31, 'admin', 'Productos', 'UPDATE', 18, 'Campos modificados', '{\n    \"imagen\": {\n        \"antes\": \"vistas\\/imagenes\\/productos\\/prod_68fc05d94a74b.png\",\n        \"después\": \"vistas\\/imagenes\\/productos\\/prod_68fc060a52809.jpg\"\n    }\n}', '2025-10-24 23:04:42'),
(32, 'admin', 'Productos', 'UPDATE', 11, 'Campos modificados', '{\n    \"precio_venta\": {\n        \"antes\": \"1400.00\",\n        \"después\": \"1000.00\"\n    }\n}', '2025-10-24 23:06:54'),
(33, 'admin', 'Usuarios', 'INSERT', 9, NULL, '{\"nombre\":\"Joaquin Soto\",\"usuario\":\"joaco\",\"rol\":\"Vendedor\",\"password\":\"********\"}', '2025-10-24 23:14:56'),
(34, 'admin', 'Usuarios', 'UPDATE', 9, '{\"id\":9,\"nombre\":\"Joaquin Soto\",\"usuario\":\"joaco\",\"password\":\"$2a$07$usesomesillystringforec7DcqHJRJWB3Wv5BkpSw7wuX961psXy\",\"rol\":\"Vendedor\"}', '{\"nombre\":{\"antes\":\"Joaquin Soto\",\"después\":\"Joaquin Soto A\"},\"rol\":{\"antes\":\"Vendedor\",\"después\":\"Administrador\"}}', '2025-10-24 23:16:05'),
(35, 'admin', 'Usuarios', 'DELETE', 9, '{\"id\":9,\"nombre\":\"Joaquin Soto A\",\"usuario\":\"joaco\",\"rol\":\"Administrador\"}', NULL, '2025-10-24 23:16:39'),
(36, 'admin', 'Usuarios', 'UPDATE', 2, '{\"id\":2,\"nombre\":\"Matias Caceres\",\"usuario\":\"mati\",\"password\":\"$2a$07$usesomesillystringforeWbVzYlorgmbfLnSWRwHce7G62sVyNeG\",\"rol\":\"Vendedor\"}', '{\"rol\":{\"antes\":\"Vendedor\",\"después\":\"Administrador\"}}', '2025-10-24 23:17:30'),
(37, 'admin', 'Usuarios', 'UPDATE', 2, '{\"id\":2,\"nombre\":\"Matias Caceres\",\"usuario\":\"mati\",\"password\":\"$2a$07$usesomesillystringforeWbVzYlorgmbfLnSWRwHce7G62sVyNeG\",\"rol\":\"Administrador\"}', '{\"rol\":{\"antes\":\"Administrador\",\"después\":\"Vendedor\"}}', '2025-10-24 23:19:00'),
(38, 'admin', 'Promociones', 'INSERT', 46, NULL, '{\"tipo\":\"2x1\",\"parametro\":\"Sin información\",\"fecha_inicio\":\"2025-10-24 20:20:00\",\"fecha_fin\":\"2025-10-25 20:20:00\",\"productos_asociados\":\"(7) Monster Ultra\",\"observacion\":\"Sin información\"}', '2025-10-24 23:21:22'),
(39, 'admin', 'Promociones', 'INSERT', 47, NULL, '{\"tipo\":\"descuento\",\"parametro\":50,\"fecha_inicio\":\"2025-10-24 20:22:00\",\"fecha_fin\":\"2025-10-25 20:22:00\",\"productos_asociados\":\"(5) Monster Naranja Ripper\",\"observacion\":\"Sin información\"}', '2025-10-24 23:22:38'),
(40, 'admin', 'Promociones', 'INSERT', 48, NULL, '{\"tipo\":\"precio_fijo\",\"parametro\":500,\"fecha_inicio\":\"2025-10-24 20:23:00\",\"fecha_fin\":\"2025-10-25 20:23:00\",\"productos_asociados\":\"(14) Red Bull Morada\",\"observacion\":\"Sin información\"}', '2025-10-24 23:23:08'),
(41, 'admin', 'Promociones', 'DELETE', 48, '{\"tipo\":\"precio_fijo\",\"parametro\":\"500.00\",\"fecha_inicio\":\"2025-10-24 20:23:00\",\"fecha_fin\":\"2025-10-25 20:23:00\",\"productos_asociados\":\"(14) Red Bull Morada\",\"observacion\":\"Sin información\"}', NULL, '2025-10-24 23:23:25'),
(42, 'admin', 'Promociones', 'INSERT', 49, NULL, '{\"tipo\":\"precio_fijo\",\"parametro\":500,\"fecha_inicio\":\"2025-10-24 20:25:00\",\"fecha_fin\":\"2025-10-25 20:23:00\",\"productos_asociados\":\"(14) Red Bull Morada\",\"observacion\":\"Sin información\"}', '2025-10-24 23:23:50'),
(43, 'admin', 'Promociones', 'DELETE', 46, '{\"tipo\":\"2x1\",\"parametro\":\"Sin información\",\"fecha_inicio\":\"2025-10-24 20:20:00\",\"fecha_fin\":\"2025-10-25 20:20:00\",\"productos_asociados\":\"(7) Monster Ultra\",\"observacion\":\"Sin información\"}', NULL, '2025-10-24 23:27:33'),
(44, 'admin', 'Promociones', 'DELETE', 47, '{\"tipo\":\"descuento\",\"parametro\":\"50.00\",\"fecha_inicio\":\"2025-10-24 20:22:00\",\"fecha_fin\":\"2025-10-25 20:22:00\",\"productos_asociados\":\"(5) Monster Naranja Ripper\",\"observacion\":\"Sin información\"}', NULL, '2025-10-24 23:27:35'),
(45, 'admin', 'Promociones', 'DELETE', 49, '{\"tipo\":\"precio_fijo\",\"parametro\":\"500.00\",\"fecha_inicio\":\"2025-10-24 20:25:00\",\"fecha_fin\":\"2025-10-25 20:23:00\",\"productos_asociados\":\"(14) Red Bull Morada\",\"observacion\":\"Sin información\"}', NULL, '2025-10-24 23:27:37'),
(46, 'admin', 'Promociones', 'INSERT', 50, NULL, '{\"tipo\":\"2x1\",\"parametro\":\"Sin información\",\"fecha_inicio\":\"2025-10-24 21:18:00\",\"fecha_fin\":\"2025-10-25 21:18:00\",\"productos_asociados\":\"(18) Pisco Mistral 35\",\"observacion\":\"Sin información\"}', '2025-10-25 00:18:58'),
(47, 'admin', 'Promociones', 'DELETE', 50, '{\"tipo\":\"2x1\",\"parametro\":\"Sin información\",\"fecha_inicio\":\"2025-10-24 21:18:00\",\"fecha_fin\":\"2025-10-25 21:18:00\",\"productos_asociados\":\"(18) Pisco Mistral 35\",\"observacion\":\"Sin información\"}', NULL, '2025-10-25 00:20:22'),
(48, 'admin', 'Stock', 'UPDATE', 18, NULL, '{\"producto\":\"Pisco Mistral 35\",\"nuevo_stock_minimo\":10}', '2025-10-25 00:23:44'),
(49, 'admin', 'Productos', 'UPDATE', 11, 'Campos modificados', '{\n    \"precio_venta\": {\n        \"antes\": \"1000.00\",\n        \"después\": \"1400.00\"\n    }\n}', '2025-10-25 00:26:09'),
(50, 'admin', 'Productos', 'UPDATE', 11, 'Campos modificados', '{\n    \"fecha_vencimiento\": {\n        \"antes\": \"2026-02-21\",\n        \"después\": \"2026-03-19\"\n    }\n}', '2025-10-25 00:26:19'),
(51, 'admin', 'Productos', 'DELETE', 18, 'Registro eliminado', '{\n    \"id\": 18,\n    \"codigo\": \"999999\",\n    \"nombre\": \"Pisco Mistral 35\",\n    \"formato\": \"Botella\",\n    \"tamano\": \"1 L\",\n    \"marca\": \"Mistral\",\n    \"cantidad\": 28,\n    \"stock_minimo\": 10,\n    \"precio_compra\": \"3000.00\",\n    \"precio_venta\": \"5000.00\",\n    \"fecha_recepcion\": null,\n    \"proveedor\": \"Los Angeles\",\n    \"fecha_vencimiento\": null,\n    \"imagen\": \"vistas\\/imagenes\\/productos\\/prod_68fc060a52809.jpg\",\n    \"estado\": 1,\n    \"fecha_ingreso\": \"2025-10-24 19:49:12\"\n}', '2025-10-25 00:26:25'),
(52, 'admin', 'Productos', 'INSERT', 937392, NULL, '{\"codigo\":\"0937392\",\"nombre\":\"Pisco Mistral 35º\",\"formato\":\"Botella\",\"tamano\":\"1 L\",\"marca\":\"Mistral\",\"cantidad\":20,\"precio_compra\":\"3000\",\"precio_venta\":\"5000\",\"fecha_vencimiento\":null,\"proveedor\":\"Los Angeles\",\"imagen\":\"vistas\\/imagenes\\/productos\\/prod_68fc19549045a.png\"}', '2025-10-25 00:27:00'),
(53, 'admin', 'Stock', 'UPDATE', 19, '{\"id\":19,\"codigo\":\"0937392\",\"nombre\":\"Pisco Mistral 35º\",\"formato\":\"Botella\",\"tamano\":\"1 L\",\"marca\":\"Mistral\",\"cantidad\":30,\"stock_minimo\":0,\"precio_compra\":\"3000.00\",\"precio_venta\":\"5000.00\",\"fecha_recepcion\":\"2025-10-24\",\"proveedor\":\"Los Angeles\",\"fecha_vencimiento\":\"2026-02-01\",\"imagen\":\"vistas\\/imagenes\\/productos\\/prod_68fc19549045a.png\",\"estado\":1,\"fecha_ingreso\":\"2025-10-24 21:27:00\"}', '{\"producto\":\"Pisco Mistral 35º\",\"cantidad_agregada\":\"10\",\"fecha_recepcion\":\"2025-10-24\",\"fecha_vencimiento\":\"2026-02-01\"}', '2025-10-25 00:27:33'),
(54, 'admin', 'Usuarios', 'INSERT', 10, NULL, '{\"nombre\":\"Joaquin Soto\",\"usuario\":\"joaco\",\"rol\":\"Vendedor\",\"password\":\"********\"}', '2025-10-25 00:29:38'),
(55, 'admin', 'Usuarios', 'UPDATE', 2, '{\"id\":2,\"nombre\":\"Matias Caceres\",\"usuario\":\"mati\",\"password\":\"$2a$07$usesomesillystringforeWbVzYlorgmbfLnSWRwHce7G62sVyNeG\",\"rol\":\"Vendedor\"}', '{\"nombre\":{\"antes\":\"Matias Caceres\",\"después\":\"Matias Raul\"},\"rol\":{\"antes\":\"Vendedor\",\"después\":\"Administrador\"}}', '2025-10-25 00:29:50'),
(56, 'admin', 'Usuarios', 'DELETE', 10, '{\"id\":10,\"nombre\":\"Joaquin Soto\",\"usuario\":\"joaco\",\"rol\":\"Vendedor\"}', NULL, '2025-10-25 00:30:02'),
(57, 'admin', 'Usuarios', 'INSERT', 11, NULL, '{\"nombre\":\"Joaquin Soto\",\"usuario\":\"joaco\",\"rol\":\"Vendedor\",\"password\":\"********\"}', '2025-10-25 00:30:26'),
(58, 'admin', 'Usuarios', 'DELETE', 11, '{\"id\":11,\"nombre\":\"Joaquin Soto\",\"usuario\":\"joaco\",\"rol\":\"Vendedor\"}', NULL, '2025-10-25 00:30:49'),
(59, 'admin', 'Usuarios', 'UPDATE', 2, '{\"id\":2,\"nombre\":\"Matias Raul\",\"usuario\":\"mati\",\"password\":\"$2a$07$usesomesillystringforeWbVzYlorgmbfLnSWRwHce7G62sVyNeG\",\"rol\":\"Administrador\"}', '{\"rol\":{\"antes\":\"Administrador\",\"después\":\"Vendedor\"}}', '2025-10-25 00:31:17'),
(60, 'admin', 'Promociones', 'INSERT', 51, NULL, '{\"tipo\":\"2x1\",\"parametro\":\"Sin información\",\"fecha_inicio\":\"2025-10-27 22:04:00\",\"fecha_fin\":\"2025-10-28 22:04:00\",\"productos_asociados\":\"(3) Coca Cola Original\",\"observacion\":\"Sin información\"}', '2025-10-28 01:05:14');

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
(11, '2025-10-03 17:15:49', 9, '87654', 'Whisky Jack Daniel\'s Tennessee ', 1, 'rotura', 'Se cayo de la estanteria', 1),
(12, '2025-10-09 12:11:13', 1, '12345', 'Coca Cola Original', 2, 'perdida', '', 1),
(13, '2025-10-09 12:41:43', 6, '67890', 'Monster Original', 3, 'merma', '', 1),
(14, '2025-10-24 19:53:07', 9, '87654', 'Whisky Jack Daniel\'s Tennessee ', 2, 'perdida', 'se cayo de la estanteria', 1),
(15, '2025-10-24 19:53:53', 6, '67890', 'Monster Original', 2, 'perdida', '', 1),
(16, '2025-10-24 20:10:04', 3, '34567', 'Coca Cola Original', 2, 'consumo_interno', '', 1),
(17, '2025-10-24 21:24:41', 7, '78901', 'Monster Ultra', 1, 'vencimiento', 'esto es una prueba', 1);

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
(1, '12345', 'Coca Cola Original', 'Botella', '3L', 'Coca-cola', 20, 15, 1550.00, 3000.00, '2025-10-24', 'Los Hermanos Torres', '2026-02-19', 'vistas/imagenes/productos/prod_68d22e046f77f.jpg', 0, '2025-09-23 02:19:55'),
(2, '23456', 'Coca Cola Original', 'Lata', '350 ml', 'Coca - cola', 17, 15, 500.00, 1000.00, '2025-10-21', 'Distribuidora Ilusionista', '2026-02-02', 'vistas/imagenes/productos/prod_68d22eacc973b.png', 1, '2025-09-23 02:22:43'),
(3, '34567', 'Coca Cola Original', 'Botella', '1.5 L', 'Coca-cola', 24, 10, 800.00, 1800.00, '2025-10-21', 'Los Hermanos Torres', '2026-01-05', 'vistas/imagenes/productos/prod_68d2339fcd4c7.jpg', 1, '2025-09-23 02:26:06'),
(4, '45678', 'Coca Cola Original', 'Vidrio', '237 ml', 'Coca-cola', 21, 10, 100.00, 600.00, '2025-10-21', 'Los Hermanos Torres', '2025-12-31', 'vistas/imagenes/productos/prod_68d2377a10dea.jpg', 1, '2025-09-23 02:58:44'),
(5, '56789', 'Monster Naranja Ripper', 'Lata', '437 ml', 'Monster', 17, 20, 600.00, 2000.00, '2025-10-21', 'Los Angeles', '2025-12-31', 'vistas/imagenes/productos/prod_68d2384cc4412.jpg', 1, '2025-09-23 03:03:56'),
(6, '67890', 'Monster Original', 'Lata', '437 ml', 'Monster', 17, 15, 600.00, 2000.00, '2025-10-21', 'Los Angeles', '2026-02-05', 'vistas/imagenes/productos/prod_68d23d2f38973.jpg', 1, '2025-09-23 03:06:52'),
(7, '78901', 'Monster Ultra', 'Lata', '437 ml', 'Monster', 23, 10, 600.00, 2000.00, '2025-10-21', 'Los Angeles', '2026-02-11', 'vistas/imagenes/productos/prod_68d23cf9e2776.jpg', 1, '2025-09-23 03:23:53'),
(8, '89012', 'Monster Energy Absolutely Zero', 'Lata', '437 ml', 'Monster', 18, 15, 600.00, 2000.00, '2025-10-21', 'Los Angeles', '2025-12-31', 'vistas/imagenes/productos/prod_68d23e2e1cb6b.jpg', 1, '2025-09-23 03:29:02'),
(9, '87654', 'Whisky Jack Daniel\'s Tennessee ', 'Botella', '750 cc', 'Jack Daniel\'s', 29, 10, 15000.00, 26500.00, '2025-10-21', 'Los Angeles', '2027-02-16', 'vistas/imagenes/productos/prod_68d95d88f2cc8.jpg', 1, '2025-09-28 13:08:40'),
(11, '98536', 'Cerveza Heineken', 'Lata', ' 470 CC', 'Heineken', 18, 10, 500.00, 1400.00, '2025-10-24', 'Los Angeles', '2026-03-19', 'vistas/imagenes/productos/prod_68e02fa3aa786.jpg', 1, '2025-09-28 13:18:30'),
(13, '00001', 'Red Bull Original', 'Lata', '473 ml', 'Red Bull', 22, 15, 1000.00, 2900.00, '2025-10-21', 'Los Angeles', '2025-12-21', 'vistas/imagenes/productos/prod_68e01ff9129e9.jpg', 1, '2025-10-03 15:54:41'),
(14, '00002', 'Red Bull Morada', 'Lata', '250 ml', 'Red Bull', 16, 10, 1000.00, 1800.00, '2025-10-19', 'Los Angeles', '2025-12-31', 'vistas/imagenes/productos/prod_68e02fff1aab5.jpg', 1, '2025-10-03 17:20:15'),
(19, '0937392', 'Pisco Mistral 35º', 'Botella', '1 L', 'Mistral', 30, 0, 3000.00, 5000.00, '2025-10-24', 'Los Angeles', '2026-02-01', 'vistas/imagenes/productos/prod_68fc19549045a.png', 1, '2025-10-24 21:27:00');

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
(23, '2x1', NULL, '2025-10-03 16:43:00', '2025-10-04 16:42:00', NULL, '2025-10-03 16:42:38', 1),
(27, '2x1', NULL, '2025-10-09 11:26:00', '2025-10-10 11:26:00', NULL, '2025-10-09 11:26:27', 1),
(28, 'descuento', 50.00, '2025-10-09 12:40:00', '2025-10-10 12:40:00', NULL, '2025-10-09 12:40:23', 1),
(39, '2x1', NULL, '2025-10-16 14:17:00', '2025-10-17 14:17:00', NULL, '2025-10-16 14:17:13', 1),
(40, 'descuento', 60.00, '2025-10-16 14:17:00', '2025-10-17 14:17:00', NULL, '2025-10-16 14:17:43', 1),
(41, '2x1', NULL, '2025-10-21 12:30:00', '2025-10-22 12:30:00', NULL, '2025-10-21 12:30:22', 1),
(42, 'descuento', 50.00, '2025-10-21 12:30:00', '2025-10-22 12:31:00', NULL, '2025-10-21 12:31:03', 1),
(43, 'precio_fijo', 500.00, '2025-10-21 14:02:00', '2025-10-22 14:02:00', NULL, '2025-10-21 14:02:09', 1),
(44, '2x1', NULL, '2025-10-21 14:06:00', '2025-10-21 14:07:00', NULL, '2025-10-21 14:06:34', 1),
(45, '2x1', NULL, '2025-10-21 14:10:00', '2025-10-21 14:12:00', NULL, '2025-10-21 14:10:32', 1),
(51, '2x1', NULL, '2025-10-27 22:04:00', '2025-10-28 22:04:00', NULL, '2025-10-27 22:05:14', 1);

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
(13, 13, 6),
(20, 20, 5),
(21, 21, 13),
(22, 22, 6),
(30, 27, 7),
(42, 39, 9),
(43, 40, 6),
(44, 41, 5),
(45, 42, 9),
(46, 43, 13),
(47, 44, 14),
(48, 45, 14),
(54, 51, 3);

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
(2, 'Matias Raul', 'mati', '$2a$07$usesomesillystringforeWbVzYlorgmbfLnSWRwHce7G62sVyNeG', 'Vendedor');

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
  `promociones_aplicadas` longtext DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `venta`
--

INSERT INTO `venta` (`id`, `id_cierre_caja`, `id_usuario`, `productos`, `total`, `metodo_pago`, `observacion`, `fecha`, `promociones_aplicadas`) VALUES
(1, NULL, 1, '[{\"id\":11,\"nombre\":\"Cerveza Heineken\",\"cantidad\":1,\"precio_base\":1000,\"precio_final\":1000,\"total_linea\":1000,\"promocion\":null},{\"id\":2,\"nombre\":\"Coca Cola Original\",\"cantidad\":3,\"precio_base\":1000,\"precio_final\":1000,\"total_linea\":3000,\"promocion\":null},{\"id\":3,\"nombre\":\"Coca Cola Original\",\"cantidad\":2,\"precio_base\":1800,\"precio_final\":1800,\"total_linea\":3600,\"promocion\":null}]', 7600.00, 'Tarjeta', '', '2025-10-13 15:05:49', '[]'),
(2, NULL, 1, '[{\"id\":9,\"nombre\":\"Whisky Jack Daniel\'s Tennessee \",\"cantidad\":1,\"precio_base\":26500,\"precio_final\":26500,\"total_linea\":26500,\"promocion\":null},{\"id\":13,\"nombre\":\"Red Bull Original\",\"cantidad\":1,\"precio_base\":2900,\"precio_final\":2900,\"total_linea\":2900,\"promocion\":null},{\"id\":3,\"nombre\":\"Coca Cola Original\",\"cantidad\":1,\"precio_base\":1800,\"precio_final\":1800,\"total_linea\":1800,\"promocion\":null}]', 31200.00, 'Tarjeta', '', '2025-10-13 15:05:58', '[]'),
(3, NULL, 1, '[{\"id\":11,\"nombre\":\"Cerveza Heineken\",\"cantidad\":1,\"precio_base\":1000,\"precio_final\":1000,\"total_linea\":1000,\"promocion\":null},{\"id\":2,\"nombre\":\"Coca Cola Original\",\"cantidad\":2,\"precio_base\":1000,\"precio_final\":1000,\"total_linea\":2000,\"promocion\":null},{\"id\":3,\"nombre\":\"Coca Cola Original\",\"cantidad\":3,\"precio_base\":1800,\"precio_final\":1800,\"total_linea\":5400,\"promocion\":null}]', 8400.00, 'Efectivo', '', '2025-10-13 15:06:18', '[]'),
(4, NULL, 1, '[{\"id\":8,\"nombre\":\"Monster Energy Absolutely Zero\",\"cantidad\":1,\"precio_base\":2000,\"precio_final\":2000,\"total_linea\":2000,\"promocion\":null},{\"id\":5,\"nombre\":\"Monster Naranja Ripper\",\"cantidad\":2,\"precio_base\":2000,\"precio_final\":2000,\"total_linea\":4000,\"promocion\":null},{\"id\":6,\"nombre\":\"Monster Original\",\"cantidad\":1,\"precio_base\":2000,\"precio_final\":2000,\"total_linea\":2000,\"promocion\":null}]', 8000.00, 'Tarjeta', '', '2025-10-13 15:06:40', '[]'),
(5, NULL, 1, '[{\"id\":6,\"nombre\":\"Monster Original\",\"cantidad\":1,\"precio_base\":2000,\"precio_final\":2000,\"total_linea\":2000,\"promocion\":null},{\"id\":11,\"nombre\":\"Cerveza Heineken\",\"cantidad\":9,\"precio_base\":1000,\"precio_final\":1000,\"total_linea\":9000,\"promocion\":null}]', 11000.00, 'Tarjeta', '', '2025-10-13 15:06:52', '[]'),
(6, NULL, 1, '[{\"id\":4,\"nombre\":\"Coca Cola Original\",\"cantidad\":1,\"precio_base\":600,\"precio_final\":600,\"total_linea\":600,\"promocion\":null},{\"id\":3,\"nombre\":\"Coca Cola Original\",\"cantidad\":1,\"precio_base\":1800,\"precio_final\":1800,\"total_linea\":1800,\"promocion\":null}]', 2400.00, 'Efectivo', '', '2025-10-13 15:06:59', '[]'),
(7, NULL, 1, '[{\"id\":9,\"nombre\":\"Whisky Jack Daniel\'s Tennessee \",\"cantidad\":1,\"precio_base\":26500,\"precio_final\":26500,\"total_linea\":26500,\"promocion\":null},{\"id\":13,\"nombre\":\"Red Bull Original\",\"cantidad\":1,\"precio_base\":2900,\"precio_final\":2900,\"total_linea\":2900,\"promocion\":null}]', 29400.00, 'Tarjeta', '', '2025-10-13 15:07:11', '[]'),
(8, NULL, 1, '[{\"id\":7,\"nombre\":\"Monster Ultra\",\"cantidad\":3,\"precio_base\":2000,\"precio_final\":2000,\"total_linea\":6000,\"promocion\":null}]', 6000.00, 'Tarjeta', '', '2025-10-14 15:12:49', '[]'),
(9, NULL, 1, '[{\"id\":9,\"nombre\":\"Whisky Jack Daniel\'s Tennessee \",\"cantidad\":2,\"precio_base\":26500,\"precio_final\":26500,\"total_linea\":53000,\"promocion\":null},{\"id\":14,\"nombre\":\"Red Bull Morada\",\"cantidad\":1,\"precio_base\":1800,\"precio_final\":1800,\"total_linea\":1800,\"promocion\":null}]', 54800.00, 'Tarjeta', '', '2025-10-14 15:13:03', '[]'),
(10, NULL, 1, '[{\"id\":6,\"nombre\":\"Monster Original\",\"cantidad\":5,\"precio_base\":2000,\"precio_final\":2000,\"total_linea\":10000,\"promocion\":null}]', 10000.00, 'Efectivo', '', '2025-10-14 15:13:16', '[]'),
(11, NULL, 1, '[{\"id\":7,\"nombre\":\"Monster Ultra\",\"cantidad\":4,\"precio_base\":2000,\"precio_final\":2000,\"total_linea\":8000,\"promocion\":null},{\"id\":9,\"nombre\":\"Whisky Jack Daniel\'s Tennessee \",\"cantidad\":1,\"precio_base\":26500,\"precio_final\":26500,\"total_linea\":26500,\"promocion\":null},{\"id\":13,\"nombre\":\"Red Bull Original\",\"cantidad\":1,\"precio_base\":2900,\"precio_final\":2900,\"total_linea\":2900,\"promocion\":null},{\"id\":6,\"nombre\":\"Monster Original\",\"cantidad\":1,\"precio_base\":2000,\"precio_final\":2000,\"total_linea\":2000,\"promocion\":null}]', 39400.00, 'Efectivo', '', '2025-10-14 15:15:44', '[]'),
(12, NULL, 1, '[{\"id\":8,\"nombre\":\"Monster Energy Absolutely Zero\",\"cantidad\":3,\"precio_base\":2000,\"precio_final\":2000,\"total_linea\":6000,\"promocion\":null},{\"id\":13,\"nombre\":\"Red Bull Original\",\"cantidad\":4,\"precio_base\":2900,\"precio_final\":2900,\"total_linea\":11600,\"promocion\":null},{\"id\":9,\"nombre\":\"Whisky Jack Daniel\'s Tennessee \",\"cantidad\":1,\"precio_base\":26500,\"precio_final\":26500,\"total_linea\":26500,\"promocion\":null},{\"id\":14,\"nombre\":\"Red Bull Morada\",\"cantidad\":4,\"precio_base\":1800,\"precio_final\":1800,\"total_linea\":7200,\"promocion\":null}]', 51300.00, 'Tarjeta', '', '2025-10-14 15:16:12', '[]'),
(13, NULL, 1, '[{\"id\":11,\"nombre\":\"Cerveza Heineken\",\"cantidad\":1,\"precio_base\":1000,\"precio_final\":1000,\"total_linea\":1000,\"promocion\":null},{\"id\":2,\"nombre\":\"Coca Cola Original\",\"cantidad\":2,\"precio_base\":1000,\"precio_final\":1000,\"total_linea\":2000,\"promocion\":null},{\"id\":3,\"nombre\":\"Coca Cola Original\",\"cantidad\":2,\"precio_base\":1800,\"precio_final\":1800,\"total_linea\":3600,\"promocion\":null},{\"id\":4,\"nombre\":\"Coca Cola Original\",\"cantidad\":1,\"precio_base\":600,\"precio_final\":600,\"total_linea\":600,\"promocion\":null},{\"id\":8,\"nombre\":\"Monster Energy Absolutely Zero\",\"cantidad\":2,\"precio_base\":2000,\"precio_final\":2000,\"total_linea\":4000,\"promocion\":null}]', 11200.00, 'Efectivo', '', '2025-10-15 15:20:11', '[]'),
(14, NULL, 1, '[{\"id\":6,\"nombre\":\"Monster Original\",\"cantidad\":2,\"precio_base\":2000,\"precio_final\":2000,\"total_linea\":4000,\"promocion\":null},{\"id\":7,\"nombre\":\"Monster Ultra\",\"cantidad\":2,\"precio_base\":2000,\"precio_final\":2000,\"total_linea\":4000,\"promocion\":null}]', 8000.00, 'Tarjeta', '', '2025-10-15 15:20:21', '[]'),
(15, NULL, 1, '[{\"id\":9,\"nombre\":\"Whisky Jack Daniel\'s Tennessee \",\"cantidad\":3,\"precio_base\":26500,\"precio_final\":26500,\"total_linea\":79500,\"promocion\":null},{\"id\":13,\"nombre\":\"Red Bull Original\",\"cantidad\":5,\"precio_base\":2900,\"precio_final\":2900,\"total_linea\":14500,\"promocion\":null}]', 94000.00, 'Tarjeta', '', '2025-10-15 15:20:35', '[]'),
(16, NULL, 1, '[{\"id\":3,\"nombre\":\"Coca Cola Original\",\"cantidad\":4,\"precio_base\":1800,\"precio_final\":1800,\"total_linea\":7200,\"promocion\":null}]', 7200.00, 'Efectivo', '', '2025-10-15 15:20:55', '[]'),
(17, NULL, 1, '[{\"id\":13,\"nombre\":\"Red Bull Original\",\"cantidad\":3,\"precio_base\":2900,\"precio_final\":2900,\"total_linea\":8700,\"promocion\":null},{\"id\":9,\"nombre\":\"Whisky Jack Daniel\'s Tennessee \",\"cantidad\":3,\"precio_base\":26500,\"precio_final\":26500,\"total_linea\":79500,\"promocion\":null}]', 88200.00, 'Efectivo', '', '2025-10-15 15:21:21', '[]'),
(22, NULL, 1, '[{\"id\":6,\"nombre\":\"Monster Original\",\"cantidad\":4,\"precio_base\":2000,\"precio_final\":800,\"total_linea\":3200,\"promocion\":{\"id_promocion\":40,\"tipo\":\"descuento\",\"parametro\":60,\"etiqueta\":\"-60%\",\"detalle\":\"Descuento de 60% aplicado\",\"observacion\":\"\",\"precio_unit_base\":2000,\"precio_unit_final\":800,\"paga_unidades\":4,\"gratis\":0}}]', 3200.00, 'Tarjeta', '', '2025-10-16 15:20:59', '[{\"id_producto\":6,\"nombre\":\"Monster Original\",\"id_promocion\":40,\"tipo\":\"descuento\",\"parametro\":60,\"etiqueta\":\"-60%\",\"detalle\":\"Descuento de 60% aplicado\",\"observacion\":\"\",\"precio_base\":2000,\"precio_final\":800,\"cantidad\":4,\"paga_unidades\":4,\"gratis\":0,\"total_linea\":3200}]'),
(23, NULL, 1, '[{\"id\":11,\"nombre\":\"Cerveza Heineken\",\"cantidad\":3,\"precio_base\":1400,\"precio_final\":1400,\"total_linea\":4200,\"promocion\":null}]', 4200.00, 'Tarjeta', '', '2025-10-16 15:47:12', '[]'),
(24, NULL, 1, '[{\"id\":9,\"nombre\":\"Whisky Jack Daniel\'s Tennessee \",\"cantidad\":3,\"precio_base\":26500,\"precio_final\":26500,\"total_linea\":53000,\"promocion\":{\"id_promocion\":39,\"tipo\":\"2x1\",\"parametro\":0,\"etiqueta\":\"2x1\",\"detalle\":\"Promo 2x1: 1 unidad(es) gratis\",\"observacion\":\"\",\"precio_unit_base\":26500,\"precio_unit_final\":26500,\"paga_unidades\":2,\"gratis\":1}}]', 53000.00, 'Tarjeta', '', '2025-10-16 15:47:23', '[{\"id_producto\":9,\"nombre\":\"Whisky Jack Daniel\'s Tennessee \",\"id_promocion\":39,\"tipo\":\"2x1\",\"parametro\":0,\"etiqueta\":\"2x1\",\"detalle\":\"Promo 2x1: 1 unidad(es) gratis\",\"observacion\":\"\",\"precio_base\":26500,\"precio_final\":26500,\"cantidad\":3,\"paga_unidades\":2,\"gratis\":1,\"total_linea\":53000}]'),
(25, NULL, 1, '[{\"id\":6,\"nombre\":\"Monster Original\",\"cantidad\":1,\"precio_base\":2000,\"precio_final\":800,\"total_linea\":800,\"promocion\":{\"id_promocion\":40,\"tipo\":\"descuento\",\"parametro\":60,\"etiqueta\":\"-60%\",\"detalle\":\"Descuento de 60% aplicado\",\"observacion\":\"\",\"precio_unit_base\":2000,\"precio_unit_final\":800,\"paga_unidades\":1,\"gratis\":0}},{\"id\":7,\"nombre\":\"Monster Ultra\",\"cantidad\":1,\"precio_base\":2000,\"precio_final\":2000,\"total_linea\":2000,\"promocion\":null},{\"id\":8,\"nombre\":\"Monster Energy Absolutely Zero\",\"cantidad\":1,\"precio_base\":2000,\"precio_final\":2000,\"total_linea\":2000,\"promocion\":null}]', 4800.00, 'Efectivo', '', '2025-10-16 15:47:33', '[{\"id_producto\":6,\"nombre\":\"Monster Original\",\"id_promocion\":40,\"tipo\":\"descuento\",\"parametro\":60,\"etiqueta\":\"-60%\",\"detalle\":\"Descuento de 60% aplicado\",\"observacion\":\"\",\"precio_base\":2000,\"precio_final\":800,\"cantidad\":1,\"paga_unidades\":1,\"gratis\":0,\"total_linea\":800}]'),
(26, NULL, 1, '[{\"id\":4,\"nombre\":\"Coca Cola Original\",\"cantidad\":1,\"precio_base\":600,\"precio_final\":600,\"total_linea\":600,\"promocion\":null},{\"id\":3,\"nombre\":\"Coca Cola Original\",\"cantidad\":1,\"precio_base\":1800,\"precio_final\":1800,\"total_linea\":1800,\"promocion\":null}]', 2400.00, 'Tarjeta', '', '2025-10-16 15:52:32', '[]'),
(27, NULL, 1, '[{\"id\":7,\"nombre\":\"Monster Ultra\",\"cantidad\":3,\"precio_base\":2000,\"precio_final\":2000,\"total_linea\":6000,\"promocion\":null}]', 6000.00, 'Tarjeta', '', '2025-10-16 16:13:17', '[]'),
(28, NULL, 1, '[{\"id\":8,\"nombre\":\"Monster Energy Absolutely Zero\",\"cantidad\":3,\"precio_base\":2000,\"precio_final\":2000,\"total_linea\":6000,\"promocion\":null},{\"id\":4,\"nombre\":\"Coca Cola Original\",\"cantidad\":1,\"precio_base\":600,\"precio_final\":600,\"total_linea\":600,\"promocion\":null},{\"id\":3,\"nombre\":\"Coca Cola Original\",\"cantidad\":2,\"precio_base\":1800,\"precio_final\":1800,\"total_linea\":3600,\"promocion\":null}]', 10200.00, 'Tarjeta', '', '2025-10-16 16:19:20', '[]'),
(29, NULL, 1, '[{\"id\":13,\"nombre\":\"Red Bull Original\",\"cantidad\":4,\"precio_base\":2900,\"precio_final\":2900,\"total_linea\":11600,\"promocion\":null}]', 11600.00, 'Efectivo', '', '2025-10-16 16:19:47', '[]'),
(30, NULL, 1, '[{\"id\":3,\"nombre\":\"Coca Cola Original\",\"cantidad\":5,\"precio_base\":1800,\"precio_final\":1800,\"total_linea\":9000,\"promocion\":null}]', 9000.00, 'Tarjeta', '', '2025-10-19 16:23:28', '[]'),
(31, NULL, 1, '[{\"id\":6,\"nombre\":\"Monster Original\",\"cantidad\":3,\"precio_base\":2000,\"precio_final\":2000,\"total_linea\":6000,\"promocion\":null}]', 6000.00, 'Tarjeta', '', '2025-10-19 16:45:15', '[]'),
(32, NULL, 1, '[{\"id\":11,\"nombre\":\"Cerveza Heineken\",\"cantidad\":3,\"precio_base\":1400,\"precio_final\":1400,\"total_linea\":4200,\"promocion\":null}]', 4200.00, 'Tarjeta', '', '2025-10-20 14:11:55', '[]'),
(33, NULL, 1, '[{\"id\":6,\"nombre\":\"Monster Original\",\"cantidad\":1,\"precio_base\":2000,\"precio_final\":2000,\"total_linea\":2000,\"promocion\":null},{\"id\":7,\"nombre\":\"Monster Ultra\",\"cantidad\":2,\"precio_base\":2000,\"precio_final\":2000,\"total_linea\":4000,\"promocion\":null},{\"id\":8,\"nombre\":\"Monster Energy Absolutely Zero\",\"cantidad\":1,\"precio_base\":2000,\"precio_final\":2000,\"total_linea\":2000,\"promocion\":null}]', 8000.00, 'Efectivo', '', '2025-10-20 14:12:07', '[]'),
(34, NULL, 1, '[{\"id\":11,\"nombre\":\"Cerveza Heineken\",\"cantidad\":2,\"precio_base\":1400,\"precio_final\":1400,\"total_linea\":2800,\"promocion\":null},{\"id\":1,\"nombre\":\"Coca Cola Original\",\"cantidad\":3,\"precio_base\":3000,\"precio_final\":3000,\"total_linea\":9000,\"promocion\":null},{\"id\":2,\"nombre\":\"Coca Cola Original\",\"cantidad\":2,\"precio_base\":1000,\"precio_final\":1000,\"total_linea\":2000,\"promocion\":null}]', 13800.00, 'Tarjeta', '', '2025-10-21 12:29:39', '[]'),
(35, NULL, 1, '[{\"id\":6,\"nombre\":\"Monster Original\",\"cantidad\":2,\"precio_base\":2000,\"precio_final\":2000,\"total_linea\":4000,\"promocion\":null},{\"id\":5,\"nombre\":\"Monster Naranja Ripper\",\"cantidad\":3,\"precio_base\":2000,\"precio_final\":2000,\"total_linea\":6000,\"promocion\":null}]', 10000.00, 'Tarjeta', '', '2025-10-21 12:29:50', '[]'),
(36, NULL, 1, '[{\"id\":9,\"nombre\":\"Whisky Jack Daniel\'s Tennessee \",\"cantidad\":3,\"precio_base\":26500,\"precio_final\":26500,\"total_linea\":79500,\"promocion\":null},{\"id\":7,\"nombre\":\"Monster Ultra\",\"cantidad\":1,\"precio_base\":2000,\"precio_final\":2000,\"total_linea\":2000,\"promocion\":null},{\"id\":14,\"nombre\":\"Red Bull Morada\",\"cantidad\":1,\"precio_base\":1800,\"precio_final\":1800,\"total_linea\":1800,\"promocion\":null}]', 83300.00, 'Tarjeta', '', '2025-10-21 12:30:01', '[]'),
(37, NULL, 1, '[{\"id\":2,\"nombre\":\"Coca Cola Original\",\"cantidad\":3,\"precio_base\":1000,\"precio_final\":1000,\"total_linea\":3000,\"promocion\":null}]', 3000.00, 'Efectivo', '', '2025-10-21 12:30:10', '[]'),
(38, NULL, 1, '[{\"id\":9,\"nombre\":\"Whisky Jack Daniel\'s Tennessee \",\"cantidad\":2,\"precio_base\":26500,\"precio_final\":13250,\"total_linea\":26500,\"promocion\":{\"id_promocion\":42,\"tipo\":\"descuento\",\"parametro\":50,\"etiqueta\":\"-50%\",\"detalle\":\"Descuento de 50% aplicado\",\"observacion\":\"\",\"precio_unit_base\":26500,\"precio_unit_final\":13250,\"paga_unidades\":2,\"gratis\":0}}]', 26500.00, 'Tarjeta', '', '2025-10-21 12:31:14', '[{\"id_producto\":9,\"nombre\":\"Whisky Jack Daniel\'s Tennessee \",\"id_promocion\":42,\"tipo\":\"descuento\",\"parametro\":50,\"etiqueta\":\"-50%\",\"detalle\":\"Descuento de 50% aplicado\",\"observacion\":\"\",\"precio_base\":26500,\"precio_final\":13250,\"cantidad\":2,\"paga_unidades\":2,\"gratis\":0,\"total_linea\":26500}]'),
(39, NULL, 1, '[{\"id\":1,\"nombre\":\"Coca Cola Original\",\"cantidad\":1,\"precio_base\":3000,\"precio_final\":3000,\"total_linea\":3000,\"promocion\":null},{\"id\":2,\"nombre\":\"Coca Cola Original\",\"cantidad\":1,\"precio_base\":1000,\"precio_final\":1000,\"total_linea\":1000,\"promocion\":null}]', 4000.00, 'Tarjeta', 'Esto es una prueba', '2025-10-21 13:37:58', '[]'),
(40, NULL, 1, '[{\"id\":11,\"nombre\":\"Cerveza Heineken\",\"cantidad\":1,\"precio_base\":1400,\"precio_final\":1400,\"total_linea\":1400,\"promocion\":null}]', 1400.00, 'Tarjeta', '', '2025-10-21 13:40:14', '[]'),
(41, NULL, 1, '[{\"id\":1,\"nombre\":\"Coca Cola Original\",\"cantidad\":4,\"precio_base\":3000,\"precio_final\":3000,\"total_linea\":12000,\"promocion\":null}]', 12000.00, 'Efectivo', '', '2025-10-21 13:54:42', '[]'),
(42, NULL, 1, '[{\"id\":11,\"nombre\":\"Cerveza Heineken\",\"cantidad\":9,\"precio_base\":1400,\"precio_final\":1400,\"total_linea\":12600,\"promocion\":null}]', 12600.00, 'Tarjeta', '', '2025-10-21 13:55:18', '[]'),
(43, NULL, 1, '[{\"id\":1,\"nombre\":\"Coca Cola Original\",\"cantidad\":1,\"precio_base\":3000,\"precio_final\":3000,\"total_linea\":3000,\"promocion\":null}]', 3000.00, 'Tarjeta', '', '2025-10-21 14:45:07', '[]'),
(49, NULL, 1, '[{\"id\":1,\"nombre\":\"Coca Cola Original\",\"cantidad\":3,\"precio_base\":3000,\"precio_final\":3000,\"total_linea\":9000,\"promocion\":null},{\"id\":3,\"nombre\":\"Coca Cola Original\",\"cantidad\":1,\"precio_base\":1800,\"precio_final\":1800,\"total_linea\":1800,\"promocion\":null},{\"id\":4,\"nombre\":\"Coca Cola Original\",\"cantidad\":1,\"precio_base\":600,\"precio_final\":600,\"total_linea\":600,\"promocion\":null},{\"id\":8,\"nombre\":\"Monster Energy Absolutely Zero\",\"cantidad\":1,\"precio_base\":2000,\"precio_final\":2000,\"total_linea\":2000,\"promocion\":null}]', 13400.00, 'Tarjeta', '', '2025-10-22 13:35:34', '[]'),
(50, NULL, 1, '[{\"id\":9,\"nombre\":\"Whisky Jack Daniel\'s Tennessee \",\"cantidad\":4,\"precio_base\":26500,\"precio_final\":26500,\"total_linea\":106000,\"promocion\":null}]', 106000.00, 'Tarjeta', '', '2025-10-22 13:35:40', '[]'),
(51, NULL, 1, '[{\"id\":3,\"nombre\":\"Coca Cola Original\",\"cantidad\":1,\"precio_base\":1800,\"precio_final\":1800,\"total_linea\":1800,\"promocion\":null},{\"id\":4,\"nombre\":\"Coca Cola Original\",\"cantidad\":2,\"precio_base\":600,\"precio_final\":600,\"total_linea\":1200,\"promocion\":null},{\"id\":8,\"nombre\":\"Monster Energy Absolutely Zero\",\"cantidad\":4,\"precio_base\":2000,\"precio_final\":2000,\"total_linea\":8000,\"promocion\":null},{\"id\":13,\"nombre\":\"Red Bull Original\",\"cantidad\":2,\"precio_base\":2900,\"precio_final\":500,\"total_linea\":1000,\"promocion\":{\"id_promocion\":43,\"tipo\":\"precio_fijo\",\"parametro\":500,\"etiqueta\":\"Precio fijo\",\"detalle\":\"Precio fijo promocional $500\",\"observacion\":\"\",\"precio_unit_base\":2900,\"precio_unit_final\":500,\"paga_unidades\":2,\"gratis\":0}}]', 12000.00, 'Efectivo', '', '2025-10-22 13:35:55', '[{\"id_producto\":13,\"nombre\":\"Red Bull Original\",\"id_promocion\":43,\"tipo\":\"precio_fijo\",\"parametro\":500,\"etiqueta\":\"Precio fijo\",\"detalle\":\"Precio fijo promocional $500\",\"observacion\":\"\",\"precio_base\":2900,\"precio_final\":500,\"cantidad\":2,\"paga_unidades\":2,\"gratis\":0,\"total_linea\":1000}]'),
(52, NULL, 1, '[{\"id\":14,\"nombre\":\"Red Bull Morada\",\"cantidad\":1,\"precio_base\":1800,\"precio_final\":1800,\"total_linea\":1800,\"promocion\":null},{\"id\":9,\"nombre\":\"Whisky Jack Daniel\'s Tennessee \",\"cantidad\":1,\"precio_base\":26500,\"precio_final\":26500,\"total_linea\":26500,\"promocion\":null}]', 28300.00, 'Tarjeta', '', '2025-10-22 13:36:01', '[]'),
(53, NULL, 1, '[{\"id\":1,\"nombre\":\"Coca Cola Original\",\"cantidad\":1,\"precio_base\":3000,\"precio_final\":3000,\"total_linea\":3000,\"promocion\":null},{\"id\":2,\"nombre\":\"Coca Cola Original\",\"cantidad\":4,\"precio_base\":1000,\"precio_final\":1000,\"total_linea\":4000,\"promocion\":null}]', 7000.00, 'Efectivo', '', '2025-10-22 13:36:09', '[]'),
(54, NULL, 1, '[{\"id\":1,\"nombre\":\"Coca Cola Original\",\"cantidad\":3,\"precio_base\":3000,\"precio_final\":3000,\"total_linea\":9000,\"promocion\":null}]', 9000.00, 'Tarjeta', '', '2025-10-26 14:50:54', '[]'),
(55, NULL, 1, '[{\"id\":3,\"nombre\":\"Coca Cola Original\",\"cantidad\":1,\"precio_base\":1800,\"precio_final\":1800,\"total_linea\":1800,\"promocion\":null},{\"id\":8,\"nombre\":\"Monster Energy Absolutely Zero\",\"cantidad\":1,\"precio_base\":2000,\"precio_final\":2000,\"total_linea\":2000,\"promocion\":null},{\"id\":5,\"nombre\":\"Monster Naranja Ripper\",\"cantidad\":1,\"precio_base\":2000,\"precio_final\":2000,\"total_linea\":2000,\"promocion\":null},{\"id\":6,\"nombre\":\"Monster Original\",\"cantidad\":1,\"precio_base\":2000,\"precio_final\":2000,\"total_linea\":2000,\"promocion\":null}]', 7800.00, 'Tarjeta', '', '2025-10-26 14:51:00', '[]'),
(56, NULL, 1, '[{\"id\":9,\"nombre\":\"Whisky Jack Daniel\'s Tennessee \",\"cantidad\":3,\"precio_base\":26500,\"precio_final\":26500,\"total_linea\":79500,\"promocion\":null},{\"id\":13,\"nombre\":\"Red Bull Original\",\"cantidad\":1,\"precio_base\":2900,\"precio_final\":2900,\"total_linea\":2900,\"promocion\":null},{\"id\":14,\"nombre\":\"Red Bull Morada\",\"cantidad\":1,\"precio_base\":1800,\"precio_final\":1800,\"total_linea\":1800,\"promocion\":null}]', 84200.00, 'Tarjeta', '', '2025-10-26 14:51:07', '[]'),
(57, NULL, 1, '[{\"id\":2,\"nombre\":\"Coca Cola Original\",\"cantidad\":2,\"precio_base\":1000,\"precio_final\":1000,\"total_linea\":2000,\"promocion\":null},{\"id\":3,\"nombre\":\"Coca Cola Original\",\"cantidad\":1,\"precio_base\":1800,\"precio_final\":1800,\"total_linea\":1800,\"promocion\":null}]', 3800.00, 'Efectivo', '', '2025-10-26 14:51:15', '[]'),
(58, NULL, 1, '[{\"id\":4,\"nombre\":\"Coca Cola Original\",\"cantidad\":1,\"precio_base\":600,\"precio_final\":600,\"total_linea\":600,\"promocion\":null},{\"id\":3,\"nombre\":\"Coca Cola Original\",\"cantidad\":1,\"precio_base\":1800,\"precio_final\":1800,\"total_linea\":1800,\"promocion\":null},{\"id\":2,\"nombre\":\"Coca Cola Original\",\"cantidad\":1,\"precio_base\":1000,\"precio_final\":1000,\"total_linea\":1000,\"promocion\":null},{\"id\":1,\"nombre\":\"Coca Cola Original\",\"cantidad\":1,\"precio_base\":3000,\"precio_final\":3000,\"total_linea\":3000,\"promocion\":null}]', 6400.00, 'Efectivo', '', '2025-10-26 14:51:23', '[]'),
(59, NULL, 1, '[{\"id\":2,\"nombre\":\"Coca Cola Original\",\"cantidad\":1,\"precio_base\":1000,\"precio_final\":1000,\"total_linea\":1000,\"promocion\":null},{\"id\":3,\"nombre\":\"Coca Cola Original\",\"cantidad\":1,\"precio_base\":1800,\"precio_final\":1800,\"total_linea\":1800,\"promocion\":null}]', 2800.00, 'Efectivo', '', '2025-10-24 19:13:34', '[]'),
(60, NULL, 1, '[{\"id\":14,\"nombre\":\"Red Bull Morada\",\"cantidad\":2,\"precio_base\":1800,\"precio_final\":1800,\"total_linea\":3600,\"promocion\":null}]', 3600.00, 'Tarjeta', '', '2025-10-24 19:21:46', '[]'),
(61, NULL, 1, '[{\"id\":7,\"nombre\":\"Monster Ultra\",\"cantidad\":1,\"precio_base\":2000,\"precio_final\":2000,\"total_linea\":2000,\"promocion\":null}]', 2000.00, 'Tarjeta', 'esto es una prueba', '2025-10-24 19:23:01', '[]'),
(62, NULL, 1, '[{\"id\":2,\"nombre\":\"Coca Cola Original\",\"cantidad\":1,\"precio_base\":1000,\"precio_final\":1000,\"total_linea\":1000,\"promocion\":null}]', 1000.00, 'Tarjeta', '', '2025-10-24 19:23:37', '[]'),
(63, NULL, 1, '[{\"id\":6,\"nombre\":\"Monster Original\",\"cantidad\":1,\"precio_base\":2000,\"precio_final\":2000,\"total_linea\":2000,\"promocion\":null},{\"id\":5,\"nombre\":\"Monster Naranja Ripper\",\"cantidad\":1,\"precio_base\":2000,\"precio_final\":2000,\"total_linea\":2000,\"promocion\":null}]', 4000.00, 'Tarjeta', '', '2025-10-24 19:25:28', '[]'),
(64, NULL, 1, '[{\"id\":9,\"nombre\":\"Whisky Jack Daniel\'s Tennessee \",\"cantidad\":1,\"precio_base\":26500,\"precio_final\":26500,\"total_linea\":26500,\"promocion\":null}]', 26500.00, 'Efectivo', '', '2025-10-24 19:36:49', '[]'),
(65, NULL, 1, '[{\"id\":5,\"nombre\":\"Monster Naranja Ripper\",\"cantidad\":4,\"precio_base\":2000,\"precio_final\":1000,\"total_linea\":4000,\"promocion\":{\"id_promocion\":47,\"tipo\":\"descuento\",\"parametro\":50,\"etiqueta\":\"-50%\",\"detalle\":\"Descuento de 50% aplicado\",\"observacion\":\"\",\"precio_unit_base\":2000,\"precio_unit_final\":1000,\"paga_unidades\":4,\"gratis\":0}}]', 4000.00, 'Tarjeta', '', '2025-10-24 20:24:31', '[{\"id_producto\":5,\"nombre\":\"Monster Naranja Ripper\",\"id_promocion\":47,\"tipo\":\"descuento\",\"parametro\":50,\"etiqueta\":\"-50%\",\"detalle\":\"Descuento de 50% aplicado\",\"observacion\":\"\",\"precio_base\":2000,\"precio_final\":1000,\"cantidad\":4,\"paga_unidades\":4,\"gratis\":0,\"total_linea\":4000}]'),
(66, NULL, 1, '[{\"id\":2,\"nombre\":\"Coca Cola Original\",\"cantidad\":1,\"precio_base\":1000,\"precio_final\":1000,\"total_linea\":1000,\"promocion\":null}]', 1000.00, 'Efectivo', '', '2025-10-24 20:27:16', '[]'),
(67, NULL, 1, '[{\"id\":2,\"nombre\":\"Coca Cola Original\",\"cantidad\":1,\"precio_base\":1000,\"precio_final\":1000,\"total_linea\":1000,\"promocion\":null}]', 1000.00, 'Efectivo', '', '2025-10-24 20:30:27', '[]'),
(68, NULL, 1, '[{\"id\":8,\"nombre\":\"Monster Energy Absolutely Zero\",\"cantidad\":1,\"precio_base\":2000,\"precio_final\":2000,\"total_linea\":2000,\"promocion\":null},{\"id\":4,\"nombre\":\"Coca Cola Original\",\"cantidad\":1,\"precio_base\":600,\"precio_final\":600,\"total_linea\":600,\"promocion\":null}]', 2600.00, 'Tarjeta', '', '2025-10-24 21:17:38', '[]'),
(69, NULL, 1, '[{\"id\":18,\"nombre\":\"Pisco Mistral 35\",\"cantidad\":2,\"precio_base\":5000,\"precio_final\":5000,\"total_linea\":5000,\"promocion\":{\"id_promocion\":50,\"tipo\":\"2x1\",\"parametro\":0,\"etiqueta\":\"2x1\",\"detalle\":\"Promo 2x1: 1 unidad(es) gratis\",\"observacion\":\"\",\"precio_unit_base\":5000,\"precio_unit_final\":5000,\"paga_unidades\":1,\"gratis\":1}}]', 5000.00, 'Efectivo', '', '2025-10-24 21:19:51', '[{\"id_producto\":18,\"nombre\":\"Pisco Mistral 35\",\"id_promocion\":50,\"tipo\":\"2x1\",\"parametro\":0,\"etiqueta\":\"2x1\",\"detalle\":\"Promo 2x1: 1 unidad(es) gratis\",\"observacion\":\"\",\"precio_base\":5000,\"precio_final\":5000,\"cantidad\":2,\"paga_unidades\":1,\"gratis\":1,\"total_linea\":5000}]'),
(70, NULL, 1, '[{\"id\":11,\"nombre\":\"Cerveza Heineken\",\"cantidad\":1,\"precio_base\":1400,\"precio_final\":1400,\"total_linea\":1400,\"promocion\":null},{\"id\":2,\"nombre\":\"Coca Cola Original\",\"cantidad\":1,\"precio_base\":1000,\"precio_final\":1000,\"total_linea\":1000,\"promocion\":null}]', 2400.00, 'Efectivo', '', '2025-10-27 22:04:45', '[]'),
(71, NULL, 1, '[{\"id\":7,\"nombre\":\"Monster Ultra\",\"cantidad\":1,\"precio_base\":2000,\"precio_final\":2000,\"total_linea\":2000,\"promocion\":null},{\"id\":6,\"nombre\":\"Monster Original\",\"cantidad\":1,\"precio_base\":2000,\"precio_final\":2000,\"total_linea\":2000,\"promocion\":null}]', 4000.00, 'Tarjeta', '', '2025-10-27 22:04:52', '[]'),
(72, NULL, 1, '[{\"id\":3,\"nombre\":\"Coca Cola Original\",\"cantidad\":2,\"precio_base\":1800,\"precio_final\":1800,\"total_linea\":1800,\"promocion\":{\"id_promocion\":51,\"tipo\":\"2x1\",\"parametro\":0,\"etiqueta\":\"2x1\",\"detalle\":\"Promo 2x1: 1 unidad(es) gratis\",\"observacion\":\"\",\"precio_unit_base\":1800,\"precio_unit_final\":1800,\"paga_unidades\":1,\"gratis\":1}}]', 1800.00, 'Tarjeta', '', '2025-10-27 22:05:24', '[{\"id_producto\":3,\"nombre\":\"Coca Cola Original\",\"id_promocion\":51,\"tipo\":\"2x1\",\"parametro\":0,\"etiqueta\":\"2x1\",\"detalle\":\"Promo 2x1: 1 unidad(es) gratis\",\"observacion\":\"\",\"precio_base\":1800,\"precio_final\":1800,\"cantidad\":2,\"paga_unidades\":1,\"gratis\":1,\"total_linea\":1800}]'),
(73, NULL, 1, '[{\"id\":9,\"nombre\":\"Whisky Jack Daniel\'s Tennessee \",\"cantidad\":4,\"precio_base\":26500,\"precio_final\":26500,\"total_linea\":106000,\"promocion\":null}]', 106000.00, 'Tarjeta', '', '2025-10-27 22:05:29', '[]'),
(74, NULL, 1, '[{\"id\":11,\"nombre\":\"Cerveza Heineken\",\"cantidad\":1,\"precio_base\":1400,\"precio_final\":1400,\"total_linea\":1400,\"promocion\":null},{\"id\":2,\"nombre\":\"Coca Cola Original\",\"cantidad\":1,\"precio_base\":1000,\"precio_final\":1000,\"total_linea\":1000,\"promocion\":null}]', 2400.00, 'Efectivo', '', '2025-10-24 13:42:18', '[]');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `cierre_caja`
--
ALTER TABLE `cierre_caja`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `historial_cambio`
--
ALTER TABLE `historial_cambio`
  ADD PRIMARY KEY (`id`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de la tabla `historial_cambio`
--
ALTER TABLE `historial_cambio`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT de la tabla `perdida`
--
ALTER TABLE `perdida`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT de la tabla `producto`
--
ALTER TABLE `producto`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT de la tabla `promocion`
--
ALTER TABLE `promocion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT de la tabla `promocion_producto`
--
ALTER TABLE `promocion_producto`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `venta`
--
ALTER TABLE `venta`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=75;

--
-- Restricciones para tablas volcadas
--

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
