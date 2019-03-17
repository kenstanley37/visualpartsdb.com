<?php
session_start();
include("../inc/inc.path.php");
require_once($path."class/class.user.php");
require_once($path."class/class.visualdb.php");
require_once($path."class/class.func.php");

$vpd = new VISUALDB;
$vail = new VALIDATE;

if(!isset($_SESSION['user_id'])){
    header('location: /');
} else {
    $user = new USER;
    if($user->accessCheck() != 'ADMIN'){
        header('location: /');
    }
}

if(isset($_GET['error']))
{
    $error = $_GET['error'];
    if($error == 'notfound')
    {
        $error = 'SKU was not found';
    }
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Visual Parts Database: Add Part</title>
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
                <h2>Add Part</h2>
            </section>
            <section class="nav">

            </section>
            <section class="content">
                <?php if(isset($error)){echo '<span>'.$error.'</span>';} ?>
                <table class="table">
                    <thead>
                        <tr>
                            <th colspan="3"><h6>Add Part</h6></th>
                        </tr>
                    </thead>
                    <tbody>
                        <form action="/processors/sku_handler.php" method="post">
                            <tr>
                                <td>Part Number:</td>
                                    <td><input type="text" name="sku" required></td>
                                    
                            </tr>
                            <tr>
                                <td>Description:</td>
                                <td><textarea name="desc" rows="4" cols="30" maxlength="100" required></textarea></td>
                            </tr>
                            <tr>
                                <td class="align-right" colspan="2">
                                    <button type="submit" name="addpart" class="info" >Add</button>
                                </td>
                            </tr>
                        </form>
                    </tbody>
                
                </table>
            </section>
        </main>
        <footer>
            <?php include($path."/inc/inc.footer.php"); ?>
        </footer>
    </div> <!-- end container -->
</body>
</html>