-- ============================================================================
-- Práctica 9: Base de datos relacional PI - Pisos e Inmuebles (pibd)
-- Compatible con MariaDB (XAMPP) y MySQL 8.0+
-- Juego de caracteres: utf8mb4 / utf8mb4_unicode_ci
-- ============================================================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

CREATE DATABASE IF NOT EXISTS `pibd`
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE `pibd`;

-- ============================================================================
-- Usuario específico para la aplicación web con permisos mínimos (pág. 11)
-- ============================================================================
CREATE USER IF NOT EXISTS 'wwwdata'@'localhost' IDENTIFIED BY 'abc';
GRANT SELECT, INSERT, UPDATE, DELETE ON `pibd`.* TO 'wwwdata'@'localhost';
FLUSH PRIVILEGES;

-- ============================================================================
-- Eliminación ordenada de tablas si ya existieran (para reimportación limpia)
-- ============================================================================
DROP TABLE IF EXISTS `Mensajes`;
DROP TABLE IF EXISTS `Solicitudes`;
DROP TABLE IF EXISTS `Fotos`;
DROP TABLE IF EXISTS `Anuncios`;
DROP TABLE IF EXISTS `Usuarios`;
DROP TABLE IF EXISTS `TiposMensajes`;
DROP TABLE IF EXISTS `TiposViviendas`;
DROP TABLE IF EXISTS `TiposAnuncios`;
DROP TABLE IF EXISTS `Estilos`;
DROP TABLE IF EXISTS `Paises`;

-- ============================================================================
-- Tablas maestras
-- ============================================================================

