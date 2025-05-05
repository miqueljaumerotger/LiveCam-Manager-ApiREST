CREATE DATABASE fctproject;

use fctproject;

-- Taula de rols sobre els usuaris
CREATE TABLE roles (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    role_name VARCHAR(50) NOT NULL UNIQUE
);

-- Taula fabricant
CREATE TABLE fabricant (
	id INT UNSIGNED NOT NULL PRIMARY KEY,
    fabricant_name VARCHAR(100) NOT NULL
);

-- Taula model_camera
CREATE TABLE model_camera (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    stream1 VARCHAR(100),
    stream2 VARCHAR(100),
    stream3 VARCHAR(100),
    fabricant_id INT UNSIGNED,
    CONSTRAINT fk_model_fabricant FOREIGN KEY (fabricant_id) REFERENCES fabricant(id)
);

-- Taula d'illes per saber on es troben les cameres
CREATE TABLE illes (
	id VARCHAR(3) PRIMARY KEY,
    illa_name VARCHAR(20) NOT NULL
);

-- Taula d'usuaris
CREATE TABLE users (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    email VARCHAR(100),
    role_id INT UNSIGNED,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_user_role FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE SET NULL
);

-- Taula de càmeres
CREATE TABLE cameras (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    cam_ip VARCHAR(45) NOT NULL,
    cam_username VARCHAR(45) NOT NULL,
    cam_password VARCHAR(45) NOT NULL,
    cam_port VARCHAR(15) NOT NULL,
    location TEXT,
    latitude DECIMAL(9,6),
    longitude DECIMAL(9,6),
    status VARCHAR(20) DEFAULT 'activa',
    url_preview VARCHAR(100),
    url_video VARCHAR(100),
    created_by INT UNSIGNED,
    model_id INT UNSIGNED,
    illa_id VARCHAR(3),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_created_by FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL,
    CONSTRAINT fk_camera_model FOREIGN KEY (model_id) REFERENCES model_camera(id),
    CONSTRAINT fk_camera_illa FOREIGN KEY (illa_id) REFERENCES illes(id)
);

-- Taula de logs d'accions sobre càmeres
CREATE TABLE camera_logs (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED,
    camera_id INT UNSIGNED NOT NULL,
    action ENUM('insert', 'update', 'delete') NOT NULL,
    changes JSON,
    timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_camera FOREIGN KEY (camera_id) REFERENCES cameras(id) ON DELETE CASCADE,
    CONSTRAINT fk_user_log FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);

-- Taula de permisos segons el rol
CREATE TABLE permissions (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    role_id INT UNSIGNED NOT NULL,
    permission_name VARCHAR(100) NOT NULL,
    CONSTRAINT fk_permission_role FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE CASCADE
);

-- Taula documentacio_camera sobre el model_camera
CREATE TABLE documentacio_camera (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    model_id INT UNSIGNED NOT NULL,
    document_url TEXT,
    notes TEXT,
    doc_type VARCHAR(50), -- Nou camp per indicar el tipus de document (pdf, txt, etc.)
    CONSTRAINT fk_doc_model FOREIGN KEY (model_id) REFERENCES model_camera(id) ON DELETE CASCADE
);

