<?php
session_start();
include("inc/inc.path.php");
require_once($path."class/class.user.php");
require_once($path."class/class.visualdb.php");
require_once($path."class/class.func.php");

if(isset($_GET['search'])){
    $vail = new VALIDATE;
    $vpd = new VISUALDB;
    $user = new USER;
    $search = $_GET['search'];
    $search = $vail->sanitizeString($search);
}

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Visual Parts Database: <?php if(isset($search)){echo strtoupper($search);} else {echo "Search";} ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php include("inc/inc.head.php"); ?> <!-- CSS and JavaScript -->
</head>
<body>
    <div class="search-wrapper">
        <header class="search-header">
            <?php include($path."inc/inc.header.php"); ?>
        </header>
        <?php 
            if($user->accessCheck() == "ADMIN"){
                ?>
        <nav class="navbar">
            <?php include($path."inc/inc.navbar.php"); ?>
        </nav>
                <?php
            }
        ?>
        
        <main class="search-main">
            <?php $vpd->skuSearch($search); ?>
        </main>
        <footer>
            <?php include("inc/inc.footer.php"); ?>
        </footer>    
    </div>
</body>
</html>