
CREATE  TABLE usuarios (

    id INT AUTO_INCREMENT PRIMARY KEY,

    username VARCHAR(50) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,

    nombre VARCHAR(80) NOT NULL,
    apellido VARCHAR(80) NULL,

    telefono VARCHAR(20) NULL,
    direccion VARCHAR(150) NULL,

    rol_id INT NOT NULL,
    activo TINYINT DEFAULT 1,

    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NULL ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_usuario_rol
        FOREIGN KEY (rol_id) REFERENCES roles(id)

  
       

);

INSERT INTO usuarios
(username,password_hash,nombre,apellido,telefono,direccion,rol_id)
VALUES
(
 'admin',
 '$2y$10$wHhQY7o6Xk1nG0QKXJQb5e4LxH9xgJ7Jw5cYkJkPpR7bYzGZ9p7hK', -- password: 1234
 'Administrador',
 'Sistema',
 '5555555555',
 'Oficina central',
 1
);


UPDATE usuarios SET rol_id = 1 WHERE username='admin';
UPDATE usuarios SET rol_id = 2 WHERE username='recepcion1';
UPDATE usuarios SET rol_id = 3 WHERE username='oficina1';


CREATE TABLE pisos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(50),
  activo TINYINT DEFAULT 1
);


-- 1. Desactivar revisión de llaves foráneas
SET FOREIGN_KEY_CHECKS = 0;

-- 2. Borrar la tabla
DROP TABLE habitaciones;

-- 3. Reactivar revisión (IMPORTANTE para no romper la integridad después)
SET FOREIGN_KEY_CHECKS = 1;

CREATE   TABLE habitaciones (
  id INT AUTO_INCREMENT PRIMARY KEY,
  numero VARCHAR(10),
  piso_id INT,
  tipo_habitacion_id INT,
  estado_id INT,
  status VARCHAR(5) DEFAULT 'X',
  activa TINYINT DEFAULT 1,
  CONSTRAINT fk_habitacion_tipo
    FOREIGN KEY (tipo_habitacion_id)
    REFERENCES habitaciones_tipos(id)
);

SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE turnos;
SET FOREIGN_KEY_CHECKS = 1;


	CREATE   TABLE turnos (

		id INT AUTO_INCREMENT PRIMARY KEY,
        usuario_id INT NULL,
		nombre VARCHAR(60) NOT NULL,          -- Turno Mañana
		hora_inicio TIME NOT NULL,             -- 06:00
		hora_fin TIME NOT NULL,                -- 14:00

		color VARCHAR(10) NULL,                -- para UI (badge, calendario)
		icono VARCHAR(30) NULL,                -- opcional visual PMS

		activo TINYINT DEFAULT 1,

		created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
		updated_at DATETIME NULL ON UPDATE CURRENT_TIMESTAMP

	);


INSERT INTO turnos (usuario_id,nombre,hora_inicio,hora_fin,color,activo) VALUES
(1,'Turno Mañana','06:00','14:00','#2563eb',1),
(1,'Turno Tarde','14:00','22:00','#16a34a',1),
(1,'Turno Noche','22:00','06:00','#ea580c',1);

CREATE TABLE turnos_operacion (

    id INT AUTO_INCREMENT PRIMARY KEY,

    turno_id INT NOT NULL,
    usuario_id INT NOT NULL,

    fecha DATE NOT NULL,

    hora_inicio_real DATETIME NULL,
    hora_fin_real DATETIME NULL,

    estado VARCHAR(20) DEFAULT 'ABIERTO',
        -- ABIERTO / CERRADO / AUDITADO

    observaciones VARCHAR(200) NULL,

    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (turno_id) REFERENCES turnos(id),
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id)

);


-- 1. Desactivar revisión de llaves foráneas
SET FOREIGN_KEY_CHECKS = 0;

-- 2. Borrar la tabla
DROP TABLE registros;

-- 3. Reactivar revisión (IMPORTANTE para no romper la integridad después)
SET FOREIGN_KEY_CHECKS = 1;


CREATE  TABLE registros (
    
    -- 🔑 IDENTIDAD / RELACIONES
    id INT AUTO_INCREMENT PRIMARY KEY,
    habitacion_id INT NOT NULL,
    huesped_id INT NOT NULL,
    tipo_estadia_id INT,
    turno_id INT,
    registro_origen_id INT NULL,
    usuario_id INT,
    id_perfil INT,

    -- 🏨 CICLO DE ESTANCIA
    fecha_estadia DATE,
    hora_entrada DATETIME,
    hora_salida DATETIME,
    hora_salida_real DATETIME,
    noches INT DEFAULT 1,
    estado_registro VARCHAR(20) DEFAULT 'CHECKIN',
    estado_servicio VARCHAR(20) DEFAULT 'ACTIVO',

    -- 👥 OCUPACIÓN
    num_personas_ext INT,
    adultos INT,
    niños INT,
    cliente_frecuente TINYINT DEFAULT 0,
    lista_negra TINYINT DEFAULT 0,

    -- 💰 FINANCIERO
    forma_pago_id INT,
    precio_base DECIMAL(10,2),
    precio DECIMAL(10,2),
    iva DECIMAL(10,2),
    ish DECIMAL(10,2),
    total DECIMAL(10,2),
    pago_adicional DECIMAL(10,2) DEFAULT 0,

    -- 🧾 OPERATIVO / COMERCIAL
    ticket_emitido TINYINT DEFAULT 0,
    motivo_visita VARCHAR(100),
    tipo_cancelacion VARCHAR(100),
    motivo_cancelacion VARCHAR(100),
    observaciones TEXT,
    
    -- 🕓 AUDITORÍA
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NULL ON UPDATE CURRENT_TIMESTAMP

);


ALTER TABLE registros 
ADD COLUMN tipo_cancelacion VARCHAR(100) DEFAULT NULL;

ALTER TABLE registros 
ADD COLUMN incluir_en_reporte TINYINT(1) DEFAULT 0;

ALTER TABLE registros 
MODIFY COLUMN incluir_en_reporte TINYINT(1) DEFAULT 0;

ALTER TABLE registros 
ADD COLUMN estado_id INT DEFAULT 2;




CREATE INDEX idx_reg_habitacion ON registros(habitacion_id);
CREATE INDEX idx_reg_huesped ON registros(huesped_id);
CREATE INDEX idx_reg_fecha ON registros(fecha_estadia);
CREATE INDEX idx_reg_estado ON registros(estado_registro);

CREATE  VIEW Resumen_pisos as
SELECT P.id,
       P.PISO,
       P.NOMBRE,
       COUNT(*) 'N° HABITACIONES',
       SUM(H.activa) ACTIVAS   FROM pisos P
LEFT JOIN habitaciones H ON P.ID=H.piso_id
GROUP BY P.PISO,
         P.NOMBRE
ORDER BY P.id   ;

CREATE TABLE estados_habitacion (
    id INT AUTO_INCREMENT PRIMARY KEY,
    codigo VARCHAR(10) NOT NULL,
    nombre VARCHAR(80) NOT NULL,
    descripcion VARCHAR(150),
    icono VARCHAR(80),
    color VARCHAR(20),
    activo TINYINT DEFAULT 1
);

INSERT INTO estados_habitacion
(codigo,nombre,descripcion,icono,color)
VALUES

('S','Sucia',
 'Ocupada o pendiente de limpieza',
 'fa-broom',
 '#ef4444'),

('X','Limpia',
 'Disponible para rentar',
 'fa-check-circle',
 '#22c55e'),

('M','Mantenimiento',
 'Fuera de servicio temporal',
 'fa-tools',
 '#f59e0b'),

('P','Planta',
 'Uso exclusivo del personal',
 'fa-user-lock',
 '#64748b'),

('VAP','VIP',
 'Habitación especial',
 'fa-star',
 '#a855f7'),
 
 ('O','Ocupada',
 'Ocupada',
 'fa-star',
 '#a855f7');
 
 
CREATE TABLE razones_sociales (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(150),
    rfc VARCHAR(20),
    telefono VARCHAR(30),
    logo VARCHAR(255),
    direccion VARCHAR(255),
    activo TINYINT DEFAULT 1,
    created_at DATETIME,
    updated_at DATETIME
);

CREATE    TABLE ticket_config (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(120),
    descripcion VARCHAR(255),
    razon_social_id INT NULL,

    ancho_mm INT DEFAULT 80,
    copias INT DEFAULT 1,
    logo_visible TINYINT DEFAULT 1,
    mensaje_pie VARCHAR(255),

    ver_habitacion TINYINT DEFAULT 1,
    ver_huesped TINYINT DEFAULT 1,
    ver_fecha TINYINT DEFAULT 1,
    ver_desglose TINYINT DEFAULT 1,
    ver_pago TINYINT DEFAULT 1,
    ver_folio TINYINT DEFAULT 1,

    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME,
    activo TINYINT DEFAULT 0,

    FOREIGN KEY (razon_social_id) REFERENCES razones_sociales(id)
);


CREATE TABLE roles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL,
    recepcion TINYINT DEFAULT 0,
    reportes TINYINT DEFAULT 0,
    editar_sistema TINYINT DEFAULT 0,
    activo TINYINT DEFAULT 1
);


