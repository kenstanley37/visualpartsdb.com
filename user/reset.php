<?php
/**
* Author - Ken Stanley
* File Name - reset.php
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

if(isset($_POST['reset']))
{
    $email = $_POST['email'];
    $email = strtolower($email);
    $email = $vail->sanitizeString($email);
    $result = $user->sendPassLink($email);
    
    if($result == "true")
    {
        $error = '<span>Please check your email for a reset link</span>';
    } else
    {
        $error = $result;
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
                <h1 class="blue-header">Password Reset</h1>
            </section>
            <section class="form">
                <div class="login shadow">
                    <div class="form-contact">
                        <h3 class="login-title">Reset Password</h3>
                        <form action="reset.php" method="post">
                            <fieldset>
                                <input placeholder="Email" type="email" name="email" required>

                                <p>This will send a reset link</p>
                                
                                <p class="error"><?php echo $error; ?></p>

                                <button type="submit" class="btn active" name="reset">Reset</button>
                            </fieldset>
                        </form>
                    </div>
                </div>
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