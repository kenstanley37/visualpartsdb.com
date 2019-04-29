<?php
/**
* VIEW for users My Searches
*
* @author Ken Stanley <ken@stanleysoft.org>
* @license MIT
*/
session_start();

include("../inc/inc.path.php");
require_once($path."class/class.user.php");
require_once($path."class/class.visualdb.php");
require_once($path."class/class.func.php");

$vpd = new VISUALDB;
$vail = new VALIDATE;
$user = new USER;

if(!isset($_SESSION['user_id']))
{
    header('location: /');
} else 
{
    $userID = $_SESSION['user_id'];
    $user->activeCheck($userID);
}

$date = date("Y-m-d");
$dateStart = strtotime('-1 day', strtotime($date));
$dateStart = date("Y-m-d", $dateStart);
$dateEnd = date("Y-m-d");

if(isset($_GET['mySearches']))
{
    $dateStart = $_GET['dfrom'];
    $dateEnd = $_GET['dto'];
} 
$result = '';
$searchHist = $vpd->mySearches($dateStart, $dateEnd, $userID);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Visual Parts Database : My Searches</title>
    <?php require_once($path."inc/inc.head.php"); ?> <!-- META, CSS, and JavaScript -->
</head>
<body>
    <div class="wrapper">
        <header>
            <?php include($path."inc/inc.header.php");?>
            
        </header>
        <aside class="admin-nav-bar hidden">
        <?php
        if($user->accessCheck() == "ADMIN")
        {
        ?>
            <?php include($path."inc/inc.adminnavbar.php"); ?>
        <?php
        }
        ?>
        </aside>
        <main class="main">  
            <section class="title">
                    <h2 class="blue-header">My Searches</h2>
            </section>
            
            <article class="content">
                <div class="grid-temp-30-70 w100p">
                    <div class="w100p shadow bg-white">
                        <div class="form-contact">
                            <h2 class="login-title">Date Range</h2>
                            <form action="/user/mysearches.php" method="get">
                            <input type="text" name="tempID" id="tempID" value="<?php echo $userID; ?>" hidden>

                                <label for="dfrom">Date From:</label>
                            <input placeholder="From Date:" type="text" name="dfrom" id="dfrom" value="<?php echo $dateStart; ?>">

                            <label for="dto">Date To:</label>
                            <input placeholder="To Date:" type="text" name="dto" id="dto" value="<?php echo $dateEnd; ?>">
                                
                            <input class="btn-blue" type="submit" name="mySearches" value="View">    
                            </form>
                        </div>
                    </div>
                
                    <section class="w100p shadow bg-white" id="mySearchCharts">
                        <h2 class="login-title">Charts</h2>
                        <div class="charts">
                            <div id="my-search-graph" class="my-search-graph"></div>
                            <div id="my-search-pie" class="my-search-pie"></div>
                        </div>
                    </section>
                </div> <!-- end grid-wrap -->
            </article>

            <div class="content2">            
                <section class="w100p shadow bg-white">
                    <h2 class="login-title">My Searches</h2>
                    <table class="table nowrap">
                        <thead>
                            <tr>
                                <th>Part Number</th>
                                <th>Description</th>
                                <th>Count</th>
                            </tr>
                        </thead> 
                        <tbody>
                            <?php
                            foreach($searchHist as $row)
                            {
                            ?>
                            <tr>
                                <td data-label="SKU">
                                <a class="sku-name" href="/search.php?search=<?php echo $row['sku_search_sku']; ?>"><?php echo $row['sku_search_sku']; ?></a>
                                </td>
                                <td data-label="Description">
                                <?php echo $row['sku_desc']; ?>
                                </td>
                                <td data-label="Count">
                                <?php echo $row['count']; ?>
                                </td>
                            </tr>
                            <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </section>
            </div>
            <div class="my-search-foot"></div>
        </main>
        <footer>
            <?php include($path."inc/inc.footer.php"); ?>
        </footer>
    </div> <!-- end container -->
</body>
</html>