CREATE DATABASE myweb;
USE myweb;

CREATE TABLE usuario (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    apellidos VARCHAR(100) NOT NULL,
    fechaNacimiento DATE NOT NULL,
    genero ENUM('Masculino', 'Femenino', 'Otro') NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    contrasena VARCHAR(255) NOT NULL,
    tipoDocumento ENUM('dni', 'nie', 'pasaporte') NOT NULL,
    numeroDocumento VARCHAR(50) NOT NULL,
    fechaRegistro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    imagen VARCHAR(255) DEFAULT NULL,
    estatus INT NOT NULL DEFAULT 0 COMMENT '0: pendiente, 1: confirmado, 2: administradores',
    direccion VARCHAR(255) DEFAULT NULL,
    codigoPostal VARCHAR(10) DEFAULT NULL,
    provincia VARCHAR(100) DEFAULT NULL,
    telefono VARCHAR(15) DEFAULT NULL
);


CREATE USER 'usr_super'@'localhost' IDENTIFIED BY 'Super@2025';
GRANT SELECT, UPDATE ON myweb.* TO 'usr_super'@'localhost';

FLUSH PRIVILEGES;


/*Creacion Usuarios*/
CREATE USER 'usr_consulta'@'localhost' IDENTIFIED BY '2025@Thos';
GRANT SELECT ON myweb.* TO 'usr_consulta'@'localhost';

CREATE USER 'usr_generic'@'localhost' IDENTIFIED BY '2025@Thos';
GRANT All privileges on myweb.* TO 'usr_generic'@'localhost';

/*Comprobar los permisos que tiene cada usuario*/
SHOW GRANTS FOR 'usr_consulta'@'localhost';
SHOW GRANTS FOR 'usr_super'@'localhost';

CREATE USER 'usr_super'@'localhost' IDENTIFIED BY 'Super@2025';
GRANT ALL PRIVILEGES ON *.* TO 'usr_super'@'localhost' WITH GRANT OPTION;
FLUSH PRIVILEGES;

CREATE USER 'usrSuper'@'localhost' IDENTIFIED BY 'Super@2025!';
GRANT ALL PRIVILEGES ON myweb.* TO 'usrSuper'@'localhost' WITH GRANT OPTION;
FLUSH PRIVILEGES;


CREATE TABLE Agenda (
    id INT AUTO_INCREMENT PRIMARY KEY,  
    titulo VARCHAR(255) NOT NULL,        
    descripcion TEXT,                    
    fechaHoraInicio DATETIME NOT NULL,   
    fechaHoraFin DATETIME NOT NULL,      
    etiqueta ENUM('Reunion', 'Examen', 'Taller', 'Social', 'Cumpleaños', 'Otros') NULL
);


