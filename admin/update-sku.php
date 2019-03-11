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

if(!isset($_GET['sku']))
{
    header('location: /admin/update-request.php?sku=active');
} else 
{
    $sku = $_GET['sku'];
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Visual Parts Database: SKU UPDATE</title>
    <?php include($path."inc/inc.head.php"); ?> <!-- META, CSS, and JavaScript -->
</head>
<body>
    <div class="wrapper">
        <header>
            <?php include($path."inc/inc.header.php"); ?>
        </header>
        <aside class="admin-nav-bar hidden">
            <?php include($path."inc/inc.adminnavbar.php"); ?>
        </aside>
        <main class="my-list-main">
            <section class="listtitle">
                <h2>Update Request</h2>
            </section>
            <section class="listmylist">
                <?php include($path."inc/inc.update-nav.php"); ?>
            </section>
            <section class="listshowlist">
                <?php $vpd->skuUpdateRequest($sku); ?>
            </section>
        </main>
        <footer>
            <?php include($path."/inc/inc.footer.php"); ?>
        </footer>
    </div> <!-- end container -->
</body>
</html>