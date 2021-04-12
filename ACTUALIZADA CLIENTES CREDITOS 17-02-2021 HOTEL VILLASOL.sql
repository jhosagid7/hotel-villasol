-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Feb 21, 2021 at 03:47 PM
-- Server version: 5.7.24
-- PHP Version: 7.2.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `hotel`
--

-- --------------------------------------------------------

--
-- Table structure for table `articulos`
--

CREATE TABLE `articulos` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `categoria_id` bigint(20) UNSIGNED NOT NULL,
  `codigo` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nombre` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `stock` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `precio_costo` decimal(25,9) DEFAULT NULL,
  `porEspecial` decimal(25,2) DEFAULT NULL,
  `isDolar` int(10) UNSIGNED DEFAULT NULL,
  `isPeso` int(10) UNSIGNED DEFAULT NULL,
  `isTransPunto` int(10) UNSIGNED DEFAULT NULL,
  `isMixto` int(10) UNSIGNED DEFAULT NULL,
  `isEfectivo` int(10) UNSIGNED DEFAULT NULL,
  `isKilo` int(10) UNSIGNED DEFAULT NULL,
  `descripcion` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `unidades` int(10) UNSIGNED DEFAULT NULL,
  `vender_al` enum('Mayor','Detal') COLLATE utf8mb4_unicode_ci NOT NULL,
  `imagen` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `estado` enum('Activo','Inactivo','Eliminado') COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `articulos`
--

INSERT INTO `articulos` (`id`, `categoria_id`, `codigo`, `nombre`, `stock`, `precio_costo`, `porEspecial`, `isDolar`, `isPeso`, `isTransPunto`, `isMixto`, `isEfectivo`, `isKilo`, `descripcion`, `unidades`, `vender_al`, `imagen`, `estado`, `created_at`, `updated_at`) VALUES
(1, 2, '7591206000381', 'CHICHARRON GRANDE', '3', '1.800000000', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'CHICHARRON GRANDE', 1, 'Detal', 'articulodefault.jpg', 'Activo', '2020-12-25 22:39:59', '2021-02-17 20:02:04'),
(2, 2, '7591016851197', 'CHOCOLATE GRANDE', '1', '1.860000000', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'CHOCOLATE GRANDE', 1, 'Detal', 'articulodefault.jpg', 'Activo', '2021-01-13 18:37:15', '2021-02-17 20:02:04'),
(3, 2, '7591016871089', 'COCOSETTE', '0', '0.840000000', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'COCOSETTE', 1, 'Detal', 'articulodefault.jpg', 'Activo', '2021-01-13 18:38:50', '2021-01-14 13:39:53'),
(4, 2, '7591016873434', 'SAMBA/COCOSETTE FUDGE', '1', '1.000000000', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'SAMBA/COCOSETTE FUDGE', 1, 'Detal', 'articulodefault.jpg', 'Activo', '2021-01-13 18:40:12', '2021-02-17 20:02:04'),
(5, 2, '7896022207489', 'GALLETA HAPPY', '3', '1.050000000', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'GALLETA HAPPY', 1, 'Detal', 'articulodefault.jpg', 'Activo', '2021-01-13 18:42:11', '2021-02-17 20:02:04'),
(6, 2, '7591206012834', 'MANI', '6', '1.770000000', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'MANI', 1, 'Detal', 'articulodefault.jpg', 'Activo', '2021-01-13 18:44:44', '2021-02-17 20:02:04'),
(7, 2, '7591016855263', 'TORONTO CAJA', '0', '3.300000000', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'TORONTO CAJA', 1, 'Detal', 'articulodefault.jpg', 'Activo', '2021-01-13 18:45:31', '2021-01-14 13:39:53'),
(8, 2, '0001', 'COMBOS PLATO', '3', '0.350000000', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'COMBOS PLATO', 1, 'Detal', 'articulodefault.jpg', 'Activo', '2021-01-13 18:47:07', '2021-02-17 20:02:04'),
(9, 2, '0002', 'TOALLAS SANITARIAS', '10', '0.300000000', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'TOALLAS SANITARIAS', 1, 'Detal', 'articulodefault.jpg', 'Activo', '2021-01-13 18:48:34', '2021-02-17 20:02:04'),
(10, 2, '7591675004781', 'PIRULIN ESTUCHE', '14', '1.860000000', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'PIRULIN ESTUCHE', 1, 'Detal', 'articulodefault.jpg', 'Activo', '2021-01-13 18:49:19', '2021-02-17 20:02:04'),
(11, 2, '7591016854648', 'CHOCO CHOCO', '11', '1.860000000', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'CHOCO CHOCO', 1, 'Detal', 'articulodefault.jpg', 'Activo', '2021-01-13 18:50:03', '2021-02-17 20:02:04'),
(12, 3, '7593843000021', 'AGUA MINERAL 355ML', '105', '0.300000000', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'AGUA MINERAL 355ML', 1, 'Detal', 'articulodefault.jpg', 'Activo', '2021-01-13 18:53:29', '2021-02-17 20:02:04'),
(13, 4, '7592243003366', 'CERVEZA LATA', '211', '1.500000000', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'CERVESA LATA', 1, 'Detal', 'articulodefault.jpg', 'Activo', '2021-01-13 18:55:22', '2021-02-17 20:02:04'),
(14, 3, '7591127104403', 'REFRESCO LATA', '95', '1.330000000', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'REFRESCO LATA', 1, 'Detal', 'articulodefault.jpg', 'Activo', '2021-01-13 18:57:18', '2021-02-17 20:02:04'),
(15, 2, '4005800254963', 'PRESERVATIVO', '32', '1.100000000', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'PRESERVATIVO', 1, 'Detal', 'articulodefault.jpg', 'Activo', '2021-01-13 18:58:17', '2021-02-17 20:02:04'),
(16, 3, '7591031005988', 'GATORADE', '90', '1.300000000', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'GATORADE', 1, 'Detal', 'articulodefault.jpg', 'Activo', '2021-01-13 19:03:57', '2021-02-17 20:02:04'),
(17, 4, '7591073012487', 'RON CACIQUE 500 AÑOS', '3', '20.000000000', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'RON CACIQUE 500 AÑOS', 1, 'Detal', 'articulodefault.jpg', 'Activo', '2021-01-13 19:12:22', '2021-02-17 20:02:04'),
(18, 4, '7591073012357', 'RON CACIQUE DORADO', '3', '13.000000000', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'RON CACIQUE DORADO', 1, 'Detal', 'articulodefault.jpg', 'Activo', '2021-01-13 19:13:01', '2021-02-17 20:02:04'),
(19, 4, '7594003620110', 'CHIMINEADO (0.70 LT)', '1', '7.000000000', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'CHIMINEADO (0.70 LT)', 1, 'Detal', 'articulodefault.jpg', 'Activo', '2021-01-13 19:13:58', '2021-02-17 20:02:04'),
(20, 4, '7594003620097', 'CHIMINEADO (0.35 LT)', '4', '4.000000000', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'CHIMINEADO (0.35 LT)', 1, 'Detal', 'articulodefault.jpg', 'Activo', '2021-01-13 19:14:58', '2021-02-17 20:02:04'),
(21, 4, '7594003622381', 'CHININEADO (0.22 LT)', '3', '2.000000000', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'CHININEADO (0.22 LT)', 1, 'Detal', 'articulodefault.jpg', 'Activo', '2021-01-13 19:15:52', '2021-02-17 20:02:04'),
(22, 4, '7591446001599', 'SANGRIA / VINO ESPUMANTE', '6', '10.000000000', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'SANGRIA / VINO ESPUMANTE', 1, 'Detal', 'articulodefault.jpg', 'Activo', '2021-01-13 19:17:10', '2021-02-17 20:02:04'),
(23, 4, '7596530000175', 'CANCILLER', '4', '8.000000000', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'CANCILLER', 1, 'Detal', 'articulodefault.jpg', 'Activo', '2021-01-13 19:19:10', '2021-02-17 20:02:04'),
(24, 4, '7596530000281', 'MAGISTRAL', '4', '8.000000000', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'MAGISTRAL', 1, 'Detal', 'articulodefault.jpg', 'Activo', '2021-01-13 19:19:46', '2021-02-17 20:02:04'),
(25, 2, '0003', 'CHOCOLATE CARRE', '4', '2.900000000', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'CHOCOLATE CARRE', 1, 'Detal', 'articulodefault.jpg', 'Activo', '2021-01-13 21:03:22', '2021-02-17 20:02:04'),
(26, 2, '0005', 'COPAS JACUZZY', '48', '0.300000000', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'COPAS JACUZZY', 1, 'Detal', 'articulodefault.jpg', 'Activo', '2021-01-19 14:42:38', '2021-02-17 20:02:04'),
(27, 2, '7896022207496', 'GALLETA RENATA WAFFER', '0', '0.980000000', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'GALLETA RENATA WAFFER', 1, 'Detal', 'articulodefault.jpg', 'Activo', '2021-02-05 11:49:20', '2021-02-05 11:52:27'),
(28, 2, '7591016163221', 'BOLERO BOLSA 125G', '5', '2.910000000', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'BOLERO BOLSA 125G', 1, 'Detal', 'articulodefault.jpg', 'Activo', '2021-02-05 19:52:06', '2021-02-17 20:02:04'),
(29, 5, '0006', 'LUBRICANTE ANAL', '0', '0.000000000', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'LUBRICANTE ANAL', 1, 'Detal', 'articulodefault.jpg', 'Activo', '2021-02-17 19:50:13', '2021-02-17 19:50:13'),
(30, 5, '0007', 'MULTIORGASMO', '0', '0.000000000', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'MULTIORGASMO', 1, 'Detal', 'articulodefault.jpg', 'Activo', '2021-02-17 19:50:43', '2021-02-17 19:50:43'),
(31, 5, '0008', 'ESTRECHANTE VAGINAL', '0', '0.000000000', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'ESTRECHANTE VAGINAL', 1, 'Detal', 'articulodefault.jpg', 'Activo', '2021-02-17 19:51:12', '2021-02-17 19:51:12'),
(32, 5, '0009', 'ANILLO VIBRADOR', '0', '0.000000000', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'ANILLO VIBRADOR', 1, 'Detal', 'articulodefault.jpg', 'Activo', '2021-02-17 19:51:33', '2021-02-17 19:51:33'),
(33, 5, '0010', 'RETARDANTE MASCULINO', '0', '0.000000000', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'RETARDANTE MASCULINO', 1, 'Detal', 'articulodefault.jpg', 'Activo', '2021-02-17 19:52:31', '2021-02-17 19:52:31');

-- --------------------------------------------------------

--
-- Table structure for table `articulo_transactions`
--

CREATE TABLE `articulo_transactions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `cantidad` int(10) UNSIGNED DEFAULT NULL,
  `precio_costo_unidad` decimal(25,9) DEFAULT NULL,
  `articulo_id` bigint(20) UNSIGNED NOT NULL,
  `transaction_id` bigint(20) UNSIGNED NOT NULL,
  `observacion` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `articulo_transactions`
--

