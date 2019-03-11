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
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Visual Parts Database: My Product List</title>
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
                <h1>My List</h1>
            </section>
            <section class="form">
                <form action="/processors/userManagement.php" method="post">
                    <h2>Create List</h2>
                    <label for="listname">List Name:</label>
                    <input type="text" id="listname" name="listname" maxlength="10" required>
                    <label for="listname">List Description:</label>
                    <input type="text" id="listdescription" name="listdescription" maxlength="30" required>
                    <button type="submit">Submit</button>
                </form>
            </section>
            <section class="content">
                <?php $user->myList(); ?>
            </section>
        </main>
        <footer>
            <?php include($path."/inc/inc.footer.php"); ?>
        </footer>
    </div> <!-- end container -->
</body>
</html>