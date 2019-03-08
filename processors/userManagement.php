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

    if(isset($_POST['listname']))
    {
        $listname = $_POST['listname'];
        $listdescription = $_POST['listdescription'];
        $listname = $vail->sanitizeString($listname);
        $listdescription = $vail->sanitizeString($listdescription);
        $user->MyListAdd($listname, $listdescription);
        header("location: /user/myexportlist.php");
        
    }

    if(isset($_POST['deletelist']))
    {
        $listID = $_POST['deletelist'];
        $user->myListDelete($listID);
        header("location: /user/myexportlist.php");
        
    }

?>