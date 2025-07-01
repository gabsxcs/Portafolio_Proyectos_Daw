CREATE DATABASE IF NOT EXISTS frases_Sandoval_Gabriela;
USE frases_Sandoval_Gabriela;

CREATE TABLE IF NOT EXISTS tbl_autores (
    id_autor INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(1000) NOT NULL,
    descripcion TEXT,
    url VARCHAR(255)
);

CREATE TABLE IF NOT EXISTS tbl_temas (
    id_tema INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(255) NOT NULL
);

CREATE TABLE IF NOT EXISTS tbl_frases (
    id_frase INT AUTO_INCREMENT PRIMARY KEY,
    texto TEXT NOT NULL,
    id_autor INT,
    FOREIGN KEY (id_autor) REFERENCES tbl_autores(id_autor) ON DELETE CASCADE
);


CREATE TABLE IF NOT EXISTS tbl_frases_temas (
    id_frase INT,
    id_tema INT,
    PRIMARY KEY (id_frase, id_tema),
    FOREIGN KEY (id_frase) REFERENCES tbl_frases(id_frase) ON DELETE CASCADE,
    FOREIGN KEY (id_tema) REFERENCES tbl_temas(id_tema)
);

CREATE USER 'usr_generic'@'localhost' IDENTIFIED BY '2025@Thos';
GRANT ALL PRIVILEGES ON frases_Sandoval_Gabriela.* TO 'usr_generic'@'localhost';


