<?php
session_start();
include("inc/inc.path.php");
require_once($path."class/class.user.php");
require_once($path."class/class.visualdb.php");
require_once($path."class/class.func.php");

if(isset($_GET['search'])){
    $vail = new VALIDATE;
    $vpd = new VISUALDB;
    $search = $_GET['search'];
    $search = $vail->sanitizeString($search);
    
}

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Visual Parts Database: <?php if(isset($search)){echo $search;} ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php include("inc/inc.head.php"); ?> <!-- CSS and JavaScript -->
</head>
<body>
    <div class="search-wrapper">
        <?php include($path."inc/inc.header.php"); ?>
            <?php $vpd->skuSearch($search); ?>
        <?php include("inc/inc.footer.php"); ?>
    </div>
</body>
</html>