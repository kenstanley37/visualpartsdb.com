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
}

if(isset($_POST['deletelist']))
{
    $listid = $_POST['listid'];
    $listname = $_POST['listname'];
    $listcount = $_POST['listcount'];
} else
{
    header('location: /user/myexportlist.php');
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Visual Parts Database: Delete List</title>
    <?php include($path."inc/inc.head.php"); ?> <!-- META, CSS, and JavaScript -->
</head>
<body>
    <div class="wrapper">
        <header>
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
        <main class="main">
            <section class="title">
                <h1>Delete List</h1>
            </section>
            <section class="form">
                <table class="table t300">
                    <caption>Delete list and <?php echo $listcount; ?> parts? </caption>
                    <thead>
                        <td>List Name</td>
                        <td>Parts</td>
                    </thead>
                    <tbody>
                        <td><?php echo strtoupper($listname); ?></td>
                        <td><?php echo $listcount; ?></td>
                    </tbody>
                    <tfoot>
                        <td>
                            <form action="/processors/userManagement.php" method="post">
                                <input type="text" hidden value="<?php echo $listid; ?>" name="listid" id="listid">
                                <button class="danger" type="submit" name="deletelist" id="deletelist" value="<?php echo $listid;?>">Delete</button>
                            </form>
                        </td>
                        <td>
                            <form action="/user/myexportlist.php">
                                <button class="info" type="submit">Cancel</button>
                            </form>
                        </td>
                    </tfoot>
                </table>
            </section>
        </main>
        <footer>
            <?php include($path."/inc/inc.footer.php"); ?>
        </footer>
    </div> <!-- end container -->
</body>
</html>