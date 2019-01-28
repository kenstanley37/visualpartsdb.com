<?php
    session_start();
    include("../inc/inc.path.php");
    require($path.'class/class.visualdb.php');
    require_once($path."class/class.func.php");
    require_once($path."class/class.user.php");

    $vpd = new VISUALDB;
    $vail = new VALIDATE;
    $user = new USER;
    
    

    if(isset($_POST['dfrom'])){ 
        $dfrom = $_POST['dfrom'];
        $dto = $_POST['dto'];
        $userID = $_POST['userID'];
        
        $result = $vpd->mysqlToJson($dfrom, $dto, $userID);
        
        //echo $result();
        return $result; 
    }

?>