INSERT INTO roles (nombre, recepcion, reportes, editar_sistema) VALUES
('Administrador',1,1,1),
('Recepcionista',1,0,0),
('Supervision',0,1,0);


CREATE TABLE configuraciones (

    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL UNIQUE,
    valor TEXT NULL,
    fecha_creacion DATETIME DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion DATETIME 
        DEFAULT CURRENT_TIMESTAMP 
        ON UPDATE CURRENT_TIMESTAMP
);

INSERT INTO configuraciones (nombre,valor) VALUES
('iva','16'),
('ish','3'),
('precio_modo','final'),
('ticket_desglose','siempre'),
('turnos_dia','3'),
('hotel_nombre','CIMASOL'),
('ticket_ancho','80');


CREATE TABLE habitaciones_tipos (

    id INT AUTO_INCREMENT PRIMARY KEY,

    nombre VARCHAR(100) NOT NULL,
    clave VARCHAR(20) NULL,

    precio_base DECIMAL(10,2) NOT NULL,
	precio_persona_extra DECIMAL(10,2) NOT NULL,

    iva DECIMAL(10,2) DEFAULT 0,
    ish DECIMAL(10,2) DEFAULT 0,
    precio_total DECIMAL(10,2) DEFAULT 0,

    personas_max INT DEFAULT 2,
    color VARCHAR(20) NULL,
    icono VARCHAR(50) NULL,

    orden INT DEFAULT 1,
    activo TINYINT DEFAULT 1,

    fecha_creacion DATETIME DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion DATETIME 
        DEFAULT CURRENT_TIMESTAMP 
        ON UPDATE CURRENT_TIMESTAMP

);

INSERT INTO habitaciones_tipos
(nombre,clave,precio_base,personas_max,color,icono,orden)
VALUES
('Estándar','STD',294.12,2,'azul','bed',1),
('Plus','PLS',352.94,2,'verde','star',2),
('Suite','STE',588.24,3,'morado','crown',3);

CREATE TABLE config_perifericos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    clave VARCHAR(50) UNIQUE,
    activo TINYINT DEFAULT 0,
    nombre_detectado VARCHAR(150),
    fecha_actualiza DATETIME
);

call sp_insertar_habitaciones_bloque (1,100,10,2,2)

DELIMITER $$

CREATE PROCEDURE sp_insertar_habitaciones_bloque(
    IN p_piso_id INT,
    IN p_numero_inicio INT,
    IN p_cantidad INT,
    IN p_tipo_habitacion_id INT,
    IN p_estado_id INT
)
BEGIN

    DECLARE i INT DEFAULT 0;
    DECLARE v_numero INT;

    SET v_numero = p_numero_inicio;

    WHILE i < p_cantidad DO

        -- validar que no exista
        IF NOT EXISTS (
            SELECT 1 FROM habitaciones 
            WHERE numero = v_numero
        ) THEN

            INSERT INTO habitaciones(
                numero,
                piso_id,
                tipo_habitacion_id,
                estado_id,
                status,
                activa
            )
            VALUES(
                v_numero,
                p_piso_id,
                p_tipo_habitacion_id,
                p_estado_id,
                'X',
                1
            );

        END IF;

        SET v_numero = v_numero + 1;
        SET i = i + 1;

    END WHILE;

END$$

DELIMITER ;




CREATE OR REPLACE VIEW vw_trabajo AS
SELECT
    h.id,
    h.numero,
    h.piso_id,
    h.tipo_habitacion_id,
    h.activa,

    e.codigo       AS estado_codigo,
    e.nombre       AS estado_nombre,
    e.icono        AS estado_icono,
    e.color        AS estado_color,

    t.nombre       AS tipo_nombre,

    /* ===== MOCK OPERATIVO ===== */

    'S'            AS tipo_operacion,
    2              AS personas,
    'E'            AS forma_pago,
    350            AS precio,

    '09:15'        AS hora_entrada,
    '11:30'        AS hora_salida,

    'HUESPED DEMO' AS huesped_nombre,

    0              AS opc_extra,
    0              AS ticket_impreso

FROM habitaciones h
LEFT JOIN estados_habitacion e 
    ON e.id = h.estado_id
LEFT JOIN habitaciones_tipos t
    ON t.id = h.tipo_habitacion_id

WHERE h.activa = 1
ORDER BY CAST(h.numero AS UNSIGNED) ;



CREATE TABLE huesped_identificaciones_tipos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(80) NOT NULL,
    activo TINYINT DEFAULT 1
);

INSERT INTO huesped_identificaciones_tipos(nombre) VALUES
('INE'),
('Pasaporte'),
('Licencia de conducir'),
('Visa'),
('Otro');


CREATE  TABLE huespedes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(120) NOT NULL,
    apellido VARCHAR(120),
    tipo_identificacion_id INT,
    numero_identificacion VARCHAR(80),
    fotografia VARCHAR(255),
    telefono VARCHAR(40),
    email VARCHAR(120),
    direccion VARCHAR(255),
    ciudad VARCHAR(120),
    estado VARCHAR(120),
    pais VARCHAR(120),
    codigo_postal VARCHAR(20),
    nacionalidad VARCHAR(80),
    fecha_nacimiento DATE,
    genero CHAR(1),
    empresa VARCHAR(150),
    notas TEXT,
    activo TINYINT DEFAULT 1,
    fecha_alta TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_huesped_tipo_identificacion
        FOREIGN KEY (tipo_identificacion_id)
        REFERENCES huesped_identificaciones_tipos(id)
);


ALTER TABLE huespedes 
ADD COLUMN firma_path VARCHAR(255);

ALTER TABLE huespedes 
ADD COLUMN identificacion VARCHAR(255);


CREATE TABLE estancias (
    id INT AUTO_INCREMENT PRIMARY KEY,

    huesped_id INT NOT NULL,
    habitacion_id INT NOT NULL,

    fecha_checkin DATETIME,
    fecha_checkout DATETIME,

    precio DECIMAL(10,2),
    forma_pago VARCHAR(20),

    personas INT DEFAULT 1,

    estado VARCHAR(20) DEFAULT 'ACTIVA',
    /* ACTIVA
       FINALIZADA
       CANCELADA
       NO_SHOW */

    notas TEXT,

    fecha_alta TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_estancia_huesped
        FOREIGN KEY (huesped_id)
        REFERENCES huespedes(id),

    CONSTRAINT fk_estancia_habitacion
        FOREIGN KEY (habitacion_id)
        REFERENCES habitaciones(id)
);


CREATE OR REPLACE VIEW vw_trabajo_operativo AS
SELECT
    h.id,
    h.numero,
    h.piso_id,
    h.tipo_habitacion_id,
    h.estado_id,
    h.activa,

    eh.codigo   AS estado_codigo,
    eh.nombre   AS estado_nombre,
    eh.icono    AS estado_icono,
    eh.color    AS estado_color,

    ht.nombre   AS tipo_nombre,

    e.id        AS estancia_id,
    e.fecha_checkin,
    e.fecha_checkout,
    e.precio,
    e.forma_pago,
    e.personas,

    hu.nombre   AS huesped_nombre,
    hu.apellido AS huesped_apellido

FROM habitaciones h

LEFT JOIN estados_habitacion eh
    ON eh.id = h.estado_id

LEFT JOIN habitaciones_tipos ht
    ON ht.id = h.tipo_habitacion_id

LEFT JOIN estancias e
    ON e.habitacion_id = h.id
    AND e.estado = 'ACTIVA'

LEFT JOIN huespedes hu
    ON hu.id = e.huesped_id

WHERE h.activa = 1

ORDER BY CAST(h.numero AS UNSIGNED);


CREATE TABLE estancias_acompanantes (
    id INT AUTO_INCREMENT PRIMARY KEY,

    estancia_id INT NOT NULL,
    nombre VARCHAR(120),
    apellido VARCHAR(120),

    tipo VARCHAR(30),
    /* CONYUGE
       HIJO
       AMIGO
       MENOR
       OTRO */

    edad INT,
    notas VARCHAR(200),

    CONSTRAINT fk_acomp_estancia
        FOREIGN KEY (estancia_id)
        REFERENCES estancias(id)
);


INSERT INTO huespedes
(
nombre,
apellido,
tipo_identificacion_id,
numero_identificacion,
fotografia,
telefono,
email,
direccion,
ciudad,
estado,
pais,
codigo_postal,
nacionalidad,
fecha_nacimiento,
genero,
empresa,
notas,
activo
)
VALUES

('Juan','García',1,'INE001',NULL,'5511111111','juan@mail.com','Av Reforma 100','CDMX','CDMX','México','06000','Mexicana','1989-05-12','M','',NULL,1),

('Ana','Martínez',2,'PAS002',NULL,'5522222222','ana@mail.com','Calle Sol 45','Madrid','Madrid','España','28001','Española','1992-03-22','F','',NULL,1),

('Carlos','Flores',1,'INE003',NULL,'5533333333','carlos@mail.com','Col Roma 12','CDMX','CDMX','México','06700','Mexicana','1985-11-01','M','Constructora Norte','Cliente frecuente',1),

('Mario','López',1,'INE004',NULL,'5544444444','mario@mail.com','Centro 88','Puebla','Puebla','México','72000','Mexicana','1978-01-15','M','',NULL,1),

