<?php
session_start();
include("inc/inc.path.php");
require_once($path."class/class.user.php");
require_once($path."class/class.visualdb.php");
require_once($path."class/class.func.php");

$vpd = new VISUALDB;
$vail = new VALIDATE;

if(isset($_GET['result']))
{
    $requestResult = $_GET['result'];
    if($requestResult == 'alreadyregistered') 
    {
        $requestResult = 'Email address is already registered';
    } 
    
    if($requestResult == 'alreadyrequested') 
    {
        $requestResult = 'Email already awaiting approval';
    } 
    
    if($requestResult == 'success') 
    {
        $requestResult = '';
        $rrSuccess = 'Thank you';
    }
    
    
    if(isset($_GET['fname'])){
        $fname = ucfirst($_GET['fname']);
    }
    if(isset($_GET['lname'])){
        $lname = ucfirst($_GET['lname']);
    }
    if(isset($_GET['email'])){
        $email = strtolower($_GET['email']);
    }
    if(isset($_GET['phone'])){
        $phone = ucfirst($_GET['phone']);
    }
    if(isset($_GET['company'])){
        $company = ucfirst($_GET['company']);
    }
    if(isset($_GET['message'])){
        $message = ucfirst($_GET['message']);
    }
}

if(isset($_GET['noaccess'])){
    $error = 'You must be a registered user';
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>Visual Parts Database</title>
    <?php require_once("inc/inc.head.php"); ?> <!-- META, CSS, and JavaScript -->
</head>
<body>
    <div class="wrapper">
        <header>
            <?php include($path."inc/inc.header.php"); ?>
        </header>
        <?php
        if($user->accessCheck() == "ADMIN")
        {
            ?>
            <nav class="adminnav">
                <?php include($path."inc/inc.adminnavbar.php"); ?>
            </nav>
            <?php
        }
       ?>
        <main id="aboutvpd" class="index-main">
            <article class="index-search">
                <section><h1>Visual Parts Database</h1></section>
                <section>                
                    <form class="search" action="/search.php" method="get">
                        <label hidden for="search">Search</label>
                        <input class="toupper" type="text" name="search" id="search" placeholder="Part Number or Description">
                    </form>
                </section>
                <section class="records"><?php $vpd->recordCount(); ?></section>
            </article>
            <article class="main-intro">
                <section class="intro">
                    <h1>Parts</h1>
                    <p>Our database of parts contains information on over 50,000 components from popular brands such as:</p>
                    <ul>
                        <li><a href="https://www.electroluxappliances.com/">Electrolux</a></li>
                        <li><a href="https://www.frigidaire.com/">Frigidaire</a></li>
                        <li><a href="https://www.kelvinatorcommercial.com/">Kelvinator</a></li>
                        <li><a href="https://www.aeg.com/">AEG</a></li>
                        <li><a href="http://gibson-intl.com/">Gibson</a></li>
                        <li><a href="https://www.zanussi.com/">Zanussi</a></li>
                        <li><a href="https://professional.electrolux.com/">Professional</a></li>
                    </ul>
                </section>
                <section id="staticImg">
                    <?php echo $vpd->randImage(); ?>
                </section>
            </article> <!-- end main-intro -->
            <article id="member">
                <section class="member-header">
                    <h2>Membership Information</h2>
                </section>
                <section class="member-info">
                    <h3>Membership Benefits</h3>
                    <ul>
                        <li>Custom Item List: create custom list of items for exporting data</li>
                        <li>Export Data: individual items or item list
                            <ul>
                                <li>Excel</li>
                                <li>PDF</li>
                                <li>CSV</li>
                            </ul>
                        </li>
                        <li>Search History: create date range reports of your search history</li>
                        <li>Visual Data: graphs and charts on skus or sku list</li>
                        <li>Request Updates: request updates on sku data with a click of a button</li>
                    </ul>
                </section>
                <section class="member-request">
                    <h3>Request Membership</h3>
                    <form action="/processors/register_request.php" method="get" class="form-example" id="requestForm">
                        <fieldset>
                            <div class="reg-form">
                                <label for="fname">First Name: </label>
                                <input type="text" name="fname" id="fname" <?php if(isset($fname)){ echo 'value='.$fname;} ?> required>
                                <i class="fas fa-asterisk"></i>
                            </div>
                            <div class="reg-form">
                                <label for="lname">Last Name: </label>
                                <input type="text" name="lname" id="lname" <?php if(isset($lname)){ echo 'value='.$lname;} ?> required>
                                <i class="fas fa-asterisk"></i>
                            </div>
                            <div class="reg-form">
                                <label for="email">Email: </label>
                                <input type="email" name="email" id="email" <?php if(isset($email)){ echo 'value='.$email;} ?> required>
                                <i class="fas fa-asterisk"></i>
                                <span><?php if(isset($requestResult)){echo $requestResult;}?></span>
                            </div>
                            <div class="reg-form">
                                <label for="phone">Phone: </label>
                                <input type="tel" name="phone" id="phone" pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}"  placeholder="xxx-xxx-xxxx" <?php if(isset($phone)){ echo 'value='.$phone;} ?> required>
                                <i class="fas fa-asterisk"></i>
                            </div>
                            <div class="reg-form">
                                <label for="email">Company: </label>
                                <input type="text" name="company" id="company" <?php if(isset($company)){ echo 'value='.$company;} ?> required>
                                <i class="fas fa-asterisk"></i>
                            </div>
                            <div class="reg-form reg-textarea">
                                <label for="messagearea">Message: </label>
                                <textarea type="text" name="messagearea" id="messagearea" id="messagearea" rows="4" cols="20"><?php if(isset($message)){ echo 'value='.$message;} ?></textarea>
                            </div>
                            <div class="reg-form">
                                <input type="submit" value="SUBMIT"><span><?php if(isset($rrSuccess)){echo $rrSuccess;} ?></span>
                            </div>
                        </fieldset>
                    </form>
                </section>
        </article> <!-- end member -->
        </main>
        <footer>
            <?php include("inc/inc.footer.php"); ?>
        </footer>
    </div> <!-- end container -->
</body>
</html>