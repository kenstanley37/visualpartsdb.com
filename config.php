<?php

return [

/**
 * Identify the database used with this installation
 * See also config option `supportedDatabases`
 *
 * Available:
 * 	- sqlite (SQLite3 - Not in Enterprise Edition)
 * 	- mysql (MySQL/MariaDB)
 * 	- pgsql (PostgreSQL)
 * 	- oci (Oracle - Enterprise Edition Only)
 */
'dbtype' => 'mysql',

/**
 * Define the database server host name
 * For example `localhost`, `hostname`, `hostname.example.com`, or the IP address.
 * To specify a port use: `hostname:####`;
 * To specify a Unix socket use: `localhost:/path/to/socket`.
 */
'dbhost' => 'localhost',

/**
 * Define the database name
 * The name of the  database which is set during installation.
 * You should not need to change this.
 */
'dbname' => 'vpd',

/**
 * Define the  database user
 * This must be unique across  instances using the same SQL database.
 * This is setup during installation, so you shouldn't need to change it.
 */
'dbuser' => 'vpd',

/**
 * Define the password for the database user
 * This is set up during installation, so you shouldn't need to change it.
 */
'dbpassword' => 'vpd1234',

/**
 *Google reCAPTCHA v3 Keys
 */
'SITE_KEY' => '6LeumfEUAAAAAOVkVC4k-UfG4Hec8r88Y3LVJ6oZ',
'SERVER_KEY' => '6LeumfEUAAAAAISXk2XJKqQ3FGPOtJU5sqmQBj6e',

/**
 * PHPMailer Email Settings
 */

];


