<?php
/**
* VIEW for resetting password
*
* @author Ken Stanley <ken@stanleysoft.org>
* @license MIT
*/
session_start();
include("inc/inc.path.php");
require_once($path."class/class.user.php");
require_once($path."class/class.visualdb.php");
require_once($path."class/class.func.php");

$vpd = new VISUALDB;
$vail = new VALIDATE;
$user = new USER;
$error='';

$date = date("Y-m-d");
$dateStart = strtotime('-1 day', strtotime($date));
$dateStart = date("Y-m-d", $dateStart);
$dateEnd = date("Y-m-d");
$userID = 2;

$resultold = $vpd->searchHistToJson($dateStart, $dateEnd, $userID);

print_r($resultold);
var_dump(json_decode($resultold));

echo json_last_error();

var_dump(json_decode( preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $resultold), true ));

?>
