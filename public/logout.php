<?php
/**
* Logout page
*
* @author Ken Stanley <ken@stanleysoft.org>
* @license MIT
*/
session_start();
include("inc/inc.path.php");
require_once($path.'/vendor/autoload.php');
use user\user;

$user = new USER;

$user->doLogout();

?>