CREATE TABLE produto (
    id      INT             NOT NULL AUTO_INCREMENT,
    nome    VARCHAR(100)    NOT NULL,
    preco   DECIMAL(10, 2),
    estoque INT             DEFAULT 0,

    CONSTRAINT `pk_produto`
        PRIMARY KEY ( id )
) ENGINE=InnoDB;