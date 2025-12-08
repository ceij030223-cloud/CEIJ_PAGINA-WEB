-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3306
-- Tiempo de generación: 08-12-2025 a las 13:42:45
-- Versión del servidor: 11.8.3-MariaDB-log
-- Versión de PHP: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `u297348119_ceij_db`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `carrusel`
--

CREATE TABLE `carrusel` (
  `id` int(11) NOT NULL,
  `imagen` varchar(255) NOT NULL,
  `fecha_subida` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `carrusel`
--

INSERT INTO `carrusel` (`id`, `imagen`, `fecha_subida`) VALUES
(1, '1760376817_68ed37f19aafb_flyer_control_electrico.jpg', '2025-10-14 00:33:37'),
(2, '1760376817_68ed37f19ce54_flyer_excel.jpg', '2025-10-14 00:33:37'),
(3, '1760376817_68ed37f1a8ca5_flyer_incendios.jpg', '2025-10-14 00:33:37'),
(4, '1760376817_68ed37f1aa6a3_flyer_instalaciones_residenciales.jpg', '2025-10-14 00:33:37'),
(5, '1760376817_68ed37f1abd43_flyer_marketing_con_IA.jpg', '2025-10-14 00:33:37'),
(6, '1760376817_68ed37f1ad1e0_flyer_minisplit.jpg', '2025-10-14 00:33:37'),
(7, '1760376817_68ed37f1af511_flyer_montacargas.jpg', '2025-10-14 00:33:37'),
(8, '1760376817_68ed37f1b09b5_flyer_programacion_CNC.jpg', '2025-10-14 00:33:37'),
(9, '1760376817_68ed37f1b24a2_flyer_programacion_PLC.jpg', '2025-10-14 00:33:37'),
(10, '1760376817_68ed37f1b3a29_flyer_reparacion_minisplit.jpg', '2025-10-14 00:33:37'),
(11, '1760376817_68ed37f1b53ea_flyer_solidworks.jpg', '2025-10-14 00:33:37'),
(12, '1763659739_691f4fdb60f82_flyer_alturas.jpg', '2025-11-21 00:28:59');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cursos`
--

CREATE TABLE `cursos` (
  `id` int(11) NOT NULL,
  `titulo` varchar(100) NOT NULL,
  `descripcion` varchar(500) NOT NULL,
  `imagen` varchar(150) NOT NULL,
  `duracion` char(50) NOT NULL,
  `alumnos` int(11) NOT NULL,
  `fecha_inicio` date NOT NULL,
  `fecha_fin` date NOT NULL,
  `horario` char(50) NOT NULL,
  `dias` char(50) NOT NULL,
  `modalidad` enum('Presencial','En línea','Semipresencial') NOT NULL DEFAULT 'Presencial',
  `sucursal` char(50) NOT NULL,
  `costo_total` decimal(10,2) NOT NULL,
  `costo_inscripcion` decimal(10,2) NOT NULL,
  `costo_sesion` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `cursos`
--

INSERT INTO `cursos` (`id`, `titulo`, `descripcion`, `imagen`, `duracion`, `alumnos`, `fecha_inicio`, `fecha_fin`, `horario`, `dias`, `modalidad`, `sucursal`, `costo_total`, `costo_inscripcion`, `costo_sesion`) VALUES
(1, 'Instalación y Mantenimiento Preventivo de Minisplit 1', 'Descubre paso a paso cómo instala, reubicar y dar mantenimiento a estos sistemas de climatización.', '../img/cursos/curso_6924ad3b8d6d1.jpg', '6 Semanas', 11, '2026-01-17', '2026-02-21', '8:00 am - 12:00 pm', 'Sabados', 'Presencial', 'Piña', 3550.00, 1000.00, 510.00),
(2, 'Instalación y Mantenimiento Preventivo de Minisplit 2', 'Descubre cómo instalar, reubicar y dar mantenimiento a estos sistemas de climatización.\r\n', '../img/cursos/curso_6925d92860f4a.jpg', ' 6 Semanas', 11, '2026-01-25', '2025-03-01', '9:00 am - 1:00 pm', 'Domingos', 'Presencial', 'Piña', 3550.00, 1000.00, 510.00),
(3, 'Instalaciones Eléctricas Residenciales ', 'Con instructores expertos y contenido práctico, estarás listo para abordar cualquier proyecto eléctrico  residencial. ', '../img/cursos/curso_6925dea26705a.jpg', '6 Semanas', 10, '2026-01-18', '2026-02-22', '8:00 am - 1:00 pm', 'Domingos', 'Presencial', 'Mezquital', 3250.00, 1000.00, 450.00),
(4, 'Programación y Cableado PLC', 'Este curso es la oportunidad perfecta para adquirir habilidades en automatización.                           Requisito: Conocimientos en control eléctrico.', '../img/cursos/curso_6925e44eaa6b0.jpg', '6 Semanas', 10, '2026-01-24', '2026-02-28', '8:00 am - 1:00 pm', 'Sábados', 'Presencial', 'Mezquital', 3250.00, 1000.00, 450.00),
(5, 'Programación de PLC', 'No pierdas la oportunidad de aprender de expertos en el campo y convertirte en un profesional altamente capacitado.\r\n', '../img/cursos/curso_6925e60fb6ec5.jpg', '6 Semanas', 10, '2026-01-17', '2026-02-22', '2:00 pm - 7:00 pm', 'Domingos', 'Presencial', 'Piña', 3250.00, 1000.00, 450.00),
(6, 'Programación y Manufactura CNC ', 'Este curso te enseñará desde cero cómo preparar y ejecutar programas CNC, combinando programación manual en máquina y programación asistida por software, además de realizar prácticas en un centro de maquinado profesional.   ', '../img/cursos/curso_692728f0c65e4.jpg', '6 Semanas', 10, '2026-01-18', '2026-02-22', '8:00 am - 1:00 pm', 'Domingos', 'Presencial', 'Piña', 3500.00, 1000.00, 500.00),
(7, ' Control Eléctrico Industrial ', ' Aprende de forma práctica y efectiva, para que desarrolles habilidades reales en el área industrial.\r\n\r\n', '../img/cursos/curso_69272a3ed9f0b.jpg', '6 Semanas', 10, '2026-01-17', '2026-02-14', '1:30 pm - 6:30 pm', 'Sábados', 'Presencial', 'Mezquital', 3250.00, 1000.00, 450.00),
(8, 'Sublimación para principiantes', 'Qué aprenderás: Fundamentos de sublimación, preparación de diseños, selección de materiales y usos de prensa, técnicas básicas de transferencia y acabados profesionales, consejos para resolver problemas comunes y obtener resultados duraderos, entre muchas cosas más. ', '../img/cursos/curso_692730266f7ea.jpg', '8 Sábados', 10, '2026-01-17', '2026-03-07', '12:00 am - 12:00 am', 'Sábados', 'Presencial', 'Piña', 3000.00, 1000.00, 285.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `imagenes_galeria`
--

CREATE TABLE `imagenes_galeria` (
  `id_imagen` int(11) NOT NULL,
  `id_seccion` int(11) NOT NULL,
  `ruta` varchar(150) NOT NULL,
  `tipo` enum('imagen','video') NOT NULL DEFAULT 'imagen'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `imagenes_galeria`
--

INSERT INTO `imagenes_galeria` (`id_imagen`, `id_seccion`, `ruta`, `tipo`) VALUES
(1, 1, '../img/galeria/1759773338_2soli.jpg', 'imagen'),
(2, 1, '../img/galeria/1759852682_4soli.jpg', 'imagen'),
(3, 1, '../img/galeria/1759852682_5soli.jpg', 'imagen'),
(4, 1, '../img/galeria/1759852682_6soli.jpg', 'imagen'),
(5, 1, '../img/galeria/1759852682_7soli.jpg', 'imagen'),
(6, 1, '../img/galeria/1759852682_8soli.jpg', 'imagen'),
(7, 1, '../img/galeria/1759852682_9soli.jpg', 'imagen'),
(8, 1, '../img/galeria/1759852682_10soli.jpg', 'imagen'),
(9, 1, '../img/galeria/1759852682_11soli.jpg', 'imagen'),
(10, 1, '../img/galeria/1759852682_15soli.jpg', 'imagen'),
(11, 2, '../img/galeria/1759855285_4con.jpg', 'imagen'),
(12, 2, '../img/galeria/1759855285_5con.jpg', 'imagen'),
(13, 2, '../img/galeria/1759855285_6con.jpg', 'imagen'),
(14, 2, '../img/galeria/1759940082_1con.jpg', 'imagen'),
(15, 2, '../img/galeria/1759940082_2con.jpg', 'imagen'),
(16, 2, '../img/galeria/1759940082_3con.jpg', 'imagen'),
(17, 3, '../img/galeria/1759943846_1cnc.jpg', 'imagen'),
(18, 3, '../img/galeria/1759943846_2cnc.jpg', 'imagen'),
(19, 3, '../img/galeria/1759943846_3cnc.jpg', 'imagen'),
(20, 3, '../img/galeria/1759943846_4cnc.jpg', 'imagen'),
(21, 3, '../img/galeria/1759943846_5cnc.jpg', 'imagen'),
(22, 3, '../img/galeria/1759943846_6cnc.jpg', 'imagen'),
(23, 4, '../img/galeria/1759946462_1res.jpg', 'imagen'),
(24, 4, '../img/galeria/1759946462_2res.jpg', 'imagen'),
(25, 4, '../img/galeria/1759946462_3res.jpg', 'imagen'),
(26, 4, '../img/galeria/1759946462_4res.jpg', 'imagen'),
(27, 4, '../img/galeria/1759946462_5res.jpg', 'imagen'),
(28, 4, '../img/galeria/1759946462_6res.jpg', 'imagen'),
(29, 4, '../img/galeria/1759946462_7res.jpg', 'imagen'),
(30, 4, '../img/galeria/1759946462_8res.jpg', 'imagen'),
(31, 4, '../img/galeria/1759946462_9res.jpg', 'imagen'),
(32, 4, '../img/galeria/1759946462_10res.jpg', 'imagen'),
(33, 4, '../img/galeria/1759946462_11res.jpg', 'imagen'),
(34, 4, '../img/galeria/1759946462_12res.jpg', 'imagen'),
(35, 4, '../img/galeria/1759946462_IMG-20250818-WA0009.jpg', 'imagen'),
(36, 4, '../img/galeria/1759946462_IMG-20250818-WA0012.jpg', 'imagen'),
(37, 6, '../img/galeria/1759947687_1mkt.jpg', 'imagen'),
(38, 6, '../img/galeria/1759947687_2mkt.jpg', 'imagen'),
(39, 6, '../img/galeria/1759947687_3mkt.jpg', 'imagen'),
(40, 6, '../img/galeria/1759947687_4mkt.jpg', 'imagen'),
(41, 6, '../img/galeria/1759947687_5mkt.jpg', 'imagen'),
(42, 6, '../img/galeria/1759947687_6mkt.jpg', 'imagen'),
(43, 5, '../img/galeria/1759947815_4min.jpg', 'imagen'),
(44, 5, '../img/galeria/1759947815_5min.jpg', 'imagen'),
(45, 5, '../img/galeria/1759947815_6min.jpg', 'imagen'),
(46, 5, '../img/galeria/1759947815_1min.jpg', 'imagen'),
(47, 5, '../img/galeria/1759947815_2min.jpg', 'imagen'),
(48, 5, '../img/galeria/1759947815_3min.jpg', 'imagen'),
(49, 7, '../img/galeria/1759947930_plc1.jpg', 'imagen'),
(50, 7, '../img/galeria/1759947930_plc2.jpg', 'imagen'),
(51, 7, '../img/galeria/1759947930_plc3.jpg', 'imagen'),
(52, 7, '../img/galeria/1759947930_plc4.jpg', 'imagen'),
(53, 7, '../img/galeria/1759947930_plc5.jpg', 'imagen'),
(54, 7, '../img/galeria/1759947930_plc6.jpg', 'imagen'),
(55, 8, '../img/galeria/1759948114_incendios1.jpg', 'imagen'),
(56, 8, '../img/galeria/1759948114_incendios2.jpg', 'imagen'),
(57, 8, '../img/galeria/1759948114_incendios3.jpg', 'imagen'),
(58, 8, '../img/galeria/1759948114_incendios4.jpg', 'imagen'),
(59, 8, '../img/galeria/1759948114_incendios5.jpg', 'imagen'),
(60, 8, '../img/galeria/1759948114_incendios6.jpg', 'imagen'),
(61, 8, '../img/galeria/1759948114_incendios7.jpg', 'imagen'),
(62, 8, '../videos/galeria/1759948114_video 1.mp4', 'video'),
(63, 9, '../img/galeria/1759949136_montacargas1.jpg', 'imagen'),
(64, 9, '../img/galeria/1759949136_montacargas2.jpg', 'imagen'),
(65, 9, '../img/galeria/1759949136_montacargas3.jpg', 'imagen'),
(66, 9, '../img/galeria/1759949136_montacargas4.jpg', 'imagen'),
(67, 9, '../img/galeria/1759949136_montacargas5.jpg', 'imagen'),
(68, 9, '../videos/galeria/1759949136_video 1.mp4', 'video'),
(69, 10, '../img/galeria/1759949299_alturas1.jpg', 'imagen'),
(70, 10, '../img/galeria/1759949299_alturas2.jpg', 'imagen'),
(71, 10, '../img/galeria/1759949299_alturas3.jpg', 'imagen'),
(72, 10, '../img/galeria/1759949299_alturas4.jpg', 'imagen'),
(73, 10, '../img/galeria/1759949299_alturas5.jpg', 'imagen'),
(74, 10, '../img/galeria/1759949299_alturas6.jpg', 'imagen'),
(75, 11, '../img/galeria/1760023874_excel1.jpg', 'imagen'),
(76, 11, '../img/galeria/1760023874_excel2.jpg', 'imagen'),
(77, 11, '../img/galeria/1760023874_excel3.jpg', 'imagen'),
(78, 11, '../img/galeria/1760023874_excel4.jpg', 'imagen'),
(79, 11, '../img/galeria/1760023874_excel5.jpg', 'imagen'),
(80, 11, '../img/galeria/1760023874_excel6.jpg', 'imagen'),
(81, 12, '../img/galeria/1760023939_laser1.jpg', 'imagen'),
(82, 12, '../img/galeria/1760023939_laser2.jpg', 'imagen'),
(83, 12, '../img/galeria/1760023939_laser3.jpg', 'imagen'),
(84, 12, '../img/galeria/1760023939_laser4.jpg', 'imagen'),
(85, 12, '../img/galeria/1760023939_laser5.jpg', 'imagen'),
(86, 12, '../img/galeria/1760023939_laser6.jpg', 'imagen'),
(87, 12, '../img/galeria/1760023939_laser7.jpg', 'imagen'),
(88, 12, '../videos/galeria/1760023939_video1.mp4', 'video'),
(89, 13, '../img/galeria/1761838192_subli1.jpg', 'imagen'),
(90, 13, '../img/galeria/1761838192_subli2.jpg', 'imagen'),
(91, 13, '../img/galeria/1761838192_subli3.jpg', 'imagen'),
(92, 13, '../img/galeria/1761838192_subli4.jpg', 'imagen'),
(93, 13, '../img/galeria/1761838193_subli5.jpg', 'imagen'),
(94, 13, '../img/galeria/1761838193_subli6.jpg', 'imagen'),
(160, 13, '../img/galeria/1764098894_f7e8d7d9-65a2-46ec-a10b-3862e5a3d93b.jpg', 'imagen'),
(161, 13, '../img/galeria/1764098894_0d44730f-fbda-422c-9859-e8e669d2dc21.jpg', 'imagen'),
(162, 13, '../img/galeria/1764098894_65f2b2c1-851e-4457-a43d-7dbdf22d8ae7.jpg', 'imagen'),
(163, 13, '../img/galeria/1764098894_ba726aa4-8016-42e3-b4de-9428e7660b1b.jpg', 'imagen'),
(164, 13, '../img/galeria/1764098894_da28edd4-a8e2-4398-9f7e-5e990d6ab738.jpg', 'imagen'),
(165, 13, '../img/galeria/1764098894_b9ce2090-bf50-4f27-93d4-ade254803fd6.jpg', 'imagen'),
(166, 13, '../img/galeria/1764098894_f01f541d-3b56-47d1-a3d4-93f85fea073f.jpg', 'imagen'),
(167, 13, '../img/galeria/1764098894_0050909c-ea01-4e78-acfe-d8d52e73b8ce.jpg', 'imagen'),
(168, 13, '../img/galeria/1764098894_3902b490-9f0f-4aed-b88b-cf74435eac73.jpg', 'imagen'),
(169, 13, '../img/galeria/1764098894_f29d9cd0-2a4f-41e9-90a0-e25dac703006.jpg', 'imagen'),
(170, 13, '../img/galeria/1764098894_9e61eb45-8208-45c2-afdd-e612fd14de0e.jpg', 'imagen'),
(171, 13, '../img/galeria/1764098894_b31efa00-7108-41d5-a0c9-7dcd249882b1.jpg', 'imagen'),
(172, 13, '../img/galeria/1764098894_aec272d9-c517-496b-be43-784c24982a32.jpg', 'imagen'),
(173, 13, '../img/galeria/1764098894_da0999b2-f0f2-44e3-83ab-cca9c98838af.jpg', 'imagen'),
(174, 13, '../img/galeria/1764098894_a59075ee-d76c-4f20-bf45-06d2dd8c6164.jpg', 'imagen'),
(175, 13, '../img/galeria/1764098894_9ad25fd1-1522-46ae-8687-291d732b99d4.jpg', 'imagen'),
(176, 13, '../img/galeria/1764098894_d5a763b3-79a0-44e9-9db4-a34db66a1584.jpg', 'imagen'),
(177, 13, '../img/galeria/1764098894_865d4fe1-2f2e-4ae6-a717-53f40e833157.jpg', 'imagen'),
(178, 13, '../img/galeria/1764098894_5dc5226d-6c94-4e67-b2f0-a6895e65f9bf.jpg', 'imagen'),
(179, 13, '../img/galeria/1764098894_4c18a29c-c1fe-47e0-8359-d2e48118e6a5.jpg', 'imagen'),
(180, 13, '../videos/galeria/1764099802_Subli.mp4', 'video'),
(181, 11, '../img/galeria/1764100004_Excel1.jpg', 'imagen'),
(182, 11, '../img/galeria/1764100004_Excel2.jpg', 'imagen'),
(183, 12, '../img/galeria/1764100341_5e15accc-f129-44a0-a729-3611141ff4d6.jpg', 'imagen'),
(184, 12, '../img/galeria/1764100341_20cb1948-44fa-4605-b38d-2968dbb11f84.jpg', 'imagen'),
(185, 12, '../img/galeria/1764100341_6f281382-c0e0-4207-92d8-9a3f57e72330.jpg', 'imagen'),
(186, 12, '../img/galeria/1764100341_26e2b50c-42a5-4bb4-9f9e-4c17e684f900.jpg', 'imagen'),
(187, 12, '../img/galeria/1764100341_84c37205-906f-4bfe-8e6b-46e3ac897897.jpg', 'imagen'),
(188, 12, '../img/galeria/1764100341_48fe9b6d-43f9-4930-8c19-2f156a0fd34a.jpg', 'imagen'),
(189, 12, '../img/galeria/1764100341_546e2ffb-a294-420c-abfe-04f5b36858bb.jpg', 'imagen'),
(190, 12, '../img/galeria/1764100341_1cab1c6e-a1e8-4248-95a2-2cf0915228f4.jpg', 'imagen'),
(191, 12, '../img/galeria/1764100341_a9115d89-ae67-4d8e-b01f-d0c606f47be3.jpg', 'imagen'),
(192, 12, '../img/galeria/1764100341_891b41f9-4e05-4db0-97bf-c17999298829.jpg', 'imagen'),
(193, 12, '../img/galeria/1764100341_843f43ee-b27a-4739-a721-41a2b6a9fc74.jpg', 'imagen'),
(194, 12, '../img/galeria/1764100341_ea222f27-f28e-4a28-aac3-ab198dd4131b.jpg', 'imagen'),
(195, 10, '../img/galeria/1764174144_b20b18e5-b7e4-4301-b6af-b2e35239b416.jpg', 'imagen'),
(196, 10, '../img/galeria/1764174144_742f98ae-d6b3-41a9-b456-8445843498ed.jpg', 'imagen'),
(197, 10, '../img/galeria/1764174144_e37fd509-b963-40ee-8cb6-4643fd5b4b13.jpg', 'imagen'),
(198, 10, '../img/galeria/1764174144_13e4e102-1dca-494c-a592-39893e7650af.jpg', 'imagen'),
(199, 10, '../img/galeria/1764174144_528d0580-2902-4112-a775-99f5bd20d4b8.jpg', 'imagen'),
(200, 10, '../img/galeria/1764174144_fdf01ea5-c771-47a7-9909-2d91c2e34900.jpg', 'imagen'),
(201, 10, '../img/galeria/1764174144_febe4a7f-c8a9-4cd5-ba30-596ff7539eec.jpg', 'imagen'),
(202, 10, '../img/galeria/1764174144_de3cc833-7d78-4e19-8363-d5e610fb3d96.jpg', 'imagen'),
(203, 10, '../img/galeria/1764174144_adf3cb0b-70b4-419a-94bf-3b26bee181e5.jpg', 'imagen'),
(204, 10, '../img/galeria/1764174144_d44e618e-c9c2-4690-b50a-5262b53a9f12.jpg', 'imagen'),
(205, 10, '../img/galeria/1764174144_210a14b1-082d-4d34-8481-3b5f9d81758d.jpg', 'imagen'),
(206, 10, '../img/galeria/1764174144_4f31fc65-3d3d-4857-8dd1-555673e5c2d4.jpg', 'imagen'),
(207, 10, '../img/galeria/1764174144_992ac1f5-faf8-47d3-bb3d-9591bb6a848a.jpg', 'imagen'),
(208, 10, '../img/galeria/1764174144_a19f199c-2279-484c-9817-7148f537485d.jpg', 'imagen'),
(209, 10, '../img/galeria/1764174144_b142d2b0-fa2b-459b-a9fc-dddd81df1248.jpg', 'imagen'),
(210, 10, '../img/galeria/1764174144_0433c4f4-7878-4591-9740-1422fbf6f486.jpg', 'imagen'),
(211, 10, '../img/galeria/1764174144_a50384c6-6783-4694-b420-45746b01e4ce.jpg', 'imagen'),
(212, 10, '../img/galeria/1764174144_906af04d-18b6-4f4b-85ca-b026ee292da6.jpg', 'imagen'),
(213, 10, '../img/galeria/1764174144_1092bd0c-8953-4d08-973f-4fc91f0d21e4.jpg', 'imagen'),
(214, 10, '../img/galeria/1764174144_b9c6e330-54fe-46f1-9ce6-b79af68bd32a.jpg', 'imagen'),
(215, 10, '../videos/galeria/1764174687_altura1.mp4', 'video'),
(216, 10, '../videos/galeria/1764174687_altura2.mp4', 'video'),
(217, 10, '../videos/galeria/1764174687_altura3.mp4', 'video'),
(218, 10, '../videos/galeria/1764174687_altura4.mp4', 'video'),
(219, 10, '../videos/galeria/1764174687_altura5.mp4', 'video'),
(220, 10, '../videos/galeria/1764174687_altura6.mp4', 'video'),
(221, 10, '../img/galeria/1764174759_b6be93ef-81cc-40bd-8214-15a66b981398.jpg', 'imagen'),
(222, 2, '../img/galeria/1764267332_f21d1284-778c-4d2b-a41a-6f5ce4028d61.jpg', 'imagen'),
(223, 2, '../img/galeria/1764267332_0809b096-11aa-43f7-9c09-79230c7f2214.jpg', 'imagen'),
(224, 2, '../img/galeria/1764267332_3db7c183-c802-472a-8293-67029830d22d.jpg', 'imagen'),
(225, 2, '../img/galeria/1764267332_6ebf2fbf-0557-4033-bf55-3ace94ce0323.jpg', 'imagen'),
(226, 2, '../img/galeria/1764267332_dae6368d-94e2-4e38-b2d3-67d4c22fe818.jpg', 'imagen'),
(227, 2, '../img/galeria/1764267332_31586b3f-5904-425f-9fbf-40976280b026.jpg', 'imagen'),
(228, 2, '../img/galeria/1764267332_20b3c372-78b6-4ecf-8f16-c5c451523384.jpg', 'imagen'),
(229, 2, '../img/galeria/1764267332_9b5ad90e-d637-47d9-aaff-30a85f684d6a.jpg', 'imagen'),
(230, 2, '../img/galeria/1764267332_a0ec164e-33e8-408f-830a-26af57425921.jpg', 'imagen'),
(231, 1, '../img/galeria/1764267418_7cc9e56e-0aec-4b20-bb3c-28af3f95db51.jpg', 'imagen'),
(232, 1, '../img/galeria/1764267418_f1e1167a-707a-4742-a97c-18641c764ddf.jpg', 'imagen'),
(233, 1, '../img/galeria/1764267418_bebdfaf9-7325-42a1-a32e-54d22ee8e9b7.jpg', 'imagen'),
(234, 1, '../img/galeria/1764267418_109211fd-6e8a-4a74-9d22-536661e561a7.jpg', 'imagen'),
(235, 1, '../img/galeria/1764267418_195ddd6b-6c31-4b1a-99bf-ac72fde98f31.jpg', 'imagen'),
(236, 1, '../img/galeria/1764267418_5b2b1cfe-af70-4ef1-a884-fe5292e3eb75.jpg', 'imagen'),
(237, 1, '../img/galeria/1764267418_aa1c0857-21ae-433b-9d78-ee57fdc1b2c7.jpg', 'imagen'),
(238, 1, '../img/galeria/1764267418_1291a2d6-db13-449b-a0e3-b6b3cc1fe946.jpg', 'imagen'),
(239, 1, '../img/galeria/1764267418_80b17153-9580-41ba-9dae-8c6f3f429822.jpg', 'imagen'),
(240, 13, '../img/galeria/1764267498_a966c055-18fc-4b6c-9524-c806d0d54330.jpg', 'imagen'),
(241, 13, '../img/galeria/1764267498_84163cdc-a0d9-45e8-9241-891f8d35cd64.jpg', 'imagen'),
(242, 13, '../videos/galeria/1764268046_WhatsApp Video 2025-11-27 at 9.08.27 AM.mp4', 'video'),
(243, 5, '../img/galeria/1764269072_mini.jpg', 'imagen'),
(244, 5, '../img/galeria/1764269072_mini2.jpg', 'imagen'),
(245, 5, '../videos/galeria/1764269332_mini.mp4', 'video'),
(246, 12, '../img/galeria/1764269568_laser.jpg', 'imagen');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `progreso_cursos`
--

CREATE TABLE `progreso_cursos` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `curso` varchar(150) NOT NULL,
  `sucursal` varchar(100) DEFAULT NULL,
  `fecha_inicio` date DEFAULT NULL,
  `fecha_finalizacion` date DEFAULT NULL,
  `estado` enum('Pendiente','En Progreso','Completado') DEFAULT 'Pendiente',
  `certificado` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `secciones_galeria`
--

CREATE TABLE `secciones_galeria` (
  `id_seccion` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `secciones_galeria`
--

INSERT INTO `secciones_galeria` (`id_seccion`, `nombre`) VALUES
(1, 'SolidWorks'),
(2, 'Control Eléctrico Industrial'),
(3, 'Programación y Manufactura CNC'),
(4, 'Instalaciones Eléctricas Residenciales'),
(5, 'Mantenimiento Minisplit'),
(6, 'Marketing Digital con IA'),
(7, 'Programación PLC'),
(8, 'Respuesta Inmediata contra Incendios'),
(9, 'Montacargas'),
(10, 'Condiciones de Seguridad para Realizar Trabajos en Altura'),
(11, 'Excel'),
(12, 'Programación y Grabado Láser CNC'),
(13, 'Sublimación');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tarjetas`
--

CREATE TABLE `tarjetas` (
  `id_tarjeta` int(11) NOT NULL,
  `titulo` varchar(100) NOT NULL,
  `descripcion` text NOT NULL,
  `imagen` varchar(150) NOT NULL,
  `id_seccion` int(11) DEFAULT NULL,
  `creado_en` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tarjetas`
--

INSERT INTO `tarjetas` (`id_tarjeta`, `titulo`, `descripcion`, `imagen`, `id_seccion`, `creado_en`) VALUES
(1, 'SolidWorks', 'Contamos con aulas totalmente equipadas con computadoras, software especializado y conexión a internet, diseñadas para que nuestros alumnos desarrollen sus habilidades teóricas y prácticas en un ambiente cómodo y productivo.', '../img/tarjetas/tarjeta_68e7eb9113b51.jpg', 1, '2025-10-09 23:38:29'),
(2, 'Instalación y Mantenimiento Preventivo de Minisplit', 'Un entorno equipado para que nuestros alumnos adquieran experiencia práctica en la instalación y mantenimiento de minisplits, combinando teoría y trabajo real.', '../img/tarjetas/tarjeta_68ed3bd5cea0b.jpg', 5, '2025-10-14 00:50:13'),
(3, 'Control Eléctrico Industrial', 'Contamos con estaciones de trabajo equipadas con tableros de control 100% industrial, herramientas y material especializado para que nuestros alumnos practiquen montaje, cableado y diagnóstico de sistemas eléctricos industriales en un entorno seguro y profesional.', '../img/tarjetas/tarjeta_68ed3c66a3c76.jpg', 2, '2025-10-14 00:52:38'),
(4, 'Programación CNC', 'Disponemos de maquinaria CNC operativa para que los estudiantes aprendan programación, operación y mantenimiento de equipos de control numérico, aplicando sus conocimientos en procesos de manufactura de precisión.', '../img/tarjetas/tarjeta_68ed3c93034a8.jpg', 3, '2025-10-14 00:53:23'),
(5, 'Excel', 'Domina Microsoft Excel desde lo básico hasta funciones avanzadas. Aprende a crear hojas de cálculo, gestionar datos, utilizar fórmulas, generar reportes y gráficos dinámicos para optimizar tareas administrativas, académicas o profesionales.', '../img/tarjetas/tarjeta_68ed3cb53cd2a.jpg', 11, '2025-10-14 00:53:57'),
(6, 'Instalaciones Eléctricas Residenciales', 'Espacio diseñado para que los estudiantes desarrollen habilidades prácticas en la instalación de sistemas eléctricos residenciales, utilizando materiales y herramientas reales bajo supervisión profesional.', '../img/tarjetas/tarjeta_68ed3cdf129fd.jpg', 4, '2025-10-14 00:54:39'),
(7, 'Marketing Digital con IA', 'Aprende a diseñar estrategias de marketing digital apoyadas en Inteligencia Artificial. Domina herramientas innovadoras para automatizar campañas, analizar datos, optimizar contenido y mejorar la interacción con tus clientes en redes sociales y plataformas digitales.', '../img/tarjetas/tarjeta_68ed3d28f19f5.jpg', 6, '2025-10-14 00:55:52'),
(8, 'Montacargas', 'Capacítate en el uso seguro y eficiente de montacargas. Aprende las técnicas correctas de operación, normas de seguridad, mantenimiento preventivo y maniobras en diferentes entornos de trabajo, garantizando la protección del operador, la carga y el área de operación.', '../img/tarjetas/tarjeta_68ed3d71c704a.jpg', 9, '2025-10-14 00:57:05'),
(9, 'Respuesta Inmediata contra Incendios', 'Adquiere los conocimientos y habilidades para actuar de forma rápida y segura ante un incendio. Aprende a identificar riesgos, utilizar correctamente los equipos de extinción, aplicar protocolos de evacuación y coordinar acciones que reduzcan daños y protejan la vida de las personas.', '../img/tarjetas/tarjeta_68ed3dc317b87.jpg', 8, '2025-10-14 00:58:27'),
(10, 'Condiciones de Seguridad Para Realizar Trabajos en Altura', 'Capacítate en las medidas de prevención y uso adecuado de equipos de protección personal para realizar labores en altura de forma segura. Aprende a identificar riesgos, aplicar técnicas de anclaje y rescate, y cumplir con la normativa para evitar accidentes y proteger tu integridad.', '../img/tarjetas/tarjeta_68ed3e0979f6c.jpg', 10, '2025-10-14 00:59:37'),
(11, 'Programación y Grabado Láser CNC', 'Aprende desde cero el manejo de equipos CNC aplicados al grabado y corte láser. Desarrolla habilidades en programación, operación, uso de herramientas y medición, aplicando tus conocimientos en proyectos reales que impulsarán tu crecimiento profesional y la creación de tu propio negocio.', '../img/tarjetas/tarjeta_68ed3fd4d244b.jpg', 12, '2025-10-14 01:07:16'),
(12, 'Sublimación', 'Aprende de forma práctica las bases de la sublimación y transforma tus ideas en productos personalizados. Desarrolla habilidades en el diseño, selección de materiales, uso de prensa, técnicas de transferencia y acabados profesionales.', '../img/tarjetas/tarjeta_68ed40153e025.jpg', 13, '2025-10-14 01:08:21'),
(13, 'Programación PLC', 'Aprende de manera práctica la programación y operación de PLC\''s aplicados a la automatización industrial. Desarrolla habilidades en lógica de control, uso de herramientas de medición, realización de prácticas en computadora y manejo de equipos.', '../img/tarjetas/tarjeta_68fa766972dfa.jpg', 7, '2025-10-24 01:39:37');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `apellido` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `telefono` char(10) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `token_recuperacion` varchar(100) DEFAULT NULL,
  `token_expira` datetime DEFAULT NULL,
  `rol` enum('usuario','administrador') NOT NULL DEFAULT 'usuario',
  `token_verificacion` char(32) DEFAULT NULL,
  `token_expira_verificacion` datetime DEFAULT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `apellido`, `email`, `telefono`, `password`, `token_recuperacion`, `token_expira`, `rol`, `token_verificacion`, `token_expira_verificacion`, `activo`) VALUES
(1, 'Uriel', 'Martínez', 'ceij030223@gmail.com', '6566367848', '$2y$10$iWCZQvKFLXjFGHTVFuOk3u9Ypwce32TuTD3NM49EblNaEOEIfznCy', NULL, NULL, 'administrador', NULL, NULL, 1),

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `carrusel`
--
ALTER TABLE `carrusel`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `cursos`
--
ALTER TABLE `cursos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `imagenes_galeria`
--
ALTER TABLE `imagenes_galeria`
  ADD PRIMARY KEY (`id_imagen`),
  ADD KEY `id_seccion` (`id_seccion`);

--
-- Indices de la tabla `progreso_cursos`
--
ALTER TABLE `progreso_cursos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `idx_usuario_curso` (`usuario_id`,`curso`);

--
-- Indices de la tabla `secciones_galeria`
--
ALTER TABLE `secciones_galeria`
  ADD PRIMARY KEY (`id_seccion`);

--
-- Indices de la tabla `tarjetas`
--
ALTER TABLE `tarjetas`
  ADD PRIMARY KEY (`id_tarjeta`),
  ADD KEY `id_seccion` (`id_seccion`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `carrusel`
--
ALTER TABLE `carrusel`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=96;

--
-- AUTO_INCREMENT de la tabla `cursos`
--
ALTER TABLE `cursos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT de la tabla `imagenes_galeria`
--
ALTER TABLE `imagenes_galeria`
  MODIFY `id_imagen` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=248;

--
-- AUTO_INCREMENT de la tabla `progreso_cursos`
--
ALTER TABLE `progreso_cursos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=67;

--
-- AUTO_INCREMENT de la tabla `secciones_galeria`
--
ALTER TABLE `secciones_galeria`
  MODIFY `id_seccion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de la tabla `tarjetas`
--
ALTER TABLE `tarjetas`
  MODIFY `id_tarjeta` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `imagenes_galeria`
--
ALTER TABLE `imagenes_galeria`
  ADD CONSTRAINT `imagenes_galeria_ibfk_1` FOREIGN KEY (`id_seccion`) REFERENCES `secciones_galeria` (`id_seccion`) ON DELETE CASCADE;

--
-- Filtros para la tabla `tarjetas`
--
ALTER TABLE `tarjetas`
  ADD CONSTRAINT `tarjetas_ibfk_1` FOREIGN KEY (`id_seccion`) REFERENCES `secciones_galeria` (`id_seccion`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
