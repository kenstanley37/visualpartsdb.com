<?php
/**
* Landing page for the website
*
* @author Ken Stanley <ken@stanleysoft.org>
* @license MIT
*/
session_start();
include("inc/inc.path.php");
require_once($path."class/class.user.php");
require_once($path."class/class.visualdb.php");
require_once($path."class/class.func.php");



$vpd = new VISUALDB;
$vail = new VALIDATE;
$user = new USER;
$vail = new VALIDATE;

/** requesting 6 images from the database */
$randomImage =  $vpd->randImage('6');

if(isset($_POST['RegisterRequest']))
{
    $first_name = $vail->sanitizeString($_POST['first_name']);
    $last_name = $vail->sanitizeString($_POST['last_name']);
    $email = $vail->sanitizeString($_POST['email']);
    $phone = $vail->sanitizeString($_POST['phone']);
    $company = $vail->sanitizeString($_POST['company']);
    $message = $vail->sanitizeString($_POST['messagearea']);
    
    $_SESSION['reg_first_name'] = $first_name;
    $_SESSION['reg_last_name'] = $last_name;
    $_SESSION['reg_email'] = $email;
    $_SESSION['reg_phone'] = $phone;
    $_SESSION['reg_company'] = $company;
    $_SESSION['reg_message'] = $message;
    
    // Build POST request:
    $recaptcha_url = 'https://www.google.com/recaptcha/api/siteverify';
    $recaptcha_secret = '6Leie50UAAAAAI4hVD-vzusG43XbZZdev2zDi4VG';
    $recaptcha_response = $_POST['recaptcha_response'];

    // Make and decode POST request:
    $recaptcha = file_get_contents($recaptcha_url . '?secret=' . $recaptcha_secret . '&response=' . $recaptcha_response);
    $recaptcha = json_decode($recaptcha);

    // Take action based on the score returned:
    if ($recaptcha->success) {
        $result = $user->registerRequest($first_name,$last_name,$email,$phone,$company,$message);

        if($result == 'alreadyregistered'){
            $requestResult = 'Email address is already registered';
        }

        if($result == 'alreadyrequested'){
            $requestResult = 'Email already awaiting approval';
        }

        if($result == 'success'){
            $requestResult = '';
            $first_name = '';
            $last_name = '';
            $email = '';
            $phone = '';
            $company = '';
            $message = '';
            $rrSuccess = '<span class="error">Thank you</span>';
        }
    } else {
        $rrSuccess = 'You did not pass reCAPTCHA verification';
    }
} 

if(isset($_GET['noaccess'])){
    $error = 'You must be a registered user';
}

