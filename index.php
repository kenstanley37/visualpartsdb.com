<?php
session_start();
include("inc/inc.path.php");
require_once($path."class/class.user.php");
require_once($path."class/class.visualdb.php");

$vpd = new VISUALDB;

?>
<!DOCTYPE html>
<html>
<head>
    <title>Visual Parts Database</title>
    <?php require_once("inc/inc.head.php"); ?> <!-- META, CSS, and JavaScript -->
</head>
<body>
    <div class="wrapper">
        <header>
            <?php include($path."inc/inc.header.php"); ?>
        </header>
        
        <article class="mainnav">
            <?php include($path."inc/inc.mainnavbar.php"); ?>
        </article>
       
        <main class="main">
            <section class="intro">
                <h1>Parts</h1>
                <p>Our database of parts contains information on over 50,000 components from popular products such as:</p>
                <ul>
                    <li><a href="https://www.electroluxappliances.com/">Electrolux</a></li>
                    <li><a href="https://www.frigidaire.com/">Frigidaire</a></li>
                    <li><a href="https://www.kelvinatorcommercial.com/">Kelvinator</a></li>
                    <li><a href="https://www.aeg.com/">AEG</a></li>
                    <li><a href="http://gibson-intl.com/">Gibson</a></li>
                    <li><a href="https://www.zanussi.com/">Zanussi</a></li>
                    <li><a href="https://professional.electrolux.com/">Professional</a></li>
                </ul>
            </section>
            <section id="staticImg">
                <?php echo $vpd->randImage(); ?>
            </section>
        </main>
        <footer>
            <?php include("inc/inc.footer.php"); ?>
        </footer>
    </div> <!-- end container -->
</body>
</html>