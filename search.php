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
$activelist = $user->myListReturn('none','name');
$activelistID = $user->myListReturn('none','id');

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
        <main class="main">
            <?php
            if(!empty($dataResult))
            {
                ?>  
                    <section class="title">
                        <section class="display shadow bg-blue">
                            <h2 class="text-white"><?php echo $dataResult['sku_id']; ?></h2>
                        </section>
                    </section>
            
                    <section class="nav">
                        <section class="display bg-white shadow search-user-functions">
                            <h2 class="block-title shadow">User Controls</h2>
                            <section class="export">
                                <section class="display shadow bg-white">
                                    <h2 class="block-title-small shadow">Export</h2>
                                    <table class="table-nores">
                                        <tbody>
                                            <tr>
                                                <td class="shadow">
                                                    <a href="/export/generate-xlsx.php?unit=excel&sku=<?php echo $dataResult['sku_id']; ?>">
                                                        <img src="/assets/msoffice/icons8-microsoft-excel-30.png">
                                                    </a>
                                                </td>
                                                <td class="shadow">
                                                    <a href="/export/generate-xlsx.php?unit=excel&sku=<?php echo $dataResult['sku_id']; ?>">
                                                        <img src="/assets/msoffice/icons8-pdf-30.png">
                                                    </a>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </section>
                            </section>
                            <section class="addtolist">
                                help
                                <section class="display shadow bg-white">
                                    <h2 class="block-title-small shadow">Active List</h2>
                                    
                                <?php if(isset($_SESSION['user_id'])){ 
                                        if(empty($activelist)){
                                            ?>
                                            <a href="/user/myexportlist.php">Create List <i class="fas fa-plus-circle"></i></a>
                                            <?php
                                        }
                                        else 
                                        {
                                            ?>
                                            <table class="table-nores shadow">
                                                <tbody>
                                                    <tr>
                                                        <td>
                                                            <a href="/user/mylistcontents.php?list=<?php echo $activelistID; ?>"><?php echo strtoupper($activelist); ?></a>
                                                        </td>
                                                        <td>
                                                            <form action="/processors/userManagement.php" method="post">
                                                                <input type="text" value="<?php echo $dataResult['sku_id']; ?>" name="skuID" id="skuID" hidden>

                                                                <input type="text" value="<?php echo $activelistID; ?>" name="listID" id="listID" hidden>
                                                                <?php 
                                                                    $skucheck = $user->myListSkuCheck($sku);
                                                                    if($skucheck)
                                                                    {
                                                                        ?>
                                                                        <button class="btn danger" type="submit" name="remSkuFromList">Remove From List</button>
                                                                        <?php
                                                                    } else 
                                                                    {
                                                                        ?>
                                                                        <button class="btn active" type="submit" name="addSkuToList">Add To List</button>
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
                    </section>
            
                    <section class="form">
                        <section class="display shadow bg-white">
                            <h2 class="block-title shadow">Description</h2>
                            <table class="table shadow">
                                <thead>
                                    <tr>
                                        <th>Part #</th>
                                        <th><?php echo $dataResult['sku_id']; ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <th>Description</th>
                                        <td><?php echo $dataResult['sku_desc']; ?></td>
                                    </tr>
                                    <tr>
                                        <th>Dimension UOM</th>
                                        <td>Inches</td>
                                    </tr>
                                    <tr>
                                        <th>Weight UOM</th>
                                        <td>Pounds</td>
                                    </tr>
                                     <?php
                                        if(isset($_SESSION['user_id']))
                                        {
                                            ?>
                                    <tr>
                                        <th>
                                            Request Data Update 
                                        </th>
                                        <?php 
                                            if($user->requestUpdateCheck($sku))
                                            {
                                                ?>
                                                <td>
                                                    <button class="btn active" type="submit" disabled>Requested</button>
                                                </td>
                                                <?php
                                            } else
                                            {
                                                ?>
                                                <td>
                                                    <form method="post" action="/processors/userManagement.php">
                                                        <input type="text" name="skuID" value="<?php echo $dataResult['sku_id']; ?>" hidden>
                                                        <button class="btn info" type="submit" name="requestUpdate">Request</button>
                                                    </form>
                                                </td>
                                                <?php
                                            }
                                        ?>
                                    </tr>
                                     <?php
                                        }
                                    ?>
                                    </tbody>                                    
                                </table> 
                            </section>
                        </section>

                        <section class="content">
                            <section class="sku-data">
                                <section class="display shadow bg-white">
                                    <h2 class="block-title shadow">Unit Data</h2>
                                    <table class="table shadow">
                                        <thead>
                                            <tr>
                                                <th colspan="2">Unit Data</th> 
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <th>Length</th>
                                                <td><?php echo $dataResult['sku_unit_length']; ?></td>
                                            </tr>
                                            <tr>
                                                <th>Width</th>
                                                <td><?php echo $dataResult['sku_unit_width']; ?></td>
                                            </tr>
                                            <tr>
                                                <th>Height</th>
                                                <td><?php echo $dataResult['sku_unit_height']; ?></td>
                                            </tr>
                                            <tr>
                                                <th>Weight</th>
                                                <td><?php echo $dataResult['sku_unit_weight']; ?></td>
                                            </tr>
                                            <tr>
                                                <th>Qty Per</th>
                                                <td>1</td>
                                            </tr>
                                        </tbody>
                                    </table> 
                                </section>
                            <?php
                                if(!empty($user->accessCheck()))
                                {
                            ?>
                                <section class="display shadow bg-white">
                                    <h2 class="block-title shadow">Case Data</h2>
                                    <table class="table shadow">
                                        <thead>
                                            <tr>
                                                <th colspan="2">Case Data</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <th>Length</th>
                                                <td><?php echo $dataResult['sku_case_length']; ?></td>
                                            </tr>
                                            <tr>
                                                <th>Width</th>
                                                <td><?php echo $dataResult['sku_case_width']; ?></td>
                                            </tr>
                                            <tr>
                                                <th>Height</th>
                                                <td><?php echo $dataResult['sku_case_height']; ?></td>
                                            </tr>
                                            <tr>
                                                <th>Weight</th>
                                                <td><?php echo $dataResult['sku_case_weight']; ?></td>
                                            </tr>
                                            <tr>
                                                <th>Qty Per</th>
                                                <td><?php echo $dataResult['sku_case_qty']; ?></td>
                                            </tr>
                                        </tbody>
                                    </table> 
                                </section>

                                <section class="display shadow bg-white">
                                    <h2 class="block-title shadow">Pallet Data</h2>
                                    <table class="table shadow">
                                        <thead>
                                            <tr>
                                                <th colspan="2">Pallet Data</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <th>Length</th>
                                                <td><?php echo $dataResult['sku_pallet_length']; ?></td>
                                            </tr>
                                            <tr>
                                                <th>Width</th>
                                                <td><?php echo $dataResult['sku_pallet_width']; ?></td>
                                            </tr>
                                            <tr>
                                                <th>Height</th>
                                                <td><?php echo $dataResult['sku_pallet_height']; ?></td>
                                            </tr>
                                            <tr>
                                                <th>Weight</th>
                                                <td><?php echo $dataResult['sku_pallet_weight']; ?></td>
                                            </tr>
                                            <tr>
                                                <th>Qty Per</th>
                                                <td><?php echo $dataResult['sku_pallet_qty']; ?></td>
                                            </tr>
                                        </tbody>
                                    </table> 
                                </section>

                                <section class="display shadow bg-white">
                                    <h2 class="block-title shadow">User Data</h2>
                                    <table class="table shadow">
                                        <thead>
                                            <tr>
                                                <th colspan="2">User Data</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <th>SKU created by</th>
                                                <td><?php echo $dataResult['sku_rec_added']; ?></td>
                                            </tr>
                                            <tr>
                                                <th>SKU created Date</th>
                                                <td><?php echo $createDate; ?></td>
                                            </tr>
                                            <tr>
                                                <th>SKU Last Updated</th>
                                                <td><?php echo $dataResult['sku_rec_update_by']; ?></td>
                                            </tr>
                                            <tr>
                                                <th>SKU Updated Date</th>
                                                <td><?php echo $updateDate; ?></td>
                                            </tr>
                                        </tbody>
                                    </table> 
                                </section>
                                    <?php
                                }
                            ?>
                                </section>
                        </section> <!-- end content --> 
                        
                        <section class="content2">
                            <section class="display shadow bg-white">
                                <h2 class="block-title shadow">Images</h2>
                                <section class="grid-wrap250">
                                    <?php 
                                    if(!empty($imageResult))
                                    {
                                        foreach($imageResult as $image){
                                            ?>
                                        <figure class="card bg-white shadow">
                                            <div class="card-img">
                                                <a href="<?php echo $image['sku_image_url']; ?>">
                                                    <img class="article-img" src="<?php echo $image['sku_image_thumb']; ?>" alt="<?php echo $image['sku_image_sku_id'].'-'.$image['sku_image_description']; ?>" />
                                                </a>
                                            </div>
                                            <figcaption>
                                                <div class="card-sku-num">
                                                    <p><?php echo $image['sku_image_description'];?></p>
                                                </div>
                                            </figcaption>
                                        </figure>
                                            <?php
                                        } 
                                    } else
                                    {
                                        ?>
                                            <p>No images currently exists for this product. Please click the Request Data Update button to inform us of missing information.</p>
                                        <?php
                                    }
                                    ?>
                                </section>
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