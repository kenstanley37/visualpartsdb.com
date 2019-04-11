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

$randomImage =  $vpd->randImage('6');

$fname = '';
$lname = '';
$email = '';
$phone = '';
$company = '';
$message = '';
$recaptcha = '';

if(isset($_POST['RegisterRequest']))
{
    $fname = $vail->sanitizeString($_POST['fname']);
    $lname = $vail->sanitizeString($_POST['lname']);
    $email = $vail->sanitizeString($_POST['email']);
    $phone = $vail->sanitizeString($_POST['phone']);
    $company = $vail->sanitizeString($_POST['company']);
    $message = $vail->sanitizeString($_POST['messagearea']);

    //$siteKey = '6Leie50UAAAAAKxWAQy4g3oDbuSDN6-OZyP0KI_x';
    //$secretKey = '6Leie50UAAAAAI4hVD-vzusG43XbZZdev2zDi4VG';
    
    $captcha;
    $captcha = filter_input(INPUT_POST, 'token', FILTER_SANITIZE_STRING);
    print_r(json_decode($captcha));
    die;
    if(!$captcha){
    echo '<h2>Please check the the captcha form.</h2>';
        
    exit;
    }
    $secretKey = "6Leie50UAAAAAKxWAQy4g3oDbuSDN6-OZyP0KI_x";
    $ip = $_SERVER['REMOTE_ADDR'];

    // post request to server
    $url = 'https://www.google.com/recaptcha/api/siteverify';
    $data = array('secret' => $secretKey, 'response' => $captcha);

    $options = array(
    'http' => array(
          'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
          'method'  => 'POST',
          'content' => http_build_query($data)
        )
    );
    $context  = stream_context_create($options);
    $response = file_get_contents($url, false, $context);
    $responseKeys = json_decode($response,true);
    header('Content-type: application/json');
    if($responseKeys["success"]) {
        echo json_encode(array('success' => 'true'));
    } else {
        echo json_encode(array('success' => 'false'));
    }
    
    print_r($test123);
    // Take action based on the score returned:
    if ($recaptcha->success) {
        $result = $user->registerRequest($fname,$lname,$email,$phone,$company,$message);

        if($result == 'alreadyregistered'){
            $requestResult = 'Email address is already registered';
        }

        if($result == 'alreadyrequested'){
            $requestResult = 'Email already awaiting approval';
        }

        if($result == 'success'){
            $requestResult = '';
            $fname = '';
            $lname = '';
            $email = '';
            $phone = '';
            $company = '';
            $message = '';
            $rrSuccess = '<span class="error">Thank you</span>';
        }
    } else {
        $rrSuccess = 'You did not pass recaptcha verification';
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
    <script src="https://www.google.com/recaptcha/api.js?render=6Leie50UAAAAAKxWAQy4g3oDbuSDN6-OZyP0KI_x"></script>
    <script>
        grecaptcha.ready(function () {
            grecaptcha.execute('6Leie50UAAAAAKxWAQy4g3oDbuSDN6-OZyP0KI_x', { action: 'contact' }).then(function (token) {
                var recaptchaResponse = document.getElementById('recaptchaResponse');
                recaptchaResponse.value = token;
            });
        });
    </script>
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
                    <p><?php echo number_format($sku_count);?> Parts </p>
                    <p><?php echo number_format($image_count);?> Pictures </p>
                    <p><?php echo number_format($search_count);?> Searches</p>
                </section>
            </article>
            <article class="main-intro">
                <section class="brands indexCard shadow">
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
                </section>
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
                <section id="staticImg">
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
                </section>
            </article> <!-- end main-intro -->
            <article id="member">
                <section class="member-header">
                    <h2>Membership Information</h2>
                </section>
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

                                <input type="text" name="fname" id="fname" placeholder="First Name" value = "<?php if(!empty($fname)){ echo $fname;} ?>" required>

                                <input type="text" name="lname" id="lname" placeholder="Last Name" value="<?php if(!empty($lname)){ echo $lname;} ?>" required>
 
                                <input type="email" name="email" id="email" placeholder="Email" value="<?php if(isset($email)){ echo $email;} ?>" required>
                                    <?php if(!empty($requestResult)){echo '<span>'.$requestResult.'</span>';}?>

                                <input type="tel" name="phone" id="phone" pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}"  placeholder="Phone xxx-xxx-xxxx" value="<?php if(!empty($phone)){ echo $phone;} ?>" required>

                                <input type="text" name="company" id="company" placeholder="Company" <?php if(!empty($company)){ echo 'value='.$company;} ?> required>
                                    
                                <textarea name="messagearea" placeholder="Message" id="messagearea"><?php if(!empty($message)){ echo $message;} ?></textarea>
                                
                                <input type="hidden" name="token" id="recaptchaResponse" value="">
                                
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