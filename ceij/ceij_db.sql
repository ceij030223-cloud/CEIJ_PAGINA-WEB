-- Crear base de datos
CREATE DATABASE IF NOT EXISTS ceij_db;
USE ceij_db;

CREATE TABLE usuarios (
  id INT(11) NOT NULL AUTO_INCREMENT,
  nombre VARCHAR(100) NOT NULL,
  apellido VARCHAR(100) NOT NULL,
  email VARCHAR(150) NOT NULL,
  telefono CHAR(10) DEFAULT NULL,
  password VARCHAR(255) NOT NULL,
  token_recuperacion VARCHAR(100) DEFAULT NULL,
  token_expira DATETIME DEFAULT NULL,
  rol ENUM('usuario', 'administrador') NOT NULL DEFAULT 'usuario',
  token_verificacion CHAR(32) DEFAULT NULL,
  token_expira_verificacion DATETIME DEFAULT NULL,
  activo TINYINT(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Tabla Carrusel (Flyers)
CREATE TABLE carrusel (
    id INT AUTO_INCREMENT PRIMARY KEY,
    imagen VARCHAR(150) NOT NULL, -- ruta o nombre del archivo
    fecha_subida TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

--Tabla Cursos
CREATE TABLE cursos (
  id INT(11) NOT NULL AUTO_INCREMENT,
  titulo VARCHAR(100) NOT NULL,
  descripcion VARCHAR(500) NOT NULL,
  imagen VARCHAR(150) NOT NULL,
  duracion CHAR(50) NOT NULL,
  alumnos INT(11) NOT NULL,
  fecha_inicio DATE NOT NULL,
  fecha_fin DATE NOT NULL,
  horario CHAR(50) NOT NULL,
  dias CHAR(50) NOT NULL,
  modalidad ENUM('Presencial', 'En línea', 'Semipresencial') NOT NULL DEFAULT 'Presencial',
  sucursal CHAR(50) NOT NULL,
  costo_total DECIMAL(10,2) NOT NULL,
  costo_inscripcion DECIMAL(10,2) NOT NULL,
  costo_sesion DECIMAL(10,2) NOT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Tabla Áreas de práctica
CREATE TABLE tarjetas (
    id_tarjeta INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(100) NOT NULL,
    descripcion TEXT NOT NULL,
    imagen VARCHAR(150) NOT NULL,
    id_seccion INT NULL,
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_seccion) REFERENCES secciones_galeria(id_seccion) ON DELETE SET NULL
);

-- Tabla Secciones de la galeria
CREATE TABLE secciones_galeria (
  id_seccion INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(100) NOT NULL
);

--Tabla Imagenes/videos de la galeria 
CREATE TABLE imagenes_galeria (
  id_imagen INT AUTO_INCREMENT PRIMARY KEY,
  id_seccion INT NOT NULL,
  ruta VARCHAR(150) NOT NULL,
  tipo ENUM('imagen', 'video') NOT NULL DEFAULT 'imagen',
  FOREIGN KEY (id_seccion) REFERENCES secciones_galeria(id_seccion) ON DELETE CASCADE
);













