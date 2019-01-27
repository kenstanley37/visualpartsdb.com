<?php
    session_start();
    include("../inc/inc.path.php");
    require($path.'class/class.visualdb.php');
    require_once($path."class/class.func.php");

    $vpd = new VISUALDB;
    $vail = new VALIDATE;
    
    

    if(isset($_POST['dfrom'])){ 
        $dfrom = $_POST['dfrom'];
        $dto = $_POST['dto'];
        $userID = $_POST['userID'];
        
        $result = $vpd->mysqlToJson($dfrom, $dto, $userID);
        return $result; 
    }

?>