DROP DATABASE IF EXISTS segundamano;
CREATE DATABASE segundamano DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE segundamano;

CREATE TABLE users(
	id INT NOT NULL PRIMARY KEY auto_increment,
	displayname VARCHAR(32) NOT NULL,
	email VARCHAR(128) NOT NULL UNIQUE KEY,
	phone VARCHAR(32) NOT NULL UNIQUE KEY,
	password VARCHAR(32) NOT NULL,
	roles JSON NULL, -- NOT NULL DEFAULT '["ROLE_USER"]',
	picture VARCHAR(256) DEFAULT NULL,
	poblacion VARCHAR(30) DEFAULT NULL,
	cp VARCHAR(5) DEFAULT NULL,
	blocked_at TIMESTAMP NULL,
	created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	updated_at TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
);

INSERT INTO users(displayname, email, phone, password, roles, picture, poblacion, cp) VALUES 
	('admin', 'admin@fastlight.com', '666666666', md5('1234'), '["ROLE_ADMIN"]', null, 'Cáceres', '10748'),
	('user1', 'user1@fastlight.com', '666666664', md5('1234'), '["ROLE_USER"]', null, 'Sevilla', '41701'),
	('user2', 'user2@fastlight.com', '666006664', md5('1234'), '["ROLE_USER"]', null, 'Barcelona', '08040'),
	('user3', 'user3@fastlight.com', '606612364', md5('1234'), '["ROLE_USER"]', null, 'Girona', '17005')
;


CREATE TABLE errors(
	id INT NOT NULL PRIMARY KEY auto_increment,
	date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	level VARCHAR(32) NOT NULL DEFAULT 'ERROR',
	url VARCHAR(256) NOT NULL,
	message VARCHAR(512) NOT NULL,
	user VARCHAR(128) DEFAULT NULL,
	ip CHAR(15) NOT NULL
);


CREATE TABLE anuncios(
	id INT NOT NULL PRIMARY KEY auto_increment,
	iduser INT NOT NULL,
	titulo VARCHAR(40) NOT NULL,
	descripcion VARCHAR(100) NOT NULL,
	precio FLOAT NOT NULL,
	imagen VARCHAR(256) NULL	
);

INSERT INTO anuncios(iduser, titulo, descripcion, precio, imagen) VALUES 
	(2, 'Venta de cd vírgenes', 'cien unidades de cd vírgenes', 25.5, null),
	(2, 'Cartuchos', 'Cartuchos de Mega Drive', 30, null),
	(3, 'Cómic', 'Cómic de Superman', 100, null),
	(2, 'Snowboard', 'Tabla de snowboar descolorida poco uso ', 150, null)
;
