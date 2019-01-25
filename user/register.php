<?php
session_start();
include("../inc/inc.path.php");
require_once($path."class/class.user.php");
require_once($path."class/class.visualdb.php");
require_once($path."class/class.func.php");

$vpd = new VISUALDB;
$vail = new VALIDATE;

if(isset($_GET['register']))
{
    
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>Visual Parts Database: Admin Home</title>
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
            <aside class="admin-nav">
                <?php include($path."inc/inc.adminnavbar.php"); ?>
            </aside>
            <?php
        }
       ?>
        <main id="aboutvpd" class="index-main">

        </main>
        <footer>
            <?php include("inc/inc.footer.php"); ?>
        </footer>
    </div> <!-- end container -->
</body>
</html>