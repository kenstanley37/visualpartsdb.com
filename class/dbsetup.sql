-- *************************************************************
-- This script only creates the stanley14_vpd database
-- *************************************************************

-- create the database
-- DROP DATABASE IF EXISTS stanley14_vpd;
-- CREATE DATABASE stanle14_vpd;

-- select the database
-- USE stanley14_vpd;

SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS user;
DROP TABLE IF EXISTS role;
DROP TABLE IF EXISTS sku;
DROP TABLE IF EXISTS sku_image;

CREATE TABLE role
(
	role_id	    		INT       			PRIMARY KEY   	AUTO_INCREMENT,
	role_name 			VARCHAR(50)			NOT NULL,
	role_description	VARCHAR(150)		NOT NULL
);

CREATE TABLE user
(
	user_id      		INT							PRIMARY KEY	AUTO_INCREMENT,
	user_fName   		VARCHAR(50)	  	NOT NULL,
	user_lName   		VARCHAR(50)	  	NOT NULL,
	user_email	 		VARCHAR(100)	NOT NULL	UNIQUE,
	user_active       	TINYINT(1)		NOT NULL,
	user_reg_code		VARCHAR(150),			
	user_reg_date		DATETIME		NOT NULL 	DEFAULT CURRENT_TIMESTAMP,
	user_password		VARCHAR(200)	NOT NULL,
	user_role_id		INT			    NOT NULL,

CONSTRAINT user_role_id_fk_role_id
	FOREIGN KEY (user_role_id)
	REFERENCES role(role_id)
);

CREATE TABLE sku
(
	sku_id						VARCHAR(100)	PRIMARY KEY,
	sku_desc					VARCHAR(100),
	sku_supplier				VARCHAR(100),
	sku_active					TINYINT(1),
	sku_sig_length				INT,
	sku_sig_width				INT,
	sku_sig_height				INT,
	sku_sig_weight				INT,
	sku_case_qty				INT,
	sku_case_length				INT,
	sku_case_width				INT,
	sku_case_height				INT,
	sku_case_weight				INT,
	sku_pallet_length			INT,
	sku_pallet_width			INT,
	sku_pallet_height			INT,
	sku_pallet_weight			INT,
	sku_pallet_case_qty			INT
	sku_rec_date				DATETIME,
	sku_rec_added				VARCHAR(100),
	sku_rec_update				DATETIME,
	sku_rec_update_by			VARCHAR(100)
);

CREATE TABLE sku_image
(
	sku_image_id				INT				PRIMARY KEY 	AUTO_INCREMENT,
	sku_image_sku_id			VARCHAR(100) 	NOT NULL,
	sku_image_file_name			VARCHAR(100)	NOT NULL,
	sku_image_url				VARCHAR(150)	NOT NULL,
	sku_image_description		VARCHAR(100)	NOT NULL,
	sku_image_width				INT				NOT NULL,
	sku_image_height			INT				NOT NULL,
	sku_image_feature			TINYINT(1),

CONSTRAINT sku_image_sku_id_fk_sku_id
	FOREIGN KEY (sku_image_sku_id)
	REFERENCES sku(sku_id)
);


INSERT INTO role VALUES 
(NULL,'USER','General User - Can request data update'),
(NULL,'ADMIN','Admin can add or update all information in database');

INSERT INTO user VALUES
(NULL, 'Kenneth', 'Stanley', 'ken@stanleysoft.org', 1, 'regcode', DEFAULT, '$2y$10$kbrE1OB0WZnWiraN0iAJluIuImTRcRVjRk5cuNAJ1AkRocP6dDGh6', 2),
(NULL, 'John', 'Doe', 'user@visualpartsdb.com', 1, 'regcode', DEFAULT, '$2y$10$kbrE1OB0WZnWiraN0iAJluIuImTRcRVjRk5cuNAJ1AkRocP6dDGh6', 1),
(NULL, 'Jane', 'Doe', 'admin@visualpartsdb.com', 1, 'regcode', DEFAULT, '$2y$10$kbrE1OB0WZnWiraN0iAJluIuImTRcRVjRk5cuNAJ1AkRocP6dDGh6', 2);

INSERT INTO sku VALUES
('9911', 'Replacement For Electrolux 9911 Refrigerator Water Filters', 'KX', 1, 10, 10, 10, 2, 6),
('ULTRAWF', 'Replacement For Electrolux ULTRAWF Refrigerator Water Filters', 'KX', 1, 10, 10, 10, 2, 6),
('WF3CB', 'Replacement For Electrolux WF3CB1 Refrigerator Water Filters', 'KX', 1, 10, 10, 10, 2, 6),
('WF2CB', 'Replacement For Electrolux WF2CB Refrigerator Water Filters', 'KX', 1, 10, 10, 10, 2, 6);

INSERT INTO sku_image VALUES
(NULL, 1, '9911-1.jpg', '/assets/images/9911/9911-1.jpg', 'FRONT', 10, 2, 1),
(NULL, 2, 'ULTRAWF-1.jpg', '/assets/images/ULTRAWF/ULTRAWF-1.jpg', 'FRONT', 10, 2, 1),
(NULL, 3, 'WF3CB-1.jpg', '/assets/images/WF3CB/WF3CB-1.jpg', 'FRONT', 10, 2, 1),
(NULL, 4, 'WF2CB-1.jpg', '/assets/images/WF2CB/WF2CB-1.jpg', 'FRONT', 10, 2, 1);

CREATE INDEX emailSearch on user(user_email);


SET FOREIGN_KEY_CHECKS = 1;