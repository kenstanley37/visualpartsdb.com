<?php
/**
* VIEW for description search
*
* @author Ken Stanley <ken@stanleysoft.org>
* @license MIT
*/
session_start();
include("inc/inc.path.php");
require_once($path."class/class.user.php");
require_once($path."class/class.visualdb.php");
require_once($path."class/class.func.php");
$vail = new VALIDATE;
$vpd = new VISUALDB;
$user = new USER;

// get the list name of the current active list, if any
$activelist = $user->getMyActiveListName();
$activelistID = $user->getMyActiveListID();

if(isset($_GET['desc']))
{
    $sku = $_GET['desc'];
    $sku = $vail->sanitizeString($sku);
    $sku = strtoupper($sku);
    $dataResult = $vpd->getDescSearch($sku);
    /*
    $createDate = $dataResult['sku_rec_date'];
    $createDate = date_create($createDate);
    $createDate = date_format($createDate, 'm/d/Y');
    $updateDate = $dataResult['sku_rec_update'];
    $updateDate = date_create($updateDate);
    $updateDate = date_format($updateDate, 'm/d/Y');
    */
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Visual Parts Database: <?php if(isset($sku)){echo strtoupper($sku);} else {echo "Search";} ?></title>
    <?php include("inc/inc.head.php"); ?> <!-- CSS and JavaScript -->
</head>
<body>
    <?php if(isset($result)){echo $result;} ?>
    
    <div class="wrapper">
        <header class="header">
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
            <div class="title">
                <section class="search-title">
                    <h2 class="blue-header">Description Result</h2>
                </section>
            </div>

            <div class="content pad-bot-50">
                <div class="w50p shadow bg-white">
                    <?php 
                        if(!empty($dataResult))
                        {
                            ?>
                    <h3 class="login-title">Results based on your search</h3>
                    <table class="table tbl_img">
                        <thead>
                            <tr>
                                <th>SKU IMG</th>
                                <th>SKU ID</th>
                                <th>Description</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                                foreach($dataResult as $row)
                                {
                                    ?>
                                     <tr>
                                        <td class="grey-text">
                                            <?php
                                                if(empty($row['sku_image_thumb']))
                                                {
                                                    ?>
                                                    <i class="far fa-file-image"></i>
                                                    <?php
                                                } else
                                                {
                                                    ?>
                                                    <img src="<?php echo $row['sku_image_thumb']; ?>">
                                                    <?php
                                                }
                                                ?>
                                                
                                        </td>
                                            <td class="grey-text">
                                                <a href="/search.php?search=<?php echo $row['sku_id']; ?>"><?php echo $row['sku_id']; ?></a>
                                        </td>
                                            <td class="grey-text">
                                                <a href="/search.php?search=<?php echo $row['sku_id']; ?>"><?php echo $row['sku_desc']; ?></a>
                                            </td>
                                        </tr>
                                    <?php
                                }
                            ?>
                        </tbody>
                    </table>
                    <?php
                    } else
                        {
                            ?>
                            <h3>SKU List</h3><br>
                            <p>Sorry! nothing was found for <span class="error">"<?php echo $sku ?></span>"</p><br>
                            <p>Please try again</p>
                            <?php
                        }
                    ?>
                </div>
            </div>
        </main>
        <footer>
            <?php include("inc/inc.footer.php"); ?>
        </footer>    
    </div>
</body>
</html>