$sku_count = $vpd->getSkuCount();
$search_count = $vpd->getSearchCount();
$image_count = $vpd->getImageCount();


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
            <div class="index-bg"></div>
            <section class="index-search">
                <h2 class="main-title">Visual Parts Database</h2>
                <div class="search-bar">                
                    <form class="search" action="/search.php" method="get">
                        <label hidden for="search">Search</label>
                        <input class="toupper" type="search" name="search" id="search" placeholder="Part Number or Keyword" required><button type="submit">SEARCH</button>
                    </form>
                </div>
                <div class="records">
                    <p><?php echo number_format($sku_count);?> Parts </p>
                    <p><?php echo number_format($image_count);?> Pictures </p>
                    <p><?php echo number_format($search_count);?> Searches</p>
                </div>
            </section>
            <article class="main-intro">
                <div class="brands indexCard shadow">
                    <h2 class="block-title shadow">Brands</h2>
                    <p>Our database of parts contains information on over 50,000 components from popular brands such as:</p>
                    <ul>
                        <li>
                            <a href="https://www.electroluxappliances.com/">Electrolux</a>
                        </li>
                        <li>
                            <a href="https://www.frigidaire.com/">Frigidaire</a>
                        </li>
                        <li>
                            <a href="https://www.kelvinatorcommercial.com/">Kelvinator</a>
                        </li>
                        <li>
                            <a href="https://www.aeg.com/">AEG</a>
                        </li>
                        <li>
                            <a href="http://gibson-intl.com/">Gibson</a>
                        </li>
                        <li>
                            <a href="https://www.zanussi.com/">Zanussi</a>
                        </li>
                        <li>
                            <a href="https://professional.electrolux.com/">Professional</a>
                        </li>
                    </ul>
                </div>
                <section class="information shadow">
                    <h2 class="block-title shadow">Information</h2>
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
                <div id="staticImg">
                    <?php
                        foreach($randomImage as $key)
                        {
                            ?>
                             <figure class="card bg-white shadow">
                                <div class="card-img">
                                    <a href="/search.php?search=<?php echo $key['sku_image_sku_id']; ?>">
                                        <img class="article-img" src="<?php echo $key['sku_image_thumb']; ?>" alt="<?php echo $key['sku_image_sku_id'].'-'.$key['sku_image_description']; ?>" />
                                    </a>
                                 </div>
                                <figcaption>
                                    <a href="/search.php?search=<?php echo $key['sku_image_sku_id']; ?>">
                                        <div class="card-sku-num">
                                            <h4><?php echo $key['sku_image_sku_id']; ?></h4>
                                        </div>
                                        <div class="card-image-desc">
                                            <p><?php echo $key['sku_image_description'];?></p>
                                        </div>
                                        <div class="card-sku-desc">
                                            <p><?php echo $key['sku_desc'];?></p>
                                        </div>
                                    </a>
                                </figcaption>
                            </figure>
                            <?php
                        }
                    ?>
                </div>
            </article> <!-- end main-intro -->
            <article id="member">
                <div class="member-header">
                    <h2>Membership Information</h2>
                </div>
                <section class="member-info shadow">
                    <h3 class="title">Benefits & Requirements</h3>
                    <h4>Benefits</h4>
                    <ul>
                        <li>
                            Export data to Excel (PDF and CVS coming soon)
                        </li>
                        <li>
                            View search history
                        </li>
                        <li>
                            Create unlimited export list
                        </li>
                        <li>
                            Request updates on SKUs
                        </li>
                    </ul>
                    <h4>Requirements</h4>
                    <ul>
                        <li>
                            Must be a customer or supplier
                        </li>
                        <li>
                            Suppliers keep their own product up to date (images and data)
                        </li>
                    </ul>
                </section>
                <section class="member-request shadow">
                    <div class="form-contact">
                        <h3 class="title">Request Membership</h3>
                        <form action="/index.php#requestForm" method="post" class="form-example" id="requestForm">
                            <fieldset>
                                <label for="fname">First Name</label>
                                <input class="required" type="text" name="first_name" id="fname" placeholder="required" value = "<?php if(isset($_SESSION['reg_first_name'])){ echo $_SESSION['reg_first_name'];} ?>" required>
                                
                                <label for="lname">Last Name</label>
                                <input class="required" type="text" name="last_name" id="lname" placeholder="required" value="<?php if(isset($_SESSION['reg_last_name'])){ echo $_SESSION['reg_last_name'];} ?>" required>
                                
                                <label for="email">Email</label>
                                <input class="required" type="email" name="email" id="email" placeholder="required" value="<?php if(isset($_SESSION['reg_email'])){ echo $_SESSION['reg_email'];} ?>" required>
                                    <?php if(!empty($requestResult)){echo '<span>'.$requestResult.'</span>';}?>
                                
                                <label for="phone">Phone</label>
                                <input class="required" type="tel" name="phone" id="phone" pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}"  placeholder="required xxx-xxx-xxxx" value="<?php if(isset($_SESSION['reg_phone'])){ echo $_SESSION['reg_phone'];} ?>" required>
                                
                                <label for="company">Company</label>
                                <input class="required" type="text" name="company" id="company" placeholder="required" <?php if(isset($_SESSION['reg_company'])){ echo 'value='.$_SESSION['reg_company'];} ?> required>
                                
                                <label for="messagearea">Message</label>
                                <textarea class="pad-35" name="messagearea" placeholder="Please tell us if you are doing business with us" id="messagearea"><?php if(isset($_SESSION['reg_message'])){ echo $_SESSION['reg_message'];} ?></textarea>
                                
                                <input type="hidden" name="recaptcha_response" id="recaptchaResponse" value="">
                                
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