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
                $stmt = $this->conn->prepare("SELECT * FROM sku WHERE sku_id=:sku ");
                $stmt->execute(array(':sku'=>$sku));

                if($stmt->rowCount() == 1)
                {
                
                    ?>
                        

                        <article class="image">
                            <img class="article-img" src="http://placekitten.com/305/205" alt=" " />
                            <h1 class="article-title">
                                Title of article
                            </h1>
                        </article>
                        <article class="info">
                            <img class="article-img" src="http://placekitten.com/320/220" alt=" " />
                            <h1 class="article-title">
                                Title of article
                            </h1>
                        </article>
                        <article class="date">
                            <img class="article-img" src="http://placekitten.com/330/240" alt=" " />
                            <h1 class="article-title">
                                Title of article
                            </h1>
                        </article>
                        <article class="request">
                            <img class="article-img" src="http://placekitten.com/280/250" alt=" " />
                            <h1 class="article-title">
                                Title of article
                            </h1>
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