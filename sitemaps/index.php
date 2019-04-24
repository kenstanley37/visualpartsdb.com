<?php
/**
* VIEW for admin dashboard
*
* @author Ken Stanley <ken@stanleysoft.org>
* @license MIT
*/
session_start();
include("../inc/inc.path.php");
require_once($path."class/class.func.php");

$vail = new VALIDATE;


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Visual Parts Database: Sitemap Generator</title>
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
                <h2 class="blue-header">Sitemap Generator</h2>
            </section>
            <div class="content">
                <?php $vail->sitemap(); ?>
            </div>
            <div class="content2">
            </div>
        </main>
        <footer>
            <?php include($path."/inc/inc.footer.php"); ?>
        </footer>
    </div> <!-- end container -->
</body>
</html>