<?php
session_start();
include("inc/inc.path.php");
require_once($path."class/class.user.php");
require_once($path."class/class.visualdb.php");
require_once($path."class/class.func.php");

$vpd = new VISUALDB;
$vail = new VALIDATE;
$user = new USER;

$randomImage =  $vpd->randImage('6');

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
    
    if($requestResult == 'alreadyloggedin') 
    {
        $requestResult = '';
        $requestResult = 'You already have an account';
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
<html lang="en">
<head>
    <title>Visual Parts Database</title>
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
        <main id="aboutvpd" class="index-main">
            <section class="index-bg"></section>
            <article class="index-search">
                <h1>Visual Parts Database</h1>
                <section class="search-bar">                
                    <form class="search" action="/search.php" method="get">
                        <label hidden for="search">Search</label>
                        <input class="toupper" type="search" name="search" id="search" placeholder="Part Number"><button type="submit">SEARCH</button>
                    </form>
                </section>
                <section class="records">
                    <?php $vpd->recordCount(); ?>
                </section>
            </article>
            <article class="main-intro">
                <section class="brands shadow">
                    <h2 class="shadow">Brands</h2>
                    <p>Our database of parts contains information on over 50,000 components from popular brands such as:</p>
                    <table>
                        <tbody>
                            <tr>
                                <td><a href="https://www.electroluxappliances.com/">Electrolux</a></td>
                                <td><a href="https://www.frigidaire.com/">Frigidaire</a></td>
                                <td><a href="https://www.kelvinatorcommercial.com/">Kelvinator</a></td>
                            </tr>
                            <tr>
                                <td><a href="https://www.aeg.com/">AEG</a></td>
                                <td><a href="http://gibson-intl.com/">Gibson</a></td>
                                <td><a href="https://www.zanussi.com/">Zanussi</a></td>
                            </tr>
                            <tr>
                                <td><a href="https://professional.electrolux.com/">Professional</a></td>
                            </tr>
                        </tbody>
                    </table>
                </section>
                <section class="information shadow">
                    <h2 class="shadow">Information</h2>
                    <p>We record part information such as weight, length, height, and depth. We record this data at the different stages of the product</p>
                    <table class="table-nores">
                        <tbody>
                            <tr>
                                <td>UNIT</td>
                                <td>Individual piece information</td>
                            </tr>
                            <tr>
                                <td>CASE</td>
                                <td>Case quantity, weight, and size</td>
                            </tr>
                            <tr>
                                <td>PALLET</td>
                                <td>Pallet quantity, weight, and size</td>
                            </tr>
                        </tbody>
                    </table>
                </section>
                <section id="staticImg">
                    <?php
                        foreach($randomImage as $key)
                        {
                            ?>
                             <figure class="card shadow">
                                <div class="card-img">
                                    <a href="<?php echo $key['sku_image_url']; ?>">
                                        <img class="article-img" src="<?php echo $key['sku_image_thumb']; ?>" alt="<?php echo $key['sku_image_sku_id'].'-'.$key['sku_image_description']; ?>" />
                                    </a>
                                </div>
                                 <a href="/search.php?search=<?php echo $key['sku_image_sku_id']; ?>">
                                    <figcaption>
                                            <div class="card-sku-num">
                                                <h4>
                                                    <?php echo $key['sku_image_sku_id']; ?></h4>
                                            </div>
                                            <div class="card-image-desc">
                                                <p><?php echo $key['sku_image_description'];?></p>
                                            </div>
                                            <div class="card-sku-desc">
                                                <p><?php echo $key['sku_desc'];?></p>
                                            </div>
                                    </figcaption>
                                 </a>
                            </figure>
                            <?php
                        }
                    ?>
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
                <section class="member-request shadow">
                    <div class="form-contact">
                        <h3>Request Membership</h3>
                        <form action="/processors/register_request.php" method="get" class="form-example" id="requestForm">
                            <fieldset>

                                <input type="text" name="fname" id="fname" placeholder="First Name" <?php if(isset($fname)){ echo 'value='.$fname;} ?> required>

                                <input type="text" name="lname" id="lname" placeholder="Last Name" <?php if(isset($lname)){ echo 'value='.$lname;} ?> required>
 
                                <input type="email" name="email" id="email" placeholder="Email" <?php if(isset($email)){ echo 'value='.$email;} ?> required>
                                    <?php if(isset($requestResult)){echo '<span>'.$requestResult.'</span>';}?>

                                <input type="tel" name="phone" id="phone" pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}"  placeholder="Phone xxx-xxx-xxxx" <?php if(isset($phone)){ echo 'value='.$phone;} ?> required>

                                <input type="text" name="company" id="company" placeholder="Company" <?php if(isset($company)){ echo 'value='.$company;} ?> required>
                                    
                                <textarea name="messagearea" id="messagearea" placeholder="Message" id="messagearea"><?php if(isset($message)){ echo 'value='.$message;} ?></textarea>
                                
                                <button type="submit" value="SUBMIT" name="RegisterRequest">SEND</button><span><?php if(isset($rrSuccess)){echo $rrSuccess;} ?></span>
                            </fieldset>
                        </form>
                    </div>
                </section>
        </article> <!-- end member -->
        </main>
        <footer>
            <?php include("inc/inc.footer.php"); ?>
        </footer>
    </div> <!-- end container -->
</body>
</html>