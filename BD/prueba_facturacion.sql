CREATE TABLE IF NOT EXISTS Personas(
    id            BIGINT PRIMARY KEY AUTO_INCREMENT,
    cedula        VARCHAR(20) NOT NULL,
    nombre        VARCHAR(255) NOT NULL,
    email         VARCHAR(255) NOT NULL,
    creado        timestamp   DEFAULT CURRENT_TIMESTAMP(),
    modificado    timestamp   DEFAULT CURRENT_TIMESTAMP() ON UPDATE CURRENT_TIMESTAMP()
)ENGINE = InnoDB
  DEFAULT CHARSET = utf8;

CREATE TABLE IF NOT EXISTS Usuarios
(
    id            BIGINT AUTO_INCREMENT PRIMARY KEY,
    persona       BIGINT       NULL,
    nick          VARCHAR(250) NULL,
    pass          VARCHAR(250) NULL,
    token         VARCHAR(250) NULL,
    ultimo_acceso DATETIME     NULL,
    estado        VARCHAR(20) DEFAULT 'ACTIVO',
    creado        timestamp   DEFAULT CURRENT_TIMESTAMP(),
    modificado    timestamp   DEFAULT CURRENT_TIMESTAMP() ON UPDATE CURRENT_TIMESTAMP(),
    FOREIGN KEY (persona) REFERENCES Personas (id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8;

CREATE TABLE IF NOT EXISTS Invoices (
    id            BIGINT PRIMARY KEY AUTO_INCREMENT,
    persona_id    BIGINT NOT NULL,
    issue_date    DATE NOT NULL,
    due_date      DATE NOT NULL,
    invoice_type  ENUM('Contado', 'Credito') NOT NULL,
    subtotal      DECIMAL(10,2) NOT NULL,
    tax_total     DECIMAL(10,2) NOT NULL,
    total         DECIMAL(10,2) NOT NULL,
    user_id       BIGINT NOT NULL,
    creado        timestamp   DEFAULT CURRENT_TIMESTAMP(),
    modificado    timestamp   DEFAULT CURRENT_TIMESTAMP() ON UPDATE CURRENT_TIMESTAMP(),
    FOREIGN KEY (user_id) REFERENCES Usuarios (id) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (persona_id) REFERENCES Personas (id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE = InnoDB
  DEFAULT CHARSET = utf8;

CREATE TABLE IF NOT EXISTS Invoice_details (
    id            BIGINT PRIMARY KEY AUTO_INCREMENT,
    invoice_id    BIGINT NOT NULL,
    item_code     VARCHAR(50) NOT NULL,
    item_name     VARCHAR(255) NOT NULL,
    unit_price    DECIMAL(10,2) NOT NULL,
    quantity      INT NOT NULL,
    applies_tax   BOOLEAN DEFAULT FALSE,
    tax_amount    DECIMAL(10,2) NOT NULL,
    subtotal      DECIMAL(10,2) NOT NULL,
    total         DECIMAL(10,2) NOT NULL,
    creado        timestamp   DEFAULT CURRENT_TIMESTAMP(),
    modificado    timestamp   DEFAULT CURRENT_TIMESTAMP() ON UPDATE CURRENT_TIMESTAMP(),
    FOREIGN KEY   (invoice_id) REFERENCES Invoices(id) ON DELETE CASCADE
)ENGINE = InnoDB
  DEFAULT CHARSET = utf8;