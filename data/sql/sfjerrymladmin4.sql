-- phpMyAdmin SQL Dump
-- version 3.4.5
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 21-01-2013 a las 15:44:06
-- Versión del servidor: 5.5.16
-- Versión de PHP: 5.3.8

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de datos: `sfjerrymladmin4`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clientes`
--

CREATE TABLE IF NOT EXISTS `clientes` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `razon_social` varchar(255) NOT NULL,
  `rfc` varchar(100) DEFAULT NULL,
  `calle` varchar(150) DEFAULT NULL,
  `numero_exterior` varchar(20) DEFAULT NULL,
  `numero_interior` varchar(20) DEFAULT NULL,
  `colonia` varchar(150) DEFAULT NULL,
  `codigo_postal` varchar(10) DEFAULT NULL,
  `cuidad` varchar(150) DEFAULT NULL,
  `municipio` varchar(150) DEFAULT NULL,
  `estado` varchar(150) DEFAULT NULL,
  `pais` varchar(150) DEFAULT NULL,
  `saldo` double(18,2) DEFAULT '0.00',
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `slug` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `razon_social` (`razon_social`),
  UNIQUE KEY `clientes_sluggable_idx` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clientes_contactos`
--

CREATE TABLE IF NOT EXISTS `clientes_contactos` (
  `cliente_id` bigint(20) NOT NULL DEFAULT '0',
  `contacto_id` bigint(20) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`cliente_id`,`contacto_id`),
  KEY `clientes_contactos_contacto_id_contactos_id` (`contacto_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `comisionistas`
--

CREATE TABLE IF NOT EXISTS `comisionistas` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) NOT NULL,
  `margen` float(18,2) DEFAULT '0.00',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `slug` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `comisionistas_sluggable_idx` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `conceptos`
--

CREATE TABLE IF NOT EXISTS `conceptos` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `concepto` varchar(255) NOT NULL,
  `requerimiento` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `slug` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `conceptos_sluggable_idx` (`slug`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Volcado de datos para la tabla `conceptos`
--

INSERT INTO `conceptos` (`id`, `concepto`, `requerimiento`, `created_at`, `updated_at`, `slug`) VALUES
(1, 'Conduccion', '', '2013-01-21 15:27:22', '2013-01-21 15:27:22', 'conduccion'),
(2, 'Twitts', '', '2013-01-21 15:27:22', '2013-01-21 15:27:22', 'twitts');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `configuracion`
--

CREATE TABLE IF NOT EXISTS `configuracion` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `seccion` varchar(255) NOT NULL,
  `contenido` text NOT NULL,
  `imagen` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `slug` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `configuracion_sluggable_idx` (`slug`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Volcado de datos para la tabla `configuracion`
--

INSERT INTO `configuracion` (`id`, `seccion`, `contenido`, `imagen`, `created_at`, `updated_at`, `slug`) VALUES
(1, 'precotizaciones', 'Pie de pagina de precotizazion', 'e77c830035cac04acb3afc803c812aa8beb94062.jpg', '2013-01-21 15:27:22', '2013-01-21 15:27:22', 'precotizaciones'),
(2, 'cotizaciones', '<td class="sf_admin_text sf_admin_list_td_contenido">\n<p><strong> NOTA: Los costos son exclusivos para este evento en las actividades mencionadas y no se podrá ligar al artista con otras marcas ni empresas fuera de lo que se indica en esta cotización. </strong></p>\n<h3>Consideraciones:</h3>\n<p>&nbsp;</p>\n<ul>\n<li>El precio es en pesos mexicanos.</li>\n<li>El precio es más I.V.A.</li>\n<li>Se requiere de un pago del 50% de anticipo a la firma del contrato y el 50% 2 días hábiles antes del evento.</li>\n<li>El costo deberá ser libre de retenciones.</li>\n</ul>\n<p><br> <strong>Costos vigentes 10 días a partir de la fecha del documento.</strong></p></td>\n', 'e77c830035cac04acb3afc803c812aa8beb94062.jpg', '2013-01-21 15:27:22', '2013-01-21 15:27:22', 'cotizaciones');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `contactos`
--

CREATE TABLE IF NOT EXISTS `contactos` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `apellidos` varchar(150) NOT NULL,
  `calle` varchar(150) DEFAULT NULL,
  `numero_exterior` varchar(20) DEFAULT NULL,
  `numero_interior` varchar(20) DEFAULT NULL,
  `colonia` varchar(150) DEFAULT NULL,
  `codigo_postal` varchar(10) DEFAULT NULL,
  `cuidad` varchar(150) DEFAULT NULL,
  `municipio` varchar(150) DEFAULT NULL,
  `estado` varchar(150) DEFAULT NULL,
  `pais` varchar(150) DEFAULT NULL,
  `telefono` varchar(150) DEFAULT NULL,
  `celular` varchar(150) DEFAULT NULL,
  `email` varchar(150) NOT NULL,
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `slug` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `contactos_sluggable_idx` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `contratos`
--

CREATE TABLE IF NOT EXISTS `contratos` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `cotizacion_id` bigint(20) DEFAULT NULL,
  `user_id` bigint(20) DEFAULT NULL,
  `file` varchar(255) DEFAULT 'no_file.pdf',
  `esta_firmado` tinyint(1) DEFAULT '0',
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `cotizacion_id_idx` (`cotizacion_id`),
  KEY `user_id_idx` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cotizaciones`
--

CREATE TABLE IF NOT EXISTS `cotizaciones` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `cliente_id` bigint(20) DEFAULT NULL,
  `contacto_id` bigint(20) DEFAULT NULL,
  `user_id` bigint(20) DEFAULT NULL,
  `manager_id` bigint(20) DEFAULT NULL,
  `empresa_id` bigint(20) DEFAULT NULL,
  `descripcion` varchar(255) NOT NULL DEFAULT 'Descripicon evento',
  `actividad` text NOT NULL,
  `plaza` varchar(255) DEFAULT NULL,
  `fecha_desde` datetime NOT NULL,
  `fecha_hasta` datetime NOT NULL,
  `mostrar_horas` tinyint(1) DEFAULT '0',
  `vigencia` varchar(255) DEFAULT '1 año',
  `medios` varchar(255) DEFAULT NULL,
  `consideraciones` text NOT NULL,
  `requerimientos` text NOT NULL,
  `pdf` varchar(255) DEFAULT 'sin_archivo.pdf',
  `status` tinyint(4) DEFAULT NULL,
  `tipo_cotizacion` tinyint(4) DEFAULT '1',
  `add_conceptos` tinyint(1) DEFAULT '1',
  `add_comisionistas` tinyint(1) DEFAULT '1',
  `add_eventos` tinyint(1) DEFAULT '1',
  `subtotal` double(18,2) DEFAULT '0.00',
  `iva` double(18,2) DEFAULT '0.00',
  `is_pay` tinyint(1) DEFAULT '0',
  `is_active` tinyint(1) DEFAULT '1',
  `monto_pagado_cliente` double(18,2) DEFAULT '0.00',
  `monto_pagado_talento` double(18,2) DEFAULT '0.00',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `slug` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `cotizaciones_sluggable_idx` (`slug`),
  KEY `cliente_id_idx` (`cliente_id`),
  KEY `contacto_id_idx` (`contacto_id`),
  KEY `user_id_idx` (`user_id`),
  KEY `manager_id_idx` (`manager_id`),
  KEY `empresa_id_idx` (`empresa_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalles_cotizacion`
--

CREATE TABLE IF NOT EXISTS `detalles_cotizacion` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `cotizacion_id` bigint(20) DEFAULT NULL,
  `talento_id` bigint(20) DEFAULT NULL,
  `actividad` text NOT NULL,
  `ganancia_jerry_ml` double(18,2) DEFAULT '0.00',
  `ganancia_talento` double(18,2) DEFAULT '0.00',
  `ganancia_comisionistas` double(18,2) DEFAULT '0.00',
  `margen_jerry_ml` float(18,2) DEFAULT '20.00',
  `margen_comisionistas` float(18,2) DEFAULT '0.00',
  `precio` double(18,2) DEFAULT '0.00',
  `iva` double(18,2) DEFAULT '0.00',
  `is_pay_talento` tinyint(1) DEFAULT '0',
  `is_active` tinyint(1) DEFAULT '1',
  `monto_pagado_talento` double(18,2) DEFAULT '0.00',
  `position` int(11) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `cotizacion_id_idx` (`cotizacion_id`),
  KEY `talento_id_idx` (`talento_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalles_cotizacion_comisionistas`
--

CREATE TABLE IF NOT EXISTS `detalles_cotizacion_comisionistas` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `cotizacion_id` bigint(20) DEFAULT '0',
  `detalles_cotizacion_id` bigint(20) DEFAULT '0',
  `comisionista_id` bigint(20) DEFAULT '0',
  `margen` float(18,2) DEFAULT '0.00',
  `ganancia` double(18,2) DEFAULT '0.00',
  `nivel` tinyint(4) DEFAULT '1',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `cotizacion_id_idx` (`cotizacion_id`),
  KEY `detalles_cotizacion_id_idx` (`detalles_cotizacion_id`),
  KEY `comisionista_id_idx` (`comisionista_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalles_cotizacion_conceptos`
--

CREATE TABLE IF NOT EXISTS `detalles_cotizacion_conceptos` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `cotizacion_id` bigint(20) DEFAULT '0',
  `detalles_cotizacion_id` bigint(20) DEFAULT '0',
  `concepto_id` bigint(20) DEFAULT '0',
  `precio` double(18,2) DEFAULT '0.00',
  `requerimiento` varchar(255) DEFAULT NULL,
  `nivel` tinyint(4) DEFAULT '1',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `cotizacion_id_idx` (`cotizacion_id`),
  KEY `detalles_cotizacion_id_idx` (`detalles_cotizacion_id`),
  KEY `concepto_id_idx` (`concepto_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalles_pagos`
--

CREATE TABLE IF NOT EXISTS `detalles_pagos` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `fecha_pago` date DEFAULT NULL,
  `user_id` bigint(20) DEFAULT '0',
  `pagos_id` bigint(20) DEFAULT '0',
  `cotizacion_id` bigint(20) DEFAULT '0',
  `tipo_pago` tinyint(4) DEFAULT '0',
  `importe` double(18,2) DEFAULT '0.00',
  `iva` double(18,2) DEFAULT '0.00',
  `status` tinyint(4) DEFAULT '0',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `pagos_id_idx` (`pagos_id`),
  KEY `cotizacion_id_idx` (`cotizacion_id`),
  KEY `user_id_idx` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalles_pagos_talentos`
--

CREATE TABLE IF NOT EXISTS `detalles_pagos_talentos` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `fecha_pago` date DEFAULT NULL,
  `user_id` bigint(20) DEFAULT '0',
  `pagos_talentos_id` bigint(20) DEFAULT '0',
  `detalles_cotizacion_id` bigint(20) DEFAULT NULL,
  `metodo_recibo` tinyint(4) DEFAULT '0',
  `importe` double(18,2) DEFAULT '0.00',
  `iva` double(18,2) DEFAULT '0.00',
  `isr` double(18,2) DEFAULT '0.00',
  `descuento` double(18,2) DEFAULT '0.00',
  `status` tinyint(4) DEFAULT '0',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `pagos_talentos_id_idx` (`pagos_talentos_id`),
  KEY `detalles_cotizacion_id_idx` (`detalles_cotizacion_id`),
  KEY `user_id_idx` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalles_precotizacion`
--

CREATE TABLE IF NOT EXISTS `detalles_precotizacion` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `precotizacion_id` bigint(20) DEFAULT NULL,
  `talento_id` bigint(20) DEFAULT NULL,
  `actividad` text NOT NULL,
  `precio` double(18,2) DEFAULT '0.00',
  `margen_jerry_ml` float(18,2) DEFAULT '20.00',
  `is_active` tinyint(4) DEFAULT NULL,
  `position` int(11) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `precotizacion_id_idx` (`precotizacion_id`),
  KEY `talento_id_idx` (`talento_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empresas`
--

CREATE TABLE IF NOT EXISTS `empresas` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `empresa` varchar(255) NOT NULL,
  `inactivar` date DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Volcado de datos para la tabla `empresas`
--

INSERT INTO `empresas` (`id`, `empresa`, `inactivar`, `created_at`, `updated_at`) VALUES
(1, 'Jerry ML SA de CV', NULL, '2013-01-21 15:27:22', '2013-01-21 15:27:22'),
(2, 'Jerry ML 2 SA de CV', NULL, '2013-01-21 15:27:22', '2013-01-21 15:27:22');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `eventos_usuarios`
--

CREATE TABLE IF NOT EXISTS `eventos_usuarios` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) DEFAULT NULL,
  `subject` varchar(64) DEFAULT NULL,
  `description` text,
  `start_time` datetime DEFAULT NULL,
  `end_time` datetime DEFAULT NULL,
  `is_all_day_event` tinyint(1) DEFAULT '0',
  `color` varchar(10) DEFAULT NULL,
  `recurring_rule` varchar(255) DEFAULT NULL,
  `status` tinyint(4) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `user_id_idx` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `facturas`
--

CREATE TABLE IF NOT EXISTS `facturas` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `cotizacion_id` bigint(20) DEFAULT NULL,
  `user_id` bigint(20) DEFAULT NULL,
  `file` varchar(255) DEFAULT 'no_file.pdf',
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `cotizacion_id_idx` (`cotizacion_id`),
  KEY `user_id_idx` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ks_w_c_event`
--

CREATE TABLE IF NOT EXISTS `ks_w_c_event` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `talento_id` bigint(20) DEFAULT NULL,
  `cotizacion_id` bigint(20) DEFAULT NULL,
  `detalles_cotizacion_id` bigint(20) DEFAULT NULL,
  `nivel` tinyint(4) DEFAULT '1',
  `subject` varchar(64) DEFAULT NULL,
  `description` text,
  `start_time` datetime DEFAULT NULL,
  `end_time` datetime DEFAULT NULL,
  `is_all_day_event` tinyint(1) DEFAULT '0',
  `color` varchar(10) DEFAULT '3',
  `recurring_rule` varchar(255) DEFAULT NULL,
  `lugar_evento` varchar(150) DEFAULT NULL,
  `calle` varchar(150) DEFAULT NULL,
  `numero_exterior` varchar(20) DEFAULT NULL,
  `numero_interior` varchar(20) DEFAULT NULL,
  `colonia` varchar(150) DEFAULT NULL,
  `codigo_postal` varchar(10) DEFAULT NULL,
  `cuidad` varchar(150) DEFAULT NULL,
  `municipio` varchar(150) DEFAULT NULL,
  `estado` varchar(150) DEFAULT NULL,
  `pais` varchar(150) DEFAULT NULL,
  `status` tinyint(4) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `talento_id_idx` (`talento_id`),
  KEY `cotizacion_id_idx` (`cotizacion_id`),
  KEY `detalles_cotizacion_id_idx` (`detalles_cotizacion_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pagos`
--

CREATE TABLE IF NOT EXISTS `pagos` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `referencia` varchar(100) DEFAULT NULL,
  `cliente_id` bigint(20) DEFAULT NULL,
  `importe` double(18,2) DEFAULT '0.00',
  `iva` double(18,2) DEFAULT '0.00',
  `adeudo` double(18,2) DEFAULT '0.00',
  `is_cerrado` tinyint(1) DEFAULT '0',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `cliente_id_idx` (`cliente_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pagos_talentos`
--

CREATE TABLE IF NOT EXISTS `pagos_talentos` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `talento_id` bigint(20) DEFAULT NULL,
  `referencia` varchar(100) DEFAULT NULL,
  `cuenta_deposito` varchar(255) DEFAULT NULL,
  `importe` double(18,2) DEFAULT '0.00',
  `iva` double(18,2) DEFAULT '0.00',
  `isr` double(18,2) DEFAULT '0.00',
  `adeudo` double(18,2) DEFAULT '0.00',
  `is_cerrado` tinyint(1) DEFAULT '0',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `talento_id_idx` (`talento_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `precotizaciones`
--

CREATE TABLE IF NOT EXISTS `precotizaciones` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `cliente_id` bigint(20) DEFAULT NULL,
  `contacto_id` bigint(20) DEFAULT NULL,
  `user_id` bigint(20) DEFAULT NULL,
  `empresa_id` bigint(20) DEFAULT NULL,
  `evento` varchar(255) NOT NULL,
  `descripcion` text NOT NULL,
  `actividad_general` text NOT NULL,
  `lugar_evento` varchar(255) DEFAULT NULL,
  `inicia_evento` datetime DEFAULT NULL,
  `termina_evento` datetime DEFAULT NULL,
  `pdf` varchar(255) DEFAULT 'sin_archivo.pdf',
  `status` tinyint(4) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `slug` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `precotizaciones_sluggable_idx` (`slug`),
  KEY `cliente_id_idx` (`cliente_id`),
  KEY `contacto_id_idx` (`contacto_id`),
  KEY `user_id_idx` (`user_id`),
  KEY `empresa_id_idx` (`empresa_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sf_guard_forgot_password`
--

CREATE TABLE IF NOT EXISTS `sf_guard_forgot_password` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) NOT NULL,
  `unique_key` varchar(255) DEFAULT NULL,
  `expires_at` datetime NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id_idx` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sf_guard_group`
--

CREATE TABLE IF NOT EXISTS `sf_guard_group` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `description` text,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Volcado de datos para la tabla `sf_guard_group`
--

INSERT INTO `sf_guard_group` (`id`, `name`, `description`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'Grupo administracion general', '2013-01-21 15:27:22', '2013-01-21 15:27:22'),
(2, 'cotizaciones', 'Grupo de colaboradores que pueden realizar cotizaciones y precotizaciones', '2013-01-21 15:27:22', '2013-01-21 15:27:22'),
(3, 'pagos', 'Grupo de colaboradores para elaborar y recibir pagos', '2013-01-21 15:27:22', '2013-01-21 15:27:22'),
(4, 'contratos', 'Grupo de colaboradores para elaborar contratos', '2013-01-21 15:27:22', '2013-01-21 15:27:22'),
(5, 'clientes', 'Grupo de administracion de clientes y contactos', '2013-01-21 15:27:22', '2013-01-21 15:27:22'),
(6, 'talentos', 'Grupo de administracion de talentos y sus calendarios', '2013-01-21 15:27:22', '2013-01-21 15:27:22');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sf_guard_group_permission`
--

CREATE TABLE IF NOT EXISTS `sf_guard_group_permission` (
  `group_id` bigint(20) NOT NULL DEFAULT '0',
  `permission_id` bigint(20) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`group_id`,`permission_id`),
  KEY `sf_guard_group_permission_permission_id_sf_guard_permission_id` (`permission_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `sf_guard_group_permission`
--

INSERT INTO `sf_guard_group_permission` (`group_id`, `permission_id`, `created_at`, `updated_at`) VALUES
(1, 1, '2013-01-21 15:27:22', '2013-01-21 15:27:22'),
(2, 2, '2013-01-21 15:27:22', '2013-01-21 15:27:22'),
(3, 3, '2013-01-21 15:27:22', '2013-01-21 15:27:22'),
(3, 4, '2013-01-21 15:27:22', '2013-01-21 15:27:22'),
(4, 5, '2013-01-21 15:27:22', '2013-01-21 15:27:22'),
(5, 6, '2013-01-21 15:27:22', '2013-01-21 15:27:22'),
(6, 7, '2013-01-21 15:27:22', '2013-01-21 15:27:22');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sf_guard_permission`
--

CREATE TABLE IF NOT EXISTS `sf_guard_permission` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `description` text,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

--
-- Volcado de datos para la tabla `sf_guard_permission`
--

INSERT INTO `sf_guard_permission` (`id`, `name`, `description`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'Administrador general', '2013-01-21 15:27:22', '2013-01-21 15:27:22'),
(2, 'cotizaciones', 'Administracion de precotizaciones y cotizaciones', '2013-01-21 15:27:22', '2013-01-21 15:27:22'),
(3, 'pagos_clientes', 'Administracion de pagos de clientes', '2013-01-21 15:27:22', '2013-01-21 15:27:22'),
(4, 'pagos_talentos', 'Administracion de pagos de talentos', '2013-01-21 15:27:22', '2013-01-21 15:27:22'),
(5, 'contratos', 'Administracion de contratos', '2013-01-21 15:27:22', '2013-01-21 15:27:22'),
(6, 'clientes', 'Administracion de clientes', '2013-01-21 15:27:22', '2013-01-21 15:27:22'),
(7, 'talentos', 'Administracion de talentos y sus calendarios', '2013-01-21 15:27:22', '2013-01-21 15:27:22'),
(8, 'usuarios', 'Permiso para visualizar los registros de usuarios', '2013-01-21 15:27:22', '2013-01-21 15:27:22');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sf_guard_remember_key`
--

CREATE TABLE IF NOT EXISTS `sf_guard_remember_key` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) DEFAULT NULL,
  `remember_key` varchar(32) DEFAULT NULL,
  `ip_address` varchar(50) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id_idx` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sf_guard_user`
--

CREATE TABLE IF NOT EXISTS `sf_guard_user` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  `email_address` varchar(255) NOT NULL,
  `color` varchar(30) DEFAULT NULL,
  `firma` varchar(255) DEFAULT NULL,
  `username` varchar(128) NOT NULL,
  `algorithm` varchar(128) NOT NULL DEFAULT 'sha1',
  `salt` varchar(128) DEFAULT NULL,
  `password` varchar(128) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT '1',
  `is_super_admin` tinyint(1) DEFAULT '0',
  `last_login` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email_address` (`email_address`),
  UNIQUE KEY `username` (`username`),
  KEY `is_active_idx_idx` (`is_active`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Volcado de datos para la tabla `sf_guard_user`
--

INSERT INTO `sf_guard_user` (`id`, `first_name`, `last_name`, `email_address`, `color`, `firma`, `username`, `algorithm`, `salt`, `password`, `is_active`, `is_super_admin`, `last_login`, `created_at`, `updated_at`) VALUES
(1, 'Richpolis', 'Systems', 'richpolis@gmail.com', '', NULL, 'Richpolis', 'sha1', 'a43026f49be022bc6df1b9c394ebed66', 'f254a00d6f3046ad0d8c52ade1b29526e00d8897', 1, 1, NULL, '2013-01-21 15:27:22', '2013-01-21 15:27:22'),
(2, 'Andrea', NULL, 'andrea@jerryml.com', '', NULL, 'andrea', 'sha1', '16094f4450a69d1ff2f3da3d2c7733b9', '60934296ca3d9eca629d981dbada0323c99da3a3', 1, 0, NULL, '2013-01-21 15:27:22', '2013-01-21 15:27:22'),
(3, 'Eduardo', NULL, 'eduardo@jerryml.com', '', NULL, 'eduardo', 'sha1', 'a5240899f118cd92fc8bd6d3cc416ab9', 'f08cffbbe56210418c09bcb9774f04ca0ba8bf8f', 1, 1, NULL, '2013-01-21 15:27:22', '2013-01-21 15:27:22'),
(4, 'Lizzy', NULL, 'lizzy@jerryml.com', '', NULL, 'lizzy', 'sha1', '6e028acb9ef5c24e353e7a3c88d5ea4d', 'fe38d3b6367de13999a24f7e003132a8b080d153', 1, 1, NULL, '2013-01-21 15:27:22', '2013-01-21 15:27:22'),
(5, 'Manuel', NULL, 'manuel@jerryml.com', '', NULL, 'manuel', 'sha1', '6da8cda39efff8b0a5e4aca460d8c4ac', '4f92642cdd0b69dc02a4083b654337b58cb9b0b5', 1, 0, NULL, '2013-01-21 15:27:22', '2013-01-21 15:27:22');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sf_guard_user_group`
--

CREATE TABLE IF NOT EXISTS `sf_guard_user_group` (
  `user_id` bigint(20) NOT NULL DEFAULT '0',
  `group_id` bigint(20) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`user_id`,`group_id`),
  KEY `sf_guard_user_group_group_id_sf_guard_group_id` (`group_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `sf_guard_user_group`
--

INSERT INTO `sf_guard_user_group` (`user_id`, `group_id`, `created_at`, `updated_at`) VALUES
(1, 1, '2013-01-21 15:27:22', '2013-01-21 15:27:22'),
(2, 3, '2013-01-21 15:27:22', '2013-01-21 15:27:22'),
(2, 4, '2013-01-21 15:27:22', '2013-01-21 15:27:22'),
(3, 1, '2013-01-21 15:27:22', '2013-01-21 15:27:22'),
(4, 1, '2013-01-21 15:27:22', '2013-01-21 15:27:22'),
(5, 2, '2013-01-21 15:27:22', '2013-01-21 15:27:22'),
(5, 5, '2013-01-21 15:27:22', '2013-01-21 15:27:22'),
(5, 6, '2013-01-21 15:27:22', '2013-01-21 15:27:22');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sf_guard_user_permission`
--

CREATE TABLE IF NOT EXISTS `sf_guard_user_permission` (
  `user_id` bigint(20) NOT NULL DEFAULT '0',
  `permission_id` bigint(20) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`user_id`,`permission_id`),
  KEY `sf_guard_user_permission_permission_id_sf_guard_permission_id` (`permission_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `sf_guard_user_permission`
--

INSERT INTO `sf_guard_user_permission` (`user_id`, `permission_id`, `created_at`, `updated_at`) VALUES
(2, 8, '2013-01-21 15:27:22', '2013-01-21 15:27:22'),
(5, 8, '2013-01-21 15:27:22', '2013-01-21 15:27:22');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `talentos`
--

CREATE TABLE IF NOT EXISTS `talentos` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `descripcion` text NOT NULL,
  `rfc` varchar(100) DEFAULT NULL,
  `calle` varchar(150) DEFAULT NULL,
  `numero_exterior` varchar(20) DEFAULT NULL,
  `numero_interior` varchar(20) DEFAULT NULL,
  `colonia` varchar(150) DEFAULT NULL,
  `codigo_postal` varchar(10) DEFAULT NULL,
  `cuidad` varchar(150) DEFAULT NULL,
  `municipio` varchar(150) DEFAULT NULL,
  `estado` varchar(150) DEFAULT NULL,
  `pais` varchar(150) DEFAULT NULL,
  `telefono` varchar(150) DEFAULT NULL,
  `celular` varchar(150) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `imagen` varchar(255) NOT NULL DEFAULT 'sin_imagen.jpg',
  `margen_jerry_ml` float(18,2) DEFAULT '20.00',
  `saldo` double(18,2) DEFAULT '0.00',
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `slug` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  UNIQUE KEY `talentos_sluggable_idx` (`slug`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Volcado de datos para la tabla `talentos`
--

INSERT INTO `talentos` (`id`, `name`, `descripcion`, `rfc`, `calle`, `numero_exterior`, `numero_interior`, `colonia`, `codigo_postal`, `cuidad`, `municipio`, `estado`, `pais`, `telefono`, `celular`, `email`, `imagen`, `margen_jerry_ml`, `saldo`, `is_active`, `created_at`, `updated_at`, `slug`) VALUES
(1, 'Artista Numero 1', 'Descripcion de lo que ha hecho el artista', 'AAAA19781978KGB', 'direccion de artista 1', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '55555555', '5555555555', 'artista1@gmail.com', 'feb21df9afe3c59bccac4cf8d79c7adedd92cdd3.jpg', 15.00, 0.00, 1, '2013-01-21 15:27:22', '2013-01-21 15:27:22', 'artista-numero-1'),
(2, 'Artista Numero 2', 'Descripcion de lo que ha hecho el artista', 'AAAA19781978KGB', 'direccion de artista 2', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '55555555', '5555555555', 'artista2@gmail.com', '0d9f342ee75609ea27555cd9d34f572caa788a38.jpg', 15.00, 0.00, 1, '2013-01-21 15:27:22', '2013-01-21 15:27:22', 'artista-numero-2');

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `clientes_contactos`
--
ALTER TABLE `clientes_contactos`
  ADD CONSTRAINT `clientes_contactos_cliente_id_clientes_id` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`),
  ADD CONSTRAINT `clientes_contactos_contacto_id_contactos_id` FOREIGN KEY (`contacto_id`) REFERENCES `contactos` (`id`);

--
-- Filtros para la tabla `contratos`
--
ALTER TABLE `contratos`
  ADD CONSTRAINT `contratos_cotizacion_id_cotizaciones_id` FOREIGN KEY (`cotizacion_id`) REFERENCES `cotizaciones` (`id`),
  ADD CONSTRAINT `contratos_user_id_sf_guard_user_id` FOREIGN KEY (`user_id`) REFERENCES `sf_guard_user` (`id`);

--
-- Filtros para la tabla `cotizaciones`
--
ALTER TABLE `cotizaciones`
  ADD CONSTRAINT `cotizaciones_cliente_id_clientes_id` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`),
  ADD CONSTRAINT `cotizaciones_contacto_id_contactos_id` FOREIGN KEY (`contacto_id`) REFERENCES `contactos` (`id`),
  ADD CONSTRAINT `cotizaciones_empresa_id_empresas_id` FOREIGN KEY (`empresa_id`) REFERENCES `empresas` (`id`),
  ADD CONSTRAINT `cotizaciones_manager_id_sf_guard_user_id` FOREIGN KEY (`manager_id`) REFERENCES `sf_guard_user` (`id`),
  ADD CONSTRAINT `cotizaciones_user_id_sf_guard_user_id` FOREIGN KEY (`user_id`) REFERENCES `sf_guard_user` (`id`);

--
-- Filtros para la tabla `detalles_cotizacion`
--
ALTER TABLE `detalles_cotizacion`
  ADD CONSTRAINT `detalles_cotizacion_cotizacion_id_cotizaciones_id` FOREIGN KEY (`cotizacion_id`) REFERENCES `cotizaciones` (`id`),
  ADD CONSTRAINT `detalles_cotizacion_talento_id_talentos_id` FOREIGN KEY (`talento_id`) REFERENCES `talentos` (`id`);

--
-- Filtros para la tabla `detalles_cotizacion_comisionistas`
--
ALTER TABLE `detalles_cotizacion_comisionistas`
  ADD CONSTRAINT `dcci` FOREIGN KEY (`comisionista_id`) REFERENCES `comisionistas` (`id`),
  ADD CONSTRAINT `dddi` FOREIGN KEY (`detalles_cotizacion_id`) REFERENCES `detalles_cotizacion` (`id`),
  ADD CONSTRAINT `detalles_cotizacion_comisionistas_cotizacion_id_cotizaciones_id` FOREIGN KEY (`cotizacion_id`) REFERENCES `cotizaciones` (`id`);

--
-- Filtros para la tabla `detalles_cotizacion_conceptos`
--
ALTER TABLE `detalles_cotizacion_conceptos`
  ADD CONSTRAINT `dddi_1` FOREIGN KEY (`detalles_cotizacion_id`) REFERENCES `detalles_cotizacion` (`id`),
  ADD CONSTRAINT `detalles_cotizacion_conceptos_concepto_id_conceptos_id` FOREIGN KEY (`concepto_id`) REFERENCES `conceptos` (`id`),
  ADD CONSTRAINT `detalles_cotizacion_conceptos_cotizacion_id_cotizaciones_id` FOREIGN KEY (`cotizacion_id`) REFERENCES `cotizaciones` (`id`);

--
-- Filtros para la tabla `detalles_pagos`
--
ALTER TABLE `detalles_pagos`
  ADD CONSTRAINT `detalles_pagos_cotizacion_id_cotizaciones_id` FOREIGN KEY (`cotizacion_id`) REFERENCES `cotizaciones` (`id`),
  ADD CONSTRAINT `detalles_pagos_pagos_id_pagos_id` FOREIGN KEY (`pagos_id`) REFERENCES `pagos` (`id`),
  ADD CONSTRAINT `detalles_pagos_user_id_sf_guard_user_id` FOREIGN KEY (`user_id`) REFERENCES `sf_guard_user` (`id`);

--
-- Filtros para la tabla `detalles_pagos_talentos`
--
ALTER TABLE `detalles_pagos_talentos`
  ADD CONSTRAINT `dddi_2` FOREIGN KEY (`detalles_cotizacion_id`) REFERENCES `detalles_cotizacion` (`id`),
  ADD CONSTRAINT `detalles_pagos_talentos_pagos_talentos_id_pagos_talentos_id` FOREIGN KEY (`pagos_talentos_id`) REFERENCES `pagos_talentos` (`id`),
  ADD CONSTRAINT `detalles_pagos_talentos_user_id_sf_guard_user_id` FOREIGN KEY (`user_id`) REFERENCES `sf_guard_user` (`id`);

--
-- Filtros para la tabla `detalles_precotizacion`
--
ALTER TABLE `detalles_precotizacion`
  ADD CONSTRAINT `detalles_precotizacion_precotizacion_id_precotizaciones_id` FOREIGN KEY (`precotizacion_id`) REFERENCES `precotizaciones` (`id`),
  ADD CONSTRAINT `detalles_precotizacion_talento_id_talentos_id` FOREIGN KEY (`talento_id`) REFERENCES `talentos` (`id`);

--
-- Filtros para la tabla `eventos_usuarios`
--
ALTER TABLE `eventos_usuarios`
  ADD CONSTRAINT `eventos_usuarios_user_id_sf_guard_user_id` FOREIGN KEY (`user_id`) REFERENCES `sf_guard_user` (`id`);

--
-- Filtros para la tabla `facturas`
--
ALTER TABLE `facturas`
  ADD CONSTRAINT `facturas_cotizacion_id_cotizaciones_id` FOREIGN KEY (`cotizacion_id`) REFERENCES `cotizaciones` (`id`),
  ADD CONSTRAINT `facturas_user_id_sf_guard_user_id` FOREIGN KEY (`user_id`) REFERENCES `sf_guard_user` (`id`);

--
-- Filtros para la tabla `ks_w_c_event`
--
ALTER TABLE `ks_w_c_event`
  ADD CONSTRAINT `ks_w_c_event_cotizacion_id_cotizaciones_id` FOREIGN KEY (`cotizacion_id`) REFERENCES `cotizaciones` (`id`),
  ADD CONSTRAINT `ks_w_c_event_detalles_cotizacion_id_detalles_cotizacion_id` FOREIGN KEY (`detalles_cotizacion_id`) REFERENCES `detalles_cotizacion` (`id`),
  ADD CONSTRAINT `ks_w_c_event_talento_id_talentos_id` FOREIGN KEY (`talento_id`) REFERENCES `talentos` (`id`);

--
-- Filtros para la tabla `pagos`
--
ALTER TABLE `pagos`
  ADD CONSTRAINT `pagos_cliente_id_clientes_id` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`);

--
-- Filtros para la tabla `pagos_talentos`
--
ALTER TABLE `pagos_talentos`
  ADD CONSTRAINT `pagos_talentos_talento_id_talentos_id` FOREIGN KEY (`talento_id`) REFERENCES `talentos` (`id`);

--
-- Filtros para la tabla `precotizaciones`
--
ALTER TABLE `precotizaciones`
  ADD CONSTRAINT `precotizaciones_cliente_id_clientes_id` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`),
  ADD CONSTRAINT `precotizaciones_contacto_id_contactos_id` FOREIGN KEY (`contacto_id`) REFERENCES `contactos` (`id`),
  ADD CONSTRAINT `precotizaciones_empresa_id_empresas_id` FOREIGN KEY (`empresa_id`) REFERENCES `empresas` (`id`),
  ADD CONSTRAINT `precotizaciones_user_id_sf_guard_user_id` FOREIGN KEY (`user_id`) REFERENCES `sf_guard_user` (`id`);

--
-- Filtros para la tabla `sf_guard_forgot_password`
--
ALTER TABLE `sf_guard_forgot_password`
  ADD CONSTRAINT `sf_guard_forgot_password_user_id_sf_guard_user_id` FOREIGN KEY (`user_id`) REFERENCES `sf_guard_user` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `sf_guard_group_permission`
--
ALTER TABLE `sf_guard_group_permission`
  ADD CONSTRAINT `sf_guard_group_permission_group_id_sf_guard_group_id` FOREIGN KEY (`group_id`) REFERENCES `sf_guard_group` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `sf_guard_group_permission_permission_id_sf_guard_permission_id` FOREIGN KEY (`permission_id`) REFERENCES `sf_guard_permission` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `sf_guard_remember_key`
--
ALTER TABLE `sf_guard_remember_key`
  ADD CONSTRAINT `sf_guard_remember_key_user_id_sf_guard_user_id` FOREIGN KEY (`user_id`) REFERENCES `sf_guard_user` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `sf_guard_user_group`
--
ALTER TABLE `sf_guard_user_group`
  ADD CONSTRAINT `sf_guard_user_group_group_id_sf_guard_group_id` FOREIGN KEY (`group_id`) REFERENCES `sf_guard_group` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `sf_guard_user_group_user_id_sf_guard_user_id` FOREIGN KEY (`user_id`) REFERENCES `sf_guard_user` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `sf_guard_user_permission`
--
ALTER TABLE `sf_guard_user_permission`
  ADD CONSTRAINT `sf_guard_user_permission_permission_id_sf_guard_permission_id` FOREIGN KEY (`permission_id`) REFERENCES `sf_guard_permission` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `sf_guard_user_permission_user_id_sf_guard_user_id` FOREIGN KEY (`user_id`) REFERENCES `sf_guard_user` (`id`) ON DELETE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
