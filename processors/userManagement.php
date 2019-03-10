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

    /**********************************************
    
    Manage "My List" functions
    
    ***********************************************/

    // Add a list
    if(isset($_POST['listname']))
    {
        $listname = $_POST['listname'];
        $listdescription = $_POST['listdescription'];
        $listname = $vail->sanitizeString($listname);
        $listdescription = $vail->sanitizeString($listdescription);
        $user->MyListAdd($listname, $listdescription);
        header("location: /user/myexportlist.php");
    }

    // delete list (dangerous!!)
    if(isset($_POST['deletelist']))
    {
        $listID = $_POST['deletelist'];
        $user->myListDelete($listID);
        header("location: /user/myexportlist.php");
    }

    // Set a list to active
    if(isset($_POST['makeActive']))
    {
        $listID = $_POST['makeActive'];
        $user->myListActive($listID);
        header("location: /user/myexportlist.php");
    }

    // Add SKU to active list
    if(isset($_POST['addSkuToList']))
    {
        $skuID = $_POST['skuID'];
        $user->myListaddSku($skuID);
        header("location: /search.php?search=".$skuID);
    }

    // Remove SKU from list
    if(isset($_POST['remSkuFromList']))
    {
        $listID = $_POST['listID'];
        $skuID = $_POST['skuID'];
        $user->myListRemSku($skuID, $listID);
        if(isset($_POST['myListContent']))
        {
            header("location: /user/mylistcontents.php?list=".$listID);
        } else
        {
            header("location: /search.php?search=".$skuID);
        }
    }

    // Request SKU data update
    if(isset($_POST['requestUpdate']))
    {
        $skuID = $_POST['skuID'];
        $user->requestUpdate($skuID);
        header("location: /search.php?search=".$skuID);
    }
    
?>