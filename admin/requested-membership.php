<?php
/**
* Author - Ken Stanley
* File Name - requested-membership.php
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
    if($user->accessCheck() != 'ADMIN'){
        header('location: /noaccess.php');
    }
}

$regRequest =  $user->regRequestList();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Visual Parts Database: Requested Membership</title>
    <?php require_once($path."inc/inc.head.php"); ?> <!-- META, CSS, and JavaScript -->
</head>
<body>
    <div class="wrapper">
        <header>
            <?php include($path."inc/inc.header.php"); ?>
        </header>
        <!-- USER SECTION -->
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
                <h2 class="blue-header">Requested Membership</h2>
            </section>
            <div class="content">
                <div class="grid-temp-30-70 w100p">
                    <section class="w100p shadow lh25 bg-white">
                        <h2 class="login-title">Description</h2>
                        <p>This is a list of users who have requested membership through the form on the home page.</p>
                    </section>
                    <section class="w100p shadow bg-white">
                        <h2 class="login-title">Member Request</h2>
                        <?php 
                         if(!empty($regRequest))
                         {
                             ?>
                           <table id="dataTable" class="display nowrap">
                                <thead>
                                    <tr>
                                        <td>First Name</td>
                                        <td>Last Name</td>
                                        <td>Email</td>
                                        <td>Phone</td>
                                        <td>Company</td>
                                        <td>Message</td>
                                        <td>Date</td>
                                        <td></td>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php
                                 foreach($regRequest as $row)
                                {
                                    $date = $row['rr_date'];
                                    $dateadded = date_create($date);
                                    $addDate = date_format($dateadded, 'm/d/Y');
                                    ?>
                                    <tr>
                                        <td data-label="First">
                                            <?php echo $row['rr_fname']; ?>
                                        </td>
                                        <td data-label="Last">
                                            <?php echo $row['rr_lname']; ?>
                                        </td>
                                        <td data-label="Email">
                                            <?php echo $row['rr_email']; ?>
                                        </td>
                                        <td data-label="Tel">
                                            <?php echo $row['rr_phone']; ?>
                                        </td>
                                        <td data-label="Company">
                                            <?php echo $row['rr_company']; ?>
                                        </td>
                                        <td data-label="Message">
                                            <?php echo $row['rr_message']; ?>
                                        </td>
                                        <td data-label="Date">
                                            <?php echo $addDate; ?>
                                        </td> 
                                        <td>
                                            <form action="/processors/userManagement.php" method="post">
                                                <input hidden type="text"  name="recordID" value="<?php echo $row['rr_id']; ?>">
                                                <button class="btn danger" type="submit" name="remRegister">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                    <?php
                                }
                             } else
                             {
                                 ?>
                                    <p>There are currently no requested memberships</p>
                                <?php
                             }

                                    ?>
                            </tbody>
                        </table> 
                    </section>
                </div> 
            </div>    
        </main>
        <footer>
        <?php include($path."inc/inc.footer.php"); ?>
        </footer>
    </div> <!-- end container -->
</body>
</html>