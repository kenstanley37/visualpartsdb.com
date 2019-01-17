<?php
    class VISUALDB
    {
        private $conn;

        // *************************************************************
        // Constructor to connect to the database
        // *************************************************************
        public function __construct()
        {
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
        // Usage: skuSearch(sku);
        // searches the database for requested sku and returns information
        // based on users access.
        // *************************************************************
        public function skuSearch($sku)
        {
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
                    $timesSearched = $skuRow['sku_times_searched'] + 1;
                    // lets update the search ticker for this sku
                    try {
                        $stmt = $this->conn->prepare("UPDATE sku SET sku_times_searched=:timesSearched where sku_id=:skuid");
                        $stmt->bindparam(":timesSearched", $timesSearched);
                        $stmt->bindparam(":skuid", $sku);
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
                            <article class="search-images">
                                <section class="skutitle">
                                    <h1><?php echo $skuRow['sku_id']; ?></h1>
                                </section>
                                <section class="skuimages">
                                    <?php 
                                        while($skuimagerow = $skuimages->fetch()){
                                            ?>
                                            <img class="article-img" src="<?php echo $skuimagerow['sku_image_url']; ?>" alt="<?php echo $skuimagerow['sku_image_description']; ?>" />
                                            <?php
                                        }
                                    ?>
                                    
                                </section>
                                
                            </article>

                            <article class="search-part-info">
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
                                    <?php
                                        if(!empty($user->accessCheck()))
                                        {
                                            ?>
                                                
                                            <table class="indent50">
                                                <thead>
                                                    <tr>
                                                        <th>Unit Data</th> 
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <th>Length</th>
                                                        <td><?php echo $skuRow['sku_sig_length']; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <th>Width</th>
                                                        <td><?php echo $skuRow['sku_sig_width']; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <th>Height</th>
                                                        <td><?php echo $skuRow['sku_sig_height']; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <th>Weight LBS</th>
                                                        <td><?php echo $skuRow['sku_sig_weight']; ?></td>
                                                    </tr>
                                                </tbody>
                                            </table> 

                                            <table class="indent50">
                                                <thead>
                                                    <tr>
                                                        <th>Case Data</th>
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
                                                        <th>Weight LBS</th>
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
                                                        <th>Pallet Data</th>
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
                                                        <th>Weight LBS</th>
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
        }
    } // end class
?>