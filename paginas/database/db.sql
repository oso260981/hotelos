

CREATE TABLE usuarios (
id INT AUTO_INCREMENT PRIMARY KEY,
username VARCHAR(50),
password VARCHAR(255),
rol VARCHAR(20),
activo TINYINT DEFAULT 1
);
INSERT INTO usuarios(username,password,rol)
VALUES('admin','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9M6qJ3oCq3c0T8sFh5H1nK','admin');


CREATE TABLE pisos(
 id INT AUTO_INCREMENT PRIMARY KEY,
 nombre VARCHAR(20),
 activo TINYINT DEFAULT 1
);

CREATE TABLE habitaciones(
 id INT AUTO_INCREMENT PRIMARY KEY,
 numero VARCHAR(10),
 piso_id INT,
 status VARCHAR(5) DEFAULT 'X',
 activa TINYINT DEFAULT 1
);

ALTER TABLE habitaciones 
ADD precio_base DECIMAL(10,2) NULL,
ADD ocupada TINYINT DEFAULT 0;

INSERT INTO pisos(nombre) VALUES ('PB'),('1'),('2'),('3');

INSERT INTO habitaciones(numero,piso_id,status) VALUES
('101',1,'X'),
('102',1,'S'),
('103',1,'M'),
('104',1,'P'),
('105',1,'X'),
('201',2,'X'),
('202',2,'S'),
('203',2,'X');


CREATE TABLE registros(
id INT AUTO_INCREMENT PRIMARY KEY,
habitacion_id INT,
nombre VARCHAR(150),
personas INT,
forma_pago VARCHAR(20),
precio DECIMAL(10,2),
iva DECIMAL(10,2),
ish DECIMAL(10,2),
total DECIMAL(10,2),
hora_entrada DATETIME,
hora_salida DATETIME,
status VARCHAR(20) DEFAULT 'ACTIVO'
);