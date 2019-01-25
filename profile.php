<?php
session_start();
include("inc/inc.path.php");
require_once($path."class/class.user.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Visual Parts Database: User Profile</title>
    <?php include("inc/inc.head.php"); ?> <!-- CSS and JavaScript -->
</head>
<body>
    <div class="wrapper">
        <?php include($path."inc/inc.header.php"); ?>
        
        <?php include("inc/inc.footer.php"); ?>
    </div> <!-- end container -->
</body>
</html>