INSERT INTO `articulo_transactions` (`id`, `cantidad`, `precio_costo_unidad`, `articulo_id`, `transaction_id`, `observacion`, `created_at`, `updated_at`) VALUES
(1, 211, '1.500000000', 13, 1, NULL, '2021-02-17 20:02:04', '2021-02-17 20:02:04'),
(2, 105, '0.300000000', 12, 1, NULL, '2021-02-17 20:02:04', '2021-02-17 20:02:04'),
(3, 90, '1.300000000', 16, 1, NULL, '2021-02-17 20:02:04', '2021-02-17 20:02:04'),
(4, 95, '1.330000000', 14, 1, NULL, '2021-02-17 20:02:04', '2021-02-17 20:02:04'),
(5, 3, '20.000000000', 17, 1, NULL, '2021-02-17 20:02:04', '2021-02-17 20:02:04'),
(6, 3, '13.000000000', 18, 1, NULL, '2021-02-17 20:02:04', '2021-02-17 20:02:04'),
(7, 6, '10.000000000', 22, 1, NULL, '2021-02-17 20:02:04', '2021-02-17 20:02:04'),
(8, 4, '8.000000000', 23, 1, NULL, '2021-02-17 20:02:04', '2021-02-17 20:02:04'),
(9, 4, '8.000000000', 24, 1, NULL, '2021-02-17 20:02:04', '2021-02-17 20:02:04'),
(10, 4, '4.000000000', 20, 1, NULL, '2021-02-17 20:02:04', '2021-02-17 20:02:04'),
(11, 1, '7.000000000', 19, 1, NULL, '2021-02-17 20:02:04', '2021-02-17 20:02:04'),
(12, 3, '2.000000000', 21, 1, NULL, '2021-02-17 20:02:04', '2021-02-17 20:02:04'),
(13, 4, '2.900000000', 25, 1, NULL, '2021-02-17 20:02:04', '2021-02-17 20:02:04'),
(14, 1, '1.000000000', 4, 1, NULL, '2021-02-17 20:02:04', '2021-02-17 20:02:04'),
(15, 3, '1.050000000', 5, 1, NULL, '2021-02-17 20:02:04', '2021-02-17 20:02:04'),
(16, 1, '1.860000000', 2, 1, NULL, '2021-02-17 20:02:04', '2021-02-17 20:02:04'),
(17, 5, '2.910000000', 28, 1, NULL, '2021-02-17 20:02:04', '2021-02-17 20:02:04'),
(18, 6, '1.770000000', 6, 1, NULL, '2021-02-17 20:02:04', '2021-02-17 20:02:04'),
(19, 3, '1.800000000', 1, 1, NULL, '2021-02-17 20:02:04', '2021-02-17 20:02:04'),
(20, 14, '1.860000000', 10, 1, NULL, '2021-02-17 20:02:04', '2021-02-17 20:02:04'),
(21, 32, '1.100000000', 15, 1, NULL, '2021-02-17 20:02:04', '2021-02-17 20:02:04'),
(22, 11, '1.860000000', 11, 1, NULL, '2021-02-17 20:02:04', '2021-02-17 20:02:04'),
(23, 10, '0.300000000', 9, 1, NULL, '2021-02-17 20:02:04', '2021-02-17 20:02:04'),
(24, 3, '0.350000000', 8, 1, NULL, '2021-02-17 20:02:04', '2021-02-17 20:02:04'),
(25, 48, '0.300000000', 26, 1, NULL, '2021-02-17 20:02:04', '2021-02-17 20:02:04');

-- --------------------------------------------------------

--
-- Table structure for table `articulo_ventas`
--

CREATE TABLE `articulo_ventas` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `cantidad` int(10) UNSIGNED DEFAULT NULL,
  `precio_costo_unidad` decimal(25,9) DEFAULT NULL,
  `precio_venta_unidad` decimal(25,9) DEFAULT NULL,
  `porEspecial` decimal(25,2) DEFAULT NULL,
  `isDolar` int(10) UNSIGNED DEFAULT NULL,
  `isPeso` int(10) UNSIGNED DEFAULT NULL,
  `isTransPunto` int(10) UNSIGNED DEFAULT NULL,
  `isMixto` int(10) UNSIGNED DEFAULT NULL,
  `isEfectivo` int(10) UNSIGNED DEFAULT NULL,
  `descuento` decimal(25,3) DEFAULT NULL,
  `estado_pago` enum('Pagado','Falta pagar','Exonerado') COLLATE utf8mb4_unicode_ci NOT NULL,
  `articulo_id` bigint(20) UNSIGNED NOT NULL,
  `venta_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Triggers `articulo_ventas`
--
DELIMITER $$
CREATE TRIGGER `tr_updStockVenta` AFTER INSERT ON `articulo_ventas` FOR EACH ROW BEGIN
        UPDATE articulos SET stock = stock - NEW.cantidad
        WHERE articulos.id = NEW.articulo_id;
        END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `articulo__ingresos`
--

CREATE TABLE `articulo__ingresos` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `cantidad` int(10) UNSIGNED DEFAULT NULL,
  `precio_costo_unidad` decimal(25,9) DEFAULT NULL,
  `ingreso_id` bigint(20) UNSIGNED NOT NULL,
  `articulo_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Triggers `articulo__ingresos`
--
DELIMITER $$
CREATE TRIGGER `tr_udpPrecioVentaIngreso` AFTER INSERT ON `articulo__ingresos` FOR EACH ROW BEGIN
            UPDATE articulos SET precio_costo = New.precio_costo_unidad
            WHERE articulos.id = NEW.articulo_id;
        END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `tr_udpStockIngreso` AFTER INSERT ON `articulo__ingresos` FOR EACH ROW BEGIN
        UPDATE articulos SET stock = stock + NEW.cantidad
         WHERE articulos.id = NEW.articulo_id;
        END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `cajas`
--

CREATE TABLE `cajas` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `codigo` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fecha` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `hora_cierre` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `hora` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mes` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `year` year(4) NOT NULL,
  `monto_dolar` decimal(25,3) DEFAULT NULL,
  `monto_peso` decimal(25,3) DEFAULT NULL,
  `monto_bolivar` decimal(25,3) DEFAULT NULL,
  `monto_dolar_cierre` decimal(25,3) DEFAULT NULL,
  `monto_peso_cierre` decimal(25,3) DEFAULT NULL,
  `monto_bolivar_cierre` decimal(25,3) DEFAULT NULL,
  `monto_punto_cierre` decimal(25,3) DEFAULT NULL,
  `monto_trans_cierre` decimal(25,3) DEFAULT NULL,
  `monto_dolar_cierre_dif` decimal(25,3) DEFAULT NULL,
  `monto_peso_cierre_dif` decimal(25,3) DEFAULT NULL,
  `monto_bolivar_cierre_dif` decimal(25,3) DEFAULT NULL,
  `monto_punto_cierre_dif` decimal(25,3) DEFAULT NULL,
  `monto_trans_cierre_dif` decimal(25,3) DEFAULT NULL,
  `dolar_dolar_operador` decimal(25,3) DEFAULT NULL,
  `peso_dolar_operador` decimal(25,3) DEFAULT NULL,
  `punto_dolar_operador` decimal(25,3) DEFAULT NULL,
  `trans_dolar_operador` decimal(25,3) DEFAULT NULL,
  `efectivo_dolar_operador` decimal(25,3) DEFAULT NULL,
  `dolar_sistema` decimal(25,3) DEFAULT NULL,
  `peso_sistema` decimal(25,3) DEFAULT NULL,
  `punto_sistema` decimal(25,3) DEFAULT NULL,
  `trans_sistema` decimal(25,3) DEFAULT NULL,
  `efectivo_sistema` decimal(25,3) DEFAULT NULL,
  `total_sistema_reg` decimal(25,3) DEFAULT NULL,
  `total_operador_reg` decimal(25,3) DEFAULT NULL,
  `total_diferencia` decimal(25,3) DEFAULT NULL,
  `Observaciones` text COLLATE utf8mb4_unicode_ci,
  `estado` enum('Abierta','Cerrada','Auditoria') COLLATE utf8mb4_unicode_ci NOT NULL,
  `caja` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tasaActualVenta` decimal(25,2) DEFAULT NULL,
  `margenActualVenta` decimal(25,2) DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `sucursal_id` bigint(20) UNSIGNED NOT NULL,
  `sessioncaja_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categorias`
--

CREATE TABLE `categorias` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nombre` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `condicion` enum('Activa','Inactiva','Eliminada') COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categorias`
--

INSERT INTO `categorias` (`id`, `nombre`, `descripcion`, `condicion`, `created_at`, `updated_at`) VALUES
(1, 'LENCERÍA Y CONTROLES', 'LENCERÍA Y CONTROLES', 'Activa', '2020-12-25 22:39:10', '2021-01-13 18:24:16'),
(2, 'CONFITERÍA Y MISCELANEOS', 'CONFITERÍA Y MISCELANEOS', 'Activa', '2021-01-13 18:26:35', '2021-01-13 18:26:35'),
(3, 'BEBIDAS Y REFRESCOS', 'BEBIDAS Y REFRESCOS', 'Activa', '2021-01-13 18:27:21', '2021-01-13 18:27:21'),
(4, 'LICORES', 'LICORES', 'Activa', '2021-01-13 18:27:54', '2021-01-13 18:27:54'),
(5, 'SEX SHOP', 'SEX SHOP', 'Activa', '2021-02-17 19:48:44', '2021-02-17 19:48:44');

-- --------------------------------------------------------

--
-- Table structure for table `cats`
--

CREATE TABLE `cats` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nombre` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` varchar(254) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `estado` enum('Activa','Eliminada') COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cats`
--

INSERT INTO `cats` (`id`, `nombre`, `descripcion`, `estado`, `created_at`, `updated_at`) VALUES
(1, 'SEMI EJECUTIVA', 'SEMI EJECUTIVA', 'Activa', '2021-02-17 20:16:22', '2021-02-17 20:21:12'),
(2, 'JUNIOR C/EST', 'SUITES', 'Activa', '2021-02-17 20:17:16', '2021-02-17 20:17:16'),
(3, 'JUNIOR JACUZZI', 'JUNIOR JACUZZI', 'Activa', '2021-02-17 20:17:43', '2021-02-17 20:21:52'),
(4, 'PECADO Y FANTASIA', 'SUITE', 'Activa', '2021-02-17 20:18:38', '2021-02-17 20:18:38'),
(5, 'TEMATICAS DE LUJO', 'TEMATICAS', 'Activa', '2021-02-17 20:18:55', '2021-02-17 20:23:05');

-- --------------------------------------------------------

--
-- Table structure for table `contabilidads`
--

CREATE TABLE `contabilidads` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `denominacion` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `valor` decimal(25,3) NOT NULL,
  `cantidad` int(10) UNSIGNED DEFAULT NULL,
  `subtotal` decimal(25,3) DEFAULT NULL,
  `tipo` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `modo` enum('Apertura','Cierre') COLLATE utf8mb4_unicode_ci NOT NULL,
  `caja_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cortesias`
--

CREATE TABLE `cortesias` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nombre_cliente` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cedula_cliente` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `direccion_cliente` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `telefono_cliente` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `exonerado` decimal(25,2) DEFAULT NULL,
  `persona_id` bigint(20) UNSIGNED NOT NULL,
  `servicio_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `creditos`
--

CREATE TABLE `creditos` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nombre_cliente` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cedula_cliente` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `direccion_cliente` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `telefono_cliente` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `total_factura` int(11) DEFAULT NULL,
  `total_deuda` decimal(25,2) DEFAULT NULL,
  `fecha_limite_pago` date DEFAULT NULL,
  `estado_credito` enum('Activo','Moroso') COLLATE utf8mb4_unicode_ci NOT NULL,
  `persona_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `credito__pagados`
--

