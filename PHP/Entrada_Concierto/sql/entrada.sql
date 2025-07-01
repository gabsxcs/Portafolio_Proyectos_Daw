/*UN script de como tengo organizada mis base de datos para que sea mas claro entender como he organizado mi bbdd y las clases*/

CREATE TABLE tbl_evento (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(255) NOT NULL,
    fecha DATE NOT NULL,
    hora TIME NOT NULL,
    lugar VARCHAR(255) NOT NULL,
    direccion VARCHAR(255) NOT NULL,
    descripcion TEXT,
    imagen VARCHAR(255),
    artista VARCHAR(255),
    INDEX idx_evento_nombre (nombre),
    INDEX idx_evento_fecha (fecha),
    INDEX idx_evento_artista (artista),
    INDEX idx_evento_lugar (lugar)
);


CREATE TABLE tbl_categoria (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL,
    descripcion TEXT,
    valor DECIMAL(10,2) NOT NULL,
    evento_id INT NOT NULL,
    INDEX idx_categoria_nombre (nombre),
    INDEX idx_categoria_evento (evento_id),
    FOREIGN KEY (evento_id) REFERENCES tbl_evento(id)
);


CREATE TABLE tbl_entrada (
    id INT AUTO_INCREMENT PRIMARY KEY,
    codigoReferencia INT(100) NOT NULL UNIQUE,
    evento_id INT NOT NULL,
    seccion VARCHAR(50) NOT NULL,
    fila INT NOT NULL,
    asiento_numero INT NOT NULL,
    categoria_id INT NOT NULL,
    estado ENUM('activa', 'disponible', 'anulada') NOT NULL,
    INDEX idx_entrada_evento (evento_id),
    INDEX idx_entrada_categoria (categoria_id),
    INDEX idx_entrada_estado (estado),
    FOREIGN KEY (evento_id) REFERENCES tbl_evento(id),
    FOREIGN KEY (categoria_id) REFERENCES tbl_categoria(id)
);


CREATE TABLE tbl_compra (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombreComprador VARCHAR(100) NOT NULL,
    emailComprador VARCHAR(100) NOT NULL,
    numeroTarjeta VARCHAR(16) NOT NULL,
    fechaCompra DATETIME NOT NULL,
    totalCompra DECIMAL(10,2) NOT NULL,
    INDEX idx_compra_fecha (fecha_compra),
    INDEX idx_compra_email (email_comprador)
);

CREATE TABLE compra_entrada (
    compra_id INT NOT NULL,
    entrada_id INT NOT NULL,
    PRIMARY KEY (compra_id, entrada_id),
    FOREIGN KEY (compra_id) REFERENCES tbl_compra(id),
    FOREIGN KEY (entrada_id) REFERENCES tbl_entrada(id)
);