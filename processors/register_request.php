<?php
    session_start();
    include("../inc/inc.path.php");
    require_once($path.'class/class.visualdb.php');
    require_once($path."class/class.func.php");
    require_once($path."class/class.user.php");

    $imageUpload = new VISUALDB;
    $vail = new VALIDATE;
    $user = new USER;
    
    if(isset($_GET['fname']))
    {
        $fname = $_GET['fname'];
        $lname = $_GET['lname'];
        $email = $_GET['email'];
        $phone = $_GET['phone'];
        $company = $_GET['company'];
        $message = $_GET['messagearea'];

        $fname = $vail->sanitizeString($fname);
        $lname = $vail->sanitizeString($lname);
        $email = $vail->sanitizeString($email);
        $phone = $vail->sanitizeString($phone);
        $company = $vail->sanitizeString($company);
        $message = $vail->sanitizeString($message);

        $result = $user->registerRequest($fname,$lname,$email,$phone,$company,$message);
        
        if($result == 'alreadyregistered'){
            header('location: /?result=alreadyregistered&fname='.$fname.'&lname='.$lname.'&email='.$email.'&phone='.$phone.'&company='.$company.'&message='.$message.'#member');
        }
        
        if($result == 'alreadyrequested'){
            header('location: /?result=alreadyrequested&fname='.$fname.'&lname='.$lname.'&email='.$email.'&phone='.$phone.'&company='.$company.'&message='.$message.'#member');
        }
        
        if($result == 'success'){
            header('location: /?result=success#member');
        }
        
        if($result == 'alreadyloggedin'){
            header('location: /?result=alreadyloggedin#member');
        }
                
    } 

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
/*    
        if($result)
        {
            header('location: /search.php?search='.$skuId.'&imageupload=successful');    
        } else 
        {
            header('location: /search.php?search='.$skuId.'&imageupload=notsupported');
        }
    }
    
*/
?>