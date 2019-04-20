<?php
/**
* my searches processor
*
* @author Ken Stanley <ken@stanleysoft.org>
* @license MIT
*/
    session_start();
    include("../inc/inc.path.php");
    require_once($path.'class/class.visualdb.php');
    require_once($path."class/class.func.php");
    require_once($path."class/class.user.php");

    $imageUpload = new VISUALDB;
    $vail = new VALIDATE;
    $user = new USER;
    
    if(isset($_POST['dfrom']))
    {
        $userID = $_SESSION['usersID'];
        $dateFrom = $_SESSION['dfrom'];
        $dateTo = $_SESSION['dto'];

        $result = $vpd->mySearches($dateFrom, $dateTo, $userID);
        
        header("location: /user/mysearches.php?dfrom=$dateFrom&dto=$dateTo&usersID=$userID");
        
    }
?>