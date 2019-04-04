<?php
session_start();
include("inc/inc.path.php");
require_once($path."class/class.user.php");
require_once($path."class/class.func.php");

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Visual Parts Database: No Access</title>
    <?php include("inc/inc.head.php"); ?> <!-- CSS and JavaScript -->
</head>
<body>
    <div class="wrapper">
        <header class="header">
            <?php include($path."inc/inc.header.php"); ?>
        </header>
        <aside class="admin-nav hidden">
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
                <section class="">
                    <h1 class="blue-header">Access Restricted</h1>
                </section>
            </section>
            
            <section class="nav">
                <section class="display">
                     <section class="login shadow">
                        <div class="form-contact">
                            <h3 class="login-title">Error</h3>
                            <p>You do not have access to view the requested page. Please contact the administrator if you believe this is in error.</p>
                        </div>
                    </section>
                </section>
            </section>
        </main>
        <footer>
            <?php include($path."/inc/inc.footer.php"); ?>
        </footer>
    </div> <!-- end container -->
</body>
</html>