('Sofía','Ramírez',2,'PAS005',NULL,'5555555555','sofia@mail.com','Gran Via 10','Barcelona','Cataluña','España','08001','Española','1998-07-09','F','',NULL,1),

('Pedro','Rodríguez',1,'INE006',NULL,'5566666666','pedro@mail.com','Av Juárez 900','Guadalajara','Jalisco','México','44100','Mexicana','1980-09-17','M','',NULL,1),

('Luis','Torres',1,'INE007',NULL,'5577777777','luis@mail.com','Norte 45','Monterrey','Nuevo León','México','64000','Mexicana','1990-02-28','M','',NULL,1),

('Laura','Herrera',1,'INE008',NULL,'5588888888','laura@mail.com','Zona Centro','Querétaro','Querétaro','México','76000','Mexicana','1995-06-14','F','Hotel Business','Viaje laboral',1),

('Roberto','Vargas',1,'INE009',NULL,'5599999999','roberto@mail.com','Av Hidalgo 50','Toluca','Estado México','México','50000','Mexicana','1975-04-03','M','',NULL,1),

('Jorge','Díaz',1,'INE010',NULL,'5510101010','jorge@mail.com','Calle 5','Tijuana','Baja California','México','22000','Mexicana','1988-12-21','M','',NULL,1),

('Andrea','Castro',2,'PAS011',NULL,'5520202020','andrea@mail.com','Ocean Drive','Miami','Florida','USA','33101','Americana','1993-08-18','F','',NULL,1),

('Ricardo','Mendoza',1,'INE012',NULL,'5530303030','ricardo@mail.com','Av Universidad','CDMX','CDMX','México','04360','Mexicana','1982-10-05','M','',NULL,1),

('Fernanda','Ortiz',1,'INE013',NULL,'5540404040','fernanda@mail.com','Col Del Valle','CDMX','CDMX','México','03100','Mexicana','1999-01-25','F','',NULL,1),

('Miguel','Barrera',1,'INE014',NULL,'5550505050','miguel@mail.com','Zona Dorada','Mazatlán','Sinaloa','México','82000','Mexicana','1987-03-30','M','',NULL,1),

('Patricia','Luna',2,'PAS015',NULL,'5560606060','patricia@mail.com','Centro Histórico','CDMX','CDMX','México','06010','Mexicana','1991-09-11','F','Empresa Luna SA','',1),

('Sergio','Vega',1,'INE016',NULL,'5570707070','sergio@mail.com','Av Insurgentes','CDMX','CDMX','México','06100','Mexicana','1984-07-07','M','',NULL,1),

('Daniela','Silva',1,'INE017',NULL,'5580808080','daniela@mail.com','Polanco','CDMX','CDMX','México','11560','Mexicana','1996-05-19','F','',NULL,1),

('Alberto','Cruz',1,'INE018',NULL,'5590909090','alberto@mail.com','Zona Norte','León','Guanajuato','México','37000','Mexicana','1979-11-02','M','',NULL,1),

('Mónica','Pérez',2,'PAS019',NULL,'5512121212','monica@mail.com','Av Central','Bogotá','Cundinamarca','Colombia','110111','Colombiana','1994-04-27','F','',NULL,1),

('Esteban','Navarro',1,'INE020',NULL,'5523232323','esteban@mail.com','Zona Sur','Mérida','Yucatán','México','97000','Mexicana','1986-02-13','M','',NULL,1);



CREATE  TABLE registro_acompanantes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    registro_id INT NOT NULL,
    nombre VARCHAR(150) NOT NULL,
    apellido VARCHAR(150) NULL,
    tipo_identificacion VARCHAR(50) NULL,
    numero_identificacion VARCHAR(80) NULL,
    parentesco VARCHAR(80) NULL,
    es_menor TINYINT DEFAULT 0,
	es_ext TINYINT DEFAULT 0,
    orden_llegada INT DEFAULT 1,
    hora_entrada DATETIME NULL,
    hora_salida DATETIME NULL,
	fotografia VARCHAR(255),
    estado_estancia VARCHAR(20) DEFAULT 'ACTIVO',
    observaciones VARCHAR(200) NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NULL,

    INDEX idx_registro (registro_id),
    CONSTRAINT fk_acompanante_registro
        FOREIGN KEY (registro_id)
        REFERENCES registros(id)
        ON DELETE CASCADE
);

ALTER TABLE registro_acompanantes 
ADD COLUMN es_ext TINYINT DEFAULT 0;

ALTER TABLE registro_acompanantes 
ADD COLUMN firma_path VARCHAR(255);

ALTER TABLE registro_acompanantes 
ADD COLUMN identificacion VARCHAR(255);

ALTER TABLE registro_acompanantes 
ADD COLUMN Responsable_menor VARCHAR(255);



INSERT INTO   registro_acompanantes
(registro_id,nombre,tipo_identificacion,numero_identificacion,parentesco,es_menor)
VALUES

(1,'MARIA GARCIA','INE','AC001','ESPOSA',0),
(1,'PEDRO GARCIA','ACTA','AC002','HIJO',1),

(3,'CARLA FLORES','INE','AC003','AMIGA',0),

(6,'LUCIA RODRIGUEZ','ACTA','AC004','HIJA',1),
(6,'JORGE RODRIGUEZ','INE','AC005','PRIMO',0),

(9,'MARIO VARGAS','INE','AC006','HERMANO',0),

(12,'ANA MENDOZA','INE','AC007','ESPOSA',0),
(12,'DIEGO MENDOZA','ACTA','AC008','HIJO',1),

(15,'PAOLA LUNA','INE','AC009','AMIGA',0),

(19,'SOFIA PEREZ','ACTA','AC010','HIJA',1),
(19,'RICARDO PEREZ','INE','AC011','ESPOSO',0);

SET FOREIGN_KEY_CHECKS = 0;

TRUNCATE TABLE registros;
-- También deberías truncar la de acompañantes si quieres limpiar todo
TRUNCATE TABLE registro_acompanantes; 

SET FOREIGN_KEY_CHECKS = 1;

INSERT INTO registros
(
habitacion_id,
turno_id,
huesped_id,
tipo_estadia_id,
dias_pagados,
num_personas,
forma_pago_id,
precio_base,
iva,
ish,
total,
hora_entrada,
hora_salida,
observaciones,
lista_negra,
cliente_frecuente,
ticket_emitido,
created_at
)
VALUES

-- S → salida mismo día 13:00
(1,1,1,1,1,3,1,294.12,47.06,8.82,350,'2026-03-16 08:05:00','2026-03-16 13:00:00','Familia con menor',0,0,1,NOW()),
(2,1,2,1,1,1,2,352.94,56.47,10.59,420,'2026-03-16 08:20:00','2026-03-16 13:00:00','Cliente frecuente',0,1,1,NOW()),
(4,1,4,1,1,1,1,252.10,40.34,7.56,300,'2026-03-16 08:50:00','2026-03-16 13:00:00',NULL,0,0,1,NOW()),
(5,1,5,1,1,2,2,294.12,47.06,8.82,350,'2026-03-16 09:05:00','2026-03-16 13:00:00',NULL,0,0,1,NOW()),
(8,1,8,1,1,2,2,352.94,56.47,10.59,420,'2026-03-16 09:55:00','2026-03-16 13:00:00','Viaje empresa',0,1,1,NOW()),
(10,1,10,1,1,1,3,294.12,47.06,8.82,350,'2026-03-16 10:25:00','2026-03-16 13:00:00',NULL,0,0,1,NOW()),
(11,1,11,1,1,3,3,352.94,56.47,10.59,420,'2026-03-16 10:40:00','2026-03-16 13:00:00','Familia turista',0,0,1,NOW()),

-- SQ → salida = entrada + dias_pagados a las 13:00
(3,1,3,2,2,2,3,470.59,75.29,14.12,560,'2026-03-16 08:35:00','2026-03-18 13:00:00','Viaje negocio',0,0,1,NOW()),
(6,1,6,2,3,4,3,588.24,94.12,17.64,700,'2026-03-16 09:20:00','2026-03-19 13:00:00','Grupo familiar',0,0,1,NOW()),
(9,1,9,2,2,2,2,470.59,75.29,14.12,560,'2026-03-16 10:10:00','2026-03-18 13:00:00','Cliente conflictivo',1,0,1,NOW()),

-- P → sin salida (planta)
(7,1,7,3,1,1,1,235.29,37.65,7.06,280,'2026-03-16 09:40:00',NULL,NULL,0,0,1,NOW()),
(12,1,12,3,4,5,1,588.24,94.12,17.64,700,'2026-03-16 11:00:00',NULL,'Grupo grande',0,0,1,NOW()),
(13,1,13,3,1,2,1,294.12,47.06,8.82,350,'2026-03-16 11:20:00',NULL,'VIP recurrente',0,1,1,NOW()),
(14,1,14,3,1,1,3,252.10,40.34,7.56,300,'2026-03-16 11:40:00',NULL,NULL,0,0,1,NOW());



