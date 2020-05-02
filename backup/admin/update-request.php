<?php
/**
* VIEW for sku update request
*
* @author Ken Stanley <ken@stanleysoft.org>
* @license MIT
*/
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

if(isset($_GET['sku']))
{
    $sku = $_GET['sku'];
} else
{
    header('location: /admin/update-request.php?sku=active');
}

$updateRequest = $vpd->skuUpdateRequest('active');

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Visual Parts Database: Update Request</title>
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
                <h2 class="blue-header">SKU Update Request</h2>
            </section>
            <div class="content">
                <div class="grid-temp-30-70 w100p">
                    <div class="w100p shadow lh25 bg-white mh500">
                        <h2 class="login-title">Description</h2>
                        <p>This list shows the amount of users who have requested the data/images of the SKU to be updated.</p>
                    </div>
                    <section class="w100p shadow bg-white">
                        <h3 class="login-title">Active Part Update Request</h3>
                        <table class="table nowrap">
                            <thead>
                                <tr>
                                    <td>SKU</td>
                                    <td>Description</td>
                                    <td class="align-right">Times Requested</td>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                            foreach($updateRequest as $row)
                            {
                            $skuID = $row['update_sku'];
                            ?>
                                <tr>
                                    <td data-label="SKU"><a href="/admin/update-sku.php?sku=<?php echo $skuID; ?>"><?php echo $skuID; ?></a></td>
                                    <td data-label="Desc"><?php echo $row['sku_desc']; ?></td>
                                    <td data-label="Count" class="align-right"><a href="/admin/update-request-sku.php?sku=<?php echo $skuID; ?>"><?php echo $row['count']; ?></a></td>
                                </tr>  
                            <?php
                            }
                            ?>
                            </tbody>
                        </table>
                    </section>
                </div>
            </div>
        </main>
        <footer>
            <?php include($path."/inc/inc.footer.php"); ?>
        </footer>
    </div> <!-- end container -->
</body>
</html>