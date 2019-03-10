<?php
session_start();
include("../inc/inc.path.php");
require_once($path."class/class.user.php");
require_once($path."class/class.visualdb.php");
require_once($path."class/class.func.php");

$vpd = new VISUALDB;
$vail = new VALIDATE;

if(!isset($_SESSION['user_id']))
{
    header('location: /');
} else 
{
    $user = new USER;
    if($user->accessCheck() != 'ADMIN'){
        header('location: /');
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


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Visual Parts Database: User Management</title>
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
            <main id="aboutvpd" class="admin-main">
                <section class="user-management-nav">
                    <?php include($path."/inc/inc.useradmin.php"); ?>
                </section>
                <section class="admin-head">
                    <h1>User Management</h1>
                </section>
                <section class="add-user">
                    <h2>Add User</h2>
                    <form id="addUser" method="post" action="/processors/register_request.php">
                        <table class="reg-table">
                            <tbody>
                                <tr>
                                    <td>
                                        <label>Company</label>
                                    </td>
                                    <td>
                                        <select name="regcompany" id="regcompany" required>
                                            <?php $user->dropDownCompany(); ?>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td><label for="regfname">First Name</label></td>
                                    <td><input type="text" name="regfname" required></td>
                                </tr>
                                <tr>
                                    <td>
                                        <label for="reglname">Last Name</label>
                                    </td>
                                    <td> 
                                        <input type="text" name="reglname" required>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label for="regemail">Email Address</label>
                                    </td>
                                    <td>
                                        <input type="email" name="regemail" required>
                                    </td>
                                </tr>
                             </tbody>
                        </table>
                        <input type="submit" name="regsubmit" value="Submit">
                    </form>
                                        <?php
                    if(isset($result))
                    {
                        echo '<span>'.$result.'</span>';
                    }
                    ?>
                </section>
                <section>
                    <h1>User List</h1>
                    <table class="table">
                        <thead>
                            <tr>
                                <td>Member ID</td>
                                <td>First Name</td>
                                <td>Last Name</td>
                                <td>Email</td>
                                <td>Company</td>
                                <td>Active</td>
                                <td>Role</td>
                                <td>Member Since</td>
                                <td>Role</td>
                                <td></td>
                                <td></td>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $user->userList(); ?>
                        </tbody>
                    </table>
                </section>    
        </main>
        <footer>
            <?php include($path."inc/inc.footer.php"); ?>
        </footer>
    </div> <!-- end container -->
</body>
</html>