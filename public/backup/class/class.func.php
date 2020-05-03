<?php
     /**
     * VALIDATE handles all types of validation methods
     *
     * @author Ken Stanley <ken@stanleysoft.org>
     * @license MIT
     */

            /**Load the database class*/
    require_once('class.db.php');
    /**
     * VALIDATE handles all types of validation methods
     *
     * @author Ken Stanley <ken@stanleysoft.org>
     * @license MIT
     */
    class VALIDATE {        
        /**
         * database connection
         *
         * @var string
         */
        private $conn;

        /**
        * Constructor to connect to the database
        *
        * @throws \PDOException
        * @author Ken Stanley <ken@stanleysoft.org>
        */
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
        
        /**
        * Removes slashes, html, and tags from a string
        *
        * @param string $str the requested string to clean up
        * @return $str returns the clean string back
        * @author Ken Stanley <ken@stanleysoft.org>
        */
        function sanitizeString($str)
        {
            $str = stripslashes($str);
            $str = htmlentities($str);
            $str = strip_tags($str);
            return $str;
        }
        
        /**
        * Checks if email is valid and returns header get error if not
        *
        * @param string $emailAddress the submitted email address
        * @author Ken Stanley <ken@stanleysoft.org>
        */
        function validEmail($emailAddress){
            $_SESSION['emailcheck'] = $emailAddress;
            if(empty($emailAddress)){
                header('location: /login.php?error=noemail');
            } else {
                if(!filter_var($emailAddress, FILTER_VALIDATE_EMAIL)){
                    header('location: /login.php?error=invalidemail');
                }
            }
            
        } // end validEmail
        
        /**
        * Creates a sitemap from the SKU table
        *
        * @author Ken Stanley <ken@stanleysoft.org>
        * @filfe = sitemap.php
        */
        function sitemap(){
            $rowCount = 0; // number of rows from the database;
            $count = 1; // ticker for naming the sitemap;
            $start = 1; // start at row;
            $end = 5000; //end at row;
            $jump = 5000; // how many records to jump each time
            $loopCount = 0; //how many times to loop
            
            settype($start, "integer");
            settype($end, "integer");
            $date = date("Y-m-d");
            try 
            {
                $stmt = $this->conn->prepare("SELECT * FROM sku");
                $stmt->execute();
                $rowCount = $stmt->rowCount();
                $loopCount = $rowCount / $jump;
            }
            catch(PDOException $e)
            {
                echo $e->getMessage();
            }
            $xmlofsitemaps = '<?xml version="1.0" encoding="UTF-8"?>';
            $xmlofsitemaps .= '<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
            
            // this variable will contain the XML sitemap that will be saved in $xmlfile
            for( $i = 0; $i < $loopCount; $i++ ) 
            {
                try 
                {
                    $query = "SELECT * FROM sku LIMIT $start, $end";
                    $stmt = $this->conn->prepare($query);
                    $stmt->execute();
                    $result = $stmt->fetchAll();

                    $xmlsitemap = '<?xml version="1.0" encoding="UTF-8"?>';
                    $xmlsitemap .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
                    foreach($result as $row)
                    {
                        $xmlsitemap .= '
                        <url>
                            <loc>https://visualpartsdb.com/search.php?search='.$row['sku_id'].'</loc>
                            <lastmod>'.$date.'</lastmod>
                        </url>';
                        
                    }
                    $xmlsitemap .= '</urlset>';
                    $xmlsitemap = gzencode($xmlsitemap);
                    $sitemap ='sitemap'.$count.'.xml.gz';
                    echo $sitemap.'<br>';
                    file_put_contents($sitemap, $xmlsitemap); // saves the sitemap on server

                    // outputs the sitemap (delete this instruction if you not want to display the sitemap in browser)
                    //echo $xmlsitemap;
                }
                catch(PDOException $e)
                {
                    echo $e->getMessage();
                }
                $count++;
                $start += $jump;
                $end += $jump;
                $xmlofsitemaps .= '
                    <sitemap>
                      <loc>https://visualpartsdb.com/sitemaps/'.$sitemap.'</loc>
                      <lastmod>'.$date.'</lastmod>
                   </sitemap>
                ';
                
            }
            $xmlofsitemaps .= '</sitemapindex>';
            $xmlofsitemaps = gzencode($xmlofsitemaps);
            echo 'sitemap.xml.gz';
            file_put_contents('sitemap.xml.gz', $xmlofsitemaps); // saves the sitemap on server
        } // end 
        
    }

?>