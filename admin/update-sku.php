<?php
/**
* VIEW for updating SKU information
*
* @author Ken Stanley <ken@stanleysoft.org>
* @license MIT
*/
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
    if($user->accessCheck() != 'ADMIN'){
        header('location: /noaccess.php');
    }
}

if(isset($_GET['sku']))
{
    $sku = $_GET['sku'];
    $sku = strtoupper($sku);
    if(isset($_GET['submit']))
    {
        $result = $_GET['submit'];
        if($result == 'successful')
        $message = 'Record was updated successfully';
    } 
}
else 
{
    header('location: /admin/part-management.php');
}

$skuData = $vpd->getSkuData($sku);
$skuImage = $vpd->getSkuImage($sku);
$count = 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Visual Parts Database: Update SKU: <?php echo $sku; ?></title>
    <?php require_once($path."inc/inc.head.php"); ?> <!-- META, CSS, and JavaScript -->
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
                <h2 class="blue-header">Modify SKU</h2>
            </section>
            <div class="form">
                <section class="sku-edit">
                    <h3 class="login-title">SKU# <?php echo $sku; ?></h3>
                    <form id="UpdateSkuForm" method="post" action="/processors/sku_handler.php">
                    <input type="text" name="sku" value="<?php echo $sku; ?>" hidden>
                    <div class="grid-wrap250">
                        <table class="table-nores shadow edit-table">
                            <thead>
                                <tr>
                                    <th data-label="Description" class="tb1-color">
                                        Description
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="desc">
                                        <textarea name="desc">
                                            <?php echo $skuData['sku_desc']; ?>
                                        </textarea>
                                    </td>
                                </tr>
                        </table>

                        <table class="table-nores shadow edit-table">
                            <thead>
                                <tr>
                                    <th colspan="2" data-label="UNIT" class="tb1-color">UNIT</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td data-label="Unit">
                                        <label for="unit-length">Length</label>
                                    </td>
                                    <td>
                                        <input type="number" name="unit-length" id="unit-length" min="0" step="0.01" value="<?php echo $skuData['sku_unit_length']; ?>">
                                    </td>
                                </tr>
                                <tr>
                                    <td data-label="Unit">
                                        <label for="unit-width">Width</label>
                                    </td>
                                    <td>
                                        <input type="number" name="unit-width" id="unit-width" min="0" step="0.01" value="<?php echo $skuData['sku_unit_width']; ?>">
                                    </td>
                                </tr>
                                <tr>
                                    <td data-label="Unit">
                                        <label for="unit-height">Height</label>
                                    </td>
                                    <td> 
                                        <input type="number" name="unit-height" id="unit-height" min="0" step="0.01" value="<?php echo $skuData['sku_unit_height']; ?>">
                                    </td>
                                </tr>
                                <tr>
                                    <td data-label="Unit">
                                        <label for="unit-weight">Weight</label>
                                    </td>
                                    <td>
                                        <input type="number" name="unit-weight" id="unit-weight" min="0" step="0.01" value="<?php echo $skuData['sku_unit_weight']; ?>">
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        <table class="table-nores shadow edit-table">
                            <thead>
                                 <tr>
                                    <th colspan="2" data-label="CASE" class="tb1-color">CASE</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td data-label="Case">
                                        <label for="case-length">Length</label>
                                    </td>
                                    <td>
                                        <input type="number" name="case-length" id="case-length" min="0" step="0.01" value="<?php echo $skuData['sku_case_length']; ?>">
                                    </td>
                                </tr>
                                <tr>
                                    <td data-label="Case">
                                        <label for="case-width">Width</label>
                                    </td>
                                    <td>
                                        <input type="number" name="case-width" id="case-width" min="0" step="0.01" value="<?php echo $skuData['sku_case_width']; ?>">
                                    </td>
                                </tr>
                                <tr>
                                    <td data-label="Case">
                                        <label for="case-height">Height</label>
                                    </td>
                                    <td> 
                                        <input type="number" name="case-height" id="case-height" min="0" step="0.01" value="<?php echo $skuData['sku_case_height']; ?>">
                                    </td>
                                </tr>
                                <tr>
                                    <td data-label="Case">
                                        <label for="case-weight">Weight</label>
                                    </td>
                                    <td>
                                        <input type="number" name="case-weight" id="case-weight" min="0" step="0.01" value="<?php echo $skuData['sku_case_weight']; ?>">
                                    </td>
                                </tr>
                                <tr>
                                    <td data-label="Case">
                                        <label for="case-qty">Quantity</label>
                                    </td>
                                    <td>
                                        <input type="number" name="case-qty" id="case-qty" min="0" step="0.01" value="<?php echo $skuData['sku_case_qty']; ?>">
                                    </td>
                                </tr>
                            </tbody>
                        </table>    

                        <table class="table-nores shadow edit-table">
                            <thead>
                                <tr>
                                    <th colspan="2" data-label="PALLET" class="tb1-color">PALLET</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td data-label="Pallet">
                                        <label for="pallet-length">Length</label>
                                    </td>
                                    <td>
                                        <input type="number" name="pallet-length" id="pallet-length" min="0" step="0.01" value="<?php echo $skuData['sku_pallet_length']; ?>">
                                    </td>
                                </tr>
                                <tr>
                                    <td data-label="Pallet">
                                        <label for="pallet-width">Width</label>
                                    </td>
                                    <td>
                                        <input type="number" name="pallet-width" id="pallet-width" min="0" step="0.01" value="<?php echo $skuData['sku_pallet_width']; ?>">
                                    </td>
                                </tr>
                                <tr>
                                    <td data-label="Pallet">
                                        <label for="pallet-height">Height</label>
                                    </td>
                                    <td> 
                                        <input type="number" name="pallet-height" id="pallet-height" min="0" step="0.01" value="<?php echo $skuData['sku_pallet_height']; ?>">
                                    </td>
                                </tr>
                                <tr>
                                    <td data-label="Pallet">
                                        <label for="pallet-weight">Weight</label>
                                    </td>
                                    <td>
                                        <input type="number" name="pallet-weight" id="pallet-weight" min="0" step="0.01" value="<?php echo $skuData['sku_pallet_weight']; ?>">
                                    </td>
                                </tr>
                                <tr>
                                    <td data-label="Pallet">
                                        <label for="pallet-qty">Quantity</label>
                                    </td>
                                    <td>
                                        <input type="number" name="pallet-qty" id="pallet-qty" min="0" step="0.01" value="<?php echo $skuData['sku_pallet_qty']; ?>">
                                    </td>
                                </tr>
                            </tbody>
                        </table>                       
                    </div> <!-- end Case Data -->
                        <button class="btn info" type="submit" name="skuUpdate" form="UpdateSkuForm">Submit</button>
                        <?php 
                            if(isset($message))
                            {
                                echo '<span class="error">'.$message.'</span>';  
                            }
                        ?>
                    </form>
                </section>
            </div>

            <div class="content">
                <section class=" w600 form-contact shadow">
                    <h2 class="login-title">Images</h2>
                    <form action="/processors/image_handler.php" method="post" enctype="multipart/form-data">
                        Select image to upload:
                        <input type="file" name="file" id="file" required>
                        <input type="text" name="desc" id="desc" placeholder="Caption" required>
                        <input type="hidden" id="skuId" name="skuId" value="<?php echo $skuData['sku_id']; ?>">
                        <input class="btn-blue" type="submit" value="Upload Image" name="imageSubmit">
                    </form>
                    <span class="imagemessage"><?php echo $vpd->imageMessage; ?></span>
                </section>
            </div>
            
            <div class="content2">
                <div class="grid-wrap250 edit-img">
                    <?php 
                   foreach($skuImage as $row)
                   {
                       $count++;
                        ?>
                    <figure class="card bg-white shadow">
                        <div class="card-img">
                            <a href="<?php echo $row['sku_image_url']; ?>">
                                <img class="article-img" src="<?php echo $row['sku_image_thumb']; ?>" alt="<?php echo $row['sku_image_sku_id'].'-'.$row['sku_image_description']; ?>" />
                            </a>
                        </div>
                        <figcaption>
                            <div class="card-sku-num">
                                <table class="table-nores">
                                    <tbody>
                                        <tr>
                                            <td colspan="2" >
                                                <label for="caption">Caption:</label>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2">
                                                <form id="imageUpdate<?php echo $count; ?>" action="/processors/image_handler.php" method="post">
                                                    <input type="text" name="imageSku" value="<?php echo $row['sku_image_sku_id']; ?>" hidden>
                                                    <input type="text" name="imageNum" value="<?php echo $row['sku_image_id']; ?>" hidden>
                                                    <input type="text" name="caption" value="<?php echo $row['sku_image_description'];?>">
                                                </form>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <form method="post" action="/processors/image_handler.php">
                                                    <input type="text" value="<?php echo $row['sku_image_sku_id']; ?>" name="image_sku" hidden>
                                                    <input type="text" value="<?php echo $row['sku_image_id']; ?>" name="image_id" hidden>
                                                    <input type="text" value="<?php echo $row['sku_image_url']; ?>" name="image_url" hidden>
                                                    <input type="text" value="<?php echo $row['sku_image_thumb']; ?>" name="image_thumb" hidden>
                                                    <button class="btn danger" type="submit" name="deleteimg">Delete</button>
                                                </form> 
                                            </td>
                                            <td class="align-right">
                                                <button type="submit" form="imageUpdate<?php echo $count; ?>" class="btn active" name="imageUpdate">Update</button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </figcaption>
                    </figure>
                        <?php
                    }
                    ?>
                </div>
            </div>
        </main>
        <footer>
            <?php include($path."/inc/inc.footer.php"); ?>
        </footer>
    </div> <!-- end container -->
</body>
</html>