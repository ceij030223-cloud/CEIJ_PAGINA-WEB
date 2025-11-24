-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 24-11-2025 a las 17:56:39
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "-07:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `ceij_db`
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
(1, '1760376817_68ed37f19aafb_flyer_control_electrico.jpg', '2025-10-13 17:33:37'),
(2, '1760376817_68ed37f19ce54_flyer_excel.jpg', '2025-10-13 17:33:37'),
(3, '1760376817_68ed37f1a8ca5_flyer_incendios.jpg', '2025-10-13 17:33:37'),
(4, '1760376817_68ed37f1aa6a3_flyer_instalaciones_residenciales.jpg', '2025-10-13 17:33:37'),
(5, '1760376817_68ed37f1abd43_flyer_marketing_con_IA.jpg', '2025-10-13 17:33:37'),
(6, '1760376817_68ed37f1ad1e0_flyer_minisplit.jpg', '2025-10-13 17:33:37'),
(7, '1760376817_68ed37f1af511_flyer_montacargas.jpg', '2025-10-13 17:33:37'),
(8, '1760376817_68ed37f1b09b5_flyer_programacion_CNC.jpg', '2025-10-13 17:33:37'),
(9, '1760376817_68ed37f1b24a2_flyer_programacion_PLC.jpg', '2025-10-13 17:33:37'),
(10, '1760376817_68ed37f1b3a29_flyer_reparacion_minisplit.jpg', '2025-10-13 17:33:37'),
(11, '1760376817_68ed37f1b53ea_flyer_solidworks.jpg', '2025-10-13 17:33:37'),
(12, '1763659739_691f4fdb60f82_flyer_alturas.jpg', '2025-11-20 17:28:59');

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
(94, 13, '../img/galeria/1761838193_subli6.jpg', 'imagen');

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

--
-- Volcado de datos para la tabla `progreso_cursos`
--


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
(3, 'Programación CNC'),
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
(1, 'Taller de SolidWorks', 'Contamos con aulas totalmente equipadas con computadoras, software especializado y conexión a internet, diseñadas para que nuestros alumnos desarrollen sus habilidades teóricas y prácticas en un ambiente cómodo y productivo.', '../img/tarjetas/tarjeta_68e7eb9113b51.jpg', 1, '2025-10-09 16:38:29'),
(2, 'Taller de Instalación y Mantenimiento Preventivo de Minisplit', 'Un entorno equipado para que nuestros alumnos adquieran experiencia práctica en la instalación y mantenimiento de minisplits, combinando teoría y trabajo real.', '../img/tarjetas/tarjeta_68ed3bd5cea0b.jpg', 5, '2025-10-13 17:50:13'),
(3, 'Taller de Control Eléctrico Industrial', 'Contamos con estaciones de trabajo equipadas con tableros de control 100% industrial, herramientas y material especializado para que nuestros alumnos practiquen montaje, cableado y diagnóstico de sistemas eléctricos industriales en un entorno seguro y profesional.', '../img/tarjetas/tarjeta_68ed3c66a3c76.jpg', 2, '2025-10-13 17:52:38'),
(4, 'Taller de Programacion de CNC', 'Disponemos de maquinaria CNC operativa para que los estudiantes aprendan programación, operación y mantenimiento de equipos de control numérico, aplicando sus conocimientos en procesos de manufactura de precisión.', '../img/tarjetas/tarjeta_68ed3c93034a8.jpg', 3, '2025-10-13 17:53:23'),
(5, 'Excel', 'Domina Microsoft Excel desde lo básico hasta funciones avanzadas. Aprende a crear hojas de cálculo, gestionar datos, utilizar fórmulas, generar reportes y gráficos dinámicos para optimizar tareas administrativas, académicas o profesionales.', '../img/tarjetas/tarjeta_68ed3cb53cd2a.jpg', 11, '2025-10-13 17:53:57'),
(6, 'Taller de Instalaciones Eléctricas Residenciales', 'Espacio diseñado para que los estudiantes desarrollen habilidades prácticas en la instalación de sistemas eléctricos residenciales, utilizando materiales y herramientas reales bajo supervisión profesional.', '../img/tarjetas/tarjeta_68ed3cdf129fd.jpg', 4, '2025-10-13 17:54:39'),
(7, 'Marketing Digital con IA', 'Aprende a diseñar estrategias de marketing digital apoyadas en Inteligencia Artificial. Domina herramientas innovadoras para automatizar campañas, analizar datos, optimizar contenido y mejorar la interacción con tus clientes en redes sociales y plataformas digitales.', '../img/tarjetas/tarjeta_68ed3d28f19f5.jpg', 6, '2025-10-13 17:55:52'),
(8, 'Montacargas', 'Capacítate en el uso seguro y eficiente de montacargas. Aprende las técnicas correctas de operación, normas de seguridad, mantenimiento preventivo y maniobras en diferentes entornos de trabajo, garantizando la protección del operador, la carga y el área de operación.', '../img/tarjetas/tarjeta_68ed3d71c704a.jpg', 9, '2025-10-13 17:57:05'),
(9, 'Respuesta Inmediata contra Incendios', 'Adquiere los conocimientos y habilidades para actuar de forma rápida y segura ante un incendio. Aprende a identificar riesgos, utilizar correctamente los equipos de extinción, aplicar protocolos de evacuación y coordinar acciones que reduzcan daños y protejan la vida de las personas.', '../img/tarjetas/tarjeta_68ed3dc317b87.jpg', 8, '2025-10-13 17:58:27'),
(10, 'Condiciones de Seguridad Para Realizar Trabajos en Altura', 'Capacítate en las medidas de prevención y uso adecuado de equipos de protección personal para realizar labores en altura de forma segura. Aprende a identificar riesgos, aplicar técnicas de anclaje y rescate, y cumplir con la normativa para evitar accidentes y proteger tu integridad.', '../img/tarjetas/tarjeta_68ed3e0979f6c.jpg', 10, '2025-10-13 17:59:37'),
(11, 'Taller de Programación y Grabado Láser CNC', 'Aprende desde cero el manejo de equipos CNC aplicados al grabado y corte láser. Desarrolla habilidades en programación, operación, uso de herramientas y medición, aplicando tus conocimientos en proyectos reales que impulsarán tu crecimiento profesional y la creación de tu propio negocio.', '../img/tarjetas/tarjeta_68ed3fd4d244b.jpg', 12, '2025-10-13 18:07:16'),
(12, 'Taller de Sublimación', 'Aprende de forma práctica las bases de la sublimación y transforma tus ideas en productos personalizados. Desarrolla habilidades en el diseño, selección de materiales, uso de prensa, técnicas de transferencia y acabados profesionales.', '../img/tarjetas/tarjeta_68ed40153e025.jpg', 13, '2025-10-13 18:08:21'),
(13, 'Taller de Programación PLC', 'Aprende de manera práctica la programación y operación de PLC''s aplicados a la automatización industrial. Desarrolla habilidades en lógica de control, uso de herramientas de medición, realización de prácticas en computadora y manejo de equipos.', '../img/tarjetas/tarjeta_68fa766972dfa.jpg', 7, '2025-10-23 18:39:37');

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
(1, 'Uriel', 'Martínez', 'ceij030223@gmail.com', '6566367848', '$2y$10$5yGGTl0B', NULL, NULL, 'administrador', NULL, NULL, 1);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `imagenes_galeria`
--
ALTER TABLE `imagenes_galeria`
  MODIFY `id_imagen` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=160;

--
-- AUTO_INCREMENT de la tabla `progreso_cursos`
--
ALTER TABLE `progreso_cursos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=66;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

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