CREATE TABLE `Paises` (
  `IdPais` INT NOT NULL AUTO_INCREMENT,
  `NomPais` VARCHAR(100) NOT NULL,
  PRIMARY KEY (`IdPais`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `Estilos` (
  `IdEstilo` INT NOT NULL AUTO_INCREMENT,
  `Nombre` VARCHAR(100) NOT NULL,
  `Descripcion` TEXT NOT NULL,
  `Fichero` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`IdEstilo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `TiposAnuncios` (
  `IdTAnuncio` SMALLINT NOT NULL AUTO_INCREMENT,
  `NomTAnuncio` VARCHAR(50) NOT NULL,
  PRIMARY KEY (`IdTAnuncio`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `TiposViviendas` (
  `IdTVivienda` SMALLINT NOT NULL AUTO_INCREMENT,
  `NomTVivienda` VARCHAR(50) NOT NULL,
  PRIMARY KEY (`IdTVivienda`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `TiposMensajes` (
  `IdTMensaje` SMALLINT NOT NULL AUTO_INCREMENT,
  `NomTMensaje` VARCHAR(100) NOT NULL,
  PRIMARY KEY (`IdTMensaje`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- Tablas principales
-- ============================================================================

CREATE TABLE `Usuarios` (
  `IdUsuario` INT NOT NULL AUTO_INCREMENT,
  `NomUsuario` VARCHAR(15) NOT NULL,
  `Clave` VARCHAR(255) NOT NULL,
  `Email` VARCHAR(254) NOT NULL,
  `Sexo` TINYINT DEFAULT NULL COMMENT '1: Hombre, 2: Mujer, 3: Otro',
  `FNacimiento` DATE DEFAULT NULL,
  `Ciudad` VARCHAR(100) DEFAULT NULL,
  `Pais` INT DEFAULT NULL,
  `Foto` VARCHAR(255) DEFAULT NULL,
  `FRegistro` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Estilo` INT NOT NULL DEFAULT 1,
  PRIMARY KEY (`IdUsuario`),
  UNIQUE KEY `uk_usuarios_nomusuario` (`NomUsuario`),
  KEY `idx_usuarios_pais` (`Pais`),
  KEY `idx_usuarios_estilo` (`Estilo`),
  CONSTRAINT `fk_usuarios_paises` FOREIGN KEY (`Pais`) REFERENCES `Paises` (`IdPais`) ON UPDATE CASCADE ON DELETE SET NULL,
  CONSTRAINT `fk_usuarios_estilos` FOREIGN KEY (`Estilo`) REFERENCES `Estilos` (`IdEstilo`) ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `Anuncios` (
  `IdAnuncio` INT NOT NULL AUTO_INCREMENT,
  `TAnuncio` SMALLINT NOT NULL,
  `TVivienda` SMALLINT NOT NULL,
  `FPrincipal` VARCHAR(255) NOT NULL,
  `Alternativo` VARCHAR(255) NOT NULL,
  `Titulo` VARCHAR(200) NOT NULL,
  `Precio` DECIMAL(12, 2) NOT NULL,
  `Texto` TEXT NOT NULL,
  `Ciudad` VARCHAR(100) NOT NULL,
  `Pais` INT NOT NULL,
  `Superficie` DECIMAL(10, 2) NOT NULL,
  `NHabitaciones` INT NOT NULL DEFAULT 0,
  `NBanyos` INT NOT NULL DEFAULT 0,
  `Planta` INT NOT NULL DEFAULT 0,
  `Anyo` INT NOT NULL,
  `FRegistro` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Usuario` INT NOT NULL,
  PRIMARY KEY (`IdAnuncio`),
  KEY `idx_anuncios_tanuncio` (`TAnuncio`),
  KEY `idx_anuncios_tvivienda` (`TVivienda`),
  KEY `idx_anuncios_pais` (`Pais`),
  KEY `idx_anuncios_usuario` (`Usuario`),
  KEY `idx_anuncios_fregistro` (`FRegistro`),
  CONSTRAINT `chk_anuncios_alternativo` CHECK (CHAR_LENGTH(`Alternativo`) >= 10),
  CONSTRAINT `fk_anuncios_tanuncios` FOREIGN KEY (`TAnuncio`) REFERENCES `TiposAnuncios` (`IdTAnuncio`) ON UPDATE CASCADE ON DELETE RESTRICT,
  CONSTRAINT `fk_anuncios_tviviendas` FOREIGN KEY (`TVivienda`) REFERENCES `TiposViviendas` (`IdTVivienda`) ON UPDATE CASCADE ON DELETE RESTRICT,
  CONSTRAINT `fk_anuncios_paises` FOREIGN KEY (`Pais`) REFERENCES `Paises` (`IdPais`) ON UPDATE CASCADE ON DELETE RESTRICT,
  CONSTRAINT `fk_anuncios_usuarios` FOREIGN KEY (`Usuario`) REFERENCES `Usuarios` (`IdUsuario`) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `Fotos` (
  `IdFoto` INT NOT NULL AUTO_INCREMENT,
  `Titulo` VARCHAR(200) NOT NULL,
  `Foto` VARCHAR(255) NOT NULL,
  `Alternativo` VARCHAR(255) NOT NULL,
  `Anuncio` INT NOT NULL,
  PRIMARY KEY (`IdFoto`),
  KEY `idx_fotos_anuncio` (`Anuncio`),
  CONSTRAINT `chk_fotos_alternativo` CHECK (CHAR_LENGTH(`Alternativo`) >= 10),
  CONSTRAINT `fk_fotos_anuncios` FOREIGN KEY (`Anuncio`) REFERENCES `Anuncios` (`IdAnuncio`) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `Solicitudes` (
  `IdSolicitud` INT NOT NULL AUTO_INCREMENT,
  `Anuncio` INT NOT NULL,
  `Texto` VARCHAR(4000) DEFAULT NULL,
  `Nombre` VARCHAR(200) NOT NULL,
  `Email` VARCHAR(254) NOT NULL,
  `Direccion` TEXT NOT NULL,
  `Telefono` VARCHAR(20) DEFAULT NULL,
  `Color` VARCHAR(50) NOT NULL,
  `Copias` INT NOT NULL,
  `Resolucion` INT NOT NULL,
  `Fecha` DATE DEFAULT NULL,
  `IColor` BOOLEAN NOT NULL DEFAULT FALSE,
  `IPrecio` BOOLEAN NOT NULL DEFAULT TRUE,
  `FRegistro` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Coste` DECIMAL(10, 2) NOT NULL,
  PRIMARY KEY (`IdSolicitud`),
  KEY `idx_solicitudes_anuncio` (`Anuncio`),
  CONSTRAINT `fk_solicitudes_anuncios` FOREIGN KEY (`Anuncio`) REFERENCES `Anuncios` (`IdAnuncio`) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `Mensajes` (
  `IdMensaje` INT NOT NULL AUTO_INCREMENT,
  `TMensaje` SMALLINT NOT NULL,
  `Texto` VARCHAR(4000) NOT NULL,
  `Anuncio` INT NOT NULL,
  `UsuOrigen` INT NOT NULL,
  `UsuDestino` INT NOT NULL,
  `FRegistro` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`IdMensaje`),
  KEY `idx_mensajes_tmensaje` (`TMensaje`),
  KEY `idx_mensajes_anuncio` (`Anuncio`),
  KEY `idx_mensajes_usuorigen` (`UsuOrigen`),
  KEY `idx_mensajes_usudestino` (`UsuDestino`),
  CONSTRAINT `fk_mensajes_tmensajes` FOREIGN KEY (`TMensaje`) REFERENCES `TiposMensajes` (`IdTMensaje`) ON UPDATE CASCADE ON DELETE RESTRICT,
  CONSTRAINT `fk_mensajes_anuncios` FOREIGN KEY (`Anuncio`) REFERENCES `Anuncios` (`IdAnuncio`) ON UPDATE CASCADE ON DELETE CASCADE,
  CONSTRAINT `fk_mensajes_usuorigen` FOREIGN KEY (`UsuOrigen`) REFERENCES `Usuarios` (`IdUsuario`) ON UPDATE CASCADE ON DELETE CASCADE,
  CONSTRAINT `fk_mensajes_usudestino` FOREIGN KEY (`UsuDestino`) REFERENCES `Usuarios` (`IdUsuario`) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS = 1;

-- ============================================================================
-- Datos iniciales para tablas maestras
-- ============================================================================

INSERT INTO `Paises` (`IdPais`, `NomPais`) VALUES
  (1, 'España'),
  (2, 'Francia'),
  (3, 'Italia'),
  (4, 'Alemania'),
  (5, 'Portugal'),
  (6, 'Países Bajos'),
  (7, 'Bélgica'),
  (8, 'Irlanda'),
  (9, 'Reino Unido'),
  (10, 'Estados Unidos');

INSERT INTO `Estilos` (`IdEstilo`, `Nombre`, `Descripcion`, `Fichero`) VALUES
  (1, 'Estilo principal', 'Diseño predeterminado adaptativo con paleta suave y alta legibilidad.', 'style.css'),
  (2, 'Modo noche', 'Esquema oscuro de bajo brillo con texto blanco desaturado para entornos con poca luz.', 'css/noche.css'),
  (3, 'Alto contraste', 'Fondo negro con texto blanco, títulos amarillos y enlaces cian (WCAG AAA) para personas con baja visión.', 'css/contraste.css'),
  (4, 'Texto grande', 'Aumenta el tamaño de letra un 25 %, amplía el interlineado y utiliza la fuente Atkinson Hyperlegible.', 'css/grande.css'),
  (5, 'Alto contraste y texto grande', 'Combina simultáneamente el esquema de alto contraste AAA y el tamaño de letra ampliado con fuente hiperlegible.', 'css/contrasteygrande.css');

INSERT INTO `TiposAnuncios` (`IdTAnuncio`, `NomTAnuncio`) VALUES
  (1, 'Venta'),
  (2, 'Alquiler');

INSERT INTO `TiposViviendas` (`IdTVivienda`, `NomTVivienda`) VALUES
  (1, 'Obra nueva'),
  (2, 'Vivienda'),
  (3, 'Oficina'),
  (4, 'Local'),
  (5, 'Garaje');

INSERT INTO `TiposMensajes` (`IdTMensaje`, `NomTMensaje`) VALUES
  (1, 'Más información'),
  (2, 'Solicitar una cita'),
  (3, 'Comunicar una oferta');

-- ============================================================================
-- Usuarios de prueba (contraseñas cifradas con password_hash / BCRYPT):
--   pedro    -> pedro123  (Estilo principal)
--   juan     -> Juan123   (Texto grande)
--   maria    -> Maria123  (Alto contraste)
--   admin    -> Admin123  (Alto contraste y texto grande)
--   usuario1 -> Pass123   (Modo noche)
-- ============================================================================

INSERT INTO `Usuarios` (`IdUsuario`, `NomUsuario`, `Clave`, `Email`, `Sexo`, `FNacimiento`, `Ciudad`, `Pais`, `Foto`, `FRegistro`, `Estilo`) VALUES
  (1, 'pedro', '$2y$10$b732tM/ldGEb3HkN4JB09OAMcYqyqxbCXIE2laFleY1EsRofO2A5C', 'pedro@pi-inmuebles.es', 1, '1998-05-14', 'Alicante', 1, 'img/interior.jpg', '2026-01-10 09:30:00', 1),
  (2, 'juan', '$2y$10$njy5QZiFiG5hFtTW/7SgYOyt2GohX6vVj.z2p4Y954kSPTKptEVzi', 'juan@pi-inmuebles.es', 1, '1992-08-22', 'Valencia', 1, 'img/interior2.jpg', '2026-02-15 11:20:00', 4),
  (3, 'maria', '$2y$10$JzS/lML2Hk.xpk06WhZq8u3BTS.pf0aqQaW9VBXz8pXVK5rAk0.1a', 'maria@pi-inmuebles.es', 2, '1995-03-11', 'Madrid', 1, 'img/interior3.jpg', '2026-03-04 17:45:00', 3),
  (4, 'admin', '$2y$10$ScQw4xDfT7ESQ.iNlmXtweqbf3333n/rDkLlDly/r6cLTfkrFfEt.', 'admin@pi-inmuebles.es', 3, '1988-11-30', 'Barcelona', 1, 'img/interior4.jpg', '2026-01-02 08:00:00', 5),
  (5, 'usuario1', '$2y$10$XTmEvn9ZZXNX7b2QXp2cKeeDuHypzV7kXmCSyAESGRjo1tWMq90..', 'usuario1@pi-inmuebles.es', 1, '1999-07-09', 'Sevilla', 1, 'img/piso.jpg', '2026-04-19 14:10:00', 2);

-- ============================================================================
-- Anuncios variados (10 anuncios en distintos tipos, ciudades, países y fechas)
-- ============================================================================

INSERT INTO `Anuncios` (`IdAnuncio`, `TAnuncio`, `TVivienda`, `FPrincipal`, `Alternativo`, `Titulo`, `Precio`, `Texto`, `Ciudad`, `Pais`, `Superficie`, `NHabitaciones`, `NBanyos`, `Planta`, `Anyo`, `FRegistro`, `Usuario`) VALUES
  (1, 1, 2, 'img/piso.jpg', 'Vista principal del piso luminoso en Alicante', 'Piso luminoso en Alicante', 185000.00, 'Magnífico piso muy luminoso situado en una de las mejores zonas de Alicante. Totalmente amueblado y listo para entrar a vivir. Cuenta con excelentes vistas despejadas y está muy bien comunicado con todos los servicios públicos, comercios y transporte de la ciudad.', 'Alicante', 1, 90.00, 3, 2, 4, 2010, '2026-09-25 10:15:00', 2),
  (2, 2, 1, 'img/piso2.jpg', 'Vista principal del apartamento moderno en Valencia', 'Apartamento moderno en Valencia', 950.00, 'Espectacular apartamento de obra nueva en pleno centro de Valencia. Diseño contemporáneo con acabados de primera calidad, climatización por conductos, gran eficiencia energética y terraza soleada ideal para disfrutar todo el año.', 'Valencia', 1, 75.00, 2, 1, 2, 2022, '2026-09-24 18:30:00', 3),
  (3, 1, 2, 'img/piso3.jpg', 'Vista principal del piso reformado en Madrid', 'Piso reformado en Madrid', 275000.00, 'Amplio piso íntegramente reformado en una zona residencial tranquila de Madrid. Dispone de suelos de parquet, carpintería exterior con rotura de puente térmico, calefacción individual y excelente conexión con metro y autobuses.', 'Madrid', 1, 105.00, 3, 2, 3, 2015, '2026-09-23 12:00:00', 1),
  (4, 2, 2, 'img/piso4.jpg', 'Vista principal de la casa con terraza en Sevilla', 'Casa con terraza en Sevilla', 1150.00, 'Encantadora vivienda con amplia terraza privada en Sevilla. Destaca por su luminosidad natural, patio interior tradicional, amplios dormitorios con armarios empotrados y cercanía a zonas verdes y comerciales.', 'Sevilla', 1, 130.00, 4, 2, 0, 2018, '2026-09-22 16:40:00', 3),
  (5, 1, 1, 'img/piso5.jpg', 'Vista principal del piso céntrico en Barcelona', 'Piso céntrico en Barcelona', 320000.00, 'Exclusivo piso céntrico en Barcelona con techos altos y acabados modernos. Ubicado a pocos minutos del transporte público, cuenta con balcón exterior, cocina equipada de última generación y gran aislamiento acústico.', 'Barcelona', 1, 98.00, 3, 2, 5, 2021, '2026-09-21 09:50:00', 4),
  (6, 2, 4, 'img/interior.jpg', 'Interior amplio del local comercial en Alicante', 'Local comercial en Alicante', 1400.00, 'Local comercial diáfano a pie de calle en una avenida con gran tránsito peatonal en Alicante. Dispone de amplio escaparate, dos aseos adaptados, almacén independiente y preinstalación de aire acondicionado.', 'Alicante', 1, 120.00, 0, 2, 0, 2016, '2026-09-18 11:10:00', 1),
  (7, 2, 5, 'img/interior2.jpg', 'Acceso cómodo a la plaza de garaje en Alicante', 'Plaza de garaje amplia en Alicante', 95.00, 'Plaza de garaje amplia en primer sótano con fácil maniobra y puerta automática con mando a distancia. Ideal para coche grande o todoterreno en zona céntrica de Alicante.', 'Alicante', 1, 18.00, 0, 0, -1, 2019, '2026-09-15 08:25:00', 2),
  (8, 1, 3, 'img/interior3.jpg', 'Despacho principal de la oficina luminosa en París', 'Oficina luminosa en París', 410000.00, 'Oficina representativa totalmente equipada en distrito financiero de París. Cuenta con cuatro salas de trabajo independientes, sala de juntas acristalada, fibra óptica de alta velocidad y recepción.', 'París', 2, 115.00, 4, 2, 3, 2017, '2026-09-12 13:45:00', 5),
  (9, 2, 2, 'img/interior4.jpg', 'Dormitorio luminoso del apartamento en Lisboa', 'Apartamento con vistas en Lisboa', 1250.00, 'Acogedor apartamento amueblado en Lisboa con vistas despejadas sobre el casco histórico. Dispone de dos dormitorios dobles, cocina equipada, balcón soleado y excelente comunicación en tranvía.', 'Lisboa', 5, 82.00, 2, 1, 4, 2020, '2026-09-10 19:05:00', 1),
  (10, 1, 1, 'img/piso2.jpg', 'Terraza y salón del ático de diseño en Roma', 'Ático de diseño en Roma', 495000.00, 'Espectacular ático de obra nueva en Roma con gran terraza panorámica. Materiales nobles, domótica integrada, tres dormitorios con baño en suite y plaza de aparcamiento incluida.', 'Roma', 3, 140.00, 3, 2, 6, 2024, '2026-09-08 15:20:00', 4);

-- ============================================================================
-- Fotos asociadas a los anuncios (múltiples fotos por anuncio)
-- ============================================================================

INSERT INTO `Fotos` (`Titulo`, `Foto`, `Alternativo`, `Anuncio`) VALUES
  ('Salón principal', 'img/interior.jpg', 'Vista amplia del salón comedor iluminado en Alicante', 1),
  ('Cocina equipada', 'img/interior2.jpg', 'Vista de la cocina totalmente equipada del piso en Alicante', 1),
  ('Baño principal', 'img/interior3.jpg', 'Vista del cuarto de baño principal reformado con ducha', 1),
  ('Dormitorio principal', 'img/interior4.jpg', 'Vista del dormitorio principal con armario empotrado', 1),

  ('Cocina americana', 'img/interior2.jpg', 'Cocina americana integrada en el salón del apartamento', 2),
  ('Dormitorio exterior', 'img/interior4.jpg', 'Dormitorio principal exterior con ventanal en Valencia', 2),
  ('Baño de diseño', 'img/interior3.jpg', 'Baño moderno con plato de ducha a ras de suelo', 2),
  ('Zona de estar', 'img/interior.jpg', 'Zona de estar luminosa junto al balcón en Valencia', 2),

  ('Salón comedor', 'img/interior.jpg', 'Salón principal del piso reformado en Madrid', 3),
  ('Cocina independiente', 'img/interior2.jpg', 'Cocina independiente con electrodomésticos en Madrid', 3),
  ('Baño completo', 'img/interior3.jpg', 'Baño completo con acabados cerámicos modernos', 3),
  ('Dormitorio principal', 'img/interior4.jpg', 'Habitación principal exterior muy luminosa en Madrid', 3),

  ('Dormitorio con terraza', 'img/interior4.jpg', 'Dormitorio principal con salida directa a la terraza', 4),
  ('Salón de estar', 'img/interior.jpg', 'Salón principal de la casa familiar en Sevilla', 4),
  ('Cocina amplia', 'img/interior2.jpg', 'Cocina amplia y luminosa con zona de desayuno', 4),
  ('Baño principal', 'img/interior3.jpg', 'Cuarto de baño principal con ventilación natural', 4),

  ('Cocina de diseño', 'img/interior2.jpg', 'Cocina de diseño contemporáneo en piso de Barcelona', 5),
  ('Salón exterior', 'img/interior.jpg', 'Salón exterior con amplio balcón en Barcelona', 5),
  ('Dormitorio doble', 'img/interior4.jpg', 'Dormitorio doble luminoso con armarios empotrados', 5),
  ('Baño con ducha', 'img/interior3.jpg', 'Baño principal con mampara de cristal templado', 5),

  ('Zona de atención', 'img/interior.jpg', 'Zona principal diáfana del local comercial en Alicante', 6),
  ('Aseos del local', 'img/interior3.jpg', 'Aseos adaptados dentro del local comercial en Alicante', 6),

  ('Acceso de rampa', 'img/interior2.jpg', 'Zona de entrada y rampa de acceso al garaje', 7),

  ('Sala de juntas', 'img/interior.jpg', 'Sala de juntas principal de la oficina en París', 8),
  ('Despacho ejecutivo', 'img/interior4.jpg', 'Despacho exterior con mobiliario moderno en París', 8),

  ('Salón con vistas', 'img/interior.jpg', 'Salón amueblado con vistas al centro de Lisboa', 9),
  ('Cocina equipada Lisboa', 'img/interior2.jpg', 'Cocina equipada del apartamento turístico en Lisboa', 9),

  ('Salón panorámico', 'img/interior.jpg', 'Salón panorámico del ático de diseño en Roma', 10),
  ('Suite principal', 'img/interior4.jpg', 'Suite principal con salida a terraza en Roma', 10);

-- ============================================================================
-- Mensajes cruzados entre usuarios sobre los anuncios
-- ============================================================================

INSERT INTO `Mensajes` (`TMensaje`, `Texto`, `Anuncio`, `UsuOrigen`, `UsuDestino`, `FRegistro`) VALUES
  (1, 'Hola Juan, estoy interesado en el piso luminoso de Alicante. ¿Podrías indicarme los gastos de comunidad?', 1, 1, 2, '2026-09-26 10:00:00'),
  (2, 'Buenos días María, me gustaría concertar una visita para el apartamento moderno en Valencia esta semana.', 2, 1, 3, '2026-09-26 11:30:00'),
  (2, 'Hola Pedro, ¿podríamos visitar el piso reformado de Madrid el próximo jueves por la tarde?', 3, 2, 1, '2026-09-27 09:15:00'),
  (1, 'Buenas tardes Pedro, ¿el local comercial de Alicante cuenta con salida de humos?', 6, 3, 1, '2026-09-27 16:40:00'),
  (3, 'Hola Pedro, quisiera presentar una oferta por el piso de Madrid de 268.000 euros al contado.', 3, 4, 1, '2026-09-28 12:20:00'),
  (1, 'Hola María, ¿el alquiler de la casa en Sevilla incluye el mantenimiento del jardín y terraza?', 4, 5, 3, '2026-09-28 18:05:00'),
  (2, 'Buenas tardes admin, me gustaría solicitar cita para ver el piso céntrico en Barcelona.', 5, 1, 4, '2026-09-29 10:45:00'),
  (1, 'Hola Pedro, ¿el apartamento en Lisboa admite estancias de media duración?', 9, 2, 1, '2026-09-29 19:10:00');

-- ============================================================================
-- Solicitudes de folleto publicitario de ejemplo
-- ============================================================================

INSERT INTO `Solicitudes` (`Anuncio`, `Texto`, `Nombre`, `Email`, `Direccion`, `Telefono`, `Color`, `Copias`, `Resolucion`, `Fecha`, `IColor`, `IPrecio`, `FRegistro`, `Coste`) VALUES
  (3, 'Folleto promocional para repartir en feria inmobiliaria de Madrid.', 'Pedro González', 'pedro@pi-inmuebles.es', 'Avda. de la Estación 12, 3º B, 03005, Alicante, España', '600112233', '#1e40af', 10, 300, '2026-10-05', TRUE, TRUE, '2026-09-28 09:00:00', 220.00),
  (6, 'Díptico informativo para escaparates de la zona centro.', 'Pedro González', 'pedro@pi-inmuebles.es', 'Calle Mayor 45, Bajo, 03002, Alicante, España', '600112233', '#0f766e', 5, 150, '2026-10-10', FALSE, TRUE, '2026-09-29 13:15:00', 95.00),
  (1, 'Folleto en alta calidad a todo color.', 'Juan Pérez', 'juan@pi-inmuebles.es', 'Gran Vía 88, 4º A, 46005, Valencia, España', '611223344', '#7c2d12', 2, 600, '2026-10-12', TRUE, TRUE, '2026-09-30 16:40:00', 48.00);
