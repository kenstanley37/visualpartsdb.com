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

CREATE TABLE role
(
	role_id	    		INT       		PRIMARY KEY   	AUTO_INCREMENT,
	role_name 			VARCHAR(50)		NOT NULL,
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

CREATE TABLE parts
(
	sku							VARCHAR(100)	PRIMARY KEY,
	sku_desc					VARCHAR(100),
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
	sku_pallet_case_qty			INT,

);

INSERT INTO role VALUES 
(NULL,'USER','General User - Can request data update'),
(NULL,'ADMIN','Admin can add or update all information in database');

INSERT INTO user VALUES
(NULL, 'Kenneth', 'Stanley', 'ken@stanleysoft.org', 1, 'regcode', DEFAULT, '$2y$10$kbrE1OB0WZnWiraN0iAJluIuImTRcRVjRk5cuNAJ1AkRocP6dDGh6', 2),
(NULL, 'John', 'Doe', 'user@visualpartsdb.com', 1, 'regcode', DEFAULT, '$2y$10$kbrE1OB0WZnWiraN0iAJluIuImTRcRVjRk5cuNAJ1AkRocP6dDGh6', 1),
(NULL, 'Jane', 'Doe', 'admin@visualpartsdb.com', 1, 'regcode', DEFAULT, '$2y$10$kbrE1OB0WZnWiraN0iAJluIuImTRcRVjRk5cuNAJ1AkRocP6dDGh6', 2);


CREATE INDEX emailSearch on user(user_email);


SET FOREIGN_KEY_CHECKS = 1;