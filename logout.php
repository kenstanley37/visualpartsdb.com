<?php
session_start();
include("inc/inc.path.php");
require_once($path."class/class.user.php");

$user = new USER;

$user->doLogout();

?>