CREATE TABLE tipo_estadia (
    id INT AUTO_INCREMENT PRIMARY KEY,
    codigo VARCHAR(5) NOT NULL,
    nombre VARCHAR(50) NOT NULL,
    permite_doble_linea TINYINT DEFAULT 0,
    salida_mismo_dia TINYINT DEFAULT 0,
    activo TINYINT DEFAULT 1,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO tipo_estadia (codigo,nombre,permite_doble_linea,salida_mismo_dia)
VALUES
('S','Salida hoy',0,1),
('SQ','Salida siguiente',0,0),
('P','Estancia prolongada',1,0);


CREATE TABLE formas_pago (
    id INT AUTO_INCREMENT PRIMARY KEY,
    codigo VARCHAR(5) NOT NULL UNIQUE,
    descripcion VARCHAR(50) NOT NULL,
    activo TINYINT DEFAULT 1,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);


INSERT INTO formas_pago (codigo, descripcion) VALUES
('E',   'Efectivo'),
('EF',  'Efectivo facturado'),
('TC',  'Tarjeta de crédito'),
('TCF', 'Tarjeta de crédito facturada'),
('TD',  'Tarjeta de débito'),
('TDF', 'Tarjeta de débito facturada');


CREATE TABLE registro_room_service (
    id INT AUTO_INCREMENT PRIMARY KEY,

    /* === RELACION ESTANCIA === */
    registro_id INT NOT NULL,

    /* === PRODUCTO CATALOGO === */
    producto_id INT NOT NULL,

    /* === SNAPSHOT HISTORICO === */
    nombre_producto VARCHAR(120) NOT NULL,
    precio_unitario DECIMAL(10,2) NOT NULL,

    cantidad INT DEFAULT 1,
    subtotal DECIMAL(10,2) NOT NULL,

    /* === CONTROL OPERATIVO === */
    estado_cargo VARCHAR(20) DEFAULT 'PENDIENTE',

    hora_cargo DATETIME DEFAULT CURRENT_TIMESTAMP,
    hora_entrega DATETIME NULL,

    usuario_id INT NULL,

    observaciones VARCHAR(200) NULL,

    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NULL,

    /* === INDEXES === */
    INDEX idx_rs_registro (registro_id),
    INDEX idx_rs_producto (producto_id),

    /* === FK === */
    CONSTRAINT fk_rs_registro
        FOREIGN KEY (registro_id)
        REFERENCES registros(id)
        ON DELETE CASCADE,

    CONSTRAINT fk_rs_producto
        FOREIGN KEY (producto_id)
        REFERENCES room_service_productos(id)
);

SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE room_service_productos;
SET FOREIGN_KEY_CHECKS = 1;

CREATE  TABLE room_service_productos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    codigo VARCHAR(20) UNIQUE,
    nombre VARCHAR(120) NOT NULL,
    descripcion VARCHAR(200),
    categoria VARCHAR(60),
    precio DECIMAL(10,2) NOT NULL,
    requiere_inventario TINYINT DEFAULT 0,
    icono VARCHAR(60),
    activo TINYINT DEFAULT 1,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NULL
);


INSERT INTO room_service_productos 
(codigo, nombre, descripcion, categoria, precio, requiere_inventario, icono, activo)
VALUES

-- DESAYUNOS
('RS-101', 'Desayuno Continental', 'Incluye café, jugo, pan y fruta', 'DESAYUNO', 180.00, 1, 'fa-coffee', 1),
('RS-102', 'Huevos al Gusto', 'Huevos preparados al gusto con guarnición', 'DESAYUNO', 155.00, 1, 'fa-egg', 1),
('RS-103', 'Hot Cakes', 'Orden de hot cakes con miel y mantequilla', 'DESAYUNO', 140.00, 1, 'fa-bread-slice', 1),
('RS-104', 'Chilaquiles', 'Chilaquiles rojos o verdes con pollo', 'DESAYUNO', 165.00, 1, 'fa-pepper-hot', 1),

-- COMIDA
('RS-201', 'Hamburguesa de la Casa', 'Carne de res con queso, papas y aderezos', 'COMIDA', 320.00, 1, 'fa-hamburger', 1),
('RS-202', 'Club Sandwich', 'Pan tostado con pollo, tocino y vegetales', 'COMIDA', 210.00, 1, 'fa-bread-slice', 1),
('RS-203', 'Pizza Personal', 'Pizza individual de pepperoni o hawaiana', 'COMIDA', 285.00, 1, 'fa-pizza-slice', 1),
('RS-204', 'Pasta Alfredo', 'Pasta cremosa con pollo o camarón', 'COMIDA', 260.00, 1, 'fa-utensils', 1),
('RS-205', 'Ensalada César', 'Lechuga, pollo, crutones y aderezo césar', 'COMIDA', 190.00, 1, 'fa-leaf', 1),

-- BEBIDAS
('RS-301', 'Vino Tinto Copa', 'Copa de vino tinto de la casa', 'BEBIDAS', 160.00, 0, 'fa-wine-glass', 1),
('RS-302', 'Cerveza Nacional', 'Cerveza clara nacional', 'BEBIDAS', 85.00, 0, 'fa-beer', 1),
('RS-303', 'Refresco de Lata', 'Refresco en lata 355ml', 'BEBIDAS', 55.00, 0, 'fa-glass-whiskey', 1),
('RS-304', 'Agua Embotellada', 'Botella de agua 600ml', 'BEBIDAS', 40.00, 0, 'fa-tint', 1),
('RS-305', 'Jugo Natural', 'Jugo de naranja recién exprimido', 'BEBIDAS', 70.00, 0, 'fa-glass-whiskey', 1),

-- EXTRA (ROOM SERVICE PREMIUM)
('RS-401', 'Tabla de Quesos', 'Selección de quesos y frutos secos', 'SNACK', 350.00, 1, 'fa-cheese', 1),
('RS-402', 'Botella de Vino', 'Botella completa de vino tinto', 'BEBIDAS', 650.00, 0, 'fa-wine-bottle', 1),
('RS-403', 'Postre del Día', 'Postre preparado por el chef', 'POSTRE', 120.00, 1, 'fa-ice-cream', 1);





/*
CREATE   TABLE registro_pagos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    registro_id INT NOT NULL,
    forma_pago_id INT NOT NULL,
    monto DECIMAL(10,2) NOT NULL,
    qty int,
    concepto VARCHAR(120) DEFAULT 'Pago adicional',
    tipo VARCHAR(120),
    tipo_movimiento ENUM('CARGO','PAGO','AJUSTE'),
    hora_pago DATETIME DEFAULT CURRENT_TIMESTAMP,
    referencia_pago VARCHAR(100) NULL,
    banco VARCHAR(80) NULL,
    estado VARCHAR(80) NULL,
	sistema VARCHAR(80) NULL,
    usuario_id INT NULL,
    observaciones VARCHAR(200) NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_pago_registro (registro_id),

    CONSTRAINT fk_pago_registro
        FOREIGN KEY (registro_id)
        REFERENCES registros(id)
        ON DELETE CASCADE,

    CONSTRAINT fk_pago_forma
        FOREIGN KEY (forma_pago_id)
        REFERENCES formas_pago(id)
);*/


INSERT INTO registro_pagos 
(registro_id, forma_pago_id, monto, concepto, hora_pago, referencia_pago, banco, estado, sistema, usuario_id, observaciones, tipo_movimiento)
VALUES

-- 🏨 Cargo hospedaje
(6, 1, 2500.00, 'Hospedaje Noche 1', '2026-03-21 14:20:00', NULL, NULL, 'Cargado', 'SISTEMA', 1, NULL, 'CARGO'),

-- 🍽 Room service
(6, 1, 450.00, 'Room Service Cena', '2026-03-21 18:45:00', NULL, NULL, 'Cargado', 'RESTAURANTE', 2, NULL, 'CARGO'),

-- 🥤 Minibar
(6, 1, 120.00, 'Minibar Refrescos', '2026-03-21 19:00:00', NULL, NULL, 'Cargado', 'AMA_LLAVES', 2, NULL, 'CARGO'),

-- 🧺 Lavandería
(6, 1, 180.00, 'Servicio Lavandería', '2026-03-22 10:10:00', NULL, NULL, 'Pendiente', 'AMA_LLAVES', 3, NULL, 'CARGO'),

-- 💳 Pago tarjeta parcial
(6, 3, 1500.00, 'Pago Tarjeta VISA', '2026-03-22 11:30:00', 'AUTH784512', 'BBVA', 'Aprobado', 'RECEPCION', 1, 'Pago parcial', 'PAGO'),

-- 💵 Pago efectivo
(6, 2, 1000.00, 'Pago Efectivo', '2026-03-22 11:45:00', NULL, NULL, 'Recibido', 'RECEPCION', 1, NULL, 'PAGO'),

-- ⚙ Ajuste descuento
(6, 1, -150.00, 'Descuento Cliente Frecuente', '2026-03-22 12:00:00', NULL, NULL, 'Aplicado', 'SISTEMA', 1, 'Promo VIP', 'AJUSTE'),

-- 🍷 Consumo bar nocturno
(6, 1, 320.00, 'Bar Lounge - Bebidas', '2026-03-22 22:15:00', NULL, NULL, 'Cargado', 'BAR', 4, NULL, 'CARGO'),

-- 💳 Pago transferencia
(6, 4, 820.00, 'Transferencia SPEI', '2026-03-23 08:05:00', 'SPEI99821', 'SANTANDER', 'Confirmado', 'RECEPCION', 1, NULL, 'PAGO');


CREATE OR REPLACE VIEW vw_total_cuenta_habitacion AS

