<?php
session_start();
include("inc/inc.path.php");
require_once($path."class/class.user.php");
require_once($path."class/class.visualdb.php");
require_once($path."class/class.func.php");
    $vail = new VALIDATE;
    $vpd = new VISUALDB;
    $user = new USER;

if($user->accessCheck() != "ADMIN")
{
    if(isset($_SERVER['HTTP_REFERER'])) 
    {
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;    
    }
    else
    {
        header('Location: /');
        exit;
    }
}



if(isset($_GET['image'])){
    $image = $_GET['image'];
    $image = $vail->sanitizeString($image);
} else {
    header('Location: ' . $_SERVER['HTTP_REFERER']);
}

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Visual Parts Database: <?php if(isset($image)){echo strtoupper($image);} else {echo "Add Image";} ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php include("inc/inc.head.php"); ?> <!-- CSS and JavaScript -->
</head>
<body>
    <div class="search-wrapper">
        <header class="search-header">
            <?php include($path."inc/inc.header.php"); ?>
        </header>
        <?php 
            if($user->accessCheck() == "ADMIN"){
                ?>
        <nav class="navbar">
            <?php include($path."inc/inc.navbar.php"); ?>
        </nav>
                <?php
            }
        ?>
        
        <main class="search-main">
            <form action="addimage.php" method="post" enctype="multipart/form-data">
                Select image to upload:
                <input type="file" name="fileToUpload" id="fileToUpload">
                <input type="submit" value="Upload Image" name="submit">
            </form>
        </main>
        <footer>
            <?php include("inc/inc.footer.php"); ?>
        </footer>    
    </div>
</body>
</html>