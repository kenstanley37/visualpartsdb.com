<?php
/**
* VIEW for user search history
*
* @author Ken Stanley <ken@stanleysoft.org>
* @license MIT
*/
session_start();

require_once(__DIR__.'../../vendor/autoload.php');

use user\user;
use sku\sku;
use sec\sec;

$sku = new sku;
$sec = new sec;
$user = new user;

$userName = '';
$searchUserID = '';

if(!isset($_SESSION['user_id']))
{
    header('location: /');
} else 
{
    $userID = $_SESSION['user_id'];
    $user->activeCheck($userID);
    if($user->accessCheck() != 'ADMIN'){
        header('location: /noaccess.php');
    }
}

$date = date("Y-m-d");
$dateStart = strtotime('-1 day', strtotime($date));
$dateStart = date("Y-m-d", $dateStart);
$dateEnd = date("Y-m-d");

if(isset($_GET['search-history']))
{
    $dateStart = $_GET['dfrom'];
    $dateEnd = $_GET['dto'];
    $searchUserID = $_GET['usersID'];
    if(!empty($searchUserID))
    {
       $userName = $user->userFullName($searchUserID); 
    } 
} 
$result = '';
$dropdown = $user->getUserList();
$searchHist = $sku->mySearches($dateStart, $dateEnd, $searchUserID);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Visual Parts Database : Search History</title>
    <?php require_once(__DIR__."../../inc/inc.head.php"); ?> <!-- META, CSS, and JavaScript -->
</head>
<body>
    <div class="wrapper">
        <header>
            <?php include(__DIR__."../../inc/inc.header.php");?>
        </header>
        <aside class="admin-nav-bar hidden">
        <?php
        if($user->accessCheck() == "ADMIN")
        {
        ?>
            <?php include(__DIR__."../../inc/inc.adminnavbar.php"); ?>
        <?php
        }
        ?>
        </aside>
        <main class="main">  
            <section class="title">
                <h2 class="blue-header">Search History</h2>
            </section>
            
            <div class="content" id="MyHistSearchCharts">
                <div class="grid-temp-30-70 w100p">
                    <div class="w100p shadow bg-white">
                        <section class="form-contact">
                            <h2 class="login-title">Date Range</h2>
                            <form action="/admin/search-history.php" method="get">
                                <input type="text" name="tempID" id="tempID" value="<?php echo $userID; ?>" hidden>
                                
                                <label for="dfrom">Date From: </label>
                                <input type="text" name="dfrom" id="dfrom" value="<?php echo $dateStart; ?>">
                                
                                 <label for="dto">Date To:</label>
                                <input type="text" name="dto" id="dto" value="<?php echo $dateEnd; ?>">
                                
                                <select class="select-css adminusers" id="users" name="usersID">
                                    <option value="">Select User</option>
                                    <?php
                                    foreach($dropdown as $row)
                                    {
                                    ?>
                                    <option value="<?php echo $row['user_id']; ?>"
                                        <?php if($row['user_id'] == $searchUserID ){ echo 'selected';}?>>
                                        <?php echo $row['user_fName'].' '.$row['user_lName']; ?> 
                                    </option>
                                    <?php
                                    }
                                    ?>
                                </select> 
                                <input class="btn-blue" type="submit" value="Search" name="search-history">
                            </form>
                        </section>
                    </div>
                    
                    <section class="w100p bg-white shadow">
                        <h2 class="login-title">Charts</h2>
                        <div class="charts">
                            <div id="my-search-graph" class="my-search-graph"></div>
                            <div id="my-search-pie" class="my-search-pie"></div>
                        </div>
                    </section>
                </div>
            </div>
                    
            <div class="content2">            
                <section class="w100p shadow bg-white">
                    <h2 class="login-title">Search History <?php if(!empty($userName)){ echo 'for '.$userName;} ?></h2>
                    <?php if(!empty($searchHist))
                    {
                    ?>
                    <table class="table display nowrap">
                        <thead>
                            <tr>
                                <th class="align-left">Part Number</th>
                                <th class="align-left">Description</th>
                                <th class="align-left">Search Count</th>
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
                    <?php
                    } else 
                    {
                        ?>
                            <p>No history for selected date range</p>
                        <?php

                    }
                    ?>
                </section>
            </div>
            <div class="my-search-foot"></div>
        </main>
        <footer>
            <?php include(__DIR__."../../inc/inc.footer.php"); ?>
        </footer>
    </div> <!-- end container -->
</body>
</html>