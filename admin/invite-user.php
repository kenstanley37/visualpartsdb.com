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
    <title>Visual Parts Database: Invite User</title>
    <?php require_once($path."inc/inc.head.php"); ?> <!-- META, CSS, and JavaScript -->
</head>
    
<body>
    <div class="wrapper">
        <header>
            <?php include($path."inc/inc.header.php"); ?>
        </header>
        <aside class="admin-nav-bar hidden">
            <?php include($path."inc/inc.adminnavbar.php"); ?>
        </aside>
        <main class="main">
            <section class="title">
                <h2>Invite User</h2>
            </section>
            <section class="nav">
               
            </section>
            <section class="form">
                <form id="addUser" method="post" action="/processors/register_request.php">
                        <table class="table">
                            <thead>
                                <th colspan="3">Invite User</th>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <label for="regcompany">Company</label>
                                    </td>
                                    <td>
                                        <select name="regcompany" id="regcompany" required>
                                            <?php $user->dropDownCompany(); ?>
                                        </select>
                                    </td>
                                    <td>
                                        <a href="/admin/add-company.php"><i class="fas fa-plus-square"></i></a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label for="regfname">First Name</label>
                                    </td>
                                    <td colspan="2">
                                        <input type="text" name="regfname" required>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label for="reglname">Last Name</label>
                                    </td>
                                    <td colspan="2"> 
                                        <input type="text" name="reglname" required>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label for="regemail">Email Address</label>
                                    </td>
                                    <td colspan="2">
                                        <input type="email" name="regemail" required>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="align-right" colspan="3"><button class=" info" type="submit" name="regsubmit" value="Submit">Submit</button></td>
                                </tr>
                             </tbody>
                        </table>
                    </form>
            </section>
            <section class="content">

            </section>
        </main>
        <footer>
            <?php include($path."/inc/inc.footer.php"); ?>
        </footer>
    </div> <!-- end container -->
</body>
</html>