<?php
/**
* Author - Ken Stanley
* File Name - ajax.php
* Revision Date - April, 10 2019
*/
    session_start();
    include("../inc/inc.path.php");
    require($path.'class/class.visualdb.php');
    require_once($path."class/class.func.php");
    require_once($path."class/class.user.php");

    $vpd = new VISUALDB;
    $vail = new VALIDATE;
    $user = new USER;
    
/*
*   This is used for ajax pull request to display charts in C3
*/
    if(isset($_POST['MySearchCharts'])){ 
        $dfrom = $_POST['dfrom'];
        $dto = $_POST['dto'];
        $userID = $_POST['userID'];
        $result = $vpd->searchHistToJson($dfrom, $dto, $userID);
        return $result; 
    }

    if(isset($_POST['top30days'])){ 
        $result = $vpd->topSearchHistToJson(30);
        return $result; 
    }

    if(isset($_POST['top7days'])){ 
        $result = $vpd->topSearchHistToJson(7);
        return $result; 
    }

?>