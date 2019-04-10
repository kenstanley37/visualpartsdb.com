<?php
/**
* Author - Ken Stanley
* File Name - logout.php
* Revision Date - April, 10 2019
*/
session_start();
include("inc/inc.path.php");
require_once($path."class/class.user.php");

$user = new USER;

$user->doLogout();

?>