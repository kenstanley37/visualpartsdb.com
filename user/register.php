<?php
session_start();
include("../inc/inc.path.php");
require_once($path."class/class.user.php");
require_once($path."class/class.visualdb.php");
require_once($path."class/class.func.php");

$vpd = new VISUALDB;
$vail = new VALIDATE;
$user = new USER;
$error='';

if(isset($_SESSION['fname'])){
    $fname = $_SESSION['fname'];
    $userID = $_SESSION['user_id'];
}

if(isset($_GET['code']))
{
    $recordID = $vail->sanitizeString($_GET['id']);
    $recordCode = $vail->sanitizeString($_GET['code']);
    $result = $user->checkVerify($recordID, $recordCode);
    //echo $result;
    if($result == 'true')
    {
        echo 'im working';
    } elseif($result == "accountexists") 
    {
        $error = 'You already have an account. Please <a href="/login.php">Login</a>.';
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
            if($updateResult){
                header('location: /login.php');
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
            <section class="nav">

            </section>
            <section class="title">
                <h1>Register</h1>
            </section>
            <section class="form">
                <table class="table">
                    <thead>
                        <tr>
                            <th colspan="2">SET PASSWORD</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <label for="password1">Password</label>
                            </td>
                            <td>
                                <input required type="password" name="password1" id="password1">
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="password1">Password Again</label>
                            </td>
                            <td>
                                <input required type="password" name="password2" id="password2">
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" class="align-right">
                                <input type="submit" value="Set Password">
                            </td>
                        </tr>
                        <?php 
                            if(!empty($error))
                            {
                                ?>
                                <tr>
                                    <td colspan="2">
                                        <span><?php echo $error; ?></span>
                                    </td>
                                </tr>
                                <?php
                            }
                        ?>
                    </tbody>
                </table>
            </section>
            <section class="content">
                
            </section>    
        </main>
        <footer>
            <?php include($path."inc/inc.footer.php"); ?>
        </footer>
    </div> <!-- end container -->
</body>
</html>