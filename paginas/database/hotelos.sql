CREATE DATABASE hotelos;
USE hotelos;
CREATE TABLE usuarios (
id INT AUTO_INCREMENT PRIMARY KEY,
username VARCHAR(50),
password VARCHAR(255),
rol VARCHAR(20),
activo TINYINT DEFAULT 1
);
INSERT INTO usuarios(username,password,rol)
VALUES('admin','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9M6qJ3oCq3c0T8sFh5H1nK','admin');