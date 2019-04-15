<?php
/**
* Author - Ken Stanley
* File Name - disabled.php
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
    if($user->accessCheck() != 'ADMIN')
    {
        header('location: /');
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Visual Parts Database: Account Disabled</title>
    <?php require_once($path."inc/inc.head.php"); ?> <!-- META, CSS, and JavaScript -->
</head>
<body>
    <div class="wrapper">
        <header>
            <?php include($path."inc/inc.header.php"); ?>
        </header>
        <!-- USER SECTION -->
        <aside class="admin-nav-bar hidden">

        </aside>
        <main class="main">
            <section class="nav">

            </section>
            <section class="title">
                <h1>Account Disabled</h1>
            </section>
            <section class="content">
                Sorry, <?php echo $_SESSION['fname']; ?> your account has been disabled.
            </section>    
        </main>
        <footer>
        <?php include($path."inc/inc.footer.php"); ?>
        </footer>
    </div> <!-- end container -->
</body>
</html>