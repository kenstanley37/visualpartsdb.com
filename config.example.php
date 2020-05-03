<?php

$CONFIG = [

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
'dbhost' => '',

/**
 * Define the database name
 * The name of the  database which is set during installation.
 * You should not need to change this.
 */
'dbname' => '',

/**
 * Define the  database user
 * This must be unique across  instances using the same SQL database.
 * This is setup during installation, so you shouldn't need to change it.
 */
'dbuser' => '',

/**
 * Define the password for the database user
 * This is set up during installation, so you shouldn't need to change it.
 */
'dbpassword' => '',

/**
 * Define the prefix for the  tables in the database
 */
'dbtableprefix' => '',


];