WITH rs AS (
    SELECT registro_id, SUM(subtotal) total_rs
    FROM registro_room_service
    WHERE estado_cargo <> 'CANCELADO'
    GROUP BY registro_id
),

pagos AS (
    SELECT registro_id, SUM(monto) total_pagos
    FROM registro_pagos
    GROUP BY registro_id
)

SELECT
    r.id registro_id,
    r.habitacion_id,

    r.total AS total_estadia,

    IFNULL(rs.total_rs,0) total_room_service,
    IFNULL(pagos.total_pagos,0) total_pagos,

    (
        r.total +
        IFNULL(rs.total_rs,0) +
        IFNULL(pagos.total_pagos,0)
    ) total_cuenta

FROM registros r
LEFT JOIN rs ON rs.registro_id = r.id
LEFT JOIN pagos ON pagos.registro_id = r.id
WHERE r.estado_servicio = 'ACTIVO';



INSERT INTO registro_pagos
(registro_id,forma_pago_id,monto,concepto,hora_pago,observaciones)
VALUES

(1,1,100,'Pago acompañante','2026-03-16 09:10:00','Esposa paga parte'),
(1,3,50,'Pago adicional','2026-03-16 10:05:00','Cargo rápido'),

(2,2,200,'Anticipo','2026-03-16 09:30:00','Cliente frecuente'),

(3,3,150,'Pago corporativo','2026-03-16 11:20:00','Empresa cubre'),

(5,1,80,'Pago snack','2026-03-16 10:40:00','Efectivo recepción'),

(6,5,300,'Pago grupo','2026-03-16 11:00:00','Parte familia'),

(6,1,120,'Pago menor','2026-03-16 12:10:00','Complemento'),

(8,3,200,'Pago tarjeta','2026-03-16 12:30:00','Viaje negocio'),

(9,1,60,'Pago rápido','2026-03-16 12:45:00','Cliente conflictivo'),

(11,6,180,'Pago facturado','2026-03-16 13:10:00','Solicita factura'),

(12,3,400,'Pago grupo','2026-03-16 13:30:00','Parte estancia'),

(13,1,90,'Pago VIP','2026-03-16 14:00:00','Cliente recurrente');





CREATE OR REPLACE VIEW vw_trabajo AS
WITH total_guests AS (
    -- Calcula el total de personas (titular + acompañantes)
    SELECT 
        r.id AS id,
        1 + IFNULL(a.acom, 0) AS Total_huespedes 
    FROM registros r 
    LEFT JOIN (
        SELECT 
            registro_id, 
            COUNT(0) AS acom 
        FROM registro_acompanantes 
        GROUP BY registro_id
    ) a ON a.registro_id = r.id 
    WHERE r.estado_servicio = 'ACTIVO'
), 

reservations AS (
    -- Cruza los registros activos con catálogos (estadía, pago, huéspedes)
    SELECT 
        r.*, 
        te.codigo AS Cod_Estadia,
        te.nombre AS Nom_estadia,
        tg.Total_huespedes AS Total_huespedes,
        fp.codigo AS Cod_Forma_pago,
        fp.descripcion AS Forma_pago,
        CONCAT(h.nombre, ' ', h.apellido) AS Nombre_Huesped, 
        h.nombre,
        h.apellido,        
        h.numero_identificacion,
        h.fotografia,
        ti.nombre tipo_identificacion
    FROM registros r 
    LEFT JOIN tipo_estadia     te ON te.id = r.tipo_estadia_id
    LEFT JOIN total_guests     tg ON tg.id = r.id
    LEFT JOIN formas_pago      fp ON fp.id = r.forma_pago_id
    LEFT JOIN huespedes        h  ON h.id  = r.huesped_id 
    LEFT JOIN huesped_identificaciones_tipos ti on h.tipo_identificacion_id=ti.id
    WHERE r.estado_servicio = 'ACTIVO'
)

-- Consulta Final: Une las habitaciones con sus reservas actuales (si existen)
SELECT 
    h.id AS id,
    eh.codigo AS cod_eh,
    eh.nombre AS estados_habitacion,
	h.id AS Habitacion_id,
    h.numero AS Numero_Habitacion,
    f.Piso AS piso,
    f.Nombre AS Piso_Descripcion,
    h.tipo_habitacion_id AS tipo_habitacion_id,
    rt.clave AS cod_tip_habitacion,
    rt.nombre AS tip_habitacion,
    re.id id_reservacion,
    re.tipo_estadia_id,
    CASE WHEN re.Cod_Estadia ='SQ' and re.noches > 1 THEN  CONCAT(re.Cod_Estadia,' ',CONVERT(re.noches,CHAR),'d') else re.Cod_Estadia end Cod_Estadia  ,
    re.Nom_estadia,
    re.Total_huespedes,
    re.Cod_Forma_pago,
    re.Forma_pago,
    re.precio_base,
    DATEDIFF(re.hora_salida, re.hora_entrada) AS noches,
    re.hora_entrada,
    re.hora_salida,
    re.hora_salida_real,
    re.huesped_id,
    re.Nombre_Huesped,
    re.nombre,
    re.apellido,
    re.fotografia,
    re.observaciones,
    re.numero_identificacion,
	re.tipo_identificacion,
    re.turno_id,
    re.estado_registro,
    re.estado_servicio,
	re.precio,
    re.iva,
	re.ish,
    re.precio + re.iva + re.ish as Sub_total,
    rt.personas_max,
    rt.precio_persona_extra,
	CONCAT('ADM-', LPAD(re.id, 6, '0')) AS folio,
    DATE_FORMAT(re.hora_entrada, '%d %b %Y') AS admin_fecha_registro
FROM habitaciones h 
LEFT JOIN pisos                f  ON f.id  = h.piso_id
LEFT JOIN habitaciones_tipos   rt ON rt.id = h.tipo_habitacion_id
LEFT JOIN reservations         re ON re.habitacion_id = h.id
LEFT JOIN estados_habitacion   eh ON h.estado_id = eh.id;



CREATE INDEX idx_huesped_nombre
ON huespedes(nombre);

CREATE INDEX idx_huesped_apellido
ON huespedes(apellido);

CREATE INDEX idx_huesped_tel
ON huespedes(telefono);

CREATE INDEX idx_huesped_idnum
ON huespedes(numero_identificacion);



CREATE  TABLE registro_estacionamiento (

    id INT AUTO_INCREMENT PRIMARY KEY,
    tipo VARCHAR(50), 
    registro_id INT NOT NULL,          -- relación con estancia
    numero_cajon VARCHAR(10),          -- cajón asignado
    placa VARCHAR(20),
    modelo VARCHAR(80),
    color VARCHAR(40),
    registro_acompanante_id INT NULL,
    tipo_vehiculo VARCHAR(30),         -- AUTO / MOTO / VAN
    cargo DECIMAL(10,2) DEFAULT 0,

    hora_entrada DATETIME,
    hora_salida DATETIME,

    estado VARCHAR(20) DEFAULT 'ACTIVO',

    observaciones VARCHAR(200),

    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NULL ON UPDATE CURRENT_TIMESTAMP

);

CREATE TABLE registro_pagos (
    id INT AUTO_INCREMENT PRIMARY KEY,

    registro_id INT NOT NULL,

    forma_pago_id INT NULL,

    monto DECIMAL(10,2) NOT NULL,
    qty INT DEFAULT 1,

    concepto VARCHAR(120),
    tipo VARCHAR(120),

    tipo_movimiento ENUM('CARGO','PAGO','AJUSTE') NOT NULL,

    hora_pago DATETIME DEFAULT CURRENT_TIMESTAMP,

    referencia_pago VARCHAR(100),
    banco VARCHAR(80),

    estado VARCHAR(80) DEFAULT 'APLICADO',
    sistema VARCHAR(80),

    usuario_id INT NULL,

    observaciones VARCHAR(200),

    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,

    INDEX idx_registro_id (registro_id),
    INDEX idx_tipo_movimiento (tipo_movimiento),

    CONSTRAINT fk_pagos_registro
        FOREIGN KEY (registro_id) REFERENCES registros(id)
        ON DELETE CASCADE
);

CREATE TABLE  registro_cargos (
    id INT AUTO_INCREMENT PRIMARY KEY,

    registro_id INT NOT NULL,        -- FK al registro principal (estadia)
    
    concepto VARCHAR(120) NOT NULL,  -- HOSPEDAJE, BAR, SERVICIO, etc
    tipo VARCHAR(80),                -- clasificacion libre
    
    cantidad INT DEFAULT 1,
    precio_unitario DECIMAL(10,2) NOT NULL,

    subtotal DECIMAL(10,2) NOT NULL, -- sin impuestos
    iva DECIMAL(10,2) DEFAULT 0.00,
    ish DECIMAL(10,2) DEFAULT 0.00,
    total DECIMAL(10,2) NOT NULL,    -- subtotal + impuestos

    aplica_iva TINYINT DEFAULT 1,
    aplica_ish TINYINT DEFAULT 0,

    estado VARCHAR(50) DEFAULT 'ACTIVO',

    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NULL,

    INDEX idx_registro_id (registro_id),

    CONSTRAINT fk_cargos_registro
        FOREIGN KEY (registro_id) REFERENCES registros(id)
        ON DELETE CASCADE
);

ALTER TABLE registro_cargos 
ADD departamento VARCHAR(50) DEFAULT 'SISTEMA' AFTER concepto;

