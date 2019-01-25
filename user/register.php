<?php
session_start();
include("../inc/inc.path.php");
require_once($path."class/class.user.php");
require_once($path."class/class.visualdb.php");
require_once($path."class/class.func.php");

$vpd = new VISUALDB;
$vail = new VALIDATE;
$user = new USER;

if(isset($_SESSION['fname'])){
    $fname = $_SESSION['fname'];
    $userID = $_SESSION['user_id'];
}

if(isset($_GET['code']))
{
    $recordID = $vail->sanitizeString($_GET['id']);
    $recordCode = $vail->sanitizeString($_GET['code']);
    $result = $user->checkVerify($recordID, $recordCode);
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
        <main class="register-main">
            <h1>Registration</h1>
            <?php 
                if($result){
                    ?>
                        <section class="register-success">
                            <form>
                                <input type="password" name="password1" id="password1">
                                <input type="password" name="password2" id="password2">
                                <input type="submit" value="Set Password">
                            </form>
                        </section>
                    <?php
                }
            ?>
            <?php 
                if(!$result){
                    ?>
                        <section class="register-fail">
                            <h2><?php if(!empty($fname)){echo 'Welcome '.$fname;} ?></h2>
                            <form action="/user/register.php" method="post">
                                <table class="reg-table">
                                    <caption>Please set your password</caption>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <label for="password1">Password</label>
                                            </td>
                                            <td>
                                                <input type="password" name="password1" id="password1">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <label for="password1">Password Again</label>
                                            </td>
                                            <td>
                                                <input type="password" name="password2" id="password2">
                                                <span><?php if(isset($error)){echo $error;} ?></span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><input type="submit" value="Set Password" name="passwordupdate"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </form>
                        </section>
                    <?php
                }
            ?>
        </main>
        <footer>
            <?php include($path."inc/inc.footer.php"); ?>
        </footer>
    </div> <!-- end container -->
</body>
</html>