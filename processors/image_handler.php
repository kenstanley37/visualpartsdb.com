<?php
    include("../inc/inc.path.php");
    require($path.'class/class.visualdb.php');
    require_once($path."class/class.func.php");

    $vpd = new VISUALDB;
    $vail = new VALIDATE;
    
    if(isset($_FILES['file'])){
        $filesize = $_FILES['file']['size'];
        $file = $_FILES['file'];
        $skuId = $_POST['skuId'];
        $desc = $_POST['desc'];
        $desc = $vail->sanitizeString($desc);
        clearstatcache();
        
        if($desc == ''){
            header('location: /search.php?search='.$skuId.'&imageupload=descriptionrequired&file='.$file.'');
            exit;
        }

        //echo $filesize.' '.$skuId.' '.$desc;
        
        $result = $vpd->addImage($skuId, $desc, $_FILES['file']);
        
        if($result)
        {
            header('location: /admin/update-sku.php?sku='.$skuId.'&message=successful#skuimages');    
        } else 
        {
            header('location: /admin/update-sku.php?sku='.$skuId.'&message=notsupported#skuimages');
        }
    }

    if(isset($_POST['deleteimg'])){ 
        $image_sku = $_POST['image_sku'];
        $image_id = $_POST['image_id'];
        $image_url = $_POST['image_url'];
        $image_thumb = $_POST['image_thumb'];
        
        $result = $vpd->remImage($image_id, $image_url, $image_thumb);
        if($result)
        {
            header('location: /admin/update-sku.php?sku='.$image_sku.'&message=successful#skuimages');    
        } else 
        {
            header('location: /admin/update-sku.php?sku='.$image_sku.'&message=error#skuimages');
        }
    }

    if(isset($_POST['imageUpdate'])){ 
        $image_id = $_POST['imageNum'];
        $image_sku = $_POST['imageSku'];
        $image_caption = $vail->sanitizeString($_POST['caption']);

        $result = $vpd->setImageCaption($image_id, $image_caption);
        if($result)
        {
            header("location: /admin/update-sku.php?sku=".$image_sku."&message=imageUpdated#skuimages");
        }
        else
        {
            header("location: /admin/update-sku.php?sku=".$image_sku."&message=imageFailed#skuimages");
        }
        
    }

?>