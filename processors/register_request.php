<?php
/**
* processor for the requested request on the main page
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
    


    // this comes from invite-user.php
    if(isset($_POST['regsubmit']))
    {    
        $fname = $vail->sanitizeString($_POST['regfname']);
        $lname =  $vail->sanitizeString($_POST['reglname']);
        $email =  $vail->sanitizeString($_POST['regemail']);
        $company =  $vail->sanitizeString($_POST['regcompany']);
        $user->addUserVerify($fname, $lname, $email, $company);
        if($user == true)
        {
            header('location: /admin/invite-user.php?register=successful');
        } else {
            header('location: /admin/invite-user.php?register=unsuccessful');
        }
        
    }
?>