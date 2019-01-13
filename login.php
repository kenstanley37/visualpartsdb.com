<?php
session_start();
if(isset($_SESSION['user_id'])){
    header('Location: /index.php');
}
include("inc/inc.path.php");
require_once($path."class/class.user.php");

if(isset($_POST['submit'])){
    $email = htmlspecialchars($_POST['email']);
    $pass = htmlspecialchars($_POST['password']);
    $user = new USER;
    $user->doLogin($email, $pass);
}

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
        <main class="login-wrap animated bounce">
            <h1>Login</h1>
            <hr>
            <form action="login.php" method="post">
                <label id="icon" for="email"><i class="fa fa-user"></i></label>
                <input type="text" placeholder="Email" id="email" name='email'>
                <label id="icon" for="password"><i class="fa fa-key"></i></label>
                <input type="password" placeholder="Password" id="password" name="password">
                <input type="submit" name="submit" value="Sign In">
                <hr>
                <div class="crtacc"><a href="#">Create Account</a></div>
            </form>
        </main>
        <?php include("inc/inc.footer.php"); ?>
    </div> <!-- end container -->
</body>
</html>