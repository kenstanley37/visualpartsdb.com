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

if(isset($_POST['reset']))
{
    $email = $_POST['email'];
    $email = strtolower($email);
    $email = $vail->sanitizeString($email);
    $user->sendPassLink($email);
    
    if($result == "true")
    {
        $error = '';
    } else
    {
        $error = 'Please check your email for a reset link';
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
                <h1>Password Reset</h1>
            </section>
            <section class="form">
                <form action="reset.php" method="post">
                    <table class="table">
                        <thead>
                            <tr>
                                <th colspan="2">Password Reset</th>
                            </tr>
                        </thead>
                        <tbody>
                            <form action="/user/reset.php" method="post">
                                <tr>
                                    <td><label for="email">Email:</label></td>
                                    <td><input type="email" name="email" required></td>
                                </tr>
                                <tr>
                                    <td colspan="2"><p>This will send a reset link</p></td>
                                </tr>
                                <tr>
                                    <td colspan="2" class="align-right"><button type="submit" class="btn active" name="reset">Reset</button></td>
                                </tr>
                                <tr>
                                    <td colspan="2"><?php echo $error; ?></td>
                                </tr>
                            </form>
                        </tbody>
                    </table>
                </form>
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