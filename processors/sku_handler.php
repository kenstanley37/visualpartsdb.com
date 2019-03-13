<?php
    session_start();
    include("../inc/inc.path.php");
    require_once($path.'class/class.visualdb.php');
    require_once($path."class/class.func.php");
    require_once($path."class/class.user.php");

    $vpd= new VISUALDB;
    $vail = new VALIDATE;
    $user = new USER;
    

    // Update SKU Data
    if(isset($_POST['skuUpdate']))
    {
        $sku = $_POST['sku'];
        $sku_desc = $vail->sanitizeString($_POST['desc']);
            
        $unit_length = $_POST['unit-length'];
        $unit_width = $_POST['unit-width'];
        $unit_height = $_POST['unit-height'];
        $unit_weight = $_POST['unit-weight'];
        
        $case_length = $_POST['case-length'];
        $case_width = $_POST['case-width'];
        $case_height = $_POST['case-height'];
        $case_weight = $_POST['case-weight'];
        $case_qty = $_POST['case-qty'];
        
        $pallet_length = $_POST['pallet-length'];
        $pallet_width = $_POST['pallet-width'];
        $pallet_height = $_POST['pallet-height'];
        $pallet_weight= $_POST['pallet-weight'];
        $pallet_qty = $_POST['pallet-qty'];
        
        $result = $vpd->setSkuData($sku, $sku_desc, $unit_length, $unit_width, $unit_height, $unit_weight, $case_length, $case_width, $case_height, $case_weight, $case_qty, $pallet_length, $pallet_width, $pallet_height, $pallet_weight, $pallet_qty);
        
        echo $sku_desc;

        
        if($result == 'true')
        {
            header("location: /admin/update-sku.php?sku=".$sku."&submit=successful");
        } else
        {
            header("location: /admin/update-sku.php?sku=".$sku."&submit=failed");
        }
        
    }

    
?>