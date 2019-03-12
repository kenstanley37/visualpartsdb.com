<?php
session_start();
include("../inc/inc.path.php");
require_once($path."class/class.user.php");
require_once($path."class/class.visualdb.php");
require_once($path."class/class.func.php");

$vpd = new VISUALDB;
$vail = new VALIDATE;

if(!isset($_SESSION['user_id']))
{
    header('location: /');
} else 
{
    $user = new USER;
    if($user->accessCheck() != 'ADMIN'){
        header('location: /');
    }
}

if(isset($_GET['sku']))
{
    $sku = $_GET['sku'];
}
else 
{
    header('location: /admin/part-management.php');
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Visual Parts Database: Update SKU</title>
    <?php require_once($path."inc/inc.head.php"); ?> <!-- META, CSS, and JavaScript -->
</head>
    
<body>
    <div class="wrapper">
        <header>
            <?php include($path."inc/inc.header.php"); ?>
        </header>
        <aside class="admin-nav-bar hidden">
            <?php include($path."inc/inc.adminnavbar.php"); ?>
        </aside>
        <main class="main">
            <section class="title">
                <h2>Update SKU</h2>
            </section>
            <section class="nav">

            </section>
            <section class="update-form">
                <h2><?php echo $sku; ?></h2>
                <button class="info" type="submit" name="skuUpdate" form="UpdateForm">Submit</button>
                <?php $vpd->getSkuData($sku); ?>
            </section>

            <section class="content">
            </section>
        </main>
        <footer>
            <?php include($path."/inc/inc.footer.php"); ?>
        </footer>
    </div> <!-- end container -->
</body>
</html>