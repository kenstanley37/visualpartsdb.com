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
                <h1>Member Login</h1>
            </section>
            <section class="nav">

            </section>
            <section class="content">
                <form action="login.php" method="post">
                    <!--
                    <?php if(isset($error)){echo '<span>'.$error.'</span>';} ?>
                    -->
                    <table class="table">
                        <thead>
                            <tr>
                                <th colspan="3">LOGIN</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <label id="icon" for="email"><i class="fa fa-user"></i> Email:</label>
                                </td>
                                <td data-label="Email">
                                    <input required type="text" placeholder="Email" id="email" name='email' <?php if(!empty($email)){echo 'value="'.$email.'"';} ?>>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3">
                                    <?php if(isset($emailError)){echo '<span class="emailError">' .$emailError.'</span>';} ?>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label id="icon" for="password"><i class="fa fa-key"></i> Password:</label>
                                </td>
                                <td data-label="Password">
                                    <input required type="password" placeholder="Password" id="password" name="password">
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3">
                                    <?php if(isset($emailError)){echo '<span class="passwordError">' .$passwordError.'</span>';} ?>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3">
                                    <div class="g-recaptcha" data-sitekey="6LcoTokUAAAAAK1eqc2ZGpJ1vg0dhLPLdUOJ_B_k"></div>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3">
                                    <?php echo $captchaError; ?>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <a class="btn info" href="/user/password-reset.php">Forgot?</a>
                                </td>
                                <td colspan="2" class="align-right">
                                    <button class="btn active" type="submit" name="Login">Login</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>            
                </form>
            </section>
        </main>
        <footer>
            <?php include($path."/inc/inc.footer.php"); ?>
        </footer>
    </div> <!-- end container -->
</body>
</html>