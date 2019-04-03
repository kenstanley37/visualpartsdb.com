<?php
session_start();
include("inc/inc.path.php");
require_once($path."class/class.user.php");
require_once($path."class/class.func.php");

$vail = new VALIDATE;

// Register API keys at https://www.google.com/recaptcha/admin
$siteKey = '6LcoTokUAAAAAK1eqc2ZGpJ1vg0dhLPLdUOJ_B_k';
$secretKey = '6LcoTokUAAAAAOJmN26GyTHtvhVxzJ7fb7JHsu9A';

$emailError = '';
$passwordError = '';
$email = '';
$captchaError = '';

if(isset($_SESSION['user_id'])){
    header('Location: /');
}

if(isset($_SESSION['emailcheck'])){
    $email = $_SESSION['emailcheck'];
}

// check if form has been submitted then start validation checks and login
if(isset($_POST['Login'])){
    $email = $vail->sanitizeString($_POST['email']);
    $pass = $vail->sanitizeString($_POST['password']);
    
    $captcha=$_POST['g-recaptcha-response'];
    $ip = $_SERVER['REMOTE_ADDR'];					
    // Request the Google server to validate our captcha
     $request = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secretKey.'&response='.$_POST['g-recaptcha-response']);
     // The result is in a JSON format. Decoding..
     $response = json_decode($request);	     

    if($response->success) {
        $vail->validEmail($email);
        if(empty($pass)){
            $passwordError = 'Please enter your password';
        } else {
            $user = new USER;
            $user->doLogin($email, $pass);
        }
    } else {
        //print_r($response);
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
    } else if($error == 'notregistered'){
        $emailError = "Email not on file";
    }
} // end error processing

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Visual Parts Database: Login</title>
    <?php include("inc/inc.head.php"); ?> <!-- CSS and JavaScript -->
</head>
<body>
    <div class="wrapper">
        <header class="header">
            <?php include($path."inc/inc.header.php"); ?>
        </header>
        <aside class="admin-nav hidden">
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
                <section class="">
                    <h1 class="blue-header">Member Login</h1>
                </section>
            </section>
            
            <section class="nav">
                <section class="display">
                     <section class="login shadow">
                        <div class="form-contact">
                            <h3 class="login-title">LOGIN</h3>
                            <form action="login.php" method="post">
                                    <input required type="text" placeholder="Email" id="email" name='email' <?php if(!empty($email)){echo 'value="'.$email.'"';} ?>>
                                    <?php if(isset($emailError)){echo '<span class="error">' .$emailError.'</span>';} ?>

                                    <input required type="password" placeholder="Password" id="password" name="password">
                                    <?php echo '<span class="error">' .$passwordError.'</span>'; ?>
                                
                                    <div class="center">
                                        <div class="g-recaptcha" data-sitekey="6LcoTokUAAAAAK1eqc2ZGpJ1vg0dhLPLdUOJ_B_k"></div>
                                    </div>
                                    <?php echo '<span class="error">' .$captchaError.'</span>'; ?>
                                    <input class="btn-blue" type="submit" name="Login" value="Login">

                                    <a href="/user/reset.php">Forgot password?</a>
                            </form>
                        </div>
                    </section>
                </section>
            </section>
        </main>
        <footer>
            <?php include($path."/inc/inc.footer.php"); ?>
        </footer>
    </div> <!-- end container -->
</body>
</html>