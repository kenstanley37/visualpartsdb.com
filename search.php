<?php
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

if(isset($_GET['search']))
{
    $sku = $_GET['search'];
    $sku = $vail->sanitizeString($sku);
    $sku = strtoupper($sku);
    $dataResult = $vpd->skuSearchData($sku);
    $imageResult = $vpd->skuSearchImage($sku);
    
    $createDate = $dataResult['sku_rec_date'];
    $createDate = date_create($createDate);
    $createDate = date_format($createDate, 'm/d/Y');
    
    $updateDate = $dataResult['sku_rec_update'];
    $updateDate = date_create($updateDate);
    $updateDate = date_format($updateDate, 'm/d/Y');
}

if(isset($_POST['imageSubmit']))
{
    $image = $_POST['fileToUpload'];
    $sku = $_POST['skuId'];
    $sku = $vail->sanitizeString($sku);
    $sku = strtoupper($sku);
    $result = $vpd->addImage($sku, $image);
}

if(isset($_GET['imageupload'])){
    if($_GET['imageupload'] == 'descriptionrequired') {
        $vpd->imageMessage = 'Please enter a description';
    }
    
    if($_GET['imageupload'] == 'notsupported') {
        $vpd->imageMessage = 'Only GIF, JPEG, and PNG are supported';
    }
}

if(isset($_GET['export'])){
    $sku = $_GET['sku'];
    $type = $_GET['export'];
    
    $sku = $vail->sanitizeString($sku);
    $type = $vail->sanitizeString($type);
    $result = $vpd->exportData($sku, $type);
    
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Visual Parts Database: <?php if(isset($sku)){echo strtoupper($sku);} else {echo "Search";} ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
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
        <main class="main-search">
            <?php
            if(!empty($dataResult))
            {
                ?>  
                    <section class="title">
                        <section class="search-title">
                            <h2 class="blue-header">PART: <?php echo $dataResult['sku_id']; ?></h2>
                        </section>
                    </section>
            
                    <section class="nav search-nav">
                        <section class="search-user-functions">
                            <section class="contain1 just-left">
                                <table class="table-nores">
                                    <tbody>
                                        <tr>
                                            <td class="nav-btn">
                                                <form action="/export/generate-xlsx.php" method="get">
                                                    <input type="text" value="excel" name="unit" hidden>
                                                   <input type="text" value="<?php echo $dataResult['sku_id']; ?>" name="sku" hidden>
                                                    <button type="submit" name="submit">EXCEL</button>
                                                </form>

                                            </td>
                                            <!--
                                            <td>
                                                <a class="btn" href="/export/generate-xlsx.php?unit=excel&sku=<?php echo $dataResult['sku_id']; ?>">
                                                    <img src="/assets/msoffice/icons8-pdf-30.png" alt="PDF Export">
                                                </a>
                                            </td>
                                            -->
                                            <td class="nav-btn">
                                                 <?php
                                                    if(isset($_SESSION['user_id']))
                                                    {

                                                        if($user->requestUpdateCheck($sku))
                                                        {
                                                            ?>
                                                        <button class="active" disabled>Update Requested</button>
                                                            <?php
                                                        } else
                                                        {
                                                            ?>

                                                                <form method="post" action="/processors/userManagement.php">
                                                                    <input type="text" name="skuID" value="<?php echo $dataResult['sku_id']; ?>" hidden>
                                                                    <button class="nav-btn " type="submit" name="requestUpdate">Request SKU Update</button>
                                                                </form>

                                                            <?php
                                                        }
                                                    }
                                                ?>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </section>
                            <section class="contain2 just-right">
                                <?php if(isset($_SESSION['user_id'])){ 
                                        if(empty($activelist)){
                                            ?>
                                        <table class="table-nores">
                                            <tr>
                                                <td class="nav-btn">
                                                    <form action="/user/myexportlist.php">
                                                        <button type="submit" name="submit">
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
                                                                <button type="submit" name="submit">Active List: <?php echo strtoupper($activelist); ?> </button>
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
                                                                        <button class="" type="submit" name="remSkuFromList">Remove From List</button>
                                                                        <?php
                                                                    } else 
                                                                    {
                                                                        ?>
                                                                        <button class="nav-btn" type="submit" name="addSkuToList">Add To List</button>
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
                                </section>
                        </section>
                    </section>
            
                    <section class="form">
                        
                    </section>

                    <section class="content pad-bot-50">
                        <section class="display">
                            <section class="grid-wrap250 sku-data">
                                <div class="sku-content shadow bg-white">
                                    <h3>INFORMATION</h3>
                                    <table>
                                        <tbody>
                                            <tr>
                                                <td class="grey-text">
                                                    <i class="fas fa-ruler"></i>
                                                </td>
                                                <td class="grey-text">
                                                    UOM
                                                </td>
                                                <td class="grey-text">
                                                    INCHES
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="grey-text"><i class="fas fa-weight"></i></td>
                                                <td class="grey-text">WEIGHT</td>
                                                <td class="grey-text">LBS</td>
                                            </tr>
                                            <tr class="sku-description">
                                                <td><i class="fas fa-file-alt"></i></td>
                                                <td colspan="2">Description</td>
                                            </tr>
                                            <tr>
                                                <td colspan="3">
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
                            </section>
                        </section>
                    </section>
                        
                        <section class="content2">
                            <section class="img-viewer">
                                
                                    <!--
                                    <h2 class="center-title up-25 pad-bot-50 blue-header">IMAGES</h2> --> 
                                    
                                <?php 
                                if(!empty($imageResult))
                                {
                                    ?>
                                <section class="display sku-images flex-start">
                                    <section class="grid-wrap250">
                                        <?php
                                    foreach($imageResult as $image)
                                    {
                                        ?>
                                            <figure class="card-responsive bg-white shadow">
                                                <div  class="card-img modal-hover">
                                                    <a href="<?php echo $image['sku_image_url']; ?>">
                                                        <img class="article-img" src="<?php echo $image['sku_image_thumb']; ?>" alt="<?php echo $image['sku_image_sku_id'].'-'.$image['sku_image_description']; ?>" />
                                                    </a>
                                                </div>
                                            </figure>
                                        <?php
                                    }
                                    ?>
                                    </section>
                                </section>
                                <section class="larger-img">
                                    <img class="shadow" src="<?php echo $imageResult[0]['sku_image_url']; ?>" alt="<?php echo $imageResult[0]['sku_image_sku_id'].'-'.$imageResult[0]['sku_image_description']; ?>" >
                                </section>
                            <?php 
                                } else
                                {
                                    ?>

                                <section></section>
                                <section class="larger-img">
                                    <p>No images currently exists for this product. Please click the Request Data Update button to inform us of missing information.</p>
                                </section>
                                            <?php
                                        }
                                        ?>
                            </section>
                            
                        </section>
                        
            <?php
            } else
            {
            ?>
            <section class="nav">
                <section class="display shadow bg-white">
                    <h2 class="block-title shadow">Not Found</h2>
                    <section class="not-found">
                        <p>Sorry, nothing was found for SKU "<i class="error"><?php echo $sku; ?></i>"</p>
                        <p>Please try again!</p>
                    </section>
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