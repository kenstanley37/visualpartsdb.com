<?php
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
    if($user->accessCheck() != 'ADMIN'){
        header('location: /noaccess.php');
    }
}

$date = date("Y-m-d");
$dateStart = strtotime('-1 day', strtotime($date));
$dateStart = date("Y-m-d", $dateStart);
$dateEnd = date("Y-m-d");

if(isset($_GET['dfrom']))
{
    $dateStart = $_GET['dfrom'];
    $dateEnd = $_GET['dto'];
    if(isset($_GET['usersID']))
    {
        $userID = $_GET['usersID'];
    } else {
        $userID = '';
    }
} 
$result = '';
$dropdown = $user->getUserList();
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
        <main class="my-search-main">  
            <article id="my-search-graph" class="my-search-graph"></article>
            <article id="my-search-pie" class="my-search-pie"></article>
            <article class="my-search-head">
                <form action="/admin/search-history.php" method="get">
                    <input type="text" name="tempID" id="tempID" value="<?php echo $userID; ?>" hidden>
                    <table>
                        <tbody>
                            <tr>
                                <td>
                                    <label for="dfrom">Date From:</label>
                                </td>
                                <td>
                                    <input type="text" name="dfrom" id="dfrom" value="<?php echo $dateStart; ?>">
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="dto">Date To:</label>
                                </td>
                                <td>
                                    <input type="text" name="dto" id="dto" value="<?php echo $dateEnd; ?>">
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <label for="users" hidden>Select User:</label>
                                </td>
                                <td>
                                    <select id="users" name="usersID">
                                        <option value=""></option>
                                        <?php
                                        foreach($dropdown as $row)
                                        {
                                        ?>
                                        <option value="<?php echo $row['user_id']; ?>"
                                            <?php if($row['user_id'] == $userID ){ echo 'selected';}?>>
                                            <?php echo $row['user_fName']; ?> <?php echo $row['user_lName']; ?>
                                        </option>
                                        <?php
                                        }
                                        ?>
                                    </select> 
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <input class="search-button" type="submit" value="Search">
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </form>
            </article>
            <article class="my-search-body shadow">            
                <section class="my-search-results">
                    <h2 class="shadow">Search History</h2>
                    <table class="table-nores shadow">
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
                                            <td scope="row" data-label="SKU">
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
            </article>
            <article class="my-search-foot"></article>
        </main>
        <footer>
            <?php include($path."inc/inc.footer.php"); ?>
        </footer>
    </div> <!-- end container -->
</body>
</html>