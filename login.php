<?php
session_start();
include("inc/inc.path.php");
require_once($path."class/class.user.php");
require_once($path."class/class.func.php");


// Register API keys at https://www.google.com/recaptcha/admin
$siteKey = '6LcoTokUAAAAAK1eqc2ZGpJ1vg0dhLPLdUOJ_B_k';
$secretKey = '6LcoTokUAAAAAOJmN26GyTHtvhVxzJ7fb7JHsu9A';

$emailError = '';
$passwordError = '';
$email = '';
$captchaError = '';

if(isset($_SESSION['user_id'])){
    header('Location: /index.php');
}

if(isset($_SESSION['emailcheck'])){
    $email = $_SESSION['emailcheck'];
}




// check if form has been submitted then start validation checks and login
if(isset($_POST['submit'])){
    $captcha=$_POST['g-recaptcha-response'];
    $ip = $_SERVER['REMOTE_ADDR'];					
    // Request the Google server to validate our captcha
     $request = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secretKey.'&response='.$_POST['g-recaptcha-response']);
     // The result is in a JSON format. Decoding..
     $response = json_decode($request);	     

    if($response->success) {
        $vail = new VALIDATE;
        $email = $vail->sanitizeString($_POST['email']);
        $pass = $vail->sanitizeString($_POST['password']);

        $vail->validEmail($email);
        if(empty($pass)){
            $passwordError = 'Please enter your password';
        } else {
            $user = new USER;
            $user->doLogin($email, $pass);
        }
    } else {
        print_r($response);
        $captchaError = '<span class="captchaError">CAPTCHA not selected</span>';
    }  
} // end POST processing

// Error processing
if(isset($_GET['error'])){
    $error = $_GET['error'];
    if($error == 'noemail'){
        $emailError = "Please enter your email address";
    } else if($error == 'invalidemail'){
        $emailError = "Your email address is invalid";
    } else if($error == 'invalidpassword'){
        $passwordError = "Your password is incorrect";
    }
} // end error processing

?>
<!DOCTYPE html>
<html>
<head>
    <title>Visual Parts Database: Login</title>
    <?php include("inc/inc.head.php"); ?> <!-- CSS and JavaScript -->
</head>
<body>
    <div class="wrapper">
        <?php include($path."inc/inc.header.php"); ?>
        <main class="searchGrid">
            <section class="leading">
                <p class="leading-bigtext">VPD</p>
                <p class="leading-text">Electrolux, Frigidaire, and more. Start your search today!</p>
            </section>
           
        </main>
        <section class="login-wrap">
             <section class="a">
                <h1>Login</h1>
                <hr>
            </section>
                <form action="login.php" method="post">
                    <div class="b">
                        <label id="icon" for="email"><i class="fa fa-user"></i></label>
                        <input type="text" placeholder="Email" id="email" name='email' <?php if(!empty($email)){echo 'value="'.$email.'"';} ?>>
                        <?php if(isset($emailError)){echo '<span class="emailError">' .$emailError.'</span>';} ?>
                    </div>
                    <div class="c">
                        <label id="icon" for="password"><i class="fa fa-key"></i></label>
                        <input type="password" placeholder="Password" id="password" name="password">
                        <?php if(isset($emailError)){echo '<span class="passwordError">' .$passwordError.'</span>';} ?>
                    </div>
                    <div class="d">
                        <div class="g-recaptcha" data-sitekey="6LcoTokUAAAAAK1eqc2ZGpJ1vg0dhLPLdUOJ_B_k"></div>
                    </div>
                    <div class="e">
                        <input type="submit" name="submit" value="Sign In">
                    </div>
                    <div class="f">
                        <?php echo $captchaError; ?>
                    </div>
                </form>
            </section>
        <?php include("inc/inc.footer.php"); ?>
    </div> <!-- end container -->
</body>
</html>