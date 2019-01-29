<?php
session_start();

if(!isset($_SESSION['user_id'])){
    header('location: /index.php?error=noaccess');
} else {
    $userID = $_SESSION['user_id'];
}


include("../inc/inc.path.php");
require_once($path."class/class.user.php");
require_once($path."class/class.visualdb.php");
require_once($path."class/class.func.php");

$vpd = new VISUALDB;
$vail = new VALIDATE;
$user = new USER;

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

if($user->accessCheck() != 'ADMIN'){
    $userID = $_SESSION['user_id'];
}

$result = '';
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
        <?php
        if($user->accessCheck() == "ADMIN")
        {
            ?>
            <aside class="admin-nav">
                <?php include($path."inc/inc.adminnavbar.php"); ?>
            </aside>
            <?php
        }
       ?>
        <main class="my-search-main">  
            <article id="my-search-graph" class="my-search-graph"></article>
            <article id="my-search-pie" class="my-search-pie"></article>
            <article class="my-search-head">
                <form action="/user/mysearches.php" method="get">
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

                                    <?php 
                                        if($user->accessCheck() == "ADMIN")
                                        {
                                            ?>
                                            <tr>
                                                <td>
                                                    <label for="users">Select User:</label>
                                                </td>
                                                <td>
                                                    <select id="users" name="usersID">
                                                        <?php $user->dropDownUser($userID); ?>
                                                    </select> 
                                                </td>
                                            </tr>
                                            
                                            
                                            <?php
                                        } else
                                        {
                                        ?>
                                            <tr>
                                                <td>
                                                    <label for="users" hidden>Select User:</label>
                                                </td>
                                                <td>
                                                    <select id="users" name="usersID" hidden>
                                                        <?php $user->dropDownUser($userID); ?>
                                                    </select> 
                                                </td>
                                            </tr>
                                    <?php
                                    }; ?>

                            <tr>
                                <td>
                                    <input class="search-button" type="submit" value="Search">
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </form>
            </article>
            <article class="my-search-body">            
                <?php 
                    $vpd->mySearches($dateStart, $dateEnd, $userID, 50);
                 ?>
            </article>
            <article class="my-search-foot"></article>
        </main>
        <footer>
            <?php include($path."inc/inc.footer.php"); ?>
        </footer>
    </div> <!-- end container -->
</body>
</html>