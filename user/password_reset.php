<?php
/**
* Author - Ken Stanley
* File Name - password_reset.php
* Revision Date - April, 10 2019
*/
session_start();
include("../inc/inc.path.php");
require_once($path."class/class.user.php");
require_once($path."class/class.visualdb.php");
require_once($path."class/class.func.php");

$vpd = new VISUALDB;
$vail = new VALIDATE;
$user = new USER;
$error='';

if(isset($_SESSION['temp_id']))
{
    $userID = $_SESSION['temp_id'];
}

if(isset($_GET['code']))
{
    $userID = $vail->sanitizeString($_GET['id']);
    $_SESSION['temp_id'] = $userID;
    $userCode = $vail->sanitizeString($_GET['code']);
    $result = $user->checkVerify($userID, $userCode);
    //echo $result;
    if($result == 'true')
    {
        //echo 'im working';
    } elseif($result == "noaccount") 
    {
        $error = 'Sorry you do not have an account on this system';
    } else
    {
        header("location: /login.php");
    }
}

if(!isset($_GET['code'])){
    $result = '';
    if(isset($_POST['passwordupdate'])){
        $password1 = $vail->sanitizeString($_POST['password1']);
        $password2 = $vail->sanitizeString($_POST['password2']);
        
        if($password1 != $password2){
            $error = 'Passwords do no match';
        } else {
            $updateResult = $user->updatePassword($userID, $password1);
            if($updateResult)
            {
                unset($_SESSION['temp_id']);
                unset($_SESSION['user_id']);
                header('location: /login.php');
            } else
            {
                $error = $updateResult;
            }
        }
    } else {
        header('location: /');
    }
    
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Visual Parts Database: Register</title>
    <?php require_once($path."inc/inc.head.php"); ?> <!-- META, CSS, and JavaScript -->
</head>
<body>
    <div class="wrapper">
        <header>
            <?php include($path."inc/inc.header.php"); ?>
        </header>
        <aside class="admin-nav">
        <?php
        if($user->accessCheck() == "ADMIN")
        {
            include($path."inc/inc.adminnavbar.php");
        }
       ?>
        </aside>
        <main class="main">
            <section class="title">
                <h2 class="blue-header">Set Password</h2>
            </section>
            
            <div class="content">
                <div class="w600 shadow bg-white">
                    <h2 class="login-title">New Password</h2>
                     <div class="form-contact">
                        <form action="password_reset.php" method="post">
                            <input required type="password" name="password1" id="password1" placeholder="Password">
                            <input required type="password" name="password2" id="password2" placeholder="Password Again">
                            <button class="btn active" type="submit" name="passwordupdate">Set Password</button>
                            <span class="error"><?php echo $error; ?></span>
                        </form>
                    </div>
                </div>
            </div>   
        </main>
        <footer>
            <?php include($path."inc/inc.footer.php"); ?>
        </footer>
    </div> <!-- end container -->
</body>
</html>