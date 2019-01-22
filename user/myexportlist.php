<?php
session_start();

if(!isset($_SESSION['user_id'])){
    header('location: /');
} else {
    $userID = $_SESSION['user_id'];
}


include("../inc/inc.path.php");
require_once($path."class/class.user.php");
require_once($path."class/class.visualdb.php");
require_once($path."class/class.func.php");

$vpd = new VISUALDB;
$vail = new VALIDATE;

$result = '';

?>
<!DOCTYPE html>
<html>
<head>
    <title>Visual Parts Database : User</title>
    <?php require_once($path."inc/inc.head.php"); ?> <!-- META, CSS, and JavaScript -->
</head>
<body>
    <div class="wrapper">
        <header>
            <?php include($path."inc/inc.header.php"); ?>
        </header>
        
        <nav class="mainnav">
            <?php include($path."inc/inc.mainnavbar.php"); ?>
        </nav>
        
        <main class="main">  
            <article class="user-search">
                <section>
                    Date From
                </section>

                <section>
                    Date To
                </section>

                <section>
                    User
                </section>
            </article>
            <?php 
                    if(isset($_GET['searchhist']))
                    {
                        $dateStart = date("Y-m-d");
                        $vpd->mySearches($dateStart, $dateStart, $userID, 50);
                    }  
            ?>
        </main>
        <footer>
            <?php include($path."inc/inc.footer.php"); ?>
        </footer>
    </div> <!-- end container -->
</body>
</html>