CREATE TABLE `credito__pagados` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `numero_factura` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tipo_operacion` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `operacion_id` int(11) NOT NULL,
  `monto` decimal(25,2) DEFAULT NULL,
  `tipo_pago` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fecha_emision` date NOT NULL,
  `fecha_vencimiento` date NOT NULL,
  `fecha_pago` date DEFAULT NULL,
  `estado_credito_al_pagar` enum('Vigente','Vencido','Pagado') COLLATE utf8mb4_unicode_ci NOT NULL,
  `persona_id` bigint(20) UNSIGNED NOT NULL,
  `detalle_credito_id` bigint(20) UNSIGNED NOT NULL,
  `credito_id` bigint(20) UNSIGNED NOT NULL,
  `caja_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `denominacions`
--

CREATE TABLE `denominacions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `moneda` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tipo` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `valor` decimal(25,2) NOT NULL,
  `denominacion` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `denominacions`
--

INSERT INTO `denominacions` (`id`, `moneda`, `tipo`, `valor`, `denominacion`, `created_at`, `updated_at`) VALUES
(1, 'Dolar', 'Billete', '1.00', 'Billete de 1 Dolar', '2020-12-25 22:34:30', '2020-12-25 22:34:30'),
(2, 'Dolar', 'Billete', '2.00', 'Billete de 2 Dolares', '2020-12-25 22:34:30', '2020-12-25 22:34:30'),
(3, 'Dolar', 'Billete', '5.00', 'Billete de 5 Dolares', '2020-12-25 22:34:30', '2020-12-25 22:34:30'),
(4, 'Dolar', 'Billete', '10.00', 'Billete de 10 Dolares', '2020-12-25 22:34:30', '2020-12-25 22:34:30'),
(5, 'Dolar', 'Billete', '20.00', 'Billete de 20 Dolares', '2020-12-25 22:34:30', '2020-12-25 22:34:30'),
(6, 'Dolar', 'Billete', '50.00', 'Billete de 50 Dolares', '2020-12-25 22:34:30', '2020-12-25 22:34:30'),
(7, 'Dolar', 'Billete', '100.00', 'Billete de 100 Dolares', '2020-12-25 22:34:30', '2020-12-25 22:34:30'),
(8, 'Bolivares', 'Billete', '500.00', 'Billete de 500 Bolivares', '2020-12-25 22:34:30', '2020-12-25 22:34:30'),
(9, 'Bolivares', 'Billete', '10000.00', 'Billete de 10.000 Bolivares', '2020-12-25 22:34:30', '2020-12-25 22:34:30'),
(10, 'Bolivares', 'Billete', '20000.00', 'Billete de 20.000 Bolivares', '2020-12-25 22:34:30', '2020-12-25 22:34:30'),
(11, 'Bolivares', 'Billete', '50000.00', 'Billete de 50.000 Bolivares', '2020-12-25 22:34:30', '2020-12-25 22:34:30'),
(12, 'Bolivares', 'Billete', '100000.00', 'Billete de 100.000 Bolivares', '2020-12-25 22:34:30', '2020-12-25 22:34:30'),
(13, 'Pesos', 'Moneda', '50.00', 'Moneda de 50 Pesos', '2020-12-25 22:34:30', '2020-12-25 22:34:30'),
(14, 'Pesos', 'Moneda', '100.00', 'Moneda de 100 Pesos', '2020-12-25 22:34:30', '2020-12-25 22:34:30'),
(15, 'Pesos', 'Moneda', '200.00', 'Moneda de 200 Pesos', '2020-12-25 22:34:30', '2020-12-25 22:34:30'),
(16, 'Pesos', 'Moneda', '500.00', 'Moneda de 500 Pesos', '2020-12-25 22:34:30', '2020-12-25 22:34:30'),
(17, 'Pesos', 'Moneda', '1000.00', 'Moneda de 1.000 Pesos', '2020-12-25 22:34:31', '2020-12-25 22:34:31'),
(18, 'Pesos', 'Billete', '1000.00', 'Billete de 1.000 Pesos', '2020-12-25 22:34:31', '2020-12-25 22:34:31'),
(19, 'Pesos', 'Billete', '2000.00', 'Billete de 2.000 Pesos', '2020-12-25 22:34:31', '2020-12-25 22:34:31'),
(20, 'Pesos', 'Billete', '5000.00', 'Billete de 5.000 Pesos', '2020-12-25 22:34:31', '2020-12-25 22:34:31'),
(21, 'Pesos', 'Billete', '10000.00', 'Billete de 10.000 Pesos', '2020-12-25 22:34:31', '2020-12-25 22:34:31'),
(22, 'Pesos', 'Billete', '20000.00', 'Billete de 20.000 Pesos', '2020-12-25 22:34:31', '2020-12-25 22:34:31'),
(23, 'Pesos', 'Billete', '50000.00', 'Billete de 50.000 Pesos', '2020-12-25 22:34:31', '2020-12-25 22:34:31'),
(24, 'Pesos', 'Billete', '100000.00', 'Billete de 100.000 Pesos', '2020-12-25 22:34:31', '2020-12-25 22:34:31');

-- --------------------------------------------------------

--
-- Table structure for table `detalle_creditos`
--

CREATE TABLE `detalle_creditos` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `numero_factura` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tipo_operacion` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `operacion_id` int(11) NOT NULL,
  `monto` decimal(25,2) DEFAULT NULL,
  `estado_pago` enum('Pendiente','Pagado') COLLATE utf8mb4_unicode_ci NOT NULL,
  `estado_credito` enum('Vigente','Vencido','Pagado') COLLATE utf8mb4_unicode_ci NOT NULL,
  `tipo_pago` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fecha_emision` date NOT NULL,
  `fecha_vencimiento` date NOT NULL,
  `fecha_pago` date DEFAULT NULL,
  `persona_id` bigint(20) UNSIGNED NOT NULL,
  `credito_id` bigint(20) UNSIGNED NOT NULL,
  `caja_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `empresas`
--

CREATE TABLE `empresas` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nombre` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slogan` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `telefono_fijo` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `telefono_mobil` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `direccion` varchar(256) COLLATE utf8mb4_unicode_ci NOT NULL,
  `imagen_logo` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `empresas`
--

INSERT INTO `empresas` (`id`, `nombre`, `slogan`, `telefono_fijo`, `telefono_mobil`, `email`, `direccion`, `imagen_logo`, `created_at`, `updated_at`) VALUES
(1, 'Villa Soft Punto', 'La mejor en precios bajos...!', '0424-7665227', '0424-7665227', 'villasoftpunto@gmail.com', 'EL Vigía.', 'photo2.png', '2020-12-25 22:34:28', '2020-12-25 22:34:28');

-- --------------------------------------------------------

--
-- Table structure for table `excedentes`
--

CREATE TABLE `excedentes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nombre_cliente` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cedula_cliente` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `direccion_cliente` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `telefono_cliente` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `excedente` decimal(25,2) DEFAULT NULL,
  `persona_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `habitaciones`
--

CREATE TABLE `habitaciones` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `cat_id` bigint(20) UNSIGNED NOT NULL,
  `level_id` bigint(20) UNSIGNED NOT NULL,
  `nombre` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `estado` enum('Activa','Eliminada') COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('Disponible','Ocupada','Limpieza','Finalizando','En reparacion') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `habitaciones`
--

INSERT INTO `habitaciones` (`id`, `cat_id`, `level_id`, `nombre`, `estado`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 1, '02', 'Activa', 'Disponible', '2021-02-17 20:24:13', '2021-02-17 20:24:13'),
(2, 1, 1, '03', 'Activa', 'Disponible', '2021-02-17 20:24:28', '2021-02-17 20:24:28'),
(3, 1, 1, '04', 'Activa', 'Disponible', '2021-02-17 20:24:52', '2021-02-17 20:24:52'),
(4, 1, 1, '05', 'Activa', 'Disponible', '2021-02-17 20:25:04', '2021-02-17 20:25:04'),
(5, 1, 1, '07', 'Activa', 'Disponible', '2021-02-17 20:25:17', '2021-02-17 20:25:17'),
(6, 1, 1, '08', 'Activa', 'Disponible', '2021-02-17 20:25:27', '2021-02-17 20:25:27'),
(7, 1, 1, '09', 'Activa', 'Disponible', '2021-02-17 20:25:35', '2021-02-17 20:25:35'),
(8, 1, 1, '10', 'Activa', 'Disponible', '2021-02-17 20:25:42', '2021-02-17 20:25:42'),
(9, 1, 1, '11', 'Activa', 'Disponible', '2021-02-17 20:25:56', '2021-02-17 20:25:56'),
(10, 2, 2, '22', 'Activa', 'Disponible', '2021-02-17 20:26:16', '2021-02-17 20:26:16'),
(11, 2, 2, '23', 'Activa', 'Disponible', '2021-02-17 20:26:26', '2021-02-17 20:26:26'),
(12, 2, 2, '24', 'Activa', 'Disponible', '2021-02-17 20:26:36', '2021-02-17 20:26:36'),
(13, 3, 2, '20', 'Activa', 'Disponible', '2021-02-17 20:27:00', '2021-02-17 20:27:00'),
(14, 3, 2, '21', 'Activa', 'Disponible', '2021-02-17 20:27:15', '2021-02-17 20:27:15'),
(15, 4, 1, '01', 'Activa', 'Disponible', '2021-02-17 20:27:28', '2021-02-17 20:27:28'),
(16, 4, 1, '06', 'Activa', 'Disponible', '2021-02-17 20:27:37', '2021-02-17 20:28:07'),
(17, 4, 1, '12', 'Activa', 'Disponible', '2021-02-17 20:27:49', '2021-02-17 20:27:49'),
(18, 4, 1, '13', 'Activa', 'Disponible', '2021-02-17 20:28:46', '2021-02-17 20:28:46'),
(19, 4, 1, '14', 'Activa', 'Disponible', '2021-02-17 20:28:55', '2021-02-17 20:28:55'),
(20, 4, 2, '15', 'Activa', 'Disponible', '2021-02-17 20:29:07', '2021-02-17 20:29:07'),
(21, 4, 2, '16', 'Activa', 'Disponible', '2021-02-17 20:29:20', '2021-02-17 20:29:20'),
(22, 4, 2, '17', 'Activa', 'Disponible', '2021-02-17 20:29:47', '2021-02-17 20:29:47'),
(23, 4, 2, '18', 'Activa', 'Disponible', '2021-02-17 20:29:56', '2021-02-17 20:29:56'),
(24, 4, 2, '19', 'Activa', 'Disponible', '2021-02-17 20:30:06', '2021-02-17 20:30:06'),
(25, 5, 3, '25', 'Activa', 'Disponible', '2021-02-17 20:30:20', '2021-02-17 20:30:20'),
(26, 5, 3, '26', 'Activa', 'Disponible', '2021-02-17 20:30:28', '2021-02-17 20:30:28'),
(27, 5, 3, '27', 'Activa', 'Disponible', '2021-02-17 20:30:37', '2021-02-17 20:30:37'),
(28, 5, 3, '28', 'Activa', 'Disponible', '2021-02-17 20:30:45', '2021-02-17 20:30:45'),
(29, 5, 3, '29', 'Activa', 'Disponible', '2021-02-17 20:30:53', '2021-02-17 20:30:53'),
(30, 5, 3, '30', 'Activa', 'Disponible', '2021-02-17 20:31:00', '2021-02-17 20:31:00'),
(31, 5, 3, '31', 'Activa', 'Disponible', '2021-02-17 20:31:09', '2021-02-17 20:31:09'),
(32, 5, 3, '32', 'Activa', 'Disponible', '2021-02-17 20:31:17', '2021-02-17 20:31:17'),
(33, 5, 3, '33', 'Activa', 'Disponible', '2021-02-17 20:31:24', '2021-02-17 20:31:24'),
(34, 5, 3, '34', 'Activa', 'Disponible', '2021-02-17 20:31:32', '2021-02-17 20:31:32'),
(35, 5, 3, '35', 'Activa', 'Disponible', '2021-02-17 20:31:40', '2021-02-17 20:31:40'),
(36, 5, 3, '36', 'Activa', 'Disponible', '2021-02-17 20:31:47', '2021-02-17 20:31:47'),
(37, 5, 3, '37', 'Activa', 'Disponible', '2021-02-17 20:31:56', '2021-02-17 20:31:56'),
(38, 5, 3, '38', 'Activa', 'Disponible', '2021-02-17 20:32:04', '2021-02-17 20:32:04');

-- --------------------------------------------------------

--
-- Table structure for table `horarios`
--

CREATE TABLE `horarios` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tipo` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nombre` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `desde` time DEFAULT NULL,
  `hasta` time DEFAULT NULL,
  `restringir` time DEFAULT NULL,
  `is24Horas` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `horarios`
--

INSERT INTO `horarios` (`id`, `tipo`, `nombre`, `desde`, `hasta`, `restringir`, `is24Horas`, `created_at`, `updated_at`) VALUES
(1, 'DIURNO', 'SERVICIO DIURNO ENTRE 5:00 AM. Y 9:00 PM.', '05:00:00', '21:00:00', '19:00:00', NULL, '2020-12-26 13:48:53', '2020-12-26 13:48:53'),
(2, 'COMERCIAL', 'SERVICIO COMERCIAL ENTRE 5:00 PM A 9:00 AM.', '17:00:00', '09:00:00', '09:00:00', NULL, '2020-12-26 13:50:23', '2020-12-26 13:50:23'),
(3, '24 HORAS', 'SERVICIO EJECUTIVO 24 HORAS', NULL, NULL, NULL, 1, '2020-12-26 13:51:03', '2020-12-26 13:51:03');

-- --------------------------------------------------------

--
-- Table structure for table `ingresos`
--

CREATE TABLE `ingresos` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tipo_comprobante` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `serie_comprobante` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `num_comprobante` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `precio_compra` decimal(25,9) DEFAULT NULL,
  `fecha_hora` datetime NOT NULL,
  `estado` enum('Aceptado','Cancelado','Procesando') COLLATE utf8mb4_unicode_ci NOT NULL,
  `persona_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `levels`
--

CREATE TABLE `levels` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `sucursal_id` bigint(20) UNSIGNED NOT NULL,
  `nombre` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `estado` enum('Activo','Eliminado') COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `levels`
--

