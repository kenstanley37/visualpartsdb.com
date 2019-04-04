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

if(isset($_GET['register']))
{
    $temp = $_GET['register'];
    if($temp == 'successful')
    {
        $result = 'Registeration request has been sent';
    }
}

$company = $user->dropDownCompany();

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
                <h2 class="blue-header">Invite User</h2>
            </section>
            <section class="nav">
                <section class="display">
                    <div class="login shadow">
                         <div class="form-contact">
                            <h3 class="login-title">Register Invite</h3>
                            <form id="addUser" method="post" action="/processors/register_request.php">
                                <fieldset>
                                    <select class="select-css" name="regcompany" id="regcompany" required>
                                            <label for="regcompany">Company</label>
                                            <option placeholder="Company" value=""></option>
                                            <?php foreach($company as $row)
                                            {
                                                ?>
                                                <option value="<?php echo $row['company_id']; ?>">
                                                    <?php echo $row['company_name']; ?>
                                                </option>
                                                <?php
                                            }
                                            ?>
                                        </select>
                                    
                                    <input placeholder="First Name" type="text" name="regfname" required>
                                    
                                    <input placeholder="Last Name" type="text" name="reglname" required>
                                    
                                    <input placeholder="Email" type="email" name="regemail" required>
                                    <button class=" info" type="submit" name="regsubmit" value="Submit">Submit</button>
                                </fieldset>
                            </form>
                        </div>
                    </div>
                   
                    
                    
                </section>
            </section>
            <section class="form">
                <span class="error"> <?php if(isset($result)){echo $result;} ?></span>
            </section>
        </main>
        <footer>
            <?php include($path."/inc/inc.footer.php"); ?>
        </footer>
    </div> <!-- end container -->
</body>
</html>