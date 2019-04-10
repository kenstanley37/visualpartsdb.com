<?php
/**
* Author - Ken Stanley
* File Name - deleteuser.php
* Revision Date - April, 10 2019
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

if(isset($_POST['remUser']))
{
    $remUserID = $_POST['userID'];
    $userName = $user->userFullName($remUserID);
    $userListCount = $user->getMyListCount($userID, 'list');
    $userListSkuCount = $user->getMyListCount($userID, 'skus');
}
else 
{
    header('location: /admin/user.php');
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Visual Parts Database: Delete User <?php echo $sku; ?></title>
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
                <h2 class="blue-header">Delete User: <?php echo $userName; ?></h2>
            </section>
            <section class="nav">
                
            </section>
            <section class="form">
                <section class="display">
                    <section class="login shadow">
                        <table class="table">
                            <thead>
                                <tr>
                                    <td colspan="2">
                                        <?php echo $userName; ?> currently has <?php echo $userListCount; ?> list and <?php echo $userListSkuCount; ?> SKUs.
                                    </td>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="2">
                                        Are you sure you want to delete <?php echo $userName; ?>?
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <form action="/processors/userManagement.php" method="post">
                                            <input hidden type="text" name="userID" value="<?php echo $remUserID; ?>">
                                            <button name="remUser" type="submit" class="btn danger">YES</button>
                                        </form>
                                    </td>
                                    <td class="align-right">
                                        <a href="/admin/user.php" class="btn info">NO</a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </section>
                    
                </section>
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