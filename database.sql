CREATE DATABASE belajar_laravel_database;

CREATE TABLE categories (
    id VARCHAR(100) NOT NULL PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description text,
    created_at TIMESTAMP ,
) ENGINE=InnoDB;


CREATE TABLE counter (
    id INT  NOT NULL  PRIMARY KEY AUTO_INCREMENT,
    counter int NOT NULL DEFAULT 0
) ENGINE=InnoDB

INSERT INTO counter (counter ) VALUES(
     1
);

CREATE TABLE products (
    id  VARCHAR(100) NOT NULL PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description text NULL,
    price int NOT NULL,
    category_id VARCHAR(100) NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_category_id FOREIGN KEY (category_id) REFERENCES categories (id)
)ENGINE=InnoDB