INSERT INTO `levels` (`id`, `sucursal_id`, `nombre`, `estado`, `created_at`, `updated_at`) VALUES
(1, 1, 'CASONA', 'Activo', '2021-02-17 20:13:00', '2021-02-17 20:13:00'),
(2, 1, 'EDIF.PLANTA BAJA', 'Activo', '2021-02-17 20:14:10', '2021-02-17 20:14:10'),
(3, 1, 'EDIF.PLANTA ALTA', 'Activo', '2021-02-17 20:14:22', '2021-02-17 20:14:22');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2020_08_16_153023_create_roles_table', 1),
(5, '2020_08_16_154812_create_role_user_table', 1),
(6, '2020_08_16_171515_create_permissions_table', 1),
(7, '2020_08_16_171828_create_permission_role_table', 1),
(8, '2020_08_25_145341_create_empresas_table', 1),
(9, '2020_08_25_145731_create_sucursals_table', 1),
(10, '2020_08_25_150123_create_sessioncajas_table', 1),
(11, '2020_08_26_054257_create_tasas_table', 1),
(12, '2020_08_31_171133_create_cajas_table', 1),
(13, '2020_09_02_080224_create_denominacions_table', 1),
(14, '2020_09_02_090526_create_contabilidads_table', 1),
(15, '2020_09_03_222156_create_categorias_table', 1),
(16, '2020_09_03_223458_create_articulos_table', 1),
(17, '2020_09_04_015232_create_personas_table', 1),
(18, '2020_09_06_011857_create_ventas_table', 1),
(19, '2020_09_06_131628_create_articulo_ventas_table', 1),
(20, '2020_09_07_213202_create_pago__ventas_table', 1),
(21, '2020_09_08_081947_create_ingresos_table', 1),
(22, '2020_09_08_082134_create_articulo__ingresos_table', 1),
(23, '2020_09_08_182249_add_trigger_for_stock_entry', 1),
(24, '2020_09_08_184348_add_trigger_for_update_price_article_after_entry', 1),
(25, '2020_09_08_193942_add_trigger_for_stock_ingreso', 1),
(26, '2020_09_16_043501_create_transferencias_table', 1),
(27, '2020_10_10_080759_create_transactions_table', 1),
(28, '2020_10_10_080926_create_articulo_transactions_table', 1),
(29, '2020_11_09_135541_create_levels_table', 1),
(30, '2020_11_09_140845_create_horarios_table', 1),
(31, '2020_11_09_143302_create_cats_table', 1),
(32, '2020_11_09_143610_create_precios_table', 1),
(33, '2020_11_09_144249_create_habitaciones_table', 1),
(53, '2020_11_27_142053_create_servicios_table', 2),
(54, '2020_12_11_170742_create_pago__servicios_table', 2),
(55, '2020_12_13_104255_create_excedentes_table', 2),
(56, '2020_12_13_140158_create_servicios__ventas_table', 2),
(59, '2020_12_13_141729_create_creditos_table', 3),
(60, '2020_12_26_152401_create_cortesias_table', 3),
(61, '2021_02_11_103833_create_detelle_creditos_table', 3),
(62, '2021_02_14_170714_create_pago__creditos_table', 3),
(63, '2021_02_16_130227_create_credito__pagados_table', 3);

-- --------------------------------------------------------

--
-- Table structure for table `pago__creditos`
--

CREATE TABLE `pago__creditos` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `Divisa` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `MontoDivisa` decimal(25,3) DEFAULT NULL,
  `TasaTiket` decimal(25,2) DEFAULT NULL,
  `MontoDolar` decimal(25,3) DEFAULT NULL,
  `Vueltos` decimal(25,3) DEFAULT NULL,
  `detalle_credito_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pago__servicios`
--

CREATE TABLE `pago__servicios` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `Divisa` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `MontoDivisa` decimal(25,3) DEFAULT NULL,
  `TasaTiket` decimal(25,2) DEFAULT NULL,
  `MontoDolar` decimal(25,3) DEFAULT NULL,
  `Vueltos` decimal(25,3) DEFAULT NULL,
  `servicio_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pago__ventas`
--

