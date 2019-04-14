<?php
/**
* Author - Ken Stanley
* File Name - myexportlist.php
* Revision Date - April, 10 2019
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
    $listcount = $user->getMyListCount($userID, 'list');
    $myList = $user->myList();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Visual Parts Database: My Product List</title>
    <?php include($path."inc/inc.head.php"); ?> <!-- META, CSS, and JavaScript -->
</head>
<body>
    <div class="wrapper">
        <header>
            <?php include($path."inc/inc.header.php"); ?>
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
                <h2 class="blue-header">My List</h2>
            </section>

            <div class="content">
                <div class="grid-temp-30-70 w100p">
                    <section class="w100p mh500 shadow bg-white">
                        <h2 class="login-title">Create List</h2>
                         <div class="form-contact">
                            <form action="/processors/userManagement.php" method="post">
                                <input placeholder="List Name" type="text" id="listname" name="listname" maxlength="10" required>
                                <input placeholder="Description" type="text" id="listdescription" name="listdescription" maxlength="30" required>
                                <button type="submit">Submit</button>
                            </form>
                        </div>
                    </section>

                    <section class="w100p shadow bg-white">
                        <h2 class="blue-header pad-bot-35">Lists</h2>
                        <?php
                            if($listcount >= 1)
                            {
                                ?>
                        <table id="dataTable" class="display nowrap">
                            <thead>
                                <tr>
                                    <td>Status</td>
                                    <td>List</td>
                                    <td>Description</td>
                                    <td>Parts</td>
                                    <td>Date Added</td>
                                    <td>Export</td>
                                    <td></td>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach($myList as $row)
                                {
                                    $date = $row['pl_list_added'];
                                    $dateadded = date_create($date);
                                    $addDate = date_format($dateadded, 'm/d/Y');
                                     if($row['pl_active'] == 1) 
                                    {
                                        $listactive = $row['pl_active'];
                                    } else {
                                        $listactive = 0;
                                    }
                                    $listid = $row['pl_id'];
                                    $count = $user->myListCount($listid);
                                    ?>  
                                        <tr>
                                            <td>
                                                <form action="/processors/userManagement.php" method="post">
                                                    <?php 
                                                        if($listactive==1)
                                                        {
                                                            ?>
                                                           <button class="btn active" disabled>ACTIVE</button>
                                                            <?php
                                                        } else 
                                                        {
                                                            ?>
                                                            <button class="btn inactive" type="submit" value="<?php echo $row['pl_id'];?>" name="makeActive" id="makeActive">Set Active</button>
                                                            <?php
                                                        }

                                                    ?> 
                                                </form>
                                            </td>
                                            <td data-label="Name"><a href="/user/mylistcontents.php?list=<?php echo $row['pl_id'];?>"><?php echo strtoupper($row['pl_list_name']); ?></a></td>
                                            <td data-label="Desc"><?php echo $row['pl_list_desc']; ?></td>
                                            <td data-label="Count"><?php echo $count; ?></td>
                                            <td data-label="Date"><?php echo $addDate; ?></td>
                                            <td data-label="Export"><a href="/export/generate-xlsx.php?unit=excel&list=<?php echo $row['pl_id']; ?>"><i class="far fa-file-excel"></i></a></td>
                                            <td>
                                                <form action="/user/deletelist.php" method="post">
                                                    <input type="text" hidden value="<?php echo $listid; ?>" name="listid" id="listid">
                                                    <input type="text" hidden value="<?php echo $row['pl_list_name']; ?>" name="listname" id="list-name">
                                                    <input type="text" hidden value="<?php echo $count; ?>" name="listcount" id="listcount">
                                                    <button class="btn danger" type="submit" name="deletelist" id="deletelist" value="<?php echo $row['pl_id'];?>">Delete</button>
                                                </form>
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
                                    <p>You do not have any list yet. Please create one.</p>
                                <?php
                            }
                        ?>
                    </section>
                </div>
            </div>
        </main>
        <footer>
            <?php include($path."/inc/inc.footer.php"); ?>
        </footer>
    </div> <!-- end container -->
</body>
</html>