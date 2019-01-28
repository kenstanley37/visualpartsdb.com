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


    if(isset($_POST['regsubmit']))
    {
        $memuserID = $_SESSION['user_id'];
        $memFName = ucfirst(strtolower($_SESSION['fname']));
        $memLName = ucfirst(strtolower($_SESSION['lname']));
        $regfname = $vail->sanitizeString($_POST['regfname']);
        $reglname =  $vail->sanitizeString($_POST['reglname']);
        $regemail =  $vail->sanitizeString($_POST['regemail']);
        $user->addUserVerify($regfname, $reglname, $regemail, $memFName, $memLName, $memuserID);
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