CREATE TABLE `pago__ventas` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `Divisa` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `MontoDivisa` decimal(25,3) DEFAULT NULL,
  `TasaTiket` decimal(25,2) DEFAULT NULL,
  `MontoDolar` decimal(25,3) DEFAULT NULL,
  `Vueltos` decimal(25,3) DEFAULT NULL,
  `venta_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `slug`, `description`, `created_at`, `updated_at`) VALUES
(1, 'List role', 'role.index', 'A user can list role', '2020-12-25 22:34:22', '2020-12-25 22:34:22'),
(2, 'Show role', 'role.show', 'A user can see role', '2020-12-25 22:34:22', '2020-12-25 22:34:22'),
(3, 'Create role', 'role.create', 'A user can create role', '2020-12-25 22:34:22', '2020-12-25 22:34:22'),
(4, 'Edit role', 'role.edit', 'A user can edit role', '2020-12-25 22:34:22', '2020-12-25 22:34:22'),
(5, 'Destroy role', 'role.destroy', 'A user can destroy role', '2020-12-25 22:34:22', '2020-12-25 22:34:22'),
(6, 'List user', 'user.index', 'A user can list user', '2020-12-25 22:34:22', '2020-12-25 22:34:22'),
(7, 'Show user', 'user.show', 'A user can see user', '2020-12-25 22:34:23', '2020-12-25 22:34:23'),
(8, 'Edit user', 'user.edit', 'A user can edit user', '2020-12-25 22:34:23', '2020-12-25 22:34:23'),
(9, 'Destroy user', 'user.destroy', 'A user can destroy user', '2020-12-25 22:34:23', '2020-12-25 22:34:23'),
(10, 'List ventas', 'venta.index', 'A user can list ventas', '2020-12-25 22:34:23', '2020-12-25 22:34:23'),
(11, 'Show ventas', 'venta.show', 'A user can see ventas', '2020-12-25 22:34:23', '2020-12-25 22:34:23'),
(12, 'Create ventas', 'venta.create', 'A user can create ventas', '2020-12-25 22:34:23', '2020-12-25 22:34:23'),
(13, 'Edit ventas', 'venta.edit', 'A user can edit ventas', '2020-12-25 22:34:23', '2020-12-25 22:34:23'),
(14, 'Destroy ventas', 'venta.destroy', 'A user can destroy ventas', '2020-12-25 22:34:23', '2020-12-25 22:34:23'),
(15, 'List transferencias', 'transferencia.index', 'A user can list transferencias', '2020-12-25 22:34:23', '2020-12-25 22:34:23'),
(16, 'Show transferencias', 'transferencia.show', 'A user can see transferencias', '2020-12-25 22:34:23', '2020-12-25 22:34:23'),
(17, 'Create transferencias', 'transferencia.create', 'A user can create transferencias', '2020-12-25 22:34:23', '2020-12-25 22:34:23'),
(18, 'Edit transferencias', 'transferencia.edit', 'A user can edit transferencias', '2020-12-25 22:34:23', '2020-12-25 22:34:23'),
(19, 'Destroy transferencias', 'transferencia.destroy', 'A user can destroy transferencias', '2020-12-25 22:34:23', '2020-12-25 22:34:23'),
(20, 'List tasas', 'tasa.index', 'A user can list tasas', '2020-12-25 22:34:23', '2020-12-25 22:34:23'),
(21, 'Show tasas', 'tasa.show', 'A user can see tasas', '2020-12-25 22:34:23', '2020-12-25 22:34:23'),
(22, 'Create tasas', 'tasa.create', 'A user can create tasas', '2020-12-25 22:34:23', '2020-12-25 22:34:23'),
(23, 'Edit tasas', 'tasa.edit', 'A user can edit tasas', '2020-12-25 22:34:23', '2020-12-25 22:34:23'),
(24, 'Destroy tasas', 'tasa.destroy', 'A user can destroy tasas', '2020-12-25 22:34:23', '2020-12-25 22:34:23'),
(25, 'Ver registro de Efectivo Venta en tasa', 'tasacampoefectivoventa.ver', 'A user see campo EfectivoVenta de tasa', '2020-12-25 22:34:23', '2020-12-25 22:34:23'),
(26, 'List reportes', 'reporte.index', 'A user can list reportes', '2020-12-25 22:34:23', '2020-12-25 22:34:23'),
(27, 'Show reportes', 'reporte.show', 'A user can see reportes', '2020-12-25 22:34:23', '2020-12-25 22:34:23'),
(28, 'Create reportes', 'reporte.create', 'A user can create reportes', '2020-12-25 22:34:23', '2020-12-25 22:34:23'),
(29, 'Edit reportes', 'reporte.edit', 'A user can edit reportes', '2020-12-25 22:34:23', '2020-12-25 22:34:23'),
(30, 'Destroy reportes', 'reporte.destroy', 'A user can destroy reportes', '2020-12-25 22:34:23', '2020-12-25 22:34:23'),
(31, 'List proveedores', 'proveedore.index', 'A user can list proveedores', '2020-12-25 22:34:23', '2020-12-25 22:34:23'),
(32, 'Show proveedores', 'proveedore.show', 'A user can see proveedores', '2020-12-25 22:34:23', '2020-12-25 22:34:23'),
(33, 'Create proveedores', 'proveedore.create', 'A user can create proveedores', '2020-12-25 22:34:23', '2020-12-25 22:34:23'),
(34, 'Edit proveedores', 'proveedore.edit', 'A user can edit proveedores', '2020-12-25 22:34:23', '2020-12-25 22:34:23'),
(35, 'Destroy proveedores', 'proveedore.destroy', 'A user can destroy proveedores', '2020-12-25 22:34:23', '2020-12-25 22:34:23'),
(36, 'List ingresos', 'ingreso.index', 'A user can list ingresos', '2020-12-25 22:34:23', '2020-12-25 22:34:23'),
(37, 'Show ingresos', 'ingreso.show', 'A user can see ingresos', '2020-12-25 22:34:23', '2020-12-25 22:34:23'),
(38, 'Create ingresos', 'ingreso.create', 'A user can create ingresos', '2020-12-25 22:34:23', '2020-12-25 22:34:23'),
(39, 'Edit ingresos', 'ingreso.edit', 'A user can edit ingresos', '2020-12-25 22:34:23', '2020-12-25 22:34:23'),
(40, 'Destroy ingresos', 'ingreso.destroy', 'A user can destroy ingresos', '2020-12-25 22:34:24', '2020-12-25 22:34:24'),
(41, 'List clientes', 'cliente.index', 'A user can list clientes', '2020-12-25 22:34:24', '2020-12-25 22:34:24'),
(42, 'Show clientes', 'cliente.show', 'A user can see clientes', '2020-12-25 22:34:24', '2020-12-25 22:34:24'),
(43, 'Create clientes', 'cliente.create', 'A user can create clientes', '2020-12-25 22:34:24', '2020-12-25 22:34:24'),
(44, 'Edit clientes', 'cliente.edit', 'A user can edit clientes', '2020-12-25 22:34:24', '2020-12-25 22:34:24'),
(45, 'Destroy clientes', 'cliente.destroy', 'A user can destroy clientes', '2020-12-25 22:34:24', '2020-12-25 22:34:24'),
(46, 'List categorias', 'categoria.index', 'A user can list categorias', '2020-12-25 22:34:24', '2020-12-25 22:34:24'),
(47, 'Show categorias', 'categoria.show', 'A user can see categorias', '2020-12-25 22:34:24', '2020-12-25 22:34:24'),
(48, 'Create categorias', 'categoria.create', 'A user can create categorias', '2020-12-25 22:34:24', '2020-12-25 22:34:24'),
(49, 'Edit categorias', 'categoria.edit', 'A user can edit categorias', '2020-12-25 22:34:24', '2020-12-25 22:34:24'),
(50, 'Destroy categorias', 'categoria.destroy', 'A user can destroy categorias', '2020-12-25 22:34:24', '2020-12-25 22:34:24'),
(51, 'List cajas', 'caja.index', 'A user can list cajas', '2020-12-25 22:34:24', '2020-12-25 22:34:24'),
(52, 'Show cajas', 'caja.show', 'A user can see cajas', '2020-12-25 22:34:24', '2020-12-25 22:34:24'),
(53, 'Create cajas', 'caja.create', 'A user can create cajas', '2020-12-25 22:34:24', '2020-12-25 22:34:24'),
(54, 'Edit cajas', 'caja.edit', 'A user can edit cajas', '2020-12-25 22:34:24', '2020-12-25 22:34:24'),
(55, 'Destroy cajas', 'caja.destroy', 'A user can destroy cajas', '2020-12-25 22:34:24', '2020-12-25 22:34:24'),
(56, 'List articulos', 'articulo.index', 'A user can list articulos', '2020-12-25 22:34:24', '2020-12-25 22:34:24'),
(57, 'Show articulos', 'articulo.show', 'A user can see articulos', '2020-12-25 22:34:24', '2020-12-25 22:34:24'),
(58, 'Create articulos', 'articulo.create', 'A user can create articulos', '2020-12-25 22:34:24', '2020-12-25 22:34:24'),
(59, 'Edit articulos', 'articulo.edit', 'A user can edit articulos', '2020-12-25 22:34:25', '2020-12-25 22:34:25'),
(60, 'Destroy articulos', 'articulo.destroy', 'A user can destroy articulos', '2020-12-25 22:34:25', '2020-12-25 22:34:25'),
(61, 'Show own user', 'userown.show', 'A user can see own user', '2020-12-25 22:34:25', '2020-12-25 22:34:25'),
(62, 'Edit own user', 'userown.edit', 'A user can edit own user', '2020-12-25 22:34:25', '2020-12-25 22:34:25'),
(63, 'Show own ventas', 'ventaown.show', 'A user can see own ventas', '2020-12-25 22:34:25', '2020-12-25 22:34:25'),
(64, 'Edit own ventas', 'ventaown.edit', 'A user can edit own ventas', '2020-12-25 22:34:25', '2020-12-25 22:34:25'),
(65, 'delete own ventas', 'ventaown.destroy', 'A user can delete own ventas', '2020-12-25 22:34:25', '2020-12-25 22:34:25'),
(66, 'Show own transferencias', 'transferenciaown.show', 'A user can see own transferencias', '2020-12-25 22:34:25', '2020-12-25 22:34:25'),
(67, 'Edit own transferencias', 'transferenciaown.edit', 'A user can edit own transferencias', '2020-12-25 22:34:25', '2020-12-25 22:34:25'),
(68, 'delete own transferencias', 'transferenciaown.destroy', 'A user can delete own transferencias', '2020-12-25 22:34:25', '2020-12-25 22:34:25'),
(69, 'Show own tasas', 'tasaown.show', 'A user can see own tasas', '2020-12-25 22:34:25', '2020-12-25 22:34:25'),
(70, 'Edit own tasas', 'tasaown.edit', 'A user can edit own tasas', '2020-12-25 22:34:25', '2020-12-25 22:34:25'),
(71, 'delete own tasas', 'tasaown.destroy', 'A user can delete own tasas', '2020-12-25 22:34:25', '2020-12-25 22:34:25'),
(72, 'Show own reportes', 'reporteown.show', 'A user can see own reportes', '2020-12-25 22:34:25', '2020-12-25 22:34:25'),
(73, 'Edit own reportes', 'reporteown.edit', 'A user can edit own reportes', '2020-12-25 22:34:25', '2020-12-25 22:34:25'),
(74, 'delete own reportes', 'reporteown.destroy', 'A user can delete own reportes', '2020-12-25 22:34:25', '2020-12-25 22:34:25'),
(75, 'Show own proveedores', 'proveedorown.show', 'A user can see own proveedores', '2020-12-25 22:34:25', '2020-12-25 22:34:25'),
(76, 'Edit own proveedores', 'proveedorown.edit', 'A user can edit own proveedores', '2020-12-25 22:34:25', '2020-12-25 22:34:25'),
(77, 'delete own proveedores', 'proveedorown.destroy', 'A user can delete own proveedores', '2020-12-25 22:34:25', '2020-12-25 22:34:25'),
(78, 'Show own ingresos', 'ingresoown.show', 'A user can see own ingresos', '2020-12-25 22:34:25', '2020-12-25 22:34:25'),
(79, 'Edit own ingresos', 'ingresoown.edit', 'A user can edit own ingresos', '2020-12-25 22:34:25', '2020-12-25 22:34:25'),
(80, 'delete own ingresos', 'ingresoown.destroy', 'A user can delete own ingresos', '2020-12-25 22:34:25', '2020-12-25 22:34:25'),
(81, 'Show own clientes', 'clienteown.show', 'A user can see own clientes', '2020-12-25 22:34:25', '2020-12-25 22:34:25'),
(82, 'Edit own clientes', 'clienteown.edit', 'A user can edit own clientes', '2020-12-25 22:34:25', '2020-12-25 22:34:25'),
(83, 'delete own clientes', 'clienteown.destroy', 'A user can delete own clientes', '2020-12-25 22:34:25', '2020-12-25 22:34:25'),
(84, 'Show own categorias', 'categoriaown.show', 'A user can see own categorias', '2020-12-25 22:34:26', '2020-12-25 22:34:26'),
(85, 'Edit own categorias', 'categoriaown.edit', 'A user can edit own categorias', '2020-12-25 22:34:26', '2020-12-25 22:34:26'),
(86, 'delete own categorias', 'categoriaown.destroy', 'A user can delete own categorias', '2020-12-25 22:34:26', '2020-12-25 22:34:26'),
(87, 'Show own cajas', 'cajaown.show', 'A user can see own cajas', '2020-12-25 22:34:26', '2020-12-25 22:34:26'),
(88, 'Edit own cajas', 'cajaown.edit', 'A user can edit own cajas', '2020-12-25 22:34:26', '2020-12-25 22:34:26'),
(89, 'delete own cajas', 'cajaown.destroy', 'A user can delete own cajas', '2020-12-25 22:34:26', '2020-12-25 22:34:26'),
(90, 'Show own articulos', 'articuloown.show', 'A user can see own articulos', '2020-12-25 22:34:26', '2020-12-25 22:34:26'),
(91, 'Edit own articulos', 'articuloown.edit', 'A user can edit own articulos', '2020-12-25 22:34:26', '2020-12-25 22:34:26'),
(92, 'delete own articulos', 'articuloown.destroy', 'A user can delete own articulos', '2020-12-25 22:34:26', '2020-12-25 22:34:26'),
(93, 'Boton Ventas', 'boton.ventas', 'A see menu venta', '2020-12-25 22:34:26', '2020-12-25 22:34:26'),
(94, 'Boton Clientes', 'boton.cliente', 'A see bub-menu cliente belongs to venta', '2020-12-25 22:34:26', '2020-12-25 22:34:26'),
(95, 'Boton Venta', 'boton.venta', 'A see bub-menu venta belongs to venta', '2020-12-25 22:34:26', '2020-12-25 22:34:26'),
(96, 'Boton Tasa', 'boton.tasa', 'A see bub-menu tasa belongs to venta', '2020-12-25 22:34:26', '2020-12-25 22:34:26'),
(97, 'Boton compras', 'boton.compras', 'A see menu compras', '2020-12-25 22:34:26', '2020-12-25 22:34:26'),
(98, 'Boton Proveedor', 'boton.proveedor', 'A see bub-menu proveedor belongs to Compras', '2020-12-25 22:34:26', '2020-12-25 22:34:26'),
(99, 'Boton Ingreso', 'boton.ingreso', 'A see bub-menu ingresos belongs to Compras', '2020-12-25 22:34:26', '2020-12-25 22:34:26'),
(100, 'Boton Almacen', 'boton.almacen', 'A see menu Almacen', '2020-12-25 22:34:26', '2020-12-25 22:34:26'),
(101, 'Boton Categorías', 'boton.categoria', 'A see menu Categoría belongs to Almacen', '2020-12-25 22:34:26', '2020-12-25 22:34:26'),
(102, 'Boton Artículos', 'boton.articulos', 'A see bub-menu artículos belongs to Almacen', '2020-12-25 22:34:26', '2020-12-25 22:34:26'),
(103, 'Boton Transacciones', 'boton.transacciones', 'A see bub-menu transacciones belongs to Almacen', '2020-12-25 22:34:26', '2020-12-25 22:34:26'),
(104, 'Menu Transacciones', 'menu.transactions', 'A user see menu transactions', '2020-12-25 22:34:26', '2020-12-25 22:34:26'),
(105, 'Boton Transferencias', 'boton.transferencias', 'A see bub-menu transferencias belongs to transferencias thas belongs to Almacen', '2020-12-25 22:34:26', '2020-12-25 22:34:26'),
(106, 'Boton Cargos', 'boton.cargos', 'A see bub-menu cargos belongs to transferencias thas belongs to Almacen', '2020-12-25 22:34:26', '2020-12-25 22:34:26'),
(107, 'Boton Descargos', 'boton.descargos', 'A see bub-menu descargos belongs to transferencias thas belongs to Almacen', '2020-12-25 22:34:26', '2020-12-25 22:34:26'),
(108, 'Boton repoReportesrtes', 'boton.reportes', 'A see menu Reportes', '2020-12-25 22:34:26', '2020-12-25 22:34:26'),
(109, 'Boton Artículos Vendidos', 'boton.articulosVendidos', 'A see bub-menu Artículos Vendidos belongs to Reportes', '2020-12-25 22:34:26', '2020-12-25 22:34:26'),
(110, 'Boton Planilla Inventario', 'boton.planillaInventario', 'A see bub-menu Planilla Inventario belongs to Reportes', '2020-12-25 22:34:27', '2020-12-25 22:34:27'),
(111, 'Boton Lista de Precios', 'boton.listaPrecios', 'A see bub-menu Lista de Precios belongs to Reportes', '2020-12-25 22:34:27', '2020-12-25 22:34:27'),
(112, 'Boton Reporte General', 'boton.reporteGeneral', 'A see bub-menu Reporte General belongs to Reportes', '2020-12-25 22:34:27', '2020-12-25 22:34:27'),
(113, 'Boton Reporte General Compras', 'boton.reporteGeneralCompras', 'A see bub-menu Reporte General Compras belongs to Reportes', '2020-12-25 22:34:27', '2020-12-25 22:34:27'),
(114, 'Boton sistema', 'boton.sistema', 'A see menu sistema', '2020-12-25 22:34:27', '2020-12-25 22:34:27'),
(115, 'Boton Role', 'boton.role', 'A see bub-menu Role belongs to Sistema', '2020-12-25 22:34:27', '2020-12-25 22:34:27'),
(116, 'Boton User', 'boton.user', 'A see bub-menu User belongs to Sistema', '2020-12-25 22:34:27', '2020-12-25 22:34:27'),
(117, 'Caja costo', 'cajacosto.show', 'A see caja costo belongs to Reporte Caja', '2020-12-25 22:34:27', '2020-12-25 22:34:27'),
(118, 'Ver Datos Ventas', 'cajadatosventas.show', 'A user see report caja Datos Ventas belongs to Reporte Caja', '2020-12-25 22:34:27', '2020-12-25 22:34:27'),
(119, 'Ver Datos Articulos', 'cajadatosarticulos.show', 'A user see report caja Datos Articulos belongs to Reporte Caja', '2020-12-25 22:34:27', '2020-12-25 22:34:27'),
(120, 'Ver Total de las ventas', 'cajatotalventa.show', 'A user see report caja Total Venta belongs to Reporte Caja', '2020-12-25 22:34:27', '2020-12-25 22:34:27'),
(121, 'Ver Precio costo', 'cajapreciocosto.show', 'A user see report caja Precio Costo belongs to Reporte Caja', '2020-12-25 22:34:27', '2020-12-25 22:34:27'),
(122, 'Ver Utilidad', 'cajautilidad.show', 'A user see report caja Utilidad belongs to Reporte Caja', '2020-12-25 22:34:27', '2020-12-25 22:34:27'),
(123, 'Ver Totales', 'cajatotales.show', 'A user see report caja Totales belongs to Reporte Caja', '2020-12-25 22:34:27', '2020-12-25 22:34:27'),
(124, 'Ver Datos Cargos', 'cargos.index', 'A user see transaction cargos belongs to Transacciones Cargos', '2020-12-25 22:34:27', '2020-12-25 22:34:27'),
(125, 'Ver Datos Cargos detallados', 'cargos.show', 'A user see transaction cargos show belongs to Transacciones Cargos', '2020-12-25 22:34:28', '2020-12-25 22:34:28'),
(126, 'Eliminar Datos Cargos detallados', 'cargos.destroy', 'A user see transaction cargos destroy belongs to Transacciones Cargos', '2020-12-25 22:34:28', '2020-12-25 22:34:28'),
(127, 'Crear Cargos', 'cargos.create', 'A user create cargos', '2020-12-25 22:34:28', '2020-12-25 22:34:28'),
(128, 'Ver Datos Descargos', 'descargos.index', 'A user see transaction descargos', '2020-12-25 22:34:28', '2020-12-25 22:34:28'),
(129, 'Ver Datos Descargos detallados', 'descargos.show', 'A user see transaction descargos show', '2020-12-25 22:34:28', '2020-12-25 22:34:28'),
(130, 'Eliminar Datos Descargos detallados', 'descargos.destroy', 'A user see transaction descargos destroy', '2020-12-25 22:34:28', '2020-12-25 22:34:28'),
(131, 'Crear Descargos', 'descargos.create', 'A user create descargos', '2020-12-25 22:34:28', '2020-12-25 22:34:28');

-- --------------------------------------------------------

--
-- Table structure for table `permission_role`
--

CREATE TABLE `permission_role` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permission_role`
--

