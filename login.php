<?php
/**
* Login page
*
* @author Ken Stanley <ken@stanleysoft.org>
* @license MIT
*/
session_start();
include("inc/inc.path.php");
require_once($path."class/class.user.php");
require_once($path."class/class.func.php");

$vail = new VALIDATE;

// Register API keys at https://www.google.com/recaptcha/admin

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
    // Build POST request:
    $recaptcha_url = 'https://www.google.com/recaptcha/api/siteverify';
    $recaptcha_secret = '6Leie50UAAAAAI4hVD-vzusG43XbZZdev2zDi4VG';
    // Make and decode POST request:
    $recaptcha_response = $_POST['recaptcha_response'];
    $recaptcha = file_get_contents($recaptcha_url . '?secret=' . $recaptcha_secret . '&response=' . $recaptcha_response);
    $recaptcha = json_decode($recaptcha);
    
   // var_dump($recaptcha);
   // echo number_format($recaptcha->success,2);
   // die;
    
    if($recaptcha->score > 0) {
        $vail->validEmail($email);
        if(empty($pass)){
            $passwordError = 'Please enter your password';
        } else {
            $user = new USER;
            $user->doLogin($email, $pass);
        }
    } else {
        //print_r($response);
        $captchaError = '<span class="captchaError">CAPTCHA Failed</span>';
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
            
            <section class="content">
                 <section class="w400 shadow bg-white">
                    <div class="form-contact">
                        <h3 class="login-title">LOGIN</h3>
                        <form action="login.php" method="post">
                            <input required type="text" placeholder="Email" id="email" name='email' <?php if(!empty($email)){echo 'value="'.$email.'"';} ?>>
                            <?php if(isset($emailError)){echo '<span class="error">' .$emailError.'</span>';} ?>

                            <input required type="password" placeholder="Password" id="password" name="password">
                            <?php echo '<span class="error">' .$passwordError.'</span>'; ?>

                            <div class="center">
                                <input type="hidden" name="recaptcha_response" id="recaptchaResponse" value="">
                            </div>
                            <?php echo '<span class="error">' .$captchaError.'</span>'; ?>
                            <input class="btn-blue" type="submit" name="Login" value="Login">

                            <a href="/user/reset.php">Forgot password?</a>
                        </form>
                    </div>
                </section>
            </section>
        </main>
        <footer>
            <?php include($path."/inc/inc.footer.php"); ?>
        </footer>
    </div> <!-- end container -->
</body>
</html>