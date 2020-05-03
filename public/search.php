<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
/**
* Search page to display results
*
* @author Ken Stanley <ken@stanleysoft.org>
* @license MIT
*/
session_start();
include("inc/inc.path.php");
require_once($path.'/vendor/autoload.php');
use user\user;
use sec\sec;
use sku\sku;

$sec = new sec;
$user = new user;
$sku = new sku;

// get the list name of the current active list, if any
$activelist = $user->getMyActiveListName();
$activelistID = $user->getMyActiveListID();

if(isset($_GET['search']))
{
    $skunum = $_GET['search'];
    $skunum = $sec->sanitizeString($skunum);
    $skunum = strtoupper($skunum);

    if($sku->checkSku($skunum))
    {
        $dataResult = $sku->getSkuData($skunum);
        $imageResult = $sku->getSkuImage($skunum);
        $createDate = $dataResult['sku_rec_date'];
        $createDate = date_create($createDate);
        $createDate = date_format($createDate, 'm/d/Y');
        $updateDate = $dataResult['sku_rec_update'];
        $updateDate = date_create($updateDate);
        $updateDate = date_format($updateDate, 'm/d/Y');
    } else {
        header('location: /desc-search.php?desc='.$skunum.'');
    }
    
}


if(isset($_GET['export'])){
    $sku = $_GET['sku'];
    $type = $_GET['export'];
    $sku = $vail->sanitizeString($sku);
    $type = $vail->sanitizeString($type);
    $result = $sku->exportData($sku, $type);
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
        <main class="search-main">
            <?php
            if(!empty($dataResult))
            {
                ?>  
                    <div class="title">
                        <section class="search-title">
                            <h2 class="blue-header">PART #: <?php echo $dataResult['sku_id']; ?></h2>
                        </section>
                    </div>
            
                    <div class="nav search-nav">
                        <div class="search-user-functions">
                            <div class="contain1 just-left">
                                <table class="table-nores">
                                    <tbody>
                                        <tr>
                                            <td class="nav-btn">
                                                <form action="/export/generate-xlsx.php" method="get">
                                                    <input type="text" value="excel" name="unit" hidden>
                                                   <input type="text" value="<?php echo $dataResult['sku_id']; ?>" name="sku" hidden>
                                                    <button class="nav-btn shadow" type="submit" name="submit">EXCEL</button>
                                                </form>

                                            </td>

                                            <td class="nav-btn">
                                                 <?php
                                                    if(isset($_SESSION['user_id']))
                                                    {

                                                        if($user->requestUpdateCheck($sku))
                                                        {
                                                            ?>
                                                        <button class="active shadow" disabled>Update Requested</button>
                                                            <?php
                                                        } else
                                                        {
                                                            ?>

                                                                <form method="post" action="/processors/userManagement.php">
                                                                    <input type="text" name="skuID" value="<?php echo $dataResult['sku_id']; ?>" hidden>
                                                                    <button class="nav-btn shadow" type="submit" name="requestUpdate">Request SKU Update</button>
                                                                </form>

                                                            <?php
                                                        }
                                                    }
                                                ?>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="contain2 just-right">
                                <?php if(isset($_SESSION['user_id'])){ 
                                        if(empty($activelist)){
                                            ?>
                                        <table class="table-nores">
                                            <tr>
                                                <td class="nav-btn">
                                                    <form action="/user/myexportlist.php">
                                                        <button class="nav-btn shadow" type="submit" name="submit">
                                                            Create List
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        </table>
                                            <?php
                                        }
                                        else 
                                        {
                                            ?>
                                            <table class="table-nores">
                                                <tbody>
                                                    <tr>
                                                        <td class="nav-btn">
                                                            <form action="/user/mylistcontents.php" method="get">
                                                                <input type="text" name="list" value="<?php echo $activelistID; ?>" hidden>
                                                                <button class="nav-btn shadow" type="submit" name="submit">Active List: <?php echo strtoupper($activelist); ?> </button>
                                                            </form>
                                                           
                                                        </td>
                                                        <td class="nav-btn">
                                                            <form action="/processors/userManagement.php" method="post">
                                                                <input type="text" value="<?php echo $dataResult['sku_id']; ?>" name="skuID" id="skuID" hidden>

                                                                <input type="text" value="<?php echo $activelistID; ?>" name="listID" id="listID" hidden>
                                                                <?php 
                                                                    $skucheck = $user->myListSkuCheck($sku);
                                                                    if($skucheck)
                                                                    {
                                                                        ?>
                                                                        <button class="nav-btn shadow active" type="submit" name="remSkuFromList">Remove From List</button>
                                                                        <?php
                                                                    } else 
                                                                    {
                                                                        ?>
                                                                        <button class="nav-btn shadow" type="submit" name="addSkuToList">Add To List</button>
                                                                        <?php
                                                                    }
                                                                ?>
                                                            </form>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                            <?php
                                        }
                                } ?> 
                                </div>
                        </div>
                    </div>
            
                    <div class="form">
                        
                    </div>

                    <div class="content pad-bot-50">
                        <div class="grid-wrap250 sku-data">
                            <div class="sku-content shadow bg-white">
                                <h3>INFORMATION</h3>
                                <table>
                                    <tbody>
                                        <tr>
                                            <td class="grey-text">
                                                <i class="fas fa-ruler"></i>
                                            </td>
                                            <td>UOM</td>
                                            <td>INCHES</td>
                                        </tr>
                                        <tr>
                                            <td><i class="fas fa-weight"></i></td>
                                            <td>WEIGHT</td>
                                            <td>LBS</td>
                                        </tr>
                                        <tr class="sku-description">
                                            <td><i class="fas fa-file-alt"></i></td>
                                            <td colspan="2">Description</td>
                                        </tr>
                                        <tr>
                                            <td class="fz12 break-word" colspan="3">
                                                <?php echo $dataResult['sku_desc']; ?>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="sku-content shadow bg-white">
                                <h3>UNIT DATA</h3>
                                <table>
                                    <tbody>
                                        <tr>
                                            <td><i class="fas fa-ruler"></i></td>
                                            <td>Length</td>
                                            <td><?php echo $dataResult['sku_unit_length']; ?></td>
                                        </tr>
                                        <tr>
                                            <td><i class="fas fa-ruler-horizontal"></i></td>
                                            <td>Width</td>
                                            <td><?php echo $dataResult['sku_unit_width']; ?></td>
                                        </tr>
                                        <tr>
                                            <td><i class="fas fa-ruler-vertical"></i></td>
                                            <td>Height</td>
                                            <td><?php echo $dataResult['sku_unit_height']; ?></td>
                                        </tr>
                                        <tr>
                                            <td><i class="fas fa-weight"></i></td>
                                            <td>Weight</td>
                                            <td><?php echo $dataResult['sku_unit_weight']; ?></td>
                                        </tr>
                                        <tr>
                                            <td><i class="fas fa-cog"></i></td>
                                            <td>Qty Per</td>
                                            <td>1</td>
                                        </tr>
                                    </tbody>
                                </table> 
                            </div>

                            <div class="sku-content shadow bg-white">
                                <h3>CASE DATA</h3>
                                <table>
                                    <tbody>
                                        <tr>
                                            <td><i class="fas fa-ruler"></i></td>
                                            <td>Length</td>
                                            <td><?php echo $dataResult['sku_case_length']; ?></td>
                                        </tr>
                                        <tr>
                                            <td><i class="fas fa-ruler-horizontal"></i></td>
                                            <td>Width</td>
                                            <td><?php echo $dataResult['sku_case_width']; ?></td>
                                        </tr>
                                        <tr>
                                            <td><i class="fas fa-ruler-vertical"></i></td>
                                            <td>Height</td>
                                            <td><?php echo $dataResult['sku_case_height']; ?></td>
                                        </tr>
                                        <tr>
                                            <td><i class="fas fa-weight"></i></td>
                                            <td>Weight</td>
                                            <td><?php echo $dataResult['sku_case_weight']; ?></td>
                                        </tr>
                                        <tr>
                                            <td><i class="fas fa-cog"></i></td>
                                            <td>Qty Per</td>
                                            <td><?php echo $dataResult['sku_case_qty']; ?></td>
                                        </tr>
                                    </tbody>
                                </table> 
                            </div>

                            <div class="sku-content shadow bg-white">
                                <h3>PALLET DATA</h3>
                                <table>
                                    <tbody>
                                        <tr>
                                            <td><i class="fas fa-ruler"></i></td>
                                            <td>Length</td>
                                            <td><?php echo $dataResult['sku_pallet_length']; ?></td>
                                        </tr>
                                        <tr>
                                            <td><i class="fas fa-ruler-horizontal"></i></td>
                                            <td>Width</td>
                                            <td><?php echo $dataResult['sku_pallet_width']; ?></td>
                                        </tr>
                                        <tr>
                                            <td><i class="fas fa-ruler-vertical"></i></td>
                                            <td>Height</td>
                                            <td><?php echo $dataResult['sku_pallet_height']; ?></td>
                                        </tr>
                                        <tr>
                                            <td><i class="fas fa-weight"></i></td>
                                            <td>Weight</td>
                                            <td><?php echo $dataResult['sku_pallet_weight']; ?></td>
                                        </tr>
                                        <tr>
                                            <td><i class="fas fa-cog"></i></td>
                                            <td>Qty Per</td>
                                            <td><?php echo $dataResult['sku_pallet_qty']; ?></td>
                                        </tr>
                                    </tbody>
                                </table> 
                            </div>

                            <?php
                            if($user->accessCheck() == 'ADMIN')
                            {
                            ?>
                            <div class="sku-content shadow bg-white">
                                <h3>USER DATA</h3>
                                <table>
                                    <tbody>
                                        <tr>
                                            <td><i class="fas fa-ruler"></i></td>
                                            <td>Created by</td>
                                            <td><?php echo $dataResult['sku_rec_added']; ?></td>
                                        </tr>
                                        <tr>
                                            <td><i class="fas fa-ruler-horizontal"></i></td>
                                            <td>Created Date</td>
                                            <td><?php echo $createDate; ?></td>
                                        </tr>
                                        <tr>
                                            <td><i class="fas fa-ruler-vertical"></i></td>
                                            <td>Updated By</td>
                                            <td><?php echo $dataResult['sku_rec_update_by']; ?></td>
                                        </tr>
                                        <tr>
                                            <td><i class="fas fa-weight"></i></td>
                                            <td>Updated Date</td>
                                            <td><?php echo $updateDate; ?></td>
                                        </tr>
                                    </tbody>
                                </table> 
                            </div>
                            <?php
                            }
                            ?>
                        </div>
                    </div>
                        
                    <div class="content2">
                        <div class="img-viewer">
                            <?php 
                            if(!empty($imageResult))
                            {
                                ?>
                            <div class="display sku-images flex-start">
                                <div class="grid-wrap250"> 
                                    <?php
                                foreach($imageResult as $image)
                                {
                                    ?>
                                        <figure class="card-responsive bg-white shadow">
                                            <div class="card-img modal-hover">
                                                <a class="hover-change" href="<?php echo $image['sku_image_url']; ?>">
                                                    <img class="article-img" src="<?php echo $image['sku_image_thumb']; ?>" alt="<?php echo $image['sku_image_sku_id'].'-'.$image['sku_image_description']; ?>" />
                                                </a>
                                            </div>
                                        </figure>
                                    <?php
                                }
                                ?>
                                </div>
                            </div>
                            <div class="larger-img shadow bg-white">
                                <img  src="<?php echo $imageResult[0]['sku_image_url']; ?>" alt="<?php echo $imageResult[0]['sku_image_sku_id'].'-'.$imageResult[0]['sku_image_description']; ?>" >
                            </div>
                        <?php 
                            } else
                            {
                                ?>

                            <div></div>
                            <div class="larger-img">
                                <p>No images currently exists for this product. Please click the Request Data Update button to inform us of missing information.</p>
                            </div>
                                <?php
                            }
                            ?>
                        </div>
                    </div>
            <?php
            } else
            {
            ?>
            <section class="form">
                <section class="w600 shadow bg-white">
                    <h2 class="login-title">Not Found</h2>
                    <p>Sorry, nothing was found for SKU "<i class="error"><?php echo $sku; ?></i>"</p>
                    <p>Please try again!</p>
                </section>
            </section>
                <?php
            }
           ?>
        </main>
        <footer>
            <?php include("inc/inc.footer.php"); ?>
        </footer>    
    </div>
</body>
</html>