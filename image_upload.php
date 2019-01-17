<?php
    include("inc/inc.path.php");
    require($path.'class/class.visualdb.php');
    require_once($path."class/class.func.php");

    $imageUpload = new VISUALDB;
    $vail = new VALIDATE;
    
    if(isset($_FILES['file'])){
        $filesize = $_FILES['file']['size'];
        $file = $_FILES['file'];
        $skuId = $_POST['skuId'];
        $desc = $_POST['desc'];
        $desc = $vail->sanitizeString($desc);
        clearstatcache();
        
        if($filesize != 0){
            header('location: /search.php?search='.$skuId.'&imageupload=missingfile');
            exit;
        } elseif($desc == ''){
            header('location: /search.php?search='.$skuId.'&imageupload=descriptionrequired&file='.$file.'');
            exit;
        }
            
        
        $result = $imageUpload->addImage($skuId, $desc, $_FILES['file']);
        
        if($result)
        {
            header('location: /search.php?search='.$skuId.'&imageupload=successful');    
        } else 
        {
            header('location: /search.php?search='.$skuId.'&imageupload=notsupported');
        }
    }

?>