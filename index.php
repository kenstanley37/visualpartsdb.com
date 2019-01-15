<?php
session_start();
include("inc/inc.path.php");
require_once($path."class/class.user.php");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Visual Parts Database</title>
    <?php require_once("inc/inc.head.php"); ?> <!-- META, CSS, and JavaScript -->
</head>
<body>
    <div class="wrapper">
        <?php include($path."inc/inc.header.php"); ?>
        <section class="banner">
            <span class="dot"></span> <p class="banner-bigtext">VPD</p>
            <p class="banner-text">Electrolux, Frigidaire, and more. Start your search today!</p>
        </section>
        <main class="main">
            <section id="slideshow">
                <article>
                    <img class="article-img" src="/assets/images/slideshow1.jpg">
                </article>
                <article>
                    <img class="article-img" src="/assets/images/slideshow2.jpg">
                </article>
                <article>
                    <img class="article-img" src="/assets/images/slideshow3.jpg">
                </article>
                <article>
                    <img class="article-img" src="/assets/images/slideshow4.jpg">
                </article>
            </section>
            <section id="staticImg">
                    <img class="article-img img1" src="/assets/images/slideshow1.jpg">
                    <img class="article-img img2" src="/assets/images/slideshow2.jpg">
                    <img class="article-img img3" src="/assets/images/slideshow3.jpg">
                    <img class="article-img img4" src="/assets/images/slideshow4.jpg">
            </section>
        </main>
        <?php include("inc/inc.footer.php"); ?>
    </div> <!-- end container -->
</body>
</html>