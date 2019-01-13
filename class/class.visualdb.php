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
        } // end construct
        
        // *************************************************************
        // Destruct - end database connection
        // *************************************************************
        public function __destruct()
        {
            $this->conn = null;
        } // end destruct
        
        public function skuSearch($sku)
        {
            try
            {
                $stmt = $this->conn->prepare("SELECT * FROM sku 
                    left join sku_image on sku_image_sku_id = sku_id
                    WHERE sku_id=:sku and sku_image_feature = 1");
                $stmt->execute(array(':sku'=>$sku));
                $skuRow=$stmt->fetch(PDO::FETCH_ASSOC);
                if($stmt->rowCount() == 1)
                {
                
                    ?>
                        
                        <main class="partInfo">
                            <section class="partImg">
                                <img class="article-img" src="<?php echo $skuRow['sku_image_url']; ?>" alt="<?php echo $skuRow['sku_image_description']; ?>" />
                            </section>
                            <section class="partDetails">
                                <table>
                                    <tr>
                                        <th>Part #</th>
                                        <th>Description</th>
                                        <th>Unit Length</th>
                                        <th>Unit Width</th>
                                        <th>Unit Height</th>
                                    </tr>
                                    <tr>
                                        <td><?php echo $skuRow['sku_id']; ?></td>
                                        <td><?php echo $skuRow['sku_desc']; ?></td>
                                        <td><?php echo $skuRow['sku_sig_length']; ?></td>
                                        <td><?php echo $skuRow['sku_sig_width']; ?></td>
                                        <td><?php echo $skuRow['sku_sig_height']; ?></td>
                                    </tr>
                                </table> 
                            </section>
                            <section class="partRev">
                                <table>
                                    <tr>
                                        <th>Date Added</th>
                                        <th>Added By</th>
                                        <th>Last Updated</th>
                                        <th>Updated By</th>
                                    </tr>
                                    <tr>
                                        <td><?php echo $skuRow['sku_rec_date']; ?></td>
                                        <td><?php echo $skuRow['sku_rec_added']; ?></td>
                                        <td><?php echo $skuRow['sku_rec_update']; ?></td>
                                        <td><?php echo $skuRow['sku_rec_update_by']; ?></td>
                                    </tr>
                                </table> 
                            </section>
                            <section class="partSupplier">
                                <table>
                                    <tr>
                                        <th>Supplier</th>
                                        <th>Business #</th>
                                        <th>Address</th>
                                        <th>Email</th>
                                    </tr>
                                    <tr>
                                        <td><?php echo $skuRow['sku_supplier']; ?></td>
                                        <td>test</td>
                                        <td>test</td>
                                        <td>test</td>
                                    </tr>
                                </table>  
                            </section>
                        </main>
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