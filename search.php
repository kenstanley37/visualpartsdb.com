<?php
session_start();
include("inc/inc.path.php");
require_once($path."class/class.user.php");
require_once($path."class/class.visualdb.php");

if(isset($_GET['search'])){
    $search = htmlspecialchars($_GET['search']);
    $vpd = new VISUALDB;
}

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Visual Parts Database: Search</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php include("inc/inc.head.php"); ?> <!-- CSS and JavaScript -->
</head>
<body>
    <div class="wrapper">
        <?php include($path."inc/inc.header.php"); ?>
        <main class="searchResults">
            <section class="cards">
                <?php $vpd->skuSearch($search); ?>
            </section>
        </main>
        <?php include("inc/inc.footer.php"); ?>
    </div> <!-- end container -->
    
    
</body>
</html>