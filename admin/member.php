<?php
session_start();
include("../inc/inc.path.php");
require_once($path."class/class.user.php");
require_once($path."class/class.visualdb.php");
require_once($path."class/class.func.php");

$vpd = new VISUALDB;
$vail = new VALIDATE;

if(!isset($_SESSION['user_id'])){
    header('location: /');
} else {
    $user = new USER;
    if($user->accessCheck() != 'ADMIN'){
        header('location: /');
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Visual Parts Database</title>
    <?php require_once($path."inc/inc.head.php"); ?> <!-- META, CSS, and JavaScript -->
</head>
<body>
    <div class="wrapper">
        <header>
            <?php include($path."inc/inc.header.php"); ?>
        </header>
        <?php
        if($user->accessCheck() == "ADMIN")
        {
            ?>
            <nav class="admin-nav">
                <?php include($path."inc/inc.adminnavbar.php"); ?>
            </nav>
            <?php
        }
       ?>
        <main id="aboutvpd" class="index-main">
            <form method="post" action="/processors/register_request.php">
                <table>
                    <tr>
                        <td>
                            <label for="regfname">First Name</label>
                            <input type="text" name="regfname" required>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="reglname">Last Name</label>
                            <input type="text" name="reglname" required>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="regemail">Email Address</label>
                            <input type="email" name="regemail" required>
                        </td>
                    </tr>
                </table>
                
                
                
                <input type="submit" name="regsubmit" value="Submit">
            </form>
        </main>
        <footer>
            <?php include("inc/inc.footer.php"); ?>
        </footer>
    </div> <!-- end container -->
</body>
</html>