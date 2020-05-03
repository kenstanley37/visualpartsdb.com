<?php
/**
* VIEW for displaying all members
*
* @author Ken Stanley <ken@stanleysoft.org>
* @license MIT
*/
session_start();
require_once(__DIR__.'../../vendor/autoload.php');

use user\user;
use sec\sec;

$sec = new sec;
$user = new user;

$checkID = ''; // set if the logged in user id = user list id

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

if(isset($_GET['register']))
{
    $temp = $_GET['register'];
    if($temp == 'successful')
    {
        $result = 'Registeration request has been sent';
    }
}

$pending = $user->userList('active');



?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Visual Parts Database: User Management</title>
    <?php require_once(__DIR__."../../inc/inc.head.php"); ?> <!-- META, CSS, and JavaScript -->
</head>
<body>
    <div class="wrapper">
        <header>
            <?php include(__DIR__."../../inc/inc.header.php"); ?>
        </header>
        <!-- USER SECTION -->
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
                <h2 class="blue-header">Member List</h2>
            </section>
            <div class="content">
                <div class="grid-temp-30-70 w100p">
                    <div class="w100p shadow lh25 bg-white mh500">
                        <h2 class="login-title">Description</h2>
                        <p>A list of all members of the website</p>
                    </div>
                    <div class="w100p shadow bg-white">
                        <h2 class="login-title">Members</h2>
                        <?php 
                        if(!empty($pending))
                        {
                        ?>
                        <table  class="table display nowrap">
                            <thead>
                                <tr>
                                    <td>First Name</td>
                                    <td>Last Name</td>
                                    <td>Email</td>
                                    <td>Company</td>
                                    <td>Status</td>
                                    <td>Role</td>
                                    <td>Member Since</td>
                                    <td></td>
                                </tr>
                            </thead>
                            <tbody>
                        <?php

                                foreach($pending as $row)
                                {
                                    $date = $row['user_reg_date'];
                                    $dateadded = date_create($date);
                                    $addDate = date_format($dateadded, 'm/d/Y');
                                    if($userID == $row['user_id'])
                                    {
                                        $checkID = 'TRUE';
                                    } else
                                    {
                                        $checkID = 'FALSE';
                                    }
                                    ?>
                                    <tr <?php if($checkID == 'TRUE'){echo 'class="bg-grey"';} ?>>
                                        <td data-label="First">
                                            <?php echo $row['user_fName']; ?>
                                        </td>
                                        <td data-label="Last">
                                            <?php echo $row['user_lName']; ?>
                                        </td>
                                        <td data-label="Email">
                                            <?php echo $row['user_email']; ?>
                                        </td>
                                        <td data-label="Company">
                                            <?php echo $row['company_name']; ?>
                                        </td>
                                        <td data-label="Status">
                                            <?php if($checkID == 'FALSE')
                                            {
                                            ?>
                                            <form method="post" action="/processors/userManagement.php">
                                                <input type="text" name="activeSwitch" value="<?php echo $row['user_id']; ?>" hidden>
                                                <button type="submit" class="btn <?php if($row['user_active'] == 1){ echo "active";} else{ echo "danger";}; ?>">
                                                    <?php if($row['user_active'] == 1){ echo "Active";} else{ echo "Disabled";}; ?>
                                                </button>
                                            </form>
                                            <?php
                                            }
                                            ?>
                                            
                                        </td>
                                        <td data-label="Role">
                                            <?php if($checkID == 'FALSE')
                                            {
                                            ?>
                                            <form action="/processors/userManagement.php" method="post">
                                                <input type="number" name="userID" value="<?php echo $row['user_id']; ?>" hidden>
                                                <table class="tbl-btns">
                                                    <tr>
                                                        <td>
                                                            <button class="btn <?php if($row['role_name'] == 'USER'){ echo "active";} else { echo "inactive";} ?>" type="submit" name="setToUser">
                                                                USER 
                                                            </button>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <button class="btn <?php if($row['role_name'] == 'ADMIN'){ echo "active";} else { echo "inactive";} ?>" type="submit" name="setToAdmin">
                                                                ADMIN 
                                                            </button>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </form>
                                            <?php
                                            }
                                            ?>
                                        </td>
                                        <td data-label="Member Since">
                                            <?php echo $addDate; ?>
                                        </td> 
                                        <td>
                                            <?php if($checkID == 'FALSE')
                                            {
                                            ?>
                                            <form action="/admin/deleteuser.php" method="post">
                                                <input hidden type="text" name="userID" value="<?php echo $row['user_id']; ?>">
                                                <button type="submit" name="remUser" class="btn danger">DELETE</button>
                                            </form>
                                            <?php
                                            }
                                            ?>
                                        </td>
                                    </tr>
                                    <?php
                                    }
                                } else 
                            {
                                ?>
                                    <p>There are currently no pending users.</p>
                                <?php
                            }
                                ?>
                             </tbody>
                        </table> 
                    </div>
                </div>   
            </div>
        </main>
        <footer>
        <?php include(__DIR__."../../inc/inc.footer.php"); ?>
        </footer>
    </div> <!-- end container -->
</body>
</html>