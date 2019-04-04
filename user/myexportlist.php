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
            <section class="nav">
                <section class="display">
                    <section class="login shadow">
                         <section class="form-contact">
                            <h2 class="login-title">Create List</h2>
                            <form action="/processors/userManagement.php" method="post">
                                <label for="listname">List Name:</label>
                                <input type="text" id="listname" name="listname" maxlength="10" required>
                                <label for="listname">List Description:</label>
                                <input type="text" id="listdescription" name="listdescription" maxlength="30" required>
                                <button type="submit">Submit</button>
                            </form>
                        </section>
                    </section>
                </section>
            </section>
            <section class="form">
                <section class="display">
                    <section class="login shadow">
                        <h2 class="blue-header pad-bot-35">Lists</h2>
                        <table class="table shadow">
                            <thead>
                                <tr align="middle">
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
                            if($listcount >= 1)
                            {
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
                                        <tr valign="middle">
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
                                                    <input type="text" hidden value="<?php echo $row['pl_list_name']; ?>" name="listname" id="listname">
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
                </section>
            </section>
        </main>
        <footer>
            <?php include($path."/inc/inc.footer.php"); ?>
        </footer>
    </div> <!-- end container -->
</body>
</html>