INSERT INTO `permission_role` (`id`, `role_id`, `permission_id`, `created_at`, `updated_at`) VALUES
(1, 4, 20, '2021-01-29 14:15:06', '2021-01-29 14:15:06'),
(2, 4, 21, '2021-01-29 14:15:06', '2021-01-29 14:15:06'),
(3, 4, 22, '2021-01-29 14:15:06', '2021-01-29 14:15:06'),
(4, 4, 23, '2021-01-29 14:15:06', '2021-01-29 14:15:06'),
(5, 4, 24, '2021-01-29 14:15:06', '2021-01-29 14:15:06'),
(6, 4, 25, '2021-01-29 14:15:06', '2021-01-29 14:15:06'),
(7, 4, 26, '2021-01-29 14:15:06', '2021-01-29 14:15:06'),
(8, 4, 27, '2021-01-29 14:15:06', '2021-01-29 14:15:06'),
(9, 4, 28, '2021-01-29 14:15:06', '2021-01-29 14:15:06'),
(10, 4, 29, '2021-01-29 14:15:06', '2021-01-29 14:15:06'),
(11, 4, 30, '2021-01-29 14:15:06', '2021-01-29 14:15:06'),
(12, 4, 41, '2021-01-29 14:15:06', '2021-01-29 14:15:06'),
(13, 4, 42, '2021-01-29 14:15:06', '2021-01-29 14:15:06'),
(14, 4, 43, '2021-01-29 14:15:06', '2021-01-29 14:15:06'),
(15, 4, 44, '2021-01-29 14:15:06', '2021-01-29 14:15:06'),
(16, 4, 45, '2021-01-29 14:15:06', '2021-01-29 14:15:06'),
(17, 4, 51, '2021-01-29 14:15:06', '2021-01-29 14:15:06'),
(18, 4, 52, '2021-01-29 14:15:06', '2021-01-29 14:15:06'),
(19, 4, 53, '2021-01-29 14:15:06', '2021-01-29 14:15:06'),
(20, 4, 54, '2021-01-29 14:15:06', '2021-01-29 14:15:06'),
(21, 4, 55, '2021-01-29 14:15:06', '2021-01-29 14:15:06'),
(22, 4, 69, '2021-01-29 14:15:06', '2021-01-29 14:15:06'),
(23, 4, 70, '2021-01-29 14:15:06', '2021-01-29 14:15:06'),
(24, 4, 71, '2021-01-29 14:15:06', '2021-01-29 14:15:06'),
(25, 4, 93, '2021-01-29 14:15:06', '2021-01-29 14:15:06'),
(26, 4, 94, '2021-01-29 14:15:06', '2021-01-29 14:15:06'),
(27, 4, 95, '2021-01-29 14:15:06', '2021-01-29 14:15:06'),
(28, 4, 96, '2021-01-29 14:15:06', '2021-01-29 14:15:06'),
(29, 4, 118, '2021-01-29 14:15:06', '2021-01-29 14:15:06'),
(30, 4, 119, '2021-01-29 14:15:06', '2021-01-29 14:15:06'),
(31, 4, 120, '2021-01-29 14:15:06', '2021-01-29 14:15:06'),
(32, 4, 121, '2021-01-29 14:15:06', '2021-01-29 14:15:06'),
(33, 4, 122, '2021-01-29 14:15:06', '2021-01-29 14:15:06'),
(34, 4, 123, '2021-01-29 14:15:06', '2021-01-29 14:15:06'),
(35, 4, 10, '2021-01-29 14:35:27', '2021-01-29 14:35:27'),
(36, 4, 11, '2021-01-29 14:35:27', '2021-01-29 14:35:27'),
(37, 4, 12, '2021-01-29 14:35:27', '2021-01-29 14:35:27'),
(38, 4, 13, '2021-01-29 14:35:27', '2021-01-29 14:35:27'),
(39, 4, 14, '2021-01-29 14:35:27', '2021-01-29 14:35:27'),
(41, 4, 117, '2021-01-29 15:09:24', '2021-01-29 15:09:24');

-- --------------------------------------------------------

--
-- Table structure for table `personas`
--

CREATE TABLE `personas` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tipo_persona` enum('Proveedor','Cliente','Administrador_mesa','Inactivo') COLLATE utf8mb4_unicode_ci NOT NULL,
  `nombre` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tipo_documento` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `num_documento` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL,
  `direccion` varchar(256) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `telefono` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `isCortesia` int(10) UNSIGNED DEFAULT NULL,
  `isCredito` int(10) UNSIGNED DEFAULT NULL,
  `imagen` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `limite_fecha` int(11) DEFAULT NULL,
  `limite_monto` decimal(25,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `precios`
--

CREATE TABLE `precios` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `horario_id` bigint(20) UNSIGNED NOT NULL,
  `cat_id` bigint(20) UNSIGNED NOT NULL,
  `precio` decimal(25,3) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `precios`
--

INSERT INTO `precios` (`id`, `horario_id`, `cat_id`, `precio`, `created_at`, `updated_at`) VALUES
(1, 1, 1, '4.000', '2021-02-17 20:34:06', '2021-02-17 20:34:06'),
(2, 2, 1, '6.000', '2021-02-17 20:34:22', '2021-02-17 20:34:22'),
(3, 3, 1, '10.000', '2021-02-17 20:34:29', '2021-02-17 20:34:29'),
(4, 1, 2, '6.000', '2021-02-17 20:34:43', '2021-02-17 20:34:43'),
(5, 2, 2, '8.000', '2021-02-17 20:34:50', '2021-02-17 20:34:50'),
(6, 3, 2, '12.000', '2021-02-17 20:35:02', '2021-02-17 20:35:02'),
(7, 1, 3, '7.000', '2021-02-17 20:35:13', '2021-02-17 20:35:13'),
(8, 2, 3, '9.000', '2021-02-17 20:35:22', '2021-02-17 20:35:22'),
(9, 3, 3, '14.000', '2021-02-17 20:35:30', '2021-02-17 20:35:30'),
(10, 1, 4, '7.000', '2021-02-17 20:35:41', '2021-02-17 20:35:41'),
(11, 2, 4, '9.000', '2021-02-17 20:35:48', '2021-02-17 20:35:48'),
(12, 3, 4, '14.000', '2021-02-17 20:35:54', '2021-02-17 20:35:54'),
(13, 1, 5, '7.000', '2021-02-17 20:36:02', '2021-02-17 20:36:02'),
(14, 2, 5, '9.000', '2021-02-17 20:36:09', '2021-02-17 20:36:09'),
(15, 3, 5, '14.000', '2021-02-17 20:36:15', '2021-02-17 20:36:15');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `full-access` enum('yes','no') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `slug`, `description`, `full-access`, `created_at`, `updated_at`) VALUES
(1, 'Admin', 'admin', 'Administrator', 'yes', '2020-12-25 22:34:22', '2020-12-25 22:34:22'),
(2, 'Registered User', 'registereduser', 'Registered User', 'no', '2020-12-25 22:34:22', '2020-12-25 22:34:22'),
(3, 'Camarera', 'camarera', 'Limpieza de habitaciones', 'no', '2021-01-13 05:57:31', '2021-01-13 05:57:31'),
(4, 'Recepcionista', 'recepcionista', 'Recepcionista del sistema', 'no', '2021-01-29 14:15:06', '2021-01-29 14:15:06');

-- --------------------------------------------------------

--
-- Table structure for table `role_user`
--

CREATE TABLE `role_user` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `role_user`
--

INSERT INTO `role_user` (`id`, `role_id`, `user_id`, `created_at`, `updated_at`) VALUES
(1, 1, 1, '2020-12-25 22:34:22', '2020-12-25 22:34:22'),
(2, 1, 2, '2020-12-25 22:34:22', '2020-12-25 22:34:22'),
(4, 3, 3, '2021-01-13 05:59:19', '2021-01-13 05:59:19'),
(6, 4, 4, '2021-01-29 14:15:31', '2021-01-29 14:15:31'),
(8, 4, 5, '2021-01-29 14:48:40', '2021-01-29 14:48:40'),
(10, 4, 6, '2021-01-29 14:50:57', '2021-01-29 14:50:57'),
(12, 4, 7, '2021-02-17 20:43:51', '2021-02-17 20:43:51'),
(14, 4, 8, '2021-02-17 20:52:03', '2021-02-17 20:52:03');

-- --------------------------------------------------------

--
-- Table structure for table `servicios`
--

CREATE TABLE `servicios` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `num_servicio` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `operador` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status_servicio` enum('Iniciado','Finalizado') COLLATE utf8mb4_unicode_ci NOT NULL,
  `nombre_habitacion` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `detalle_habitacion` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tipo_habitacion` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `horario` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fecha_entrada` date NOT NULL,
  `hora_entrada` time NOT NULL,
  `fecha_salida` date NOT NULL,
  `hora_salida` time NOT NULL,
  `tasaDolar` decimal(25,2) DEFAULT NULL,
  `porDolar` decimal(25,2) DEFAULT NULL,
  `tasaPeso` decimal(25,2) DEFAULT NULL,
  `porPeso` decimal(25,2) DEFAULT NULL,
  `tasaTransPunto` decimal(25,2) DEFAULT NULL,
  `porTransPunto` decimal(25,2) DEFAULT NULL,
  `tasaMixto` decimal(25,2) DEFAULT NULL,
  `porMixto` decimal(25,2) DEFAULT NULL,
  `tasaEfectivo` decimal(25,2) DEFAULT NULL,
  `porEfectivo` decimal(25,2) DEFAULT NULL,
  `tasaDolarHabitacion` decimal(25,2) DEFAULT NULL,
  `porDolarHabitacion` decimal(25,2) DEFAULT NULL,
  `tasaPesoHabitacion` decimal(25,2) DEFAULT NULL,
  `porPesoHabitacion` decimal(25,2) DEFAULT NULL,
  `num_Punto` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `num_Trans` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `modo_pago` enum('Contado','Crédito','Cortesía') COLLATE utf8mb4_unicode_ci NOT NULL,
  `tipo_pago` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('Pagado','Falta pagar','Exonerado') COLLATE utf8mb4_unicode_ci NOT NULL,
  `precio_costo` decimal(25,9) DEFAULT NULL,
  `cantidad` int(10) UNSIGNED DEFAULT NULL,
  `dinero_dejado` decimal(25,2) DEFAULT NULL,
  `total_venta` decimal(25,3) DEFAULT NULL,
  `estado` enum('Aceptada','Cancelada','Procesando') COLLATE utf8mb4_unicode_ci NOT NULL,
  `nombre_cliente` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cedula_cliente` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `direccion_cliente` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `telefono_cliente` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `limite_fecha` int(11) DEFAULT NULL,
  `limite_monto` decimal(25,2) DEFAULT NULL,
  `persona_id` bigint(20) UNSIGNED NOT NULL,
  `habitacion_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `caja_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `servicios__ventas`
--

CREATE TABLE `servicios__ventas` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `cantidad` int(10) UNSIGNED DEFAULT NULL,
  `precio_costo_unidad` decimal(25,9) DEFAULT NULL,
  `precio_venta_unidad` decimal(25,9) DEFAULT NULL,
  `porEspecial` decimal(25,2) DEFAULT NULL,
  `isDolar` int(10) UNSIGNED DEFAULT NULL,
  `isPeso` int(10) UNSIGNED DEFAULT NULL,
  `isTransPunto` int(10) UNSIGNED DEFAULT NULL,
  `isMixto` int(10) UNSIGNED DEFAULT NULL,
  `isEfectivo` int(10) UNSIGNED DEFAULT NULL,
  `descuento` decimal(25,3) DEFAULT NULL,
  `estado_pago` enum('Pagado','Falta pagar','Exonerado') COLLATE utf8mb4_unicode_ci NOT NULL,
  `tipo_pago` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `articulo_id` bigint(20) UNSIGNED NOT NULL,
  `servicio_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sessioncajas`
--

CREATE TABLE `sessioncajas` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `estado` enum('Abierta','Cerrada','Auditoria') COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessioncajas`
--

INSERT INTO `sessioncajas` (`id`, `estado`, `created_at`, `updated_at`) VALUES
(1, 'Abierta', '2021-02-17 20:42:55', '2021-02-17 20:42:55');

-- --------------------------------------------------------

--
-- Table structure for table `sucursals`
--

CREATE TABLE `sucursals` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nombre` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `telefono_fijo` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `telefono_mobil` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `direccion` varchar(256) COLLATE utf8mb4_unicode_ci NOT NULL,
  `estado` enum('Activa','Cancelada','Suspendida') COLLATE utf8mb4_unicode_ci NOT NULL,
  `empresa_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sucursals`
