<?php
session_start();
include("inc/inc.path.php");
require_once($path."class/class.user.php");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Visual Parts Database</title>
    <?php include("inc/inc.head.php"); ?> <!-- META, CSS, and JavaScript -->
</head>
<body>
    <div class="wrapper">
        <?php include($path."inc/inc.header.php"); ?>
        <main class="searchGrid">
            <section class="leading">
                <p class="leading-bigtext">VPD</p>
                <p class="leading-text">Electrolux, Frigidaire, and more. Start your search today!</p>
            </section>
            <section class="indexSearch">
                <div class="imageroll">
                    test
                </div>
                <form class="searchForm" action="/search.php" method="get">
                    <input type="text" name="search" id="search" placeholder="Enter Part Number">
                    <button type="submit">Search</button>
                </form>
            </section>
            
            
        </main>
        <?php include("inc/inc.footer.php"); ?>
    </div> <!-- end container -->
    
    
</body>
</html>