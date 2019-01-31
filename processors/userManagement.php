<?php
    session_start();
    include("../inc/inc.path.php");
    require_once($path.'class/class.visualdb.php');
    require_once($path."class/class.func.php");
    require_once($path."class/class.user.php");

    $vpd= new VISUALDB;
    $vail = new VALIDATE;
    $user = new USER;
    
    if(isset($_POST['activeSwitch']))
    {
        $userID = $_POST['activeSwitch'];

        $result = $user->activeSwitch($userID);
        
        echo $result;
        
        header("location: /admin/member.php?user");
        
    }

?>