--

INSERT INTO `sucursals` (`id`, `nombre`, `telefono_fijo`, `telefono_mobil`, `direccion`, `estado`, `empresa_id`, `created_at`, `updated_at`) VALUES
(1, 'La Mansion', '0424-7665227', '0424-7665227', 'Calle #3 El Vigía.', 'Activa', 1, '2020-12-25 22:34:29', '2020-12-25 22:34:29');

-- --------------------------------------------------------

--
-- Table structure for table `tasas`
--

CREATE TABLE `tasas` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nombre` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tasa` decimal(25,2) NOT NULL DEFAULT '0.00',
  `porcentaje_ganancia` decimal(25,2) NOT NULL DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tasas`
--

INSERT INTO `tasas` (`id`, `nombre`, `tasa`, `porcentaje_ganancia`, `created_at`, `updated_at`) VALUES
(1, 'Dolar', '1.00', '0.00', '2020-12-25 22:34:29', '2021-02-17 09:31:14'),
(2, 'Peso', '3500.00', '0.00', '2020-12-25 22:34:29', '2021-02-08 22:22:50'),
(3, 'Transferencia_Punto', '1800000.00', '0.00', '2020-12-25 22:34:29', '2021-02-08 22:22:50'),
(4, 'Mixto', '1800000.00', '0.00', '2020-12-25 22:34:29', '2021-02-08 22:22:50'),
(5, 'Efectivo', '1800000.00', '0.00', '2020-12-25 22:34:29', '2021-02-08 22:22:50'),
(6, 'EfectivoVenta', '1800000.00', '0.00', '2020-12-25 22:34:29', '2021-02-08 22:22:50'),
(7, 'DolarHabitacion', '1800000.00', '0.00', '2020-12-25 22:34:29', '2021-02-08 22:22:50'),
(8, 'PesoHabitacion', '3500.00', '0.00', '2020-12-25 22:34:29', '2021-02-08 22:22:50');

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tipo_operacion` enum('Cargo','Descargo','Transferencia') COLLATE utf8mb4_unicode_ci NOT NULL,
  `tipo_descargo` enum('Normal','Autoconsumo','Retiro') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deposito` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `num_documento` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `autorizado_por` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `proposito` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `detalle` text COLLATE utf8mb4_unicode_ci,
  `total_operacion` decimal(25,3) NOT NULL,
  `estado` enum('Aceptado','Cancelado') COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`id`, `tipo_operacion`, `tipo_descargo`, `deposito`, `num_documento`, `user_id`, `autorizado_por`, `proposito`, `detalle`, `total_operacion`, `estado`, `created_at`, `updated_at`) VALUES
(1, 'Cargo', NULL, '1', 'CG100000001', 1, 'ROSAURA ARRIAS', 'INGRESO INV.16022021 NUEVOS', 'INGRESO INV.16022021 NUEVOS', '991.680', 'Aceptado', '2021-02-17 20:02:04', '2021-02-17 20:02:04');

-- --------------------------------------------------------

--
-- Table structure for table `transferencias`
--

CREATE TABLE `transferencias` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `accion` enum('Mayor a Detal','Detal a Mayor') COLLATE utf8mb4_unicode_ci NOT NULL,
  `origen_id` int(10) UNSIGNED NOT NULL,
  `origenNombreProducto` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `origenStockInicial` int(10) UNSIGNED NOT NULL,
  `origenStockFinal` int(10) UNSIGNED NOT NULL,
  `origenUnidades` int(10) UNSIGNED NOT NULL,
  `origenVender_al` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cantidadRestarOrigen` int(10) UNSIGNED NOT NULL,
  `destino_id` int(10) UNSIGNED NOT NULL,
  `destinoNombreProducto` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `destinoStockInicial` int(10) UNSIGNED NOT NULL,
  `destinoStockFinal` int(10) UNSIGNED NOT NULL,
  `destinoUnidades` int(10) UNSIGNED NOT NULL,
  `destinoVender_al` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cantidadSumarDestino` int(10) UNSIGNED NOT NULL,
  `operador` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Jhonny Sagid Pirela Pineda', 'jhosagid77@gmail.com', NULL, '$2y$10$bXp5a7kcjK5B.XqOhCvRiud6389ujXBDVfg8GL5BMbOev.7BuiUXW', 'Kzen10uJy1CNSNBuMkzlHYmaGboNhueVQsfD7N3R1GPQAtbZ89Xl1Wm6fJa8', '2020-12-25 22:34:21', '2020-12-25 22:34:21'),
(2, 'admin', 'admin@admin.com', NULL, '$2y$10$7VKedW/sRY37bYd7.iOfh.iFastDHrZm75oWrLefKJN0tGNL8dxsW', NULL, '2020-12-25 22:34:21', '2020-12-25 22:34:21'),
(3, 'Maria Gutierrez', 'maria@gmail.com', NULL, '$2y$10$4hTLTW8a3j72IJcIrr3FPeS.AHX3NOCnh6pM9bCd9JS1w6eQqyqt2', NULL, '2021-01-13 05:58:49', '2021-01-13 05:58:49'),
(4, 'Irene Suarez', 'irenesuarezr@gmail.com', NULL, '$2y$10$oK/TIh6HkTbY6xCos7Rrn.k.e9GRZ9k6fvo5LIrUB8hGf.tpd79Yy', 'w0d52BJRzQ7BnY4fyra3Y4L5lZUnsroQBGOdgE7YLvT60AB55T7nMhawO5aK', '2021-01-29 14:07:32', '2021-01-29 14:07:32'),
(5, 'Usuario Emergete', 'usuarioemergente@gmail.com', NULL, '$2y$10$lqoQkFSa4mT87hrNnjGhL.0EXDqScAkmUBrPcfjw.GIhWF4JjvMze', NULL, '2021-01-29 14:48:17', '2021-01-29 14:48:17'),
(6, 'Yani Gonzalez', 'yanig224@gmail.com', NULL, '$2y$10$rGVZpd46Dx8AoV5YrFeHQOmiT3gcNqHxP/4jqca1ys81haCR2ipT.', 'wNiR6zGmxgZUGPM6TlMTgy9tJCjjps2LMXqHII6lMhtLmGz6wDSkhfpnXD3r', '2021-01-29 14:49:18', '2021-01-29 14:49:18'),
(7, 'ADNERIS', 'adnerisjoani@gmail.com', NULL, '$2y$10$e0NSJzYgXAH25sP.CsNl/.EJm7ZjK9T52Y7aCDI6Vt7JRuT4UJXhO', NULL, '2021-02-17 20:42:55', '2021-02-17 20:42:55'),
(8, 'VIRGINIA', 'virginia@gmail.com', NULL, '$2y$10$hS0qHIyuqfDA2p67S2PF5.HdymKr3xVgdjrKEB2RLoFJpoYQjFJXW', NULL, '2021-02-17 20:51:29', '2021-02-17 20:51:29');

-- --------------------------------------------------------

--
-- Table structure for table `ventas`
--

CREATE TABLE `ventas` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tipo_comprobante` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `serie_comprobante` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `num_comprobante` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fecha_hora` datetime NOT NULL,
  `modo_pago` enum('Contado','Crédito','Cortesía') COLLATE utf8mb4_unicode_ci NOT NULL,
  `tipo_pago` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('Pagado','Falta pagar','Exonerado') COLLATE utf8mb4_unicode_ci NOT NULL,
  `tasaDolar` decimal(25,2) DEFAULT NULL,
  `porDolar` decimal(25,2) DEFAULT NULL,
  `tasaPeso` decimal(25,2) DEFAULT NULL,
  `porPeso` decimal(25,2) DEFAULT NULL,
  `tasaTransPunto` decimal(25,2) DEFAULT NULL,
  `porTransPunto` decimal(25,2) DEFAULT NULL,
  `tasaMixto` decimal(25,2) DEFAULT NULL,
  `porMixto` decimal(25,2) DEFAULT NULL,
  `tasaEfectivo` decimal(25,2) DEFAULT NULL,
  `porEfectivo` decimal(25,2) DEFAULT NULL,
  `num_Punto` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `num_Trans` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `precio_costo` decimal(25,9) DEFAULT NULL,
  `margen_ganancia` decimal(25,2) DEFAULT NULL,
  `total_venta` decimal(25,3) DEFAULT NULL,
  `ganancia_neta` decimal(25,3) DEFAULT NULL,
  `estado` enum('Aceptada','Cancelada','Procesando') COLLATE utf8mb4_unicode_ci NOT NULL,
  `persona_id` bigint(20) UNSIGNED NOT NULL,
  `caja_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `articulos`
--
ALTER TABLE `articulos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `articulos_categoria_id_foreign` (`categoria_id`);

--
-- Indexes for table `articulo_transactions`
--
ALTER TABLE `articulo_transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `articulo_transactions_articulo_id_foreign` (`articulo_id`),
  ADD KEY `articulo_transactions_transaction_id_foreign` (`transaction_id`);

--
-- Indexes for table `articulo_ventas`
--
ALTER TABLE `articulo_ventas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `articulo_ventas_articulo_id_foreign` (`articulo_id`),
  ADD KEY `articulo_ventas_venta_id_foreign` (`venta_id`);

--
-- Indexes for table `articulo__ingresos`
--
ALTER TABLE `articulo__ingresos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `articulo__ingresos_ingreso_id_foreign` (`ingreso_id`),
  ADD KEY `articulo__ingresos_articulo_id_foreign` (`articulo_id`);

--
-- Indexes for table `cajas`
--
ALTER TABLE `cajas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cajas_user_id_foreign` (`user_id`),
  ADD KEY `cajas_sucursal_id_foreign` (`sucursal_id`),
  ADD KEY `cajas_sessioncaja_id_foreign` (`sessioncaja_id`);

--
-- Indexes for table `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cats`
--
ALTER TABLE `cats`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `contabilidads`
--
ALTER TABLE `contabilidads`
  ADD PRIMARY KEY (`id`),
  ADD KEY `contabilidads_caja_id_foreign` (`caja_id`);

--
-- Indexes for table `cortesias`
--
ALTER TABLE `cortesias`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cortesias_persona_id_foreign` (`persona_id`),
  ADD KEY `cortesias_servicio_id_foreign` (`servicio_id`);

--
-- Indexes for table `creditos`
--
ALTER TABLE `creditos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `creditos_persona_id_foreign` (`persona_id`),
  ADD KEY `creditos_user_id_foreign` (`user_id`);

--
-- Indexes for table `credito__pagados`
--
ALTER TABLE `credito__pagados`
  ADD PRIMARY KEY (`id`),
  ADD KEY `credito__pagados_persona_id_foreign` (`persona_id`),
  ADD KEY `credito__pagados_detalle_credito_id_foreign` (`detalle_credito_id`),
  ADD KEY `credito__pagados_credito_id_foreign` (`credito_id`),
  ADD KEY `credito__pagados_caja_id_foreign` (`caja_id`);

--
-- Indexes for table `denominacions`
--
ALTER TABLE `denominacions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `detalle_creditos`
--
ALTER TABLE `detalle_creditos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `detalle_creditos_persona_id_foreign` (`persona_id`),
  ADD KEY `detalle_creditos_credito_id_foreign` (`credito_id`),
  ADD KEY `detalle_creditos_caja_id_foreign` (`caja_id`);

--
-- Indexes for table `empresas`
--
ALTER TABLE `empresas`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `excedentes`
--
ALTER TABLE `excedentes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `excedentes_persona_id_foreign` (`persona_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `habitaciones`
--
ALTER TABLE `habitaciones`
  ADD PRIMARY KEY (`id`),
  ADD KEY `habitaciones_cat_id_foreign` (`cat_id`),
  ADD KEY `habitaciones_level_id_foreign` (`level_id`);

--
-- Indexes for table `horarios`
--
ALTER TABLE `horarios`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ingresos`
--
ALTER TABLE `ingresos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ingresos_persona_id_foreign` (`persona_id`),
  ADD KEY `ingresos_user_id_foreign` (`user_id`);

--
-- Indexes for table `levels`
--
ALTER TABLE `levels`
  ADD PRIMARY KEY (`id`),
  ADD KEY `levels_sucursal_id_foreign` (`sucursal_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pago__creditos`
--
ALTER TABLE `pago__creditos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pago__creditos_detalle_credito_id_foreign` (`detalle_credito_id`);

--
-- Indexes for table `pago__servicios`
--
ALTER TABLE `pago__servicios`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pago__servicios_servicio_id_foreign` (`servicio_id`);

