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
        // Usage: getSkuImage();
        // Created an admin view of the SKU images
        // *************************************************************
        public function getSkuImage($sku)
        {
            try
            {
                $skuimages = $this->conn->prepare("SELECT * FROM sku_image 
                WHERE sku_image_sku_id=:sku");
                $skuimages->execute(array(':sku'=>$sku));
                $skuRow=$skuimages->fetch(PDO::FETCH_ASSOC);
                
                ?>
                    <section class="sku-image-data">
                        <h2>SKU Images</h2>
                        <form action="/processors/image_handler.php" method="post" enctype="multipart/form-data">
                            Select image to upload:
                            <input type="file" name="file" id="file">
                            <input type="text" name="desc" id="desc" placeholder="Caption">
                            <input type="hidden" id="skuId" name="skuId" value="<?php echo $skuRow['sku_image_sku_id']; ?>">
                            <input type="submit" value="Upload Image" name="imageSubmit">
                        </form>
                        <span class="imagemessage"><?php echo $this->imageMessage; ?></span>
                    </section>
                    <section class="sku-images" id="skuimages">
                        <?php 
                        while($skuimagerow = $skuimages->fetch()){
                            ?>
                        <figure class="card bg-white shadow">
                            <div class="card-img">
                                <a href="<?php echo $skuimagerow['sku_image_url']; ?>">
                                    <img class="article-img" src="<?php echo $skuimagerow['sku_image_thumb']; ?>" alt="<?php echo $skuimagerow['sku_image_sku_id'].'-'.$skuimagerow['sku_image_description']; ?>" />
                                </a>
                            </div>
                            <figcaption>
                                <section class="card-sku-num">
                                    <table class="table-nores">
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <label for="caption">Desc:</label>
                                                </td>
                                                <td>
                                                    <form id="imageUpdate" action="/processors/image_handler.php" method="post">
                                                        <input type="text" name="imageSku" value="<?php echo $skuimagerow['sku_image_sku_id']; ?>" hidden>
                                                        <input type="text" name="imageNum" value="<?php echo $skuimagerow['sku_image_id']; ?>" hidden>
                                                        <input type="text" name="caption" value="<?php echo $skuimagerow['sku_image_description'];?>">
                                                    </form>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <form method="post" action="/processors/image_handler.php">
                                                        <input type="text" value="<?php echo $skuimagerow['sku_image_sku_id']; ?>" name="image_sku" hidden>
                                                        <input type="text" value="<?php echo $skuimagerow['sku_image_id']; ?>" name="image_id" hidden>
                                                        <input type="text" value="<?php echo $skuimagerow['sku_image_url']; ?>" name="image_url" hidden>
                                                        <input type="text" value="<?php echo $skuimagerow['sku_image_thumb']; ?>" name="image_thumb" hidden>
                                                        <button class="btn danger" type="submit" name="deleteimg">Delete</button>
                                                    </form> 
                                                </td>
                                                <td class="align-right">
                                                    <button type="submit" form="imageUpdate" class="btn active" name="imageUpdate">Update</button>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </section>
                            </figcaption>
                        </figure>
                            <?php
                        }
                        ?>
                    </section>
                <?php
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
        // Usage: mySearches($dateFrom, $dateTo, $userID, $recordCount)
        // returns a table of searches from users search history
        // my date range (or all if blank)
        // *************************************************************
        public function mySearches($dateFrom, $dateTo, $userID, $recordCount)
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

                            $stmt = $this->conn->prepare("SELECT * FROM sku_search 
                            left join user on user_id = sku_search_by
                            left join sku on sku_id = sku_search_sku
                             WHERE sku_search_by = :userID and date(sku_search_date) >= :dateFrom and date(sku_search_date) <= :dateTo 
                             ORDER BY sku_search_id desc");
                            $stmt->bindparam(":userID", $userID);
                            $stmt->bindparam(":dateFrom", $dateFrom);
                            $stmt->bindparam(":dateTo", $dateTo);
                            
                            
                            $count = $this->conn->prepare("SELECT sku_search_sku, count(sku_search_sku) as count FROM sku_search 
                            left join user on user_id = sku_search_by
                            left join sku on sku_id = sku_search_sku
                             WHERE sku_search_by = :userID and date(sku_search_date) >= :dateFrom and date(sku_search_date) <= :dateTo 
                             GROUP by sku_search_sku
                             ORDER BY count desc");
                            $count->bindparam(":userID", $userID);
                            $count->bindparam(":dateFrom", $dateFrom);
                            $count->bindparam(":dateTo", $dateTo);

                    } else 
                    {
                            $stmt = $this->conn->prepare("SELECT * FROM sku_search 
                            left join user on user_id = sku_search_by
                            left join sku on sku_id = sku_search_sku
                                WHERE date(sku_search_date) >= :dateFrom and date(sku_search_date) <= :dateTo 
                              ORDER BY sku_search_id desc");
                            $stmt->bindparam(":dateFrom", $dateFrom);
                            $stmt->bindparam(":dateTo", $dateTo);
                            
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
                    $stmt->execute();
                    $count->execute();
                    
                    if($stmt->rowCount() >= 1)
                    {
                        ?>
                        <section class="my-search-results">
                            <table class="table">
                                <caption>Search History</caption>
                                <thead>
                                    <tr>
                                        <th>Part Number</th>
                                        <th>Description</th>
                                        <th>Date</th>
                                        <th>Time</th>
                                        <th>First Name</th>
                                        <th>Last Name</th>
                                    </tr>
                                </thead> 
                                 <tbody>
                        <?php
                        while($row = $stmt->fetch())
                        {
                        ?>
                                <?php 
                                    $fname = $row['user_fName'];
                                    $lname = $row['user_lName'];
                                    $desc = $row['sku_desc'];
                                    $date = $row['sku_search_date']; 
                                    $newDate = new DateTime($date);
                                    $dateOnly = $newDate->format('Y-m-d'); // pull the date out
                                    $timeOnly = $newDate->format('h:i:s A'); // pull the time out
                                ?>
                                    <tr>
                                        <td scope="row" data-label="SKU"><a class="sku-name" href="/search.php?search=<?php echo $row['sku_search_sku']; ?>"><?php echo $row['sku_search_sku']; ?></a></td>
                                        <td data-label="Description"><?php echo $desc; ?></td>
                                        <td data-label="Date"><?php echo $dateOnly; ?></td>
                                        <td data-label="Time"><?php echo $timeOnly; ?></td>
                                        <td data-label="First Name"><?php echo $fname; ?></td>
                                        <td data-label="Last Name"><?php echo $lname; ?></td>
                                    </tr>

                        <?php
                        }
                            ?></tbody>
                            </table>
                            </section>    
                            <section class="my-search-count">
                                 <table class="table table-count">
                                    <caption>Search Count</caption>
                                    <thead>
                                        <tr>
                                            <th scope="col">Part Number</th>
                                            <th scope="col">Count</th>
                                        </tr>
                                    </thead> 
                                     <tbody>

                                <?php

                        while($countrow = $count->fetch())
                        {
                            ?>
                             <tr>
                                <td scope="row" data-label="SKU"><a class="sku-name" href="/search.php?search=<?php echo $countrow['sku_search_sku']; ?>"><?php echo $countrow['sku_search_sku']; ?></a></td>
                                <td data-label="Count"><?php echo $countrow['count']; ?></td>
                            </tr>
                            <?php
                        }
                        ?>
                                     </tbody>
                                </table>
                        </section>
                        <?php
                    } else 
                    {
                        ?>
                            <section>Sorry, nothing was found for this date range.</section>
                        <?php
                    }
                }   
                catch(PDOException $e)
                {
                    echo $e->getMessage();
                }	
        } // end mySearches
        
        // *************************************************************
        // Usage: recordCount();
        // display counts for records, images, and searches
        // *************************************************************
        public function recordCount()
        {
            // lets update the search ticker for this sku
            try {
                    $stmt = $this->conn->prepare("SELECT count(*) as SKU_Count FROM sku");
                    $stmt->execute();
                    $row_sku = $stmt->fetch();
                    $sku_count = $row_sku['SKU_Count'];
                
                    $stmt = $this->conn->prepare("SELECT count(*) as Search_Count FROM sku_search");
                    $stmt->execute();
                    $row_search = $stmt->fetch();
                    $search_count = $row_search['Search_Count'];
                
                    $stmt = $this->conn->prepare("SELECT count(*) as Search_Count FROM sku_image");
                    $stmt->execute();
                    $row_image = $stmt->fetch();
                    $image_count = $row_image['Search_Count'];
                
                    ?>
                    <p><?php echo number_format($sku_count);?> Parts </p>
                    <p><?php echo number_format($image_count);?> Pictures </p>
                    <p><?php echo number_format($search_count);?> Searches</p>
                    <?php
                    
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
                
            // lets update the search ticker for this sku
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
                    
                    ?>
                    <table class="table shadow">
                        <thead>
                            <tr>
                                <td scope="col">SKU</td>
                                <td scope="col">Description</td>
                                <td scope="col">Count</td>
                            </tr>
                        </thead>
                        <tbody>
                    <?php
                    while($row = $stmt->fetch())
                    {
                        $skuID = $row['update_sku'];
                        ?>
                            <tr>
                                <td data-label="SKU"><a href="/admin/update-sku.php?sku=<?php echo $skuID; ?>"><?php echo $skuID; ?></a></td>
                                <td data-label="Desc"><?php echo $row['sku_desc']; ?></td>
                                <td data-label="Count" class="align-right"><a href="/admin/update-request.php?sku=<?php echo $skuID; ?>"><?php echo $row['count']; ?></a></td>
                            </tr>  
                        <?php
                    }
                ?>
                    </tbody>
                </table>
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
                    
                    ?>
                    <table class="table">
                        <thead>
                            <tr>
                                <td scope="col">SKU</td>
                                <td scope="col">Description</td>
                                <td scope="col">Count</td>
                            </tr>
                        </thead>
                        <tbody>
                    <?php
                    while($row = $stmt->fetch())
                    {
                        $skuID = $row['update_sku'];
                        ?>
                            <tr>
                                <td data-label="SKU"><a href="/admin/update-sku.php?sku=<?php echo $skuID; ?>"><?php echo $skuID; ?></a></td>
                                <td data-label="Desc"><?php echo $row['sku_desc']; ?></td>
                                <td data-label="Count" class="align-right"><a href="/admin/update-request.php?sku=<?php echo $skuID; ?>"><?php echo $row['count']; ?></a></td>
                            </tr>  
                        <?php
                    }
                ?>
                    </tbody>
                </table>
                <?php
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

                    ?>
                    <table class="table">
                        <thead>
                            <tr>
                                <td scope="col">List Name</td>
                                <td scope="col">Description</td>
                                <td scope="col">Requested By</td>
                                <td scope="col">Date</td>
                            </tr>
                        </thead>
                        <tbody>
                    <?php
                    while($row = $stmt->fetch())
                    {
                        $date = $row['update_request_date'];
                        $dateadded = date_create($date);
                        $addDate = date_format($dateadded, 'm/d/Y');
                        ?>
                            <tr>
                                <td data-label="SKU"><a href="/admin/update-sku.php?sku=<?php echo $type; ?>"><?php echo $type; ?></a></td>
                                <td data-label="Desc"><?php echo $row['sku_desc']; ?></td>
                                <td data-label="User"><?php echo $row['user_fName'].' '.$row['user_lName']; ?></td>
                                <td data-label="Date"><?php echo $addDate; ?></td>
                            </tr>  
                        <?php
                    }
                ?>
                    </tbody>
                </table>
                <?php
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
                $row = $stmt->fetch();
                
            ?>
                <form id="UpdateSkuForm" method="post" action="/processors/sku_handler.php">
                    <input type="text" name="sku" value="<?php echo $sku; ?>" hidden>
                    <section class="update-sku">
                        <table class="table-nores sku-set1">
                            <thead>
                                <tr>
                                    <th data-label="<?php echo $row['sku_id']; ?>" scope="col" colspan="2">
                                        <?php echo $row['sku_id']; ?>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="valign-top">
                                        <label for="desc">Description</label>
                                    </td>
                                    <td>
                                        <textarea rows="4" cols="25" name="desc">
                                            <?php echo $row['sku_desc']; ?>
                                        </textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="align-right" colspan="2">
                                        <button class="btn info" type="submit" name="skuUpdate" form="UpdateSkuForm">Submit</button>
                                    </td>
                                </tr>
                        </table>

                        <table class="table-nores sku-set2">
                            <thead>
                                <tr>
                                    <th colspan="2" class="tb1-color">UNIT</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td data-label="Unit">
                                        <label for="unit-length">Length</label>
                                    </td>
                                    <td>
                                        <input type="number" name="unit-length" min="0" step="0.01" value="<?php echo $row['sku_unit_length'] ?>">
                                    </td>
                                </tr>
                                <tr>
                                    <td data-label="Unit">
                                        <label for="unit-width">Width</label>
                                    </td>
                                    <td colspan="2">
                                        <input type="number" name="unit-width" min="0" step="0.01" value="<?php echo $row['sku_unit_width'] ?>">
                                    </td>
                                </tr>
                                <tr>
                                    <td data-label="Unit">
                                        <label for="unit-height">Height</label>
                                    </td>
                                    <td colspan="2"> 
                                        <input type="number" name="unit-height" min="0" step="0.01" value="<?php echo $row['sku_unit_height'] ?>">
                                    </td>
                                </tr>
                                <tr>
                                    <td data-label="Unit">
                                        <label for="unit-weight">Weight</label>
                                    </td>
                                    <td colspan="2">
                                        <input type="number" name="unit-weight" min="0" step="0.01" value="<?php echo $row['sku_unit_weight'] ?>">
                                    </td>
                                </tr>
                                <tr>
                                    <td class="align-right" colspan="2"><button class="btn info" type="submit" name="skuUpdate" form="UpdateSkuForm">Submit</button></td>
                                </tr>
                            </tbody>
                        </table>

                        <table class="table-nores sku-set3">
                            <thead>
                                 <tr>
                                    <th colspan="2" class="tb1-color">CASE</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td data-label="Case">
                                        <label for="case-length">Length</label>
                                    </td>
                                    <td>
                                        <input type="number" name="case-length" min="0" step="0.01" value="<?php echo $row['sku_case_length'] ?>">
                                    </td>
                                </tr>
                                <tr>
                                    <td data-label="Case">
                                        <label for="case-width">Width</label>
                                    </td>
                                    <td colspan="2">
                                        <input type="number" name="case-width" min="0" step="0.01" value="<?php echo $row['sku_case_width'] ?>">
                                    </td>
                                </tr>
                                <tr>
                                    <td data-label="Case">
                                        <label for="case-height">Height</label>
                                    </td>
                                    <td colspan="2"> 
                                        <input type="number" name="case-height" min="0" step="0.01" value="<?php echo $row['sku_case_height'] ?>">
                                    </td>
                                </tr>
                                <tr>
                                    <td data-label="Case">
                                        <label for="case-weight">Weight</label>
                                    </td>
                                    <td colspan="2">
                                        <input type="number" name="case-weight" min="0" step="0.01" value="<?php echo $row['sku_case_weight'] ?>">
                                    </td>
                                </tr>
                                <tr>
                                    <td data-label="Case">
                                        <label for="case-qty">Quantity</label>
                                    </td>
                                    <td colspan="2">
                                        <input type="number" name="case-qty" min="0" step="0.01" value="<?php echo $row['sku_case_qty'] ?>">
                                    </td>
                                </tr>
                                <tr>
                                    <td class="align-right" colspan="2"><button class="btn info" type="submit" name="skuUpdate" form="UpdateSkuForm">Submit</button></td>
                                </tr>
                            </tbody>
                        </table>    

                        <table class="table-nores sku-set4">
                            <thead>
                                <tr>
                                    <th colspan="2" class="tb1-color">PALLET</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td data-label="Pallet">
                                        <label for="pallet-length">Length</label>
                                    </td>
                                    <td>
                                        <input type="number" name="pallet-length" min="0" step="0.01" value="<?php echo $row['sku_pallet_length'] ?>">
                                    </td>
                                </tr>
                                <tr>
                                    <td data-label="Pallet">
                                        <label for="pallet-width">Width</label>
                                    </td>
                                    <td colspan="2">
                                        <input type="number" name="pallet-width" min="0" step="0.01" value="<?php echo $row['sku_pallet_width'] ?>">
                                    </td>
                                </tr>
                                <tr>
                                    <td data-label="Pallet">
                                        <label for="pallet-height">Height</label>
                                    </td>
                                    <td colspan="2"> 
                                        <input type="number" name="pallet-height" min="0" step="0.01" value="<?php echo $row['sku_pallet_height'] ?>">
                                    </td>
                                </tr>
                                <tr>
                                    <td data-label="Pallet">
                                        <label for="pallet-weight">Weight</label>
                                    </td>
                                    <td colspan="2">
                                        <input type="number" name="pallet-weight" min="0" step="0.01" value="<?php echo $row['sku_pallet_weight'] ?>">
                                    </td>
                                </tr>
                                <tr>
                                    <td data-label="Pallet">
                                        <label for="pallet-qty">Quantity</label>
                                    </td>
                                    <td colspan="2">
                                        <input type="number" name="pallet-qty" min="0" step="0.01" value="<?php echo $row['sku_pallet_qty'] ?>">
                                    </td>
                                </tr>
                                <tr>
                                    <td class="align-right" colspan="2"><button class="btn info" type="submit" name="skuUpdate" form="UpdateSkuForm">Submit</button></td>
                                </tr>
                            </tbody>
                        </table>                       
                    </section> <!-- end Case Data -->
                </form>
            <?php

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
                    
                    sku_rec_update = :update_date,
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
                    
                    $stmt->bindparam(":update_date", $date);
                    $stmt->bindparam(":user_name", $user);
                    
                    $stmt->execute();
                
                return true;
            }
            catch(PDOException $e)
            {
                echo $e->getMessage();
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