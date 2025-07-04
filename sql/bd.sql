CREATE DATABASE plataforma;

USE plataforma;

CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100),
    correo VARCHAR(100) UNIQUE,
    clave VARCHAR(255),
    rol ENUM('trabajador','admin') DEFAULT 'trabajador'
);

CREATE TABLE archivos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT,
    nombre_original VARCHAR(255),
    ruta_destino VARCHAR(255),
    estado ENUM('pendiente','aprobado','rechazado') DEFAULT 'pendiente',
    fecha_subida TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id)
);