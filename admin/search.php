<?php
session_start();
include("../inc/inc.path.php");
require_once($path."class/class.user.php");
require_once($path."class/class.visualdb.php");
require_once($path."class/class.func.php");
$vail = new VALIDATE;
$vpd = new VISUALDB;
$user = new USER;

if(!isset($_SESSION['user_id']))
{
    header('location: /');
} else 
{
    $userID = $_SESSION['user_id'];
    $user->activeCheck($userID);
    if($user->accessCheck() != 'ADMIN'){
        header('location: /noaccess.php');
    }
}

if(isset($_GET['search']))
{
    $sku = $_GET['search'];
    $sku = $vail->sanitizeString($sku);
    $sku = strtoupper($sku);
}

if(isset($_POST['imageSubmit']))
{
    $image = $_POST['fileToUpload'];
    $sku = $_POST['skuId'];
    $sku = $vail->sanitizeString($sku);
    $sku = strtoupper($sku);
    $result = $vpd->addImage($sku, $image);
}

if(isset($_GET['imageupload'])){
    if($_GET['imageupload'] == 'descriptionrequired') {
        $vpd->imageMessage = 'Please enter a description';
    }
    
    if($_GET['imageupload'] == 'notsupported') {
        $vpd->imageMessage = 'Only GIF, JPEG, and PNG are supported';
    }
}

if(isset($_GET['export'])){
    $sku = $_GET['sku'];
    $type = $_GET['export'];
    
    $sku = $vail->sanitizeString($sku);
    $type = $vail->sanitizeString($type);
    
    $result = $vpd->exportData($sku, $type);
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Visual Parts Database: <?php if(isset($sku)){echo strtoupper($sku);} else {echo "Search";} ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php include($path."inc/inc.head.php"); ?> <!-- CSS and JavaScript -->
</head>
<body>
    <?php if(isset($result)){echo $result;} ?>
    
    <div class="wrapper">
        <header class="header">
            <?php include($path."inc/inc.header.php"); ?>
        </header>
        <aside class="admin-nav-bar hidden">
        <?php
        if($user->accessCheck() == "ADMIN")
        {
        ?>
            <?php include($path."inc/inc.adminnavbar.php"); ?>
        <?php
        }
        ?>
        </aside>
        <main class="search-main">
            <?php $vpd->skuSearch($sku, 'admin'); ?>
        </main>
        <footer>
            <?php include($path."inc/inc.footer.php"); ?>
        </footer>    
    </div>
</body>
</html>