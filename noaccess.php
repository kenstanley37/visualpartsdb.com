<?php
/**
* Author - Ken Stanley
* File Name - noaccess.php
* Revision Date - April, 10 2019
*/
session_start();
include("inc/inc.path.php");
require_once($path."class/class.user.php");
require_once($path."class/class.visualdb.php");
require_once($path."class/class.func.php");

$vpd = new VISUALDB;
$vail = new VALIDATE;

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Visual Parts Database: No Access</title>
    <?php require_once($path."inc/inc.head.php"); ?> <!-- META, CSS, and JavaScript -->
</head>
<body>
    <div class="wrapper">
        <header>
            <?php include($path."inc/inc.header.php"); ?>
        </header>
        <?php
        if($user->accessCheck() == "ADMIN")
        {
            ?>
            <nav class="adminnav">
                <?php include($path."inc/inc.adminnavbar.php"); ?>
            </nav>
            <?php
        }
       ?>
        <main class="main">
            <section class="content">
                <section class="display">
                    <section class="login shadow">
                        <h1 class="login-title">No Access</h1>
                        <p>OOPS! You do not have access to the request file</p>
                    </section>
                </section>
            </section>
        </main>
        <footer>
            <?php include("inc/inc.footer.php"); ?>
        </footer>
    </div> <!-- end container -->
</body>
</html>