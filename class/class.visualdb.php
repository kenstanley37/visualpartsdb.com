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
        // Usage: skuSearch(sku);
        // searches the database for requested sku and returns information
        // based on users access.
        // *************************************************************
        public function skuSearch($sku)
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
                $stmt->execute(array(':sku'=>$sku));
                $skuRow=$stmt->fetch(PDO::FETCH_ASSOC);
                
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
                    
                    $skuimages = $this->conn->prepare("SELECT * FROM sku_image 
                    WHERE sku_image_sku_id=:sku");
                    $skuimages->execute(array(':sku'=>$sku));
                    ?>
                            
                         <article class="slogo-search">
                            <h1><?php echo $skuRow['sku_id']; ?></h1>
                            <?php if($user->accessCheck() == "ADMIN")
                            {
                                ?>
                                    <form action="/processors/image_handler.php" method="post" enctype="multipart/form-data">
                                        Select image to upload:
                                        <input type="file" name="file" id="file">
                                        <input type="text" name="desc" id="desc" placeholder="Description">
                                        <input type="hidden" id="skuId" name="skuId" value="<?php echo $skuRow['sku_id']; ?>">
                                        <input type="submit" value="Upload Image" name="imageSubmit">
                                    </form>
                                    <span class="imagemessage"><?php echo $this->imageMessage; ?></span>
                                <?php                                            

                            }
                            ?>
                        </article>
                        <article class="search-grid">
                            <article class="skuimages">
                                <?php 
                                    while($skuimagerow = $skuimages->fetch()){
                                        ?>
                                    <figure>
                                        <a href="<?php echo $skuimagerow['sku_image_url']; ?>" target="_blank"><img src="<?php echo $skuimagerow['sku_image_thumb']; ?>" alt="<?php echo $skuimagerow['sku_image_sku_id'].'-'.$skuimagerow['sku_image_description']; ?>" /></a>
                                        <figcaption><?php echo $skuimagerow['sku_image_description']; ?></figcaption>
                                        <form method="post" action="/processors/image_handler.php">
                                            <input type="text" value="<?php echo $skuimagerow['sku_image_sku_id']; ?>" name="image_sku" hidden>
                                            <input type="text" value="<?php echo $skuimagerow['sku_image_id']; ?>" name="image_id" hidden>
                                            <input type="text" value="<?php echo $skuimagerow['sku_image_url']; ?>" name="image_url" hidden>
                                            <input type="text" value="<?php echo $skuimagerow['sku_image_thumb']; ?>" name="image_thumb" hidden>
                                            <input type="submit" value="Delete Image" name="deleteimg">
                                        </form>
                                    </figure>

                                        <?php
                                    }
                                ?>

                            </article>
                            <article class="search-part-info">
                                <section class="export-data">
                                    <section class="excel">
                                        <ul>
                                            <li><a href="/export/generate-xlsx.php?export=excel&sku=<?php echo $skuRow['sku_id']; ?>">Excel <i class="far fa-file-excel"></i></a></li>
                                            <li><a href="search.php?export=pdf&sku=<?php echo $skuRow['sku_id']; ?>">PDF <i class="far fa-file-pdf"></i></a></li>
                                        </ul>
                                    </section>
                                    <section class="addtolist">
                                        <?php if(isset($_SESSION['user_id'])){ 
                                            ?>
                                            <a href="search.php?export=excel&sku=<?php echo $skuRow['sku_id']; ?>">Add to List <i class="fas fa-plus-circle"></i></a>
                                            <?php
                                        } ?> 
                                        
                                    </section>
                                </section>
                                <section class="skudetails">
                                    <table>
                                        <tbody>
                                            <tr>
                                                <th>Part #</th>
                                                <td><?php echo $skuRow['sku_id']; ?></td>
                                            </tr>
                                            <tr>
                                                <th>Description</th>
                                                <td><?php echo $skuRow['sku_desc']; ?></td>
                                            </tr>
                                            <tr>
                                                <th>Dimension UOM</th>
                                                <td>Inches</td>
                                            </tr>
                                            <tr>
                                                <th>Weight UOM</th>
                                                <td>Pounds</td>
                                            </tr>
                                        </tbody>                                    
                                    </table> 
                                                
                                    <table class="indent50">
                                        <thead>
                                            <tr>
                                                <th colspan="2">Unit Data</th> 
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <th>Length</th>
                                                <td><?php echo $skuRow['sku_unit_length']; ?></td>
                                            </tr>
                                            <tr>
                                                <th>Width</th>
                                                <td><?php echo $skuRow['sku_unit_width']; ?></td>
                                            </tr>
                                            <tr>
                                                <th>Height</th>
                                                <td><?php echo $skuRow['sku_unit_height']; ?></td>
                                            </tr>
                                            <tr>
                                                <th>Weight</th>
                                                <td><?php echo $skuRow['sku_unit_weight']; ?></td>
                                            </tr>
                                        </tbody>
                                    </table> 
                                    <?php
                                        if(!empty($user->accessCheck()))
                                        {
                                    ?>
                                    <table class="indent50">
                                        <thead>
                                            <tr>
                                                <th colspan="2">Case Data</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <th>Length</th>
                                                <td><?php echo $skuRow['sku_case_length']; ?></td>
                                            </tr>
                                            <tr>
                                                <th>Width</th>
                                                <td><?php echo $skuRow['sku_case_width']; ?></td>
                                            </tr>
                                            <tr>
                                                <th>Height</th>
                                                <td><?php echo $skuRow['sku_case_height']; ?></td>
                                            </tr>
                                            <tr>
                                                <th>Weight</th>
                                                <td><?php echo $skuRow['sku_case_weight']; ?></td>
                                            </tr>
                                            <tr>
                                                <th>Qty Per</th>
                                                <td><?php echo $skuRow['sku_case_qty']; ?></td>
                                            </tr>
                                        </tbody>
                                    </table> 

                                    <table class="indent50">
                                        <thead>
                                            <tr>
                                                <th colspan="2">Pallet Data</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <th>Length</th>
                                                <td><?php echo $skuRow['sku_pallet_length']; ?></td>
                                            </tr>
                                            <tr>
                                                <th>Width</th>
                                                <td><?php echo $skuRow['sku_pallet_width']; ?></td>
                                            </tr>
                                            <tr>
                                                <th>Height</th>
                                                <td><?php echo $skuRow['sku_pallet_height']; ?></td>
                                            </tr>
                                            <tr>
                                                <th>Weight</th>
                                                <td><?php echo $skuRow['sku_pallet_weight']; ?></td>
                                            </tr>
                                            <tr>
                                                <th>Qty Per</th>
                                                <td><?php echo $skuRow['sku_pallet_qty']; ?></td>
                                            </tr>
                                        </tbody>
                                    </table> 
                                    
                                            <?php
                                        }
                    
                                    ?>
                                    
                                </section>
                            </article>
                        </article>
                    <?php
                } else {
                    ?>
                    <section class="indexSearch">
                        <div class="imageroll">
                            test
                        </div>
                        <form class="searchForm" action="/search.php" method="get">
                            <input type="text" name="search" id="search" placeholder="Enter Part Number">
                            <button type="submit">Search</button>
                        </form>
                    </section>
                    <?php
                }
            }
            catch(PDOException $e)
            {
                echo $e->getMessage();
            }
        } // end skuSearch
        
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
                   if(!is_dir('../images/'.$sku)){
                        //Directory does not exist, so lets create it.
                        mkdir('../images/'.$sku, 0755, true);
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
        // Usage: randImage
        // pulls 4 random images from the database to display on home page
        // *************************************************************
        public function randImage() 
        {
            // lets update the search ticker for this sku
            try {
                    $skuimages = $this->conn->prepare("SELECT * FROM sku_image ORDER BY RAND() LIMIT 4;");
                    $skuimages->execute();
                    while($skuimagerow = $skuimages->fetch())
                    {
                    ?>
                        <img class="article-img" src="<?php echo $skuimagerow['sku_image_thumb']; ?>" alt="<?php echo $skuimagerow['sku_image_sku_id'].'-'.$skuimagerow['sku_image_description']; ?>" />
                    <?php
                    }
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
            if(empty($dateFrom) || empty($dateTo))
            {
                
            }
            
            if(!empty($userID)){  }
            
            // lets update the search ticker for this sku
            try {
                    if(!empty($userID))
                    {  
                        if(empty($dateFrom) || empty($dateTo))
                        {
                            $stmt = $this->conn->prepare("SELECT * FROM sku_search WHERE sku_search_by = :userID ORDER BY sku_search_id desc");
                            $stmt->bindparam(":userID", $userID);
                        } else {
                            $stmt = $this->conn->prepare("SELECT * FROM sku_search 
                            left join user on user_id = sku_search_by
                            left join sku on sku_id = sku_search_sku
                             WHERE sku_search_by = :userID and date(sku_search_date) >= :dateFrom and date(sku_search_date) <= :dateTo ORDER BY sku_search_id desc");
                            $stmt->bindparam(":userID", $userID);
                            $stmt->bindparam(":dateFrom", $dateFrom);
                            $stmt->bindparam(":dateTo", $dateTo);
                        }
                    } else 
                    {
                        if(empty($dateFrom) || empty($dateTo))
                        {
                            $stmt = $this->conn->prepare("SELECT * FROM sku_search ORDER BY sku_search_id desc");
                        } else {
                            $stmt = $this->conn->prepare("SELECT * FROM sku_search 
                            left join user on user_id = sku_search_by
                            left join sku on sku_id = sku_search_sku
                              and date(sku_search_date) >= :dateFrom and date(sku_search_date) <= :dateTo ORDER BY sku_search_id desc");
                            $stmt->bindparam(":dateFrom", $dateFrom);
                            $stmt->bindparam(":dateTo", $dateTo);
                        }
                    }
                
                    
                    $stmt->execute();
                    ?>
                        <table class="table">
                            <caption>Search History</caption>
                            <thead>
                                <tr align="middle">
                                    <th scope="col">Part Number</th>
                                    <th scope="col">Description</th>
                                    <th scope="col">Date</th>
                                    <th scope="col">Time</th>
                                    <th scope="col">First Name</th>
                                    <th scope="col">Last Name</th>
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
                                <tr valign="middle">
                                    <td scope="row" data-label="SKU"><a href="/search.php?search=<?php echo $row['sku_search_sku']; ?>"><?php echo $row['sku_search_sku']; ?></a></td>
                                    <td data-label="Description"><?php echo $desc; ?></td>
                                    <td data-label="Date"><?php echo $dateOnly; ?></td>
                                    <td data-label="Time"><?php echo $timeOnly; ?></td>
                                    <td data-label="First Name"><?php echo $fname; ?></td>
                                    <td data-label="Last Name"><?php echo $lname; ?></td>
                                </tr>
                            
                    <?php
                    }
                        ?></tbody>
                        </table><?php
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
                        <p><?php echo number_format($sku_count);?> Parts, <?php echo number_format($image_count);?> Pictures, and <?php echo number_format($search_count);?> searches</p>
                    <?php
                    
                }   
                catch(PDOException $e)
                {
                    echo $e->getMessage();
                }	
        } // end mySearches
        
    } // end class
?>