--
-- Indexes for table `pago__ventas`
--
ALTER TABLE `pago__ventas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pago__ventas_venta_id_foreign` (`venta_id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permissions_name_unique` (`name`),
  ADD UNIQUE KEY `permissions_slug_unique` (`slug`);

--
-- Indexes for table `permission_role`
--
ALTER TABLE `permission_role`
  ADD PRIMARY KEY (`id`),
  ADD KEY `permission_role_role_id_foreign` (`role_id`),
  ADD KEY `permission_role_permission_id_foreign` (`permission_id`);

--
-- Indexes for table `personas`
--
ALTER TABLE `personas`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `precios`
--
ALTER TABLE `precios`
  ADD PRIMARY KEY (`id`),
  ADD KEY `precios_horario_id_foreign` (`horario_id`),
  ADD KEY `precios_cat_id_foreign` (`cat_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_unique` (`name`),
  ADD UNIQUE KEY `roles_slug_unique` (`slug`);

--
-- Indexes for table `role_user`
--
ALTER TABLE `role_user`
  ADD PRIMARY KEY (`id`),
  ADD KEY `role_user_role_id_foreign` (`role_id`),
  ADD KEY `role_user_user_id_foreign` (`user_id`);

--
-- Indexes for table `servicios`
--
ALTER TABLE `servicios`
  ADD PRIMARY KEY (`id`),
  ADD KEY `servicios_persona_id_foreign` (`persona_id`),
  ADD KEY `servicios_habitacion_id_foreign` (`habitacion_id`),
  ADD KEY `servicios_user_id_foreign` (`user_id`),
  ADD KEY `servicios_caja_id_foreign` (`caja_id`);

--
-- Indexes for table `servicios__ventas`
--
ALTER TABLE `servicios__ventas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `servicios__ventas_articulo_id_foreign` (`articulo_id`),
  ADD KEY `servicios__ventas_servicio_id_foreign` (`servicio_id`);

--
-- Indexes for table `sessioncajas`
--
ALTER TABLE `sessioncajas`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sucursals`
--
ALTER TABLE `sucursals`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sucursals_empresa_id_foreign` (`empresa_id`);

--
-- Indexes for table `tasas`
--
ALTER TABLE `tasas`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `transactions_user_id_foreign` (`user_id`);

--
-- Indexes for table `transferencias`
--
ALTER TABLE `transferencias`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indexes for table `ventas`
--
ALTER TABLE `ventas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ventas_persona_id_foreign` (`persona_id`),
  ADD KEY `ventas_caja_id_foreign` (`caja_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `articulos`
--
ALTER TABLE `articulos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `articulo_transactions`
--
ALTER TABLE `articulo_transactions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `articulo_ventas`
--
ALTER TABLE `articulo_ventas`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `articulo__ingresos`
--
ALTER TABLE `articulo__ingresos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cajas`
--
ALTER TABLE `cajas`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `categorias`
--
ALTER TABLE `categorias`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `cats`
--
ALTER TABLE `cats`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `contabilidads`
--
ALTER TABLE `contabilidads`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cortesias`
--
ALTER TABLE `cortesias`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `creditos`
--
ALTER TABLE `creditos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `credito__pagados`
--
ALTER TABLE `credito__pagados`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `denominacions`
--
ALTER TABLE `denominacions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `detalle_creditos`
--
ALTER TABLE `detalle_creditos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `empresas`
--
ALTER TABLE `empresas`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `excedentes`
--
ALTER TABLE `excedentes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `habitaciones`
--
ALTER TABLE `habitaciones`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `horarios`
--
ALTER TABLE `horarios`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `ingresos`
--
ALTER TABLE `ingresos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `levels`
--
ALTER TABLE `levels`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;

--
-- AUTO_INCREMENT for table `pago__creditos`
--
ALTER TABLE `pago__creditos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pago__servicios`
--
ALTER TABLE `pago__servicios`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pago__ventas`
--
ALTER TABLE `pago__ventas`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=132;

--
-- AUTO_INCREMENT for table `permission_role`
--
ALTER TABLE `permission_role`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `personas`
--
ALTER TABLE `personas`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `precios`
--
ALTER TABLE `precios`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `role_user`
--
ALTER TABLE `role_user`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `servicios`
--
ALTER TABLE `servicios`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `servicios__ventas`
--
ALTER TABLE `servicios__ventas`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sessioncajas`
--
ALTER TABLE `sessioncajas`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `sucursals`
--
ALTER TABLE `sucursals`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tasas`
--
ALTER TABLE `tasas`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `transferencias`
--
ALTER TABLE `transferencias`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `ventas`
--
ALTER TABLE `ventas`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `articulos`
--
ALTER TABLE `articulos`
  ADD CONSTRAINT `articulos_categoria_id_foreign` FOREIGN KEY (`categoria_id`) REFERENCES `categorias` (`id`);

--
-- Constraints for table `articulo_transactions`
--
ALTER TABLE `articulo_transactions`
  ADD CONSTRAINT `articulo_transactions_articulo_id_foreign` FOREIGN KEY (`articulo_id`) REFERENCES `articulos` (`id`),
  ADD CONSTRAINT `articulo_transactions_transaction_id_foreign` FOREIGN KEY (`transaction_id`) REFERENCES `transactions` (`id`);

--
-- Constraints for table `articulo_ventas`
--
ALTER TABLE `articulo_ventas`
  ADD CONSTRAINT `articulo_ventas_articulo_id_foreign` FOREIGN KEY (`articulo_id`) REFERENCES `articulos` (`id`),
  ADD CONSTRAINT `articulo_ventas_venta_id_foreign` FOREIGN KEY (`venta_id`) REFERENCES `ventas` (`id`);

--
-- Constraints for table `articulo__ingresos`
--
ALTER TABLE `articulo__ingresos`
  ADD CONSTRAINT `articulo__ingresos_articulo_id_foreign` FOREIGN KEY (`articulo_id`) REFERENCES `articulos` (`id`),
  ADD CONSTRAINT `articulo__ingresos_ingreso_id_foreign` FOREIGN KEY (`ingreso_id`) REFERENCES `ingresos` (`id`);

--
-- Constraints for table `cajas`
--
ALTER TABLE `cajas`
  ADD CONSTRAINT `cajas_sessioncaja_id_foreign` FOREIGN KEY (`sessioncaja_id`) REFERENCES `sessioncajas` (`id`),
  ADD CONSTRAINT `cajas_sucursal_id_foreign` FOREIGN KEY (`sucursal_id`) REFERENCES `sucursals` (`id`),
  ADD CONSTRAINT `cajas_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `contabilidads`
--
ALTER TABLE `contabilidads`
  ADD CONSTRAINT `contabilidads_caja_id_foreign` FOREIGN KEY (`caja_id`) REFERENCES `cajas` (`id`);

--
-- Constraints for table `cortesias`
--
ALTER TABLE `cortesias`
  ADD CONSTRAINT `cortesias_persona_id_foreign` FOREIGN KEY (`persona_id`) REFERENCES `personas` (`id`),
  ADD CONSTRAINT `cortesias_servicio_id_foreign` FOREIGN KEY (`servicio_id`) REFERENCES `servicios` (`id`);

--
-- Constraints for table `creditos`
--
ALTER TABLE `creditos`
  ADD CONSTRAINT `creditos_persona_id_foreign` FOREIGN KEY (`persona_id`) REFERENCES `personas` (`id`),
  ADD CONSTRAINT `creditos_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `credito__pagados`
--
ALTER TABLE `credito__pagados`
  ADD CONSTRAINT `credito__pagados_caja_id_foreign` FOREIGN KEY (`caja_id`) REFERENCES `cajas` (`id`),
  ADD CONSTRAINT `credito__pagados_credito_id_foreign` FOREIGN KEY (`credito_id`) REFERENCES `creditos` (`id`),
  ADD CONSTRAINT `credito__pagados_detalle_credito_id_foreign` FOREIGN KEY (`detalle_credito_id`) REFERENCES `detalle_creditos` (`id`),
  ADD CONSTRAINT `credito__pagados_persona_id_foreign` FOREIGN KEY (`persona_id`) REFERENCES `personas` (`id`);

--
-- Constraints for table `detalle_creditos`
--
ALTER TABLE `detalle_creditos`
  ADD CONSTRAINT `detalle_creditos_caja_id_foreign` FOREIGN KEY (`caja_id`) REFERENCES `cajas` (`id`),
  ADD CONSTRAINT `detalle_creditos_credito_id_foreign` FOREIGN KEY (`credito_id`) REFERENCES `creditos` (`id`),
  ADD CONSTRAINT `detalle_creditos_persona_id_foreign` FOREIGN KEY (`persona_id`) REFERENCES `personas` (`id`);

--
-- Constraints for table `excedentes`
--
ALTER TABLE `excedentes`
  ADD CONSTRAINT `excedentes_persona_id_foreign` FOREIGN KEY (`persona_id`) REFERENCES `personas` (`id`);

--
-- Constraints for table `habitaciones`
--
ALTER TABLE `habitaciones`
  ADD CONSTRAINT `habitaciones_cat_id_foreign` FOREIGN KEY (`cat_id`) REFERENCES `cats` (`id`),
  ADD CONSTRAINT `habitaciones_level_id_foreign` FOREIGN KEY (`level_id`) REFERENCES `levels` (`id`);

--
-- Constraints for table `ingresos`
--
ALTER TABLE `ingresos`
  ADD CONSTRAINT `ingresos_persona_id_foreign` FOREIGN KEY (`persona_id`) REFERENCES `personas` (`id`),
  ADD CONSTRAINT `ingresos_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `levels`
--
ALTER TABLE `levels`
  ADD CONSTRAINT `levels_sucursal_id_foreign` FOREIGN KEY (`sucursal_id`) REFERENCES `sucursals` (`id`);

--
-- Constraints for table `pago__creditos`
--
ALTER TABLE `pago__creditos`
  ADD CONSTRAINT `pago__creditos_detalle_credito_id_foreign` FOREIGN KEY (`detalle_credito_id`) REFERENCES `detalle_creditos` (`id`);

--
-- Constraints for table `pago__servicios`
--
ALTER TABLE `pago__servicios`
  ADD CONSTRAINT `pago__servicios_servicio_id_foreign` FOREIGN KEY (`servicio_id`) REFERENCES `servicios` (`id`);

--
-- Constraints for table `pago__ventas`
--
ALTER TABLE `pago__ventas`
  ADD CONSTRAINT `pago__ventas_venta_id_foreign` FOREIGN KEY (`venta_id`) REFERENCES `ventas` (`id`);

--
-- Constraints for table `permission_role`
--
ALTER TABLE `permission_role`
  ADD CONSTRAINT `permission_role_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `permission_role_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `precios`
--
ALTER TABLE `precios`
  ADD CONSTRAINT `precios_cat_id_foreign` FOREIGN KEY (`cat_id`) REFERENCES `cats` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `precios_horario_id_foreign` FOREIGN KEY (`horario_id`) REFERENCES `horarios` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `role_user`
--
ALTER TABLE `role_user`
  ADD CONSTRAINT `role_user_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_user_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `servicios`
--
ALTER TABLE `servicios`
  ADD CONSTRAINT `servicios_caja_id_foreign` FOREIGN KEY (`caja_id`) REFERENCES `cajas` (`id`),
  ADD CONSTRAINT `servicios_habitacion_id_foreign` FOREIGN KEY (`habitacion_id`) REFERENCES `habitaciones` (`id`),
  ADD CONSTRAINT `servicios_persona_id_foreign` FOREIGN KEY (`persona_id`) REFERENCES `personas` (`id`),
  ADD CONSTRAINT `servicios_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `servicios__ventas`
--
ALTER TABLE `servicios__ventas`
  ADD CONSTRAINT `servicios__ventas_articulo_id_foreign` FOREIGN KEY (`articulo_id`) REFERENCES `articulos` (`id`),
  ADD CONSTRAINT `servicios__ventas_servicio_id_foreign` FOREIGN KEY (`servicio_id`) REFERENCES `servicios` (`id`);

--
-- Constraints for table `sucursals`
--
ALTER TABLE `sucursals`
  ADD CONSTRAINT `sucursals_empresa_id_foreign` FOREIGN KEY (`empresa_id`) REFERENCES `empresas` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `transactions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