CREATE TABLE impuestos (
    id INT AUTO_INCREMENT PRIMARY KEY,

    nombre VARCHAR(50) NOT NULL,     -- IVA, ISH
    tasa DECIMAL(5,2) NOT NULL,      -- 16.00, 3.00

    aplica_hospedaje TINYINT DEFAULT 1,
    aplica_servicios TINYINT DEFAULT 0,

    activo TINYINT DEFAULT 1,

    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);


CREATE TABLE registro_cargos_impuestos (
    id INT AUTO_INCREMENT PRIMARY KEY,

    cargo_id INT NOT NULL,
    impuesto_id INT NOT NULL,

    base DECIMAL(10,2) NOT NULL,
    monto DECIMAL(10,2) NOT NULL,

    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_ci_cargo
        FOREIGN KEY (cargo_id) REFERENCES registro_cargos(id)
        ON DELETE CASCADE,

    CONSTRAINT fk_ci_impuesto
        FOREIGN KEY (impuesto_id) REFERENCES impuestos(id)
        ON DELETE CASCADE
);


INSERT INTO impuestos (nombre, tasa, aplica_hospedaje)
VALUES 
('IVA', 16.00, 1),
('ISH', 3.00, 1);


SET FOREIGN_KEY_CHECKS = 0;
TRUNCATE TABLE huespedes;
TRUNCATE TABLE registros;
TRUNCATE TABLE registro_acompanantes; 
TRUNCATE TABLE registro_cargos;
TRUNCATE TABLE registro_pagos;
UPDATE  habitaciones set estado_id=2 where id > 0 ;
TRUNCATE TABLE registro_estacionamiento;
SET FOREIGN_KEY_CHECKS = 1;


INSERT INTO registro_cargos 
(registro_id, concepto, departamento, tipo, cantidad, precio_unitario, subtotal, iva, ish, total, aplica_iva, aplica_ish, estado, created_at)
VALUES

-- 🏨 Hospedaje (3 noches)
(1, 'Hospedaje (3 Noches)', 'SISTEMA', 'HABITACION', 3, 2500, 6302.52, 1008.40, 189.08, 7500.00, 1, 1, 'ACTIVO', '2026-03-21 14:15:00'),

-- 🍽️ Room Service
(1, 'Room Service: Cena Familiar', 'REST', 'SERVICIO', 1, 680, 571.43, 91.43, 17.14, 680.00, 1, 1, 'ACTIVO', '2026-03-21 19:30:00'),

-- 💆 Spa
(1, 'Spa: Masaje Relajante', 'SPA', 'SERVICIO', 1, 1200, 1008.40, 161.34, 30.25, 1200.00, 1, 1, 'ACTIVO', '2026-03-22 16:20:00'),

-- 🧺 Lavandería
(1, 'Lavandería Express', 'AMA', 'SERVICIO', 1, 240, 201.68, 32.27, 6.05, 240.00, 1, 1, 'ACTIVO', '2026-03-23 09:10:00');


INSERT INTO registro_pagos
(registro_id, forma_pago_id, monto, concepto, tipo, tipo_movimiento, hora_pago, estado, sistema, usuario_id, created_at)
VALUES

-- 💵 Depósito inicial
(1, 1, 3000.00, 'Depósito de Garantía', 'HOSPEDAJE', 'PAGO', '2026-03-21 14:20:00', 'APLICADO', 'PMS', 1, '2026-03-21 14:20:00'),

-- 💳 Pago parcial tarjeta
(1, 2, 2500.00, 'Abono Parcial (Tarjeta)', 'HOSPEDAJE', 'PAGO', '2026-03-22 18:10:00', 'APLICADO', 'PMS', 1, '2026-03-22 18:10:00'),

-- 💵 Pago final efectivo
(1, 1, 4120.00, 'Liquidación Final', 'HOSPEDAJE', 'PAGO', '2026-03-23 11:30:00', 'APLICADO', 'PMS', 1, '2026-03-23 11:30:00');

CREATE TABLE registro_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    fecha DATETIME,
    tipo VARCHAR(50),
    id_referencia INT,
    motivo VARCHAR(255)
);



INSERT INTO room_service_productos 
(codigo, nombre, descripcion, categoria, precio, requiere_inventario, icono, activo, created_at)
VALUES

-- 👕 Lavandería
('SRV002', 'Servicio Lavandería', 'Lavado y planchado de prendas', 'SERVICIO', 280.00, 0, 'fa-tshirt', 1, NOW()),

-- 🚗 Parking
('SRV003', 'Estacionamiento', 'Servicio de estacionamiento', 'SERVICIO', 150.00, 0, 'fa-car', 1, NOW()),

-- 💆 Spa
('SRV004', 'Masaje 50 min', 'Masaje relajante de 50 minutos', 'SERVICIO', 1200.00, 0, 'fa-spa', 1, NOW());



INSERT INTO room_service_productos 
(codigo, nombre, descripcion, categoria, precio, requiere_inventario, icono, activo, created_at)
VALUES

-- 💧 MINI BAR
('SRV005', 'Refresco Lata', 'Bebida en lata 355ml', 'SERVICIO', 35.00, 1, 'fa-can-food', 1, NOW()),
('SRV006', 'Cerveza', 'Cerveza nacional', 'SERVICIO', 55.00, 1, 'fa-beer', 1, NOW()),
('SRV007', 'Snack Papas', 'Botana individual', 'SERVICIO', 40.00, 1, 'fa-bag-shopping', 1, NOW()),

-- 🍽️ ROOM SERVICE
('SRV008', 'Desayuno Americano', 'Huevos, café y pan', 'SERVICIO', 180.00, 0, 'fa-utensils', 1, NOW()),
('SRV009', 'Hamburguesa', 'Hamburguesa con papas', 'SERVICIO', 220.00, 0, 'fa-burger', 1, NOW()),
('SRV010', 'Cena Romántica', 'Servicio especial en habitación', 'SERVICIO', 850.00, 0, 'fa-heart', 1, NOW()),

-- 💆 SPA
('SRV011', 'Facial', 'Tratamiento facial', 'SERVICIO', 600.00, 0, 'fa-face-smile', 1, NOW()),
('SRV012', 'Masaje Deportivo', 'Masaje terapéutico', 'SERVICIO', 950.00, 0, 'fa-dumbbell', 1, NOW()),
('SRV013', 'Jacuzzi', 'Uso de jacuzzi privado', 'SERVICIO', 400.00, 0, 'fa-hot-tub-person', 1, NOW()),

-- 🧺 AMA DE LLAVES
('SRV014', 'Limpieza Extra', 'Servicio adicional de limpieza', 'SERVICIO', 150.00, 0, 'fa-broom', 1, NOW()),
('SRV015', 'Toallas Extra', 'Paquete de toallas', 'SERVICIO', 80.00, 0, 'fa-bath', 1, NOW()),
('SRV016', 'Cambio de Sábanas', 'Reemplazo completo', 'SERVICIO', 120.00, 0, 'fa-bed', 1, NOW()),

-- 🎮 RECREACIÓN
('SRV017', 'Renta Bicicleta', 'Uso por 1 hora', 'SERVICIO', 100.00, 0, 'fa-bicycle', 1, NOW()),
('SRV018', 'Tour Local', 'Recorrido turístico', 'SERVICIO', 500.00, 0, 'fa-map', 1, NOW()),
('SRV019', 'Acceso Gym', 'Uso de gimnasio', 'SERVICIO', 90.00, 0, 'fa-dumbbell', 1, NOW()),

-- 🚗 EXTRA
('SRV020', 'Valet Parking', 'Servicio de estacionamiento', 'SERVICIO', 120.00, 0, 'fa-car', 1, NOW()),
('SRV021', 'Transporte Aeropuerto', 'Traslado privado', 'SERVICIO', 600.00, 0, 'fa-shuttle-van', 1, NOW());









-- 🔥 INSERTAR DATA MODO MOTEL
INSERT INTO registros (
habitacion_id, huesped_id, tipo_estadia_id, turno_id, usuario_id,
fecha_estadia, hora_entrada, hora_salida, hora_salida_real,
noches, estado_registro, estado_servicio,
adultos, niños,
forma_pago_id,
precio_base, precio, iva, ish, total,
created_at
)
SELECT 
    h.habitacion_id,
    h.habitacion_id, -- huésped dummy
    1,
    FLOOR(1 + RAND()*3), -- turno 1-3
    FLOOR(1 + RAND()*2), -- usuario 1-2

    DATE(NOW() - INTERVAL FLOOR(RAND()*3) DAY),

    -- 🔥 hora entrada
    DATE_ADD(
        NOW() - INTERVAL FLOOR(RAND()*3) DAY,
        INTERVAL FLOOR(RAND()*20) HOUR
    ),

    -- 🔥 hora salida programada
    DATE_ADD(
        NOW() - INTERVAL FLOOR(RAND()*3) DAY,
        INTERVAL FLOOR(RAND()*20 + 2) HOUR
    ),

    -- 🔥 hora salida real (algunos null = activos)
    IF(RAND() > 0.2,
        DATE_ADD(
            NOW() - INTERVAL FLOOR(RAND()*3) DAY,
            INTERVAL FLOOR(RAND()*20 + 3) HOUR
        ),
        NULL
    ),

    1,

    -- 🔥 estado_registro
    IF(RAND() > 0.2, 'FINALIZADO', 'ACTIVO'),

    -- 🔥 estado_servicio
    IF(RAND() > 0.7, 'LIMPIEZA', 'OK'),

    FLOOR(1 + RAND()*2),
    FLOOR(RAND()*2),

    FLOOR(1 + RAND()*2),

    400 + RAND()*400,
    400 + RAND()*400,
    60 + RAND()*60,
    15 + RAND()*20,
    500 + RAND()*500,

    NOW()

