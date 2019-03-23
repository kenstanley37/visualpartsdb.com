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
        <main class="search-main bg-clr-grey">
            <?php
            if(!empty($dataResult))
            {
                ?>
                    <section class="search-sku-num">
                        <h2><?php echo $dataResult['sku_id']; ?></h2>
                    </section>
                    <section class="sku-part-desc">
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


                        <section class="export-data">
                            <section class="export">
                                <table class="table-nores shadow">
                                    <td>Export Data:</td>
                                    <td><a href="/export/generate-xlsx.php?unit=excel&sku=<?php echo $dataResult['sku_id']; ?>">Excel <i class="far fa-file-excel"></i></a></td>
                                    <!--
                                    <td><a href="search.php?export=pdf&sku=<?php echo $dataResult['sku_id']; ?>">PDF <i class="far fa-file-pdf"></i></a></td>
                                    -->
                                </table>
                            </section>
                            <section class="addtolist">
                                <?php if(isset($_SESSION['user_id'])){ 
                                        if(empty($activelist)){
                                            ?>
                                            <a href="/user/myexportlist.php">Create List <i class="fas fa-plus-circle"></i></a>
                                            <?php
                                        }
                                        else 
                                        {
                                            ?>
                                            <table class="table shadow">
                                                <tbody>
                                                    <tr>
                                                        <td>
                                                            Active List: <a href="/user/mylistcontents.php?list=<?php echo $activelistID; ?>"><?php echo strtoupper($activelist); ?></a>
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

                        <section class="sku-dim-information">
                            <section class="sku-unit-data">
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
                            <section class="sku-case-data">
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

                            <section class="sku-pallet-data">
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
                            <section class="sku-user-data">
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
                                            <td><?php echo $dataResult['sku_rec_date']; ?></td>
                                        </tr>
                                        <tr>
                                            <th>SKU Last Updated</th>
                                            <td><?php echo $dataResult['sku_rec_update_by']; ?></td>
                                        </tr>
                                        <tr>
                                            <th>SKU Updated Date</th>
                                            <td><?php echo $dataResult['sku_rec_update']; ?></td>
                                        </tr>
                                    </tbody>
                                </table> 
                            </section>
                                <?php
                            }

                        ?>
                        </section> <!-- end sku-dim-information --> 
                        <section class="sku-image-data">
                            <h2>Images</h2>
                        </section>
                        <section class="sku-images shadow bg-white">
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
                <?php
            } else
            {
                 ?>
                <article class="search-error center">
                    <h1>Sorry, nothing was found for "<?php echo $sku; ?>"</h1>
                    <p>Please consider these parts:</p>
                    <section id="staticImg">
                        <?php echo $vpd->randImage('10'); ?>
                    </section>
                </article>
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