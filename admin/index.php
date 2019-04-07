<?php
session_start();
include("../inc/inc.path.php");
require_once($path."class/class.user.php");
require_once($path."class/class.visualdb.php");
require_once($path."class/class.func.php");

$vpd = new VISUALDB;
$vail = new VALIDATE;
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

$regRequestCount = $user->getRegRequestCount();
$pendingUser = $user->getUserPendingCount();
$skuUpdateCount = $vpd->getSkuUpdateRequestCount();
$mostSearchedSku = $vpd->getMostSearchedSkuCount(1);


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Visual Parts Database: Admin Dashboard</title>
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
        <main class="main">
            <section class="title">
                <h2 class="blue-header">Dashboard</h2>
            </section>
            <section class="nav">
                
            </section>
            <section class="content">
                <div class="dash-top">
                    <a href="/admin/requested-membership.php">
                        <div class="dash1 bg-blue shadow" id="dash1">
                            <div class="fz75 bold text-white"><?php echo $regRequestCount; ?></div>
                            <div class="text-white">Active Register Request</div>
                        </div>
                    </a>
                    <a href="/admin/user-pending.php">
                        <div class="dash2 bg-red shadow" id="dash2">
                            <div class="fz75 bold text-white"><?php echo $pendingUser; ?></div>
                            <div class="text-white">Pending Users</div>
                        </div>
                    </a>
                    <a href="/admin/update-request.php?sku=active">
                        <div class="dash3 bg-green shadow" id="dash3">
                            <div class="fz75 bold text-white"><?php echo $skuUpdateCount; ?></div>
                            <div class="text-white">Update Request</div>
                        </div>
                    </a>
                    <a href="/admin/update-request.php?sku=active">
                        <div class="dash4 bg-orange shadow" id="dash3">
                            <div class="fz35 bold text-white"><?php echo $mostSearchedSku[0]['sku_search_sku']; ?></div>
                            <div class="fz15 bold text-white"><?php echo $mostSearchedSku[0]['Count']; ?> Searches</div>
                            <div class="text-white">Past 30 Days Most Searched SKU</div>
                        </div>
                    </a>
                </div>
            </section>
            <section class="content2">
                <section class="dash-middle">
                    <section class="dash4">100% Width</section>
                </section>
            </section>
        </main>
        <footer>
            <?php include($path."/inc/inc.footer.php"); ?>
        </footer>
    </div> <!-- end container -->
</body>
</html>