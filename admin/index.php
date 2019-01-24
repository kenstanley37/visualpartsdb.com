<?php
session_start();
include("../inc/inc.path.php");
require_once($path."class/class.user.php");
require_once($path."class/class.visualdb.php");
require_once($path."class/class.func.php");


if(!isset($_SESSION['user_id'])){
    header('location: /');
} else {
    $user = new USER;
    if($user->accessCheck() != 'ADMIN'){
        header('location: /');
    }
}

$vpd = new VISUALDB;
$vail = new VALIDATE;

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
            <nav class="adminnav">
                <?php include($path."inc/inc.adminnavbar.php"); ?>
            </nav>
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