FROM (
    SELECT 
        (1 + FLOOR(RAND()*30)) as habitacion_id
    FROM information_schema.tables
    LIMIT 100
) h;




CREATE OR REPLACE VIEW Reporte_general AS
WITH total_guests AS (
    -- Calcula el total de personas (titular + acompañantes)
    SELECT 
        r.id AS id,
        1 + IFNULL(a.acom, 0) AS Total_huespedes 
    FROM registros r 
    LEFT JOIN (
        SELECT 
            registro_id, 
            COUNT(0) AS acom 
        FROM registro_acompanantes 
        GROUP BY registro_id
    ) a ON a.registro_id = r.id 
   -- WHERE r.estado_servicio = 'ACTIVO'
), 

reservations AS (
    -- Cruza los registros activos con catálogos (estadía, pago, huéspedes)
    SELECT 
        r.*, 
        te.codigo AS Cod_Estadia,
        te.nombre AS Nom_estadia,
        tg.Total_huespedes AS Total_huespedes,
        fp.codigo AS Cod_Forma_pago,
        fp.descripcion AS Forma_pago,
        CONCAT(h.nombre, ' ', h.apellido) AS Nombre_Huesped, 
        h.nombre,
        h.apellido,        
        h.numero_identificacion,
        ti.nombre tipo_identificacion
    FROM registros r 
    LEFT JOIN tipo_estadia     te ON te.id = r.tipo_estadia_id
    LEFT JOIN total_guests     tg ON tg.id = r.id
    LEFT JOIN formas_pago      fp ON fp.id = r.forma_pago_id
    LEFT JOIN huespedes        h  ON h.id  = r.huesped_id 
    LEFT JOIN huesped_identificaciones_tipos ti on h.tipo_identificacion_id=ti.id
   -- WHERE r.estado_servicio = 'ACTIVO'
)

-- Consulta Final: Une las habitaciones con sus reservas actuales (si existen)
SELECT 
    h.id AS id,
    eh.codigo AS cod_eh,
    eh.nombre AS estados_habitacion,
    h.numero AS Numero_Habitacion,
    f.Piso AS piso,
    f.Nombre AS Piso_Descripcion,
    h.tipo_habitacion_id AS tipo_habitacion_id,
    rt.clave AS cod_tip_habitacion,
    rt.nombre AS tip_habitacion,
    re.id id_reservacion,
    re.tipo_estadia_id,
    CASE WHEN re.Cod_Estadia ='SQ' and re.noches > 1 THEN  CONCAT(re.Cod_Estadia,' ',CONVERT(re.noches,CHAR),'d') else re.Cod_Estadia end Cod_Estadia  ,
    re.Nom_estadia,
    re.Total_huespedes,
    re.Cod_Forma_pago,
    re.Forma_pago,
    re.precio_base,
    re.hora_entrada,
    re.hora_salida,
    re.hora_salida_real,
    re.huesped_id,
    re.Nombre_Huesped,
    re.nombre,
    re.apellido,
    re.observaciones,
    re.numero_identificacion,
	re.tipo_identificacion,
    re.turno_id,
    re.precio,
    re.iva,
	re.ish,
    re.precio + re.iva + re.ish as Sub_total,
    
	CONCAT('ADM-', LPAD(re.id, 4, '0')) AS folio,
    DATE_FORMAT(re.hora_entrada, '%d %b %Y') AS admin_fecha_registro
FROM habitaciones h 
LEFT JOIN pisos                f  ON f.id  = h.piso_id
LEFT JOIN habitaciones_tipos   rt ON rt.id = h.tipo_habitacion_id
LEFT JOIN reservations         re ON re.habitacion_id = h.id
LEFT JOIN estados_habitacion   eh ON h.estado_id = eh.id;


CREATE OR REPLACE VIEW vw_reporte_turnos AS
SELECT 
    h.id AS habitacion_id,
    h.numero AS habitacion,

    -- =========================
    -- 🔵 TURNO 1
    -- =========================
    MAX(CASE WHEN r.turno_id = 1 THEN fp.codigo END) AS t1_pago,
    MAX(CASE WHEN r.turno_id = 1 THEN r.precio END) AS t1_precio,
    MAX(CASE WHEN r.turno_id = 1 THEN CONCAT(hs.nombre,' ',hs.apellido) END) AS t1_nombre,

    -- =========================
    -- 🟢 TURNO 2
    -- =========================
    MAX(CASE WHEN r.turno_id = 2 THEN fp.codigo END) AS t2_pago,
    MAX(CASE WHEN r.turno_id = 2 THEN r.precio END) AS t2_precio,
    MAX(CASE WHEN r.turno_id = 2 THEN CONCAT(hs.nombre,' ',hs.apellido) END) AS t2_nombre,

    -- =========================
    -- 🟠 TURNO 3
    -- =========================
    MAX(CASE WHEN r.turno_id = 3 THEN fp.codigo END) AS t3_pago,
    MAX(CASE WHEN r.turno_id = 3 THEN r.precio END) AS t3_precio,
    MAX(CASE WHEN r.turno_id = 3 THEN CONCAT(hs.nombre,' ',hs.apellido) END) AS t3_nombre

FROM habitaciones h

LEFT JOIN registros r 
    ON r.habitacion_id = h.id

LEFT JOIN formas_pago fp 
    ON fp.id = r.forma_pago_id

LEFT JOIN huespedes hs 
    ON hs.id = r.huesped_id

-- 🔥 SOLO EL DÍA ACTUAL (CLAVE)
WHERE DATE(r.hora_entrada) = CURDATE()

GROUP BY h.id, h.numero;

SELECT * FROM vw_reporte_turnos;


create view Reporte_datos as
SELECT 
    h.numero AS HAB,

    CONCAT(
        hu.apellido, ', ', hu.nombre
    ) AS NOMBRE_COMPLETO,

    hu.tipo_identificacion_id AS DOC,

    hu.numero_identificacion AS NUMERO_DOCUMENTO,

    CASE 
        WHEN hu.fotografia IS NOT NULL AND hu.fotografia <> '' 
        THEN '✔' 
        ELSE '⚠ Pend.' 
    END AS FOTO,

    CASE 
        WHEN hu.firma_path IS NOT NULL AND hu.firma_path <> '' 
        THEN '✔ Digital'
        ELSE '⚠ Pend.'
    END AS FIRMA,

    hu.notas AS OBSERVACIONES,

    CASE 
        WHEN hu.activo = 0 THEN '⛔ L.NEGRA'
        WHEN hu.empresa IS NOT NULL AND hu.empresa <> '' THEN '⭐ VIP'
        ELSE ''
    END AS LISTA

FROM registros r
INNER JOIN habitaciones h 
    ON h.id = r.habitacion_id

INNER JOIN huespedes hu 
    ON hu.id = r.huesped_id

ORDER BY h.numero ASC;


CREATE   TABLE registros_fiscal (
    id INT AUTO_INCREMENT PRIMARY KEY,
    registro_id INT,
	id_perfil INT,
    observaciones varchar(250),
    rfc varchar(15),
    precio DECIMAL(10,2),
    iva DECIMAL(10,2),
    ish DECIMAL(10,2),
    total DECIMAL(10,2),
    usuario_id INT,
    fecha DATE,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);




select * from registros_fiscal;


