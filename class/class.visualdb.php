<?php
    require_once('class.db.php');

    class VISUALDB
    {
        private $conn;
        public $imageMessage;

        // *************************************************************
        // Constructor to connect to the database
        // *************************************************************
        public function __construct()
        {
            $this->imageMessage = '';
            
            try 
            {
                $database = new Database();
                $db = $database->dbConnection();
                $this->conn = $db;
                $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Sets the error mode
            } catch (PDOException $e)
            {
                echo "Connection error: " . $e->getMessage(); // return error message
            }
            
            require_once('class.user.php');
        } // end construct
        
        // *************************************************************
        // Destruct - end database connection
        // *************************************************************
        public function __destruct()
        {
            // close connection to the DB
            $this->conn = null;
        } // end destruct
        
        
        // *************************************************************
        // Usage: imageMessage($message);
        // sets success or error message of image upload
        // *************************************************************
        public function imageMessage($message)
        {
            $this->imageMessage = $message;
        }
        
        
        // *************************************************************
        // Usage: skuSearchData($sku);
        // searches the database for requested sku and returns information
        // @param $sku = the sku id
        // *************************************************************
        public function skuSearchData($sku)
        {
            if(isset($_SESSION['user_id']))
            {
                $userID = $_SESSION['user_id'];
            } else {
                $userID = '';
            }
            
            $sku = strtoupper($sku);
            $user = new USER;

            try
            {
                $stmt = $this->conn->prepare("SELECT * FROM sku 
                    WHERE sku_id=:sku");
                $stmt->bindparam(":sku", $sku);
                $stmt->execute();
                $skuRow = $stmt->fetch();

                if($stmt->rowCount() == 1)
                {
                    // lets update the search ticker for this sku
                    try {
                        $stmt = $this->conn->prepare("INSERT INTO sku_search (sku_search_sku, sku_search_by) VALUES (:sku_search_id, :sku_search_by)");
                        $stmt->bindparam(":sku_search_id", $sku);
                        $stmt->bindparam(":sku_search_by", $userID);
                        $stmt->execute();
                    }
                    catch(PDOException $e)
                    {
                        echo $e->getMessage();
                    }	
                }
                return $skuRow;
            }
            catch(PDOException $e)
            {
                echo $e->getMessage();
            }
        } // end skuSearchData
        

        // *************************************************************
        // Usage: skuSearchImage($sku);
        // searches the database for requested sku and returns information
        // @param $sku = the sku id
        // *************************************************************
        public function skuSearchImage($sku)
        {
            
            if(isset($_SESSION['user_id']))
            {
                $userID = $_SESSION['user_id'];
            } else {
                $userID = '';
            }
            $sku = strtoupper($sku);
            try
            {
                $stmt = $this->conn->prepare("SELECT * FROM sku_image 
                WHERE sku_image_sku_id=:sku");
                $stmt->bindparam(":sku", $sku);
                $stmt->execute();
                $result = array(array());
                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                return $result;
            }
            catch(PDOException $e)
            {
                echo $e->getMessage();
            }
        } // end skuSearchData
        
        // *************************************************************
        // Usage: getSkuImage($sku);
        // Returns image links for $sku
        // *************************************************************
        public function getSkuImage($sku)
        {
            try
            {
                $stmt = $this->conn->prepare("SELECT * FROM sku_image 
                WHERE sku_image_sku_id=:sku");
                $stmt->bindparam(":sku", $sku);
                $stmt->execute();
                $result = array(array());
                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                return $result;
            }
            
            catch(PDOException $e)
            {
                echo $e->getMessage();
            }
            
            
        }
        
        // *************************************************************
        // Usage: setImageCaption($imageID, $caption);
        // Updates the image caption
        // *************************************************************
        public function setImageCaption($imageID, $caption)
        {
            try
            {
                $stmt = $this->conn->prepare("UPDATE sku_image SET sku_image_description = :caption
                WHERE sku_image_id = :image_id");
                $stmt->bindparam(":image_id", $imageID);
                $stmt->bindparam(":caption", $caption);
                $stmt->execute();
                return true;
            }
            catch(PDOException $e)
            {
                echo $e->getMessage();
            }
        }
        
        // *************************************************************
        // Usage: addImage(sku);
        // Adds a new image to current sku
        // *************************************************************
        public function addImage($sku, $desc, $image)
        {
            $sku = strtoupper($sku);  // ensure sku is upper case
            $desc = strtoupper($desc);
            $_supportedFormats = ['image/png','image/jpeg','image/gif'];
            $uploadPath = '../images/'.$sku.'/';
            $exif = exif_read_data($image['tmp_name']);
            
            if (!empty($exif['Orientation'])) {
                switch ($exif['Orientation']) {
                case 3:
                    $angle = 180 ;
                    break;

                case 6:
                    $angle = -90;
                    break;

                case 8:
                    $angle = 90; 
                    break;
                default:
                    $angle = 0;
                    break;
                } 
            }  else {
                    $angle = 0;
                }  
            
            if (is_array($image))
            {
               if (in_array($image['type'],$_supportedFormats))
               {
                   if(!is_dir($uploadPath)){
                        //Directory does not exist, so lets create it.
                        mkdir($uploadPath, 0755, true);
                    }
                    $fileName = $image['tmp_name']; 
                    // get the dims of the image
                    $sourceProperties = getimagesize($fileName);
                    // add the time to the image name for a unique file name
                    $resizeFileName = time();
                    // get the image extension name
                    $fileExt = pathinfo($image['tmp_name'], PATHINFO_EXTENSION);
                    // get image propertities
                    $uploadImageType = $sourceProperties[2];
                    $sourceImageWidth = $sourceProperties[0];
                    $sourceImageHeight = $sourceProperties[1];
                    switch ($uploadImageType) {
                        case IMAGETYPE_JPEG:
                            // create a image in memory
                            $resourceType = imagecreatefromjpeg($fileName); 
                            // resize the image
                            $imageLayerFull = $this->resizeImageFull($resourceType,$sourceImageWidth,$sourceImageHeight);
                            // make a thumbnail of the image
                            $imageLayerThumb = $this->resizeImageThumb($resourceType,$sourceImageWidth,$sourceImageHeight);
                            // rotate the image back to the orignal
                            $imageLayerFull = imagerotate($imageLayerFull, $angle, 0);
                            // rotate the thumb back to the orignal
                            $imageLayerThumb = imagerotate($imageLayerThumb, $angle, 0);
                            // save new image to disk
                            imagejpeg($imageLayerFull,$uploadPath.$sku.'-'.$resizeFileName.'_full.jpeg');
                            // save new thumb to disk
                            imagejpeg($imageLayerThumb,$uploadPath.$sku.'-'.$resizeFileName.'_thumb.jpeg');
                            // add image to database
                            $this->AddImageUrl($sku, '/'.$uploadPath.$sku.'-'.$resizeFileName.'_full.jpeg', '/'.$uploadPath.$sku.'-'.$resizeFileName.'_thumb.jpeg', $desc);
                            break;

                        case IMAGETYPE_GIF:
                            $resourceType = imagecreatefromgif($fileName); 
                            $imageLayerFull = $this->resizeImageFull($resourceType,$sourceImageWidth,$sourceImageHeight);
                            $imageLayerThumb = $this->resizeImageThumb($resourceType,$sourceImageWidth,$sourceImageHeight);
                            $imageLayerFull = imagerotate($imageLayerFull, $angle, 0);
                            $imageLayerThumb = imagerotate($imageLayerThumb, $angle, 0);
                            imagegif($imageLayerFull,$uploadPath.$sku."full_".$resizeFileName.'.gif');
                            imagegif($imageLayerThumb,$uploadPath.$sku."thumb_".$resizeFileName.'.gif');
                            break;

                        case IMAGETYPE_PNG:
                            $resourceType = imagecreatefrompng($fileName); 
                            $imageLayerFull = $this->resizeImageFull($resourceType,$sourceImageWidth,$sourceImageHeight);
                            $imageLayerThumb = $this->resizeImageThumb($resourceType,$sourceImageWidth,$sourceImageHeight);
                            $imageLayerFull = imagerotate($imageLayerFull, $angle, 0);
                            $imageLayerThumb = imagerotate($imageLayerThumb, $angle, 0);
                            imagepng($imageLayerFull,$uploadPath.$sku."full_".$resizeFileName.'.png');
                            imagepng($imageLayerThumb,$uploadPath.$sku."thumb_".$resizeFileName.'.png');
                            break;

                        default:
                            $imageProcess = 0;
                            break;
                    }
                   return 1;
               } else 
               {
                   return 0;
               }
            } else 
            {
                return 0;
            }
        } // end addImage
        
        
        // *************************************************************
        // Usage: remImage(sku);
        // Removes image from database and harddrive
        // *************************************************************
        public function remImage($image_id, $image_url, $image_thumb)
        {

            try 
            {
                unlink('..'.$image_url);
                unlink('..'.$image_thumb);
                $stmt = $this->conn->prepare("DELETE from sku_image where sku_image_id = :image_id");
                $stmt->bindparam(":image_id", $image_id);
                $stmt->execute();
            }
            catch(PDOException $e)
            {
                echo $e->getMessage();
            }	
        } // end remImage
        
        // *************************************************************
        // Usage: resizeImageThumb($resourceType,$image_width,$image_height)
        // creates a thumbnail of an image
        // *************************************************************
        function resizeImageThumb($resourceType,$image_width,$image_height) {
            $w = $image_width;
            $h = $image_height;
            $max_width = 300;
            $max_height = 300;
            //try max width first...
            
            //if (($w <= $max_width) && ($h <= $max_height)) { return $image; } //no resizing needed
            
            //try max width first...
            $ratio = $max_width / $w;
            $new_w = $max_width;
            $new_h = $h * $ratio;

            //if that didn't work
            if ($new_h > $max_height) {
                $ratio = $max_height / $h;
                $new_h = $max_height;
                $new_w = $w * $ratio;
            }
            
            $imageLayer = imagecreatetruecolor($new_w,$new_h);
            imagecopyresampled($imageLayer,$resourceType,0,0,0,0, $new_w, $new_h, $image_width,$image_height);
            return $imageLayer;
        }
        
        // *************************************************************
        // Usage: resizeImageFull($resourceType,$image_width,$image_height)
        // Resizes the image to 800x600
        // *************************************************************
        function resizeImageFull($resourceType,$image_width,$image_height) {
            $w = $image_width;
            $h = $image_height;
            $max_width = 800;
            $max_height = 600;
            //try max width first...
            
            //if (($w <= $max_width) && ($h <= $max_height)) { return $image; } //no resizing needed
            
            //try max width first...
            $ratio = $max_width / $w;
            $new_w = $max_width;
            $new_h = $h * $ratio;

            //if that didn't work
            if ($new_h > $max_height) {
                $ratio = $max_height / $h;
                $new_h = $max_height;
                $new_w = $w * $ratio;
            }
            
            $imageLayer = imagecreatetruecolor($new_w,$new_h);
            imagecopyresampled($imageLayer,$resourceType,0,0,0,0, $new_w, $new_h, $image_width,$image_height);
            return $imageLayer;
        }
        
        // *************************************************************
        // Usage: AddImageUrl($sku, $url, $thumb, $desc)
        // Adds image to database from the addImage function
        // *************************************************************
        public function AddImageUrl($sku, $url, $thumb, $desc) {
            session_start();
            $userName = $_SESSION['fname'].' '.$_SESSION['lname'];
            try {
                $stmt = $this->conn->prepare("INSERT INTO sku_image (sku_image_sku_id, sku_image_url, sku_image_thumb, sku_image_description, sku_image_added_by) VALUES (:sku_id, :sku_url, :sku_thumb, :sku_description, :sku_added_by)");
                $stmt->bindparam(":sku_id", $sku);
                $stmt->bindparam(":sku_url", $url);
                $stmt->bindparam(":sku_thumb", $thumb);
                $stmt->bindparam(":sku_description", $desc);
                $stmt->bindparam(":sku_added_by", $userName);
                $stmt->execute();
            }
            catch(PDOException $e)
            {
                echo $e->getMessage();
            }	
        }
        
        // *************************************************************
        // Usage: randImage($num)
        // pulls $num random images from the database to display on home page
        // *************************************************************
        public function randImage($num) 
        {
            // lets update the search ticker for this sku
            try {
                    $stmt = $this->conn->prepare("SELECT * FROM sku_image 
                        LEFT JOIN sku on sku_id = sku_image_sku_id
                        ORDER BY RAND() LIMIT $num;");
                    $stmt->bindparam(":num", $num);
                    $stmt->execute();
                    $result = array(array());
                    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    return $result;
                }   
                catch(PDOException $e)
                {
                    echo $e->getMessage();
                }	
        } // end randImage
        
        
        // *************************************************************
        // Usage: mySearches($dateFrom, $dateTo, $userID)
        // returns a table of searches from users search history
        // my date range (or all if blank)
        // *************************************************************
        public function mySearches($dateFrom, $dateTo, $userID)
        {
           require_once('class.user.php');
            $user = new USER;
            
            if($user->accessCheck() != 'ADMIN'){
                $userID = $_SESSION['user_id'];
            }
                
            // lets update the search ticker for this sku
            try {
                    if(!empty($userID))
                    {  

                        $stmt = $this->conn->prepare("SELECT sku_search_sku, sku_desc, count(sku_search_sku) as count 
                            FROM sku_search 
                            LEFT JOIN user on user_id = sku_search_by
                            LEFT JOIN sku on sku_id = sku_search_sku
                         WHERE sku_search_by = :userID and date(sku_search_date) >= :dateFrom and date(sku_search_date) <= :dateTo 
                         GROUP by sku_search_sku, sku_desc
                         ORDER BY count desc");
                        $stmt->bindparam(":userID", $userID);
                        $stmt->bindparam(":dateFrom", $dateFrom);
                        $stmt->bindparam(":dateTo", $dateTo);

                    } else 
                    {
                        $stmt = $this->conn->prepare("SELECT sku_search_sku, sku_desc, count(sku_search_sku) as count 
                            FROM sku_search 
                            LEFT JOIN user on user_id = sku_search_by
                            LEFT JOIN sku on sku_id = sku_search_sku
                            WHERE date(sku_search_date) >= :dateFrom and date(sku_search_date) <= :dateTo 
                          GROUP by sku_search_sku, sku_desc
                          ORDER BY count desc
                          ");
                        $stmt->bindparam(":dateFrom", $dateFrom);
                        $stmt->bindparam(":dateTo", $dateTo);
                    }    
                    $stmt->execute();
                    $result = array(array());
                    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    return $result;

                }   
                catch(PDOException $e)
                {
                    echo $e->getMessage();
                }	
        } // end mySearches
        
        // *************************************************************
        // Usage: skuCount();
        // display counts for records, images, and searches
        // *************************************************************
        public function getSkuCount()
        {
            try {
                    $stmt = $this->conn->prepare("SELECT count(*) as SKU_Count FROM sku");
                    $stmt->execute();
                    $row_sku = $stmt->fetch();
                    $sku_count = $row_sku['SKU_Count'];
                    return $sku_count;
                }   
                catch(PDOException $e)
                {
                    echo $e->getMessage();
                }	
        } // end recordCount;
        
                // *************************************************************
        // Usage: recordCount();
        // display counts for records, images, and searches
        // *************************************************************
        public function getSearchCount()
        {
            try {
                    $stmt = $this->conn->prepare("SELECT count(*) as Search_Count FROM sku_search");
                    $stmt->execute();
                    $row_search = $stmt->fetch();
                    $search_count = $row_search['Search_Count'];
                    return $search_count;
                }   
                catch(PDOException $e)
                {
                    echo $e->getMessage();
                }	
        } // end recordCount;
        
                // *************************************************************
        // Usage: recordCount();
        // display counts for records, images, and searches
        // *************************************************************
        public function getImageCount()
        {
            try {
                    $stmt = $this->conn->prepare("SELECT count(*) as Search_Count FROM sku_image");
                    $stmt->execute();
                    $row_image = $stmt->fetch();
                    $image_count = $row_image['Search_Count'];
                    return $image_count;
                    
                }   
                catch(PDOException $e)
                {
                    echo $e->getMessage();
                }	
        } // end recordCount;
        
        
        // *************************************************************
        // Usage: mysqlToJson($drom, dto, $user);
        // display counts for records, images, and searches
        // sends the data back in JSON format
        // *************************************************************
        public function mysqlToJson($dateFrom, $dateTo, $userID)
        {
            require_once('class.user.php');
            $user = new USER;
            
            if($user->accessCheck() != 'ADMIN'){
                $userID = $_SESSION['user_id'];
            }

            try {
                    if(!empty($userID))
                    {  
                        if(empty($dateFrom) || empty($dateTo))
                        {            
                            $count = $this->conn->prepare("SELECT sku_search_sku, count(sku_search_sku) as count  FROM sku_search 
                            left join sku on sku_id = sku_search_sku
                            WHERE sku_search_by = :userID 
                            GROUP by sku_search_sku
                            ORDER BY count desc");
                            $count->bindparam(":userID", $userID);
                            
                        } else {                
                            $count = $this->conn->prepare("SELECT sku_search_sku, count(sku_search_sku) as count FROM sku_search 
                            left join user on user_id = sku_search_by
                            left join sku on sku_id = sku_search_sku
                             WHERE sku_search_by = :userID and date(sku_search_date) >= :dateFrom and date(sku_search_date) <= :dateTo 
                             GROUP by sku_search_sku
                             ORDER BY count desc");
                            $count->bindparam(":userID", $userID);
                            $count->bindparam(":dateFrom", $dateFrom);
                            $count->bindparam(":dateTo", $dateTo);
                            
                        }
                    } else 
                    {
                        if(empty($dateFrom) || empty($dateTo))
                        {                
                            $count = $this->conn->prepare("SELECT sku_search_sku, count(sku_search_sku) as count FROM sku_search 
                            left join sku on sku_id = sku_search_sku
                            left join user on user_id = sku_search_by
                            GROUP by sku_search_sku
                            ORDER BY count desc");
                        } else {
                            $count = $this->conn->prepare("SELECT sku_search_sku, count(sku_search_sku) as count FROM sku_search 
                            left join user on user_id = sku_search_by
                            left join sku on sku_id = sku_search_sku
                                WHERE date(sku_search_date) >= :dateFrom and date(sku_search_date) <= :dateTo 
                              GROUP by sku_search_sku
                              ORDER BY count desc
                              ");
                            $count->bindparam(":dateFrom", $dateFrom);
                            $count->bindparam(":dateTo", $dateTo);
                        }
                    }    
                    $count->execute();
                    $data = array(); // set an array for JSON output
               
                    while($countrow = $count->fetch(PDO::FETCH_ASSOC))
                    {
                        //Create an array that C3 can read correctly
                        $index = $countrow['sku_search_sku'];
                        $data[$index] = $countrow['count'];                
                    }
                    // since we are formatting in JSON we need to set the header before returning the data.
                    if(!empty($data)){
                    header("Access-Control-Allow-Origin: *");//this allows coors
                    header('Content-Type: application/json');
                    print json_encode($data);
                    }
                }   
                catch(PDOException $e)
                {
                    echo $e->getMessage();
                }	
        } // end mySqlToJson
        
        
        // *************************************************************
        // Usage: skuUpdateRequest($type); 
        // $type can be:
        //   'active' = returns a list of skus that have been requested and how many times requested
        //   'complete' = returns a list of skus that have been requested and updated by an admin
        //   'sku#' IE: 9911: = returns a list of users who have requested the sku update and when it was requested
        // *************************************************************
        
        public function skuUpdateRequest($type)
        {
            if($type == 'active') 
            {
                try
                {
                    $stmt = $this->conn->prepare("SELECT distinct(update_sku), count(update_sku) as count, sku_desc from sku_update_request
                        LEFT JOIN sku on sku_id = update_sku
                        WHERE update_updated_by = '' 
                        GROUP BY update_sku
                        ORDER BY count(update_sku) desc
                        ");
                    $stmt->execute();
                    $result = array(array());
                    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    return $result;
                    
                    ?>
                    
                <?php
                }
                catch(PDOException $e)
                {
                    echo $e->getMessage();
                }
            } elseif($type == 'complete') 
            {
                try
                {
                    $stmt = $this->conn->prepare("SELECT distinct(update_sku), count(update_sku) as count, sku_desc from sku_update_request
                        LEFT JOIN sku on sku_id = update_sku
                        WHERE update_updated_by <> '' 
                        GROUP BY update_sku
                        ORDER BY count(update_sku) desc
                        ");
                    $stmt->execute();
                    $result = array(array());
                    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    return $result;

                }
                catch(PDOException $e)
                {
                    echo $e->getMessage();
                }
            } else
            {
                try
                {
                    $stmt = $this->conn->prepare("SELECT * from sku_update_request
                        LEFT JOIN user on user_id = update_request_by
                        LEFT JOIN sku on sku_id = update_sku
                        where update_updated_by = '' and update_sku = :skuid ");
                    $stmt->bindparam(":skuid", $type);
                    $stmt->execute();
                    $result = array(array());
                    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    return $result;
                }
                catch(PDOException $e)
                {
                    echo $e->getMessage();
                }
            }
        } // end skuUpdateRequest
        
        // *************************************************************
        // Usage: getSkuData($sku); 
        // Returns table of SKU Unit, Case, Pallet data to be edited
        // *************************************************************
        
        public function getSkuData($sku) 
        {
            try
            {
                $stmt = $this->conn->prepare("SELECT * from sku
                    WHERE sku_id = :skuID");
                $stmt->bindparam(":skuID", $sku);
                $stmt->execute();
                $result = $stmt->fetch();
                return $result;
            }
            catch(PDOException $e)
            {
                echo $e->getMessage();
            }
        } // end getSkuData
        
        // *************************************************************
        // Usage: setSkuData($sku, $unit_length, $unit_width, $unit_height, $unit_weight, 
        // $case_length, $case_width, $case_height, $case_weight, $case_qty, $pallet_length, 
        // $pallet_width, $pallet_height, $pallet_weight, $pallet_qty); 
        // Updates the sku data fields
        // *************************************************************
        
        public function setSkuData($sku, 
               $sku_desc, $unit_length, $unit_width, $unit_height, $unit_weight,
               $case_length, $case_width, $case_height, $case_weight, $case_qty, 
               $pallet_length, $pallet_width, $pallet_height, $pallet_weight, $pallet_qty) 
        {
            
            $sku_desc = trim($sku_desc);
            $user = $_SESSION['fname'].' '.$_SESSION['lname'];
            date_default_timezone_set('US/Eastern');
            $date = date('m/d/Y h:i:s a', time());
            
            try
            {
                $stmt = $this->conn->prepare("UPDATE sku 
                    SET 
                    sku_desc = :sku_desc,
                    sku_unit_length = :unit_length,
                    sku_unit_width = :unit_width,
                    sku_unit_height = :unit_height,
                    sku_unit_weight = :unit_weight,

                    sku_case_length = :case_length,
                    sku_case_width = :case_width,
                    sku_case_height = :case_height,
                    sku_case_weight = :case_weight,
                    sku_case_qty = :case_qty,

                    sku_pallet_length = :pallet_length,
                    sku_pallet_width = :pallet_width,
                    sku_pallet_height = :pallet_height,
                    sku_pallet_weight = :pallet_weight,
                    sku_pallet_qty = :pallet_qty,
                    
                    sku_rec_update = now(),
                    sku_rec_update_by = :user_name
        
                    WHERE sku_id = :skuID");
                    $stmt->bindparam(":skuID", $sku);
                    $stmt->bindparam(":sku_desc", $sku_desc);

                    $stmt->bindparam(":unit_length", $unit_length);
                    $stmt->bindparam(":unit_width", $unit_width);
                    $stmt->bindparam(":unit_height", $unit_height);
                    $stmt->bindparam(":unit_weight", $unit_weight);

                    $stmt->bindparam(":case_length", $case_length);
                    $stmt->bindparam(":case_width", $case_width);
                    $stmt->bindparam(":case_height", $case_height);
                    $stmt->bindparam(":case_weight", $case_weight);
                    $stmt->bindparam(":case_qty", $case_qty);

                    $stmt->bindparam(":pallet_length", $pallet_length);
                    $stmt->bindparam(":pallet_width", $pallet_width);
                    $stmt->bindparam(":pallet_height", $pallet_height);
                    $stmt->bindparam(":pallet_weight", $pallet_weight);
                    $stmt->bindparam(":pallet_qty", $pallet_qty);
                    
                    $stmt->bindparam(":user_name", $user);
                    
                    $stmt->execute();
                    $ru = $this->setRequestUpdate($sku);
                    if($ru)
                    {
                        return true;
                    } else
                    {
                        echo 'something went wrong';
                        die;
                        return false;
                    }
            }
            catch(PDOException $e)
            {
                echo $e->getMessage();
            }
        } // end setSkuData
        
        // *************************************************************
        // Usage: setSkuData($sku, $unit_length, $unit_width, $unit_height, $unit_weight, 
        // $case_length, $case_width, $case_height, $case_weight, $case_qty, $pallet_length, 
        // $pallet_width, $pallet_height, $pallet_weight, $pallet_qty); 
        // Updates the sku data fields
        // *************************************************************
        
        public function setRequestUpdate($sku) 
        {
            $user = $_SESSION['user_id'];
            $updateDate = date("Y-m-d H:i:s");
            
            try
            {
                $stmt = $this->conn->prepare("UPDATE sku_update_request 
                    SET 
                    update_complete = 1, 
                    update_updated_by = $user,
                    update_complete_date = now()
                    WHERE update_sku = :skuID");
                
                    $stmt->bindparam(":skuID", $sku);
                    
                    $stmt->execute();
                    return true;
            }
            catch(PDOException $e)
            {
                echo $e->getMessage();
                die;
            }
        } // end setSkuData
        
        
        // *************************************************************
        // Usage: checkSku($sku);
        // checks if SKU exists.. Returns true or false
        // *************************************************************
        public function checkSku($sku)
        {   
            try {
                    $stmt = $this->conn->prepare("SELECT * FROM sku
                        WHERE sku_id = :sku");
                    $stmt->bindparam(":sku", $sku);
                    $stmt->execute();
                    $count = $stmt->rowCount();
                    
                     if($count < 1)
                     {
                         return false;
                     } else
                     {
                         return true;
                     }
                }   
                catch(PDOException $e)
                {
                    echo $e->getMessage();
                }	
        } // end checkSku;
        
        
        // *************************************************************
        // Usage: addSku($sku, $desc);
        // adds sku and description to the database. 
        // *************************************************************
        public function addSku($sku, $desc)
        {
            $user = $_SESSION['user_id'];
            try {
                    $stmt = $this->conn->prepare("INSERT INTO sku
                        (sku_id, sku_desc, sku_rec_added)
                        VALUES(:sku_id, :sku_desc, :sku_rec_added)");
                    $stmt->bindparam(":sku_id", $sku);
                    $stmt->bindparam(":sku_desc", $desc);
                    $stmt->bindparam(":sku_rec_added", $user);
                    $stmt->execute();
                    return true;
                }   
                catch(PDOException $e)
                {
                    echo $e->getMessage();
                }	
        } // end addSku;
        
    } // end class
?>