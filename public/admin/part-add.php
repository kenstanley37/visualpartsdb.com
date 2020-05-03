<?php
/**
* VIEW for adding a SKU
*
* @author Ken Stanley <ken@stanleysoft.org>
* @license MIT
*/
session_start();
require_once(__DIR__.'../../vendor/autoload.php');

use user\user;
use sku\sku;
use sec\sec;

$sku = new sku;
$sec = new sec;
$user = new user;

if(!isset($_SESSION['user_id']))
{
    header('location: /');
} else 
{
    $userID = $_SESSION['user_id'];
    $user->activeCheck($userID);
    if($user->accessCheck() != 'ADMIN'){
        header('location: /noaccess.php');
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
    <?php include(__DIR__."../../inc/inc.head.php"); ?> <!-- META, CSS, and JavaScript -->
</head>
<body>
    <div class="wrapper">
        <header>
            <?php include(__DIR__."../../inc/inc.header.php"); ?>
        </header>
        <aside class="admin-nav-bar hidden">
            <?php include(__DIR__."../../inc/inc.adminnavbar.php"); ?>
        </aside>
        <main class="main">
            <section class="title">
                <h2 class="blue-header">Add Part</h2>
            </section>
            <div class="content">
                <section class="w600 shadow bg-white">
                    <div class="form-contact">
                        <h3 class="login-title">NEW SKU</h3>
                        <form action="/processors/sku_handler.php" method="post">
                            <fieldset>
                                <input placeholder="Part #" type="text" name="sku" required>

                                <textarea placeholder="Part description" name="desc" rows="4" cols="30" maxlength="100" required></textarea>

                                <button type="submit" name="addpart" class="info" >Add</button>
                                <?php if(isset($error)){echo '<span class="error">'.$error.'</span>';} ?>
                            </fieldset>
                        </form>
                    </div>
                </section>
            </div>
        </main>
        <footer>
            <?php include(__DIR__."../../inc/inc.footer.php"); ?>
        </footer>
    </div> <!-- end container -->
</body>
</html>