CREATE  TABLE Perfiles_Fiscales (
    id_perfil SERIAL PRIMARY KEY,
    rfc VARCHAR(15) NOT NULL, -- RFC o Tax ID
    razon_social VARCHAR(255) NOT NULL,
    regimen_fiscal VARCHAR(10), -- Ejemplo: 601, 605
    codigo_postal_fiscal VARCHAR(10),
    uso_cfdi VARCHAR(10), -- Ejemplo: G03, P01
    email_facturacion VARCHAR(150),
    es_extranjero BOOLEAN DEFAULT FALSE,
    QR VARCHAR(50),
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


INSERT INTO Perfiles_Fiscales 
(rfc, razon_social, regimen_fiscal, codigo_postal_fiscal, uso_cfdi, email_facturacion, es_extranjero, QR)
VALUES

-- 🔹 Persona Física
('GODE561231GR8', 'JUAN PEREZ LOPEZ', '612', '01000', 'G03', 'juan.perez@mail.com', FALSE, 'QR001'),

('MART850912HDFLNS09', 'MARIA TORRES GOMEZ', '626', '44100', 'P01', 'maria.torres@mail.com', FALSE, 'QR002'),

('LOPR900101HDFRRN04', 'LUIS RODRIGUEZ RAMOS', '612', '64000', 'D01', 'luis.rodriguez@mail.com', FALSE, 'QR003'),

-- 🔹 Persona Moral
('ABC123456T89', 'SERVICIOS HOTELEROS DEL NORTE SA DE CV', '601', '64000', 'G03', 'facturacion@hotelnorte.com', FALSE, 'QR004'),

('DEF987654K21', 'INVERSIONES TURISTICAS DEL PACIFICO SA', '601', '82100', 'G03', 'contabilidad@turismo.com', FALSE, 'QR005'),

('GHI456789L12', 'GRUPO HOSPEDAJE EXPRESS SA DE CV', '603', '06000', 'I01', 'admin@hospedaje.com', FALSE, 'QR006'),

-- 🔹 Extranjero
('XEXX010101000', 'FOREIGN GUEST LLC', '616', '99999', 'S01', 'billing@foreign.com', TRUE, 'QR007'),

('XEXX010101000', 'GLOBAL TRAVEL INC', '616', '99999', 'S01', 'invoice@globaltravel.com', TRUE, 'QR008'),

-- 🔹 Cliente frecuente con CFDI específico
('CARL920303HDFRRL08', 'CARLOS RAMIREZ LOPEZ', '612', '03300', 'G01', 'carlos.ramirez@mail.com', FALSE, 'QR009'),

-- 🔹 Empresa pequeña
('JKL321654M98', 'HOSTAL CENTRO HISTORICO SA DE CV', '601', '06000', 'G03', 'facturacion@hostal.com', FALSE, 'QR010');






-- 🔹 Clientes con perfil fiscal (facturan)
UPDATE registros SET id_perfil = 1 WHERE id IN (1, 5, 10, 15);
UPDATE registros SET id_perfil = 2 WHERE id IN (2, 12, 22);
UPDATE registros SET id_perfil = 3 WHERE id IN (3, 13, 23);

-- 🔹 Empresas (más frecuentes)
UPDATE registros SET id_perfil = 4 WHERE id IN (4, 14, 24, 34, 44);
UPDATE registros SET id_perfil = 5 WHERE id IN (6, 16, 26, 36);
UPDATE registros SET id_perfil = 6 WHERE id IN (7, 17, 27);

-- 🔹 Extranjeros
UPDATE registros SET id_perfil = 7 WHERE id IN (8, 18);
UPDATE registros SET id_perfil = 8 WHERE id IN (9, 19);

-- 🔹 Clientes frecuentes
UPDATE registros SET id_perfil = 9 WHERE id IN (20, 30, 40);
UPDATE registros SET id_perfil = 10 WHERE id IN (25, 35, 45);

-- 🔹 Algunos más aleatorios
UPDATE registros SET id_perfil = 1 WHERE id IN (50, 60);
UPDATE registros SET id_perfil = 4 WHERE id IN (55, 65);
UPDATE registros SET id_perfil = 2 WHERE id IN (70, 80);
UPDATE registros SET id_perfil = 3 WHERE id IN (75, 85);

-- 🔹 El resto queda NULL (no facturan)

CREATE TABLE reporte_auditoria (
    id INT AUTO_INCREMENT PRIMARY KEY,
    reporte_id INT NOT NULL,
    habitacion_id INT NOT NULL,
    usuario VARCHAR(100),
    fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


CREATE TABLE reportes_generados (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(150) NULL,
    fecha DATETIME,
    usuario VARCHAR(100)
);

CREATE TABLE reporte_detalle (
    id INT AUTO_INCREMENT PRIMARY KEY,
    reporte_id INT,
    habitacion_id INT,
    habitacion VARCHAR(10),

    t1_pago VARCHAR(5),
    t1_precio DECIMAL(10,2),
    t1_nombre VARCHAR(241),

    t2_pago VARCHAR(5),
    t2_precio DECIMAL(10,2),
    t2_nombre VARCHAR(241),

    t3_pago VARCHAR(5),
    t3_precio DECIMAL(10,2),
    t3_nombre VARCHAR(241)
);

select * from reportes_generados;
select * from reporte_auditoria;
select * from reporte_detalle;

CREATE TABLE ocr_registros (
    id INT AUTO_INCREMENT PRIMARY KEY,
    imagen VARCHAR(255),
    tipo_documento VARCHAR(50),
    nombre VARCHAR(100),
    apellidos VARCHAR(100),
    numero_id VARCHAR(100),
    fecha_nacimiento DATE,
    genero VARCHAR(20),
    nacionalidad VARCHAR(100),
    es_menor TINYINT(1),
    created_at DATETIME
);


select * from ocr_registros;

SET FOREIGN_KEY_CHECKS = 0;
TRUNCATE TABLE huespedes;
TRUNCATE TABLE registros;
TRUNCATE TABLE registro_acompanantes; 
TRUNCATE TABLE registro_cargos;
TRUNCATE TABLE registro_pagos;
UPDATE  habitaciones set estado_id=2 where id > 0 ;
TRUNCATE TABLE registro_estacionamiento;
SET FOREIGN_KEY_CHECKS = 1;






INSERT INTO registros (
    habitacion_id,
    estado_registro,
    created_at
)
SELECT 
    h.id,
    'DISPONIBLE',
    NOW()
FROM habitaciones h
WHERE h.activa = 1;

CREATE TABLE salidas_clientes (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    registro_id BIGINT UNSIGNED NOT NULL,
    habitacion_id BIGINT UNSIGNED NOT NULL,

    nombre_huesped VARCHAR(255) NULL,

    tipo_salida ENUM('TEMPORAL','DEFINITIVA') NOT NULL,

    motivo VARCHAR(255) NULL,

    fecha_salida DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,

    usuario_id BIGINT UNSIGNED NULL,

    observaciones TEXT NULL,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP

  

) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


CREATE  TABLE registro_movimientos (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    registro_id BIGINT UNSIGNED NOT NULL,
    registro_nuevo_id BIGINT UNSIGNED NULL,

    habitacion_anterior BIGINT UNSIGNED NOT NULL,
    habitacion_nueva BIGINT UNSIGNED NOT NULL,

    tipo_movimiento ENUM('REASIGNACION','CHECKOUT','AJUSTE') 
        NOT NULL DEFAULT 'REASIGNACION',

    motivo VARCHAR(255) NULL,

    usuario_id BIGINT UNSIGNED NULL,

    fecha DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP

   
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



-- ============================================================
-- 1. TABLA: configuracion_turnos
-- Guarda la definición de los turnos operativos del hotel.
-- ============================================================
CREATE TABLE IF NOT EXISTS configuracion_turnos (
    id INTEGER PRIMARY KEY,           -- 1, 2 o 3 (fijo para HotelOS)
    nombre TEXT NOT NULL,             -- Ej: 'Matutino', 'Vespertino', 'Nocturno'
    hora_inicio TEXT NOT NULL,         -- Formato HH:MM (ej: '07:00')
    hora_fin TEXT NOT NULL,           -- Formato HH:MM (ej: '15:00')
    responsable_defecto TEXT          -- Nombre del recepcionista habitual (opcional)
);

-- Insertar la configuración por defecto para HotelOS (3 turnos de 8 horas)
INSERT OR IGNORE INTO configuracion_turnos (id, nombre, hora_inicio, hora_fin) VALUES 
(1, 'Turno 1 - Matutino', '07:00', '15:00'),
(2, 'Turno 2 - Vespertino', '15:00', '23:00'),
(3, 'Turno 3 - Nocturno', '23:00', '07:00');

-- ============================================================
-- 2. TABLA: historial_turnos
-- Registra la actividad real de cada turno operado.
-- ============================================================
CREATE TABLE IF NOT EXISTS historial_turnos (
    id INTEGER PRIMARY KEY AUTOINCREMENT, -- Identificador único de la operación
    configuracion_turno_id INTEGER NOT NULL, -- FK a configuracion_turnos(id)
    fecha_operativa TEXT NOT NULL,        -- Fecha de negocio (YYYY-MM-DD)
    empleado_apertura TEXT NOT NULL,      -- Nombre o PIN del empleado que abre
    empleado_cierre TEXT,                -- Nombre o PIN del empleado que cierra
    hora_apertura TEXT NOT NULL,          -- Timestamp real (ISO8601 YYYY-MM-DD HH:MM:SS)
    hora_cierre TEXT,                    -- Timestamp real (ISO8601)
    
    -- Control de Efectivo (Corte de Caja)
    fondo_fijo_inicial REAL NOT NULL DEFAULT 0.0, -- Dinero base al empezar
    ingresos_efectivo REAL NOT NULL DEFAULT 0.0,   -- Suma de pagos en efectivo
    egresos_efectivo REAL NOT NULL DEFAULT 0.0,    -- Suma de gastos/salidas
    efectivo_calculado REAL NOT NULL DEFAULT 0.0, -- (Fondo + Ingresos - Egresos)
    efectivo_real_entregado REAL,                  -- Lo que el empleado cuenta físicamente
    diferencia_arqueo REAL,                        -- (Real - Calculado)
    
    -- Estado del Turno
    estado TEXT CHECK(estado IN ('ABIERTO', 'CERRADO')) NOT NULL DEFAULT 'ABIERTO',
    notas_cierre TEXT,                             -- Comentarios sobre diferencias o novedades
    
    FOREIGN KEY (configuracion_turno_id) REFERENCES configuracion_turnos(id)
);

-- Crear un índice para búsquedas rápidas de turnos abiertos
CREATE INDEX IF NOT EXISTS idx_turnos_estado ON historial_turnos(estado);