CREATE DATABASE IF NOT EXISTS db_chat; 

USE db_chat; 

-- Guarda las cuentas y los datos necesarios para iniciar sesión.

CREATE TABLE tbl_usuario ( 
  id_usuario INT NOT NULL AUTO_INCREMENT, 
  username VARCHAR(45) NOT NULL UNIQUE, 
  nombre_real VARCHAR(100) NOT NULL, 
  contrasena VARCHAR(255) NOT NULL, 
  rol VARCHAR(50) NOT NULL,
  PRIMARY KEY (id_usuario) 
) ENGINE=InnoDB; 

-- Registra quién envía cada solicitud de amistad y su estado.
CREATE TABLE tbl_soli_amistad ( 
  id_soli_amistad INT NOT NULL AUTO_INCREMENT, 
  id_emisor INT NOT NULL, 
  id_receptor INT NOT NULL, 
  estado ENUM('pendiente', 'aceptada', 'rechazada') NOT NULL DEFAULT 'pendiente', 
  PRIMARY KEY (id_soli_amistad), 
  UNIQUE KEY uq_solicitud (id_emisor, id_receptor) 
) ENGINE=InnoDB;   

-- Almacena los mensajes, participantes, tipo de contenido y fecha de envío.
CREATE TABLE tbl_chats ( 
  id_chats INT NOT NULL AUTO_INCREMENT, 
  id_emisor INT NOT NULL, 
  id_receptor INT NOT NULL, 
  mensaje VARCHAR(250) NULL, 
  tipo_contenido ENUM('texto', 'imagen', 'archivo') NOT NULL DEFAULT 'texto', 
  archivo_url VARCHAR(255) NULL, 
  fecha_envio DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP, 
  PRIMARY KEY (id_chats) 
) ENGINE=InnoDB; 

-- Evita solicitudes o mensajes asociados a usuarios que no existen.

ALTER TABLE tbl_soli_amistad
  ADD CONSTRAINT fk_soli_emisor FOREIGN KEY (id_emisor) REFERENCES tbl_usuario (id_usuario),
  ADD CONSTRAINT fk_soli_receptor FOREIGN KEY (id_receptor) REFERENCES tbl_usuario (id_usuario);

ALTER TABLE tbl_chats
  ADD CONSTRAINT fk_chat_emisor FOREIGN KEY (id_emisor) REFERENCES tbl_usuario (id_usuario),
  ADD CONSTRAINT fk_chat_receptor FOREIGN KEY (id_receptor) REFERENCES tbl_usuario (id_usuario);