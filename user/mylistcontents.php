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
} else 
{
    $userID = $_SESSION['user_id'];
    $user->activeCheck($userID);
}

if(isset($_GET['list']))
{
    $listid = $_GET['list'];
} else
{
    header('location: /user/myexportlist.php');
}

$mylistcontent =  $user->myListContent($listid); 

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Visual Parts Database: My List Content</title>
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
                <h1>List: <?php echo strtoupper($user->myListReturn($listid, 'name')); ?></h1>
            </section>
            <section class="form">
                Insert Export Links Here
            </section>
            <section class="content">
                <table class="table">
                    <thead>
                        <tr align="middle">
                            <td scope="col">SKU</td>
                            <td scope="col" colspan="2">Description</td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                         foreach($mylistcontent as $row)
                {
                    ?>
                        <tr valign="middle">
                            <td scope="row" data-label="SKU"><a href="/search.php?search=<?php echo $row['pls_list_sku']; ?>"><?php echo $row['pls_list_sku']; ?></a></td>
                            <td data-label="Desc"><?php echo $row['sku_desc']; ?></td>
                            <td>
                                <form action="/processors/userManagement.php" method="post">
                                    <input name="listID" value="<?php echo $row['pls_list_id']; ?>" hidden>
                                    <input name="skuID" value="<?php echo $row['pls_list_sku']; ?>" hidden>
                                    <input name="myListContent" value="myListContent" hidden>
                                    <button class="btn danger" type="submit" name="remSkuFromList" id="remSkuFromList">Remove</button>
                                </form>
                            </td>
                        </tr>  
                    <